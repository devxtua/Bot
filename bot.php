<?php
$start = $_SERVER['REQUEST_TIME'];
$mem_start = memory_get_usage();
header('refresh: 3');
//биржа
$exchange = 'binance';
//Определяем базове валют
$base['USDT']= array('minBalans'=>100, 'minPriceBuy'=>0.00000100);
$base['BNB']= array('minBalans'=>1, 'minPriceBuy'=>0.00000100);
//минимальное время между запупками по одной стратегии
$minimum_time_BUY = 30;

$free_SELL_OCO = array('Price' => 1.005, 'S_Price' => 0.9951, 'SL_Price' => 0.995);

// базовое OCO
$distance_END_SELL_OCO = -0.1;
$END_SELL_OCO = array('Price' => 1.003, 'S_Price' => 0.9991, 'SL_Price' => 0.999);

// базовое OCO
$BUY_OCO = array('Price' => 0.995, 'S_Price' => 1.0029, 'SL_Price' => 1.003);
$distance_END_BUY_OCO = 0.1;
$END_BUY_OCO = array('Price' => 0.997, 'S_Price' => 1.0009, 'SL_Price' => 1.001);

require "./~core/model/binance_c.php";
require "./~core/model/functions_c.php";
require "./~core/model/indicators_c.php";
require "./~core/model/user_c.php";
// require "./libraries/binance_api/vendor/autoload.php";

$Users = new Users();
$Bin = new Binance();
$Indicators = new Indicators();

foreach ($Users->user_arrey as $key => $user) {
    $Bin->initialization($user[$exchange]['config']['KEY'], $user[$exchange]['config']['SEC']);
    if (!$tickerPriceAll = $Bin->tickerPrice(array())) continue;

    //***** получаем открытые ордера и проверяем активные
    if ($orderOPEN = $Bin->orderOPEN(array())) {
        foreach ($orderOPEN as $key => &$order) {
            //Получаем информацию о symbol
            if (!$symbolInfo = Functions::multiSearch($Bin->exchangeInfo['symbols'], array('symbol' => $order['symbol'], 'status'=>'TRADING'))[0])  continue;
            if (!$tickerPrice = Functions::multiSearch($tickerPriceAll, array('symbol' => $order['symbol']))[0]) continue;

            //определяем дистанцию цены
            $order['level_price'] = bcdiv(bcsub($tickerPrice['price'], $order['price'], 8), $tickerPrice['price'], 8)*100;
            //если нужно переставляем ордера ПРОДАЖИ
            if (1 == bccomp($order['level_price'], $distance_END_SELL_OCO, 5) && $order['type'] == 'LIMIT_MAKER' && $order['side'] == 'SELL') {
                $strateg_uniqid = explode('_', $value['clientOrderId'])[0];
                $step = explode('_', $value['clientOrderId'])[1];
                $step ++;
                $ParamsDELETE = array('symbol'=>$order['symbol'],
                                        'orderId'=>$order['orderId']);
                if ($orderDELETE = $Bin->orderDELETE($ParamsDELETE)){
                    $minPrice = Functions::multiSearch($symbolInfo['filters'], array('filterType' => 'PRICE_FILTER'))[0]['minPrice'];
                    $Params_END_SELL_OCO = array('symbol'=>$order['symbol'],
                                                    'side' => 'SELL',
                                                    'quantity' => $orderDELETE['orderReports'][1]['origQty'],
                                                    'price' => $Bin->round_min(bcmul($tickerPrice['price'], $END_SELL_OCO['Price'], 8), $minPrice),
                                                    'stopPrice' => $Bin->round_min(bcmul($tickerPrice['price'], $END_SELL_OCO['S_Price'], 8), $minPrice),
                                                    'stopLimitPrice' => $Bin->round_min(bcmul($tickerPrice['price'], $END_SELL_OCO['SL_Price'], 8), $minPrice),
                                                    'stopLimitTimeInForce' => 'GTC',
                                                    'listClientOrderId'  => $strateg_uniqid.'_'.$step.uniqid('_'),
                                                    'limitClientOrderId'  => $strateg_uniqid.'_'.$step.uniqid('_'),
                                                    'stopClientOrderId'  => $strateg_uniqid.'_'.$step.uniqid('_'));
                    if ($orderOCO = $Bin->newOCO($Params_END_SELL_OCO)) {
                        //LOG
                    }
                }
            }
            //если нужно переставляем ордера ПОКУПКИ
            if (-1 == bccomp($order['level_price'], $distance_END_BUY_OCO, 5) && $order['type'] == 'LIMIT_MAKER' && $order['side'] == 'BUY') {
                $strateg_uniqid = explode("_", $order['ClientOrderId'])[0];
                $ParamsDELETE = array('symbol'=>$order['symbol'], 'orderId'=>$order['orderId']);
                if ($orderDELETE = $Bin->orderDELETE($ParamsDELETE)) {
                    $minPrice = Functions::multiSearch($symbolInfo['filters'], array('filterType' => 'PRICE_FILTER'))[0]['minPrice'];
                    $Params_END_BUY_OCO = array('symbol'=>$order['symbol'],
                                                'side' => 'BUY',
                                                'quantity' => $orderDELETE['orderReports'][1]['origQty'],
                                                'price' => $Bin->round_min(bcmul($tickerPrice['price'], $END_BUY_OCO['Price'], 8), $minPrice),
                                                'stopPrice' => $Bin->round_min(bcmul($tickerPrice['price'], $END_BUY_OCO['S_Price'], 8), $minPrice),
                                                'stopLimitPrice' => $Bin->round_min(bcmul($tickerPrice['price'], $END_BUY_OCO['SL_Price'], 8), $minPrice),
                                                'stopLimitTimeInForce' => 'GTC',
                                                'listClientOrderId'  => $strateg_uniqid.'_'.$step.uniqid('_'),
                                                'limitClientOrderId'  => $strateg_uniqid.'_'.$step.uniqid('_'),
                                                'stopClientOrderId'  => $strateg_uniqid.'_'.$step.uniqid('_'));
                    if ($orderOCO = $Bin->newOCO($Params_END_BUY_OCO)) {
                        //LOG
                    }
                }
            }
        }
    }

    //***** Получить свободный баланс
    if (!$accountBalance = $Bin->accountBalance($base)) continue;

    //***** проверяем free балансы и ставим ОСО
    foreach ($accountBalance as $key => $balans) {
        //Получаем информацию о symbol и исключаем неактивные пары
        if ($balans['asset'] == 'USDT') continue;
        if (!$symbolInfo = Functions::multiSearch($Bin->exchangeInfo['symbols'], array('symbol' => $balans['asset'].'USDT', 'status'=>'TRADING'))[0])  continue;
        if (!$tickerPrice = Functions::multiSearch($tickerPriceAll, array('symbol' => $balans['asset'].'USDT'))[0]) continue;

        //На free активы ставим OCO
        $balancequantitySELL = $balans['total'] - $balans['locked'] - (float)$balans['min'];
        if (bcmul($balancequantitySELL, $tickerPrice['price'], 8)<10) continue;
        $minQty = Functions::multiSearch($symbolInfo['filters'], array('filterType' => 'LOT_SIZE'))['0']['minQty'];
        $minPrice = Functions::multiSearch($symbolInfo['filters'], array('filterType' => 'PRICE_FILTER'))['0']['minPrice'];
        $Params_SELL_OCO = array('symbol'=>$balans['asset'].'USDT',
                                  'side' => 'SELL',
                                  'quantity' => $Bin->round_min($balancequantitySELL, $minQty),
                                  'price' => $Bin->round_min(bcmul($tickerPrice['price'], $free_SELL_OCO['Price'], 8), $minPrice),
                                  'stopPrice' => $Bin->round_min(bcmul($tickerPrice['price'], $free_SELL_OCO['S_Price'], 8), $minPrice),
                                  'stopLimitPrice' => $Bin->round_min(bcmul($tickerPrice['price'], $free_SELL_OCO['SL_Price'], 8), $minPrice),
                                  'stopLimitTimeInForce' => 'GTC',
                                  'listClientOrderId'  => 'free'.uniqid('_'),
                                  'limitClientOrderId'  => 'free'.uniqid('_'),
                                  'stopClientOrderId'  => 'free'.uniqid('_'));
        if ($orderOCO = $Bin->newOCO($Params_SELL_OCO)) {
            //LOG
        }
    }

    //***** Проверяем наличие USDT (если нет пропускаем)
    if ($accountBalance['USDT']['free'] < 11) goto end;

    //***** покупаем BNB если их баланс менее 0.5
    if ($accountBalance['BNB']['free'] < 0.3) {
        //масив параметров для покупки  BNB
        $ParamsBUY = array('symbol'=>'BNBUSDT',
                        'side' => 'BUY',
                        'type' => 'MARKET',
                        'quantity' => 1,
                        'timeInForce' => 'IOC',
                        'newClientOrderId'=>uniqid('buyBNB_'));
        //ПОКУПКА BNB
        if (!$order = $Bin->orderNEW($ParamsBUY)) {
            //LOG
        }
    }

    //***** проверяем наличие стратегий  (если нет пропускаем)
    if (count($user[$exchange]['strategies'])==0) goto end;

    //***** проверяем стратегии на покупку
    foreach ($user[$exchange]['strategies'] as $key => &$strateg) {
        //Исключаем отключеные стратегии
        if ($strateg['status']=='OFF') continue;
        //Получаем информацию о symbol
        if (!$symbolInfo = Functions::multiSearch($Bin->exchangeInfo['symbols'], array('symbol' => $strateg['symbol'], 'status'=>'TRADING'))[0]) continue;
        if (!$tickerPrice = Functions::multiSearch($tickerPriceAll, array('symbol' => $strateg['symbol']))[0]) continue;

        //отбыраем открытые ордера symbol стратегии проверяем условия
        if (count($orderOPEN)>0) {
            if ($open = Functions::multiSearch($orderOPEN, array('symbol' => $strateg['symbol']))){
                //минимальный переиод между операциями
                if (time() - round(max($open)['time']/1000) < $minimum_time_BUY) continue;
                //проверяем общее число max_open
                $max_open = Functions::multiSearch($symbolInfo['filters'], array('filterType' => 'MAX_NUM_ORDERS'))['0']['maxNumOrders'];
                if (count($open) >= $max_open) continue;
                //проверяем общее число max_ALGO
                $max_ALGO = Functions::multiSearch($symbolInfo['filters'], array('filterType' => 'MAX_NUM_ALGO_ORDERS'))['0']['maxNumAlgoOrders'];
                $algo_orders = count(array_unique(array_column($open, 'orderListId')))-1;
                if ($algo_orders >= $max_ALGO) continue;
            }
        }

        //получаем индикаторы торговой пары
        $all_indicator = $Indicators->all_indicator($strateg['symbol'], $strateg['interval']);

        //Получаем курс BTC USD
        $kurs = $Bin->kurs($symbolInfo['quoteAsset']);
        //Проверяем индикаторы стратегии
        $control = count($strateg['indicator_arrey']);
        $yes = 0;
        $status_ind = $status_BUY = '';
        //Проверяем индикаторы и плюсуем подтверждение
        foreach ($strateg['indicator_arrey'] as $key=> $indicator) {
            $yes += Functions::comparison_indicator($all_indicator[$indicator['indicator']], $indicator['operator'], $indicator['value']);
            //формируем статус проверки индикатора
            $statuc = $yes == 1?'ДА':'НЕТ';
            $status_ind .=  $statuc.' => '.$indicator['indicator']. ': '. $all_indicator[$indicator['indicator']]. ' '. $indicator['operator']. ' '. $indicator['value'].'<br/>';
        }
        //если сразу покупаем
        $status_BUY .= $control.' yes '.$yes;
        if ($control == $yes  && 1==1){
            $status_BUY .= " BUY";
            $minQty = Functions::multiSearch($symbolInfo['filters'], array('filterType' => 'LOT_SIZE'))['0']['minQty'];
            //параметры BUY LIMIT IOC
            $Params_BUY = array('symbol'=>$strateg['symbol'],
                                'side' => 'BUY',
                                'type' => 'MARKET',
                                'quantity' => $Bin->round_min(bcdiv($strateg['trading_limit'], bcmul($tickerPrice['price'], $kurs['kursUSD'], 8), 8), $minQty),
                                'timeInForce' => 'IOC',
                                'newClientOrderId'=> $strateg['key'].uniqid('_'));
            //отправляем BUY ордер
            if ($order = $Bin->orderNEW($Params_BUY)) {
                //при успехе BUY выставляем ОСО ордера
                if (0 != bccomp((string)$order['executedQty'], (string)0, 8)) {
                    $priceBUY = bcdiv($order['cummulativeQuoteQty'], $order['executedQty'], 8);
                    $minPrice = Functions::multiSearch($symbolInfo['filters'], array('filterType' => 'PRICE_FILTER'))['0']['minPrice'];
                    //масив параметров SELL_OCO
                    $Params_SELL_OCO = array('symbol'=>$strateg['symbol'],
                                          'side' => 'SELL',
                                          'quantity' => $order['executedQty'],
                                          'price' => $Bin->round_min(bcmul($priceBUY, $strateg['coefficient_profit'], 8), $minPrice),
                                          'stopPrice' => $Bin->round_min(bcmul($priceBUY, $strateg['coefficient_stop_loss'], 8), $minPrice),
                                          'stopLimitPrice' => $Bin->round_min(bcmul($priceBUY, $strateg['coefficient_stop_loss'], 8), $minPrice),
                                          'stopLimitTimeInForce' => 'GTC',
                                          'listClientOrderId'  => $strateg['key'].uniqid('_0_'),
                                          'limitClientOrderId'  => $strateg['key'].uniqid('_0_'),
                                          'stopClientOrderId'  => $strateg['key'].uniqid('_0_'));
                    //отправляем SELL ОСО
                    if ($orderOCO = $Bin->newOCO($Params_SELL_OCO)) {
                        //LOG
                    }
                }
            }
        }
        //если выставляем ОСО закупки
        if ($control == $yes && 2==1) {
            $status_BUY .= " OCO на BUY";
            $minQty = Functions::multiSearch($symbolInfo['filters'], array('filterType' => 'LOT_SIZE'))['0']['minQty'];
            $minPrice = Functions::multiSearch($symbolInfo['filters'], array('filterType' => 'PRICE_FILTER'))['0']['minPrice'];
            //масив параметров SELL_OCO
            $Params_BUY_OCO = array('symbol'=>$strateg['symbol'],
                                        'side' => 'BUY',
                                        'quantity' => $Bin->round_min(bcdiv($strateg['trading_limit'], bcmul($tickerPrice['price'], $kurs['kursUSD'], 8), 8), $minQty),
                                        'price' => $Bin->round_min(bcmul($tickerPrice['price'], $BUY_OCO['Price'], 8), $minPrice),
                                        'stopPrice' => $Bin->round_min(bcmul($tickerPrice['price'], $BUY_OCO['S_Price'], 8), $minPrice),
                                        'stopLimitPrice' => $Bin->round_min(bcmul($tickerPrice['price'], $BUY_OCO['SL_Price'], 8), $minPrice),
                                        'stopLimitTimeInForce' => 'GTC',
                                        'listClientOrderId'  => $strateg['key'].uniqid('_'),
                                        'limitClientOrderId'  => $strateg['key'].uniqid('_'),
                                        'stopClientOrderId'  => $strateg['key'].uniqid('_'));
            if ($orderOCO = $Bin->newOCO($Params_BUY_OCO)) {
                //LOG
            }
        }
        $strateg['status_indicator'] = $status_ind;
        $strateg['status_BUY'] = $status_BUY;
    }




    end:
    //Отправка контрольной SMS
    $getdate = getdate();
    // Functions::show($getdate, '');
    if (in_array($getdate['hours'], [18]) && $getdate['minutes'] == 0 && $getdate['seconds'] < 15) {
        require "./~core/model/apisms_c.php";
        $sms .= 'Open '.count($orderOPEN). ' Balance '.round(array_sum(array_column($accountBalance, 'total_USD')),2);
        $ApiSMS = new APISMS('843e5bec02b4c36e0202d4a0cf227eaa', 'd9c9f37cc5b86d4368da4cd7b3781a27', 'http://atompark.com/api/sms/', false);
        $log = $ApiSMS->execCommad('sendSMS', array(
                                         'sender' => 'xt.ua',
                                         'text' => $sms,
                                         'phone' => '380505953494',
                                         'datetime' => null,
                                         'sms_lifetime' => 0));
    }

    //***** Смотрим даные если запустили с браузера
    if ($_GET['action'] == 'show'){
        echo '<div style="text-align: right; background: #fc0;">', '  <font size="20" color=blue face="Arial">', date("H:i:s", time()), '</font></div>';
        echo $user['login'], ' баланс: <font size="10" color="green" face="Arial">', round(array_sum(array_column($accountBalance, 'total_USD')),2),  '</font>$<br/>';
        Functions::showArrayTable($accountBalance, '');
        Functions::showArrayTable($user[$exchange]['strategies'], "СТРАТЕГИИ ");
        Functions::showArrayTable($orderOPEN, 'OPEN order');
        //Время выполнения скрипта:
        echo 'Время выполнения скрипта: ', round(microtime(true) - $start, 4), ' сек.<br/>';
        echo 'Обем памяти: ', (memory_get_usage() - $mem_start)/1000000, ' мегабайта.<br/><br/><br/><br/>';
    }
}

?>