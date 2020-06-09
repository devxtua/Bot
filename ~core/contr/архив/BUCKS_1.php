<?php
    // header('refresh: 1');

    //Устанавливаем настройки памяти
    // echo "memory_limit ", ini_get('memory_limit'), "<br />";
    // ini_set('memory_limit', '1024M');   
    // echo "memory_limit ", ini_get('memory_limit'), "<br />";

    //Устанавливаем настройки времени
    // echo "max_execution_time ", ini_get('max_execution_time'), "<br />";
    // ini_set('max_execution_time', 60);    //  одно и тоже что set_time_limit(6000);
    // echo "max_execution_time ", ini_get('max_execution_time'), "<br />";

    // ob_implicit_flush(1);
     
    // ob_start();
    // ob_get_contents();
    // ob_get_clean();
    // ob_end_flush();

$start = microtime(true);
echo "Привет! Меня зовут BUCKS (v 1)<br/>";
// назначаем пары и их актив
// $symbolArray[]=array('symbol'=>'PHBBNB', 'base'=>'BNB', 'asset'=>'PHB', 'limitUSDUSD'=>1000, 'position'=> 'short'); 

$symbolArray[]=array('symbol'=>'ETHBTC', 'base'=>'BTC', 'asset'=>'ETH', 'limitUSD'=>100, 'position'=> 'short');
$symbolArray[]=array('symbol'=>'XRPBTC', 'base'=>'BTC', 'asset'=>'XRP', 'limitUSD'=>100, 'position'=> 'short');
$symbolArray[]=array('symbol'=>'BCHBTC', 'base'=>'BTC', 'asset'=>'BCH', 'limitUSD'=>100, 'position'=> 'short');
$symbolArray[]=array('symbol'=>'LTCBTC', 'base'=>'BTC', 'asset'=>'LTC', 'limitUSD'=>100, 'position'=> 'short');
$symbolArray[]=array('symbol'=>'EOSBTC', 'base'=>'BTC', 'asset'=>'EOS', 'limitUSD'=>100, 'position'=> 'short');
$symbolArray[]=array('symbol'=>'BNBBTC', 'base'=>'BTC', 'asset'=>'BNB', 'limitUSD'=>100, 'position'=> 'long');
$symbolArray[]=array('symbol'=>'BQXBTC', 'base'=>'BTC', 'asset'=>'BQX', 'limitUSD'=>100, 'position'=> 'long');
$symbolArray[]=array('symbol'=>'MTLBTC', 'base'=>'BTC', 'asset'=>'MTL', 'limitUSD'=>100, 'position'=> 'long');
$symbolArray[]=array('symbol'=>'LINKBTC', 'base'=>'BTC', 'asset'=>'LINK', 'limitUSD'=>100, 'position'=> 'long');
$symbolArray[]=array('symbol'=>'PHBBTC', 'base'=>'BTC', 'asset'=>'PHB', 'limitUSD'=>100, 'position'=> 'long');
$symbolArray[]=array('symbol'=>'BATBTC', 'base'=>'BTC', 'asset'=>'BAT', 'limitUSD'=>100, 'position'=> 'short');
$symbolArray[]=array('symbol'=>'XRPBTC', 'base'=>'BTC', 'asset'=>'XRP', 'limitUSD'=>100, 'position'=> 'short');


$symbolArray[]=array('symbol'=>'XRPETH', 'base'=>'ETH', 'asset'=>'XRP', 'limitUSD'=>100, 'position'=> 'short');
$symbolArray[]=array('symbol'=>'BNBETH', 'base'=>'ETH', 'asset'=>'BNB', 'limitUSD'=>100, 'position'=> 'long');
$symbolArray[]=array('symbol'=>'BATETH', 'base'=>'ETH', 'asset'=>'BAT', 'limitUSD'=>100, 'position'=> 'short');
// $symbolArray[]=array('symbol'=>'LINKETH', 'base'=>'ETH', 'asset'=>'LINK', 'limitUSD'=>100, 'position'=> 'short');
$symbolArray[]=array('symbol'=>'EOSETH', 'base'=>'ETH', 'asset'=>'EOS', 'limitUSD'=>100, 'position'=> 'short');
$symbolArray[]=array('symbol'=>'MCOETH', 'base'=>'ETH', 'asset'=>'MCO', 'limitUSD'=>100, 'position'=> 'short');


$symbolArray[]=array('symbol'=>'BTCUSDT', 'base'=>'USDT', 'asset'=>'BTC', 'limitUSD'=>200, 'position'=> 'long');
$symbolArray[]=array('symbol'=>'ETHUSDT', 'base'=>'USDT', 'asset'=>'ETH', 'limitUSD'=>100, 'position'=> 'short');
$symbolArray[]=array('symbol'=>'XRPUSDT', 'base'=>'USDT', 'asset'=>'XRP', 'limitUSD'=>100, 'position'=> 'short');
$symbolArray[]=array('symbol'=>'BCHUSDT', 'base'=>'USDT', 'asset'=>'BCH', 'limitUSD'=>100, 'position'=> 'short');
$symbolArray[]=array('symbol'=>'EOSUSDT', 'base'=>'USDT', 'asset'=>'EOS', 'limitUSD'=>100, 'position'=> 'short');
$symbolArray[]=array('symbol'=>'XMRUSDT', 'base'=>'USDT', 'asset'=>'XMR', 'limitUSD'=>100, 'position'=> 'short');
$symbolArray[]=array('symbol'=>'XLMUSDT', 'base'=>'USDT', 'asset'=>'XLM', 'limitUSD'=>100, 'position'=> 'short');
$symbolArray[]=array('symbol'=>'TRXUSDT', 'base'=>'USDT', 'asset'=>'TRX', 'limitUSD'=>100, 'position'=> 'short');
$symbolArray[]=array('symbol'=>'BATUSDT', 'base'=>'USDT', 'asset'=>'BAT', 'limitUSD'=>100, 'position'=> 'short');

$symbolArray[]=array('symbol'=>'XRPBNB', 'base'=>'BNB', 'asset'=>'XRP', 'limitUSD'=>100, 'position'=> 'short');
// $symbolArray[]=array('symbol'=>'LTCBNB', 'base'=>'BNB', 'asset'=>'LTC', 'limitUSD'=>100, 'position'=> 'short');
$symbolArray[]=array('symbol'=>'BCHBNB', 'base'=>'BNB', 'asset'=>'BCH', 'limitUSD'=>100, 'position'=> 'short');
$symbolArray[]=array('symbol'=>'EOSBNB', 'base'=>'BNB', 'asset'=>'EOS', 'limitUSD'=>100, 'position'=> 'long');
$symbolArray[]=array('symbol'=>'TRXBNB', 'base'=>'BNB', 'asset'=>'TRX', 'limitUSD'=>100, 'position'=> 'short');
$symbolArray[]=array('symbol'=>'ADABNB', 'base'=>'BNB', 'asset'=>'ADA', 'limitUSD'=>100, 'position'=> 'short');
$symbolArray[]=array('symbol'=>'XMRBNB', 'base'=>'BNB', 'asset'=>'XMR', 'limitUSD'=>100, 'position'=> 'long');
$symbolArray[]=array('symbol'=>'XLMBNB', 'base'=>'BNB', 'asset'=>'XLM', 'limitUSD'=>100, 'position'=> 'short');


//Создаем класс
$Bin = new binance();

//Проверяем торговый статус API аккаунта
// $apiTradingStatus= $Bin->apiTradingStatus(array());
// $Bin->show($apiTradingStatus);
// die();

//************************ОБЩИЕ ДАНЫЕ БИРЖИ (загружаем или читаем с файла)**************
$filetradeFeeKom = 'E:\binance\tradeFeeKom.txt';
$fileexchangeInfo = 'E:\binance\exchangeInfo.txt';
$fileticker24hr = 'E:\binance\ticker24hr.txt';
if(time()-filemtime($filetradeFeeKom) > 3600 || time()-filemtime($fileexchangeInfo) > 3600|| time()-filemtime($fileticker24hr) > 3600){
    //Даные в файле устарели УДАЛЯЕМ 
    unlink($filetradeFeeKom);
    unlink($fileexchangeInfo);
    unlink($fileticker24hr);   
    //Получаем актуальную информацию
    $tradeFeeKom= $Bin->tradeFeeKom();      //О комисиях
    $exchangeInfo = $Bin->exchangeInfo();   //Правила биржевой торговли и символьная информация
    $ticker24hr = $Bin->ticker24hr();       //Изминения рынка за 24 часа
    //записиваем даные в файл
    $Bin->saveFile($tradeFeeKom, $filetradeFeeKom);
    $Bin->saveFile($exchangeInfo, $fileexchangeInfo);
    $Bin->saveFile($ticker24hr, $fileticker24hr);

    $time = microtime(true) - $start;
    echo 'Файлы ОБНОВЛЕНЫ. время: '.round($time, 4).' сек.<br/>';
}else{    
    //Даные в файле актуальны читаем
    $tradeFeeKom = $Bin->readFile($filetradeFeeKom);
    $exchangeInfo = $Bin->readFile($fileexchangeInfo);
    $ticker24hr = $Bin->readFile($fileticker24hr);

    $time = microtime(true) - $start;
    echo 'Файлы прочитаны. время: '.round($time, 4).' сек.<br/>';
}

//Смотрим

// $Bin->show($tradeFeeKom);
// $Bin->show($exchangeInfo);

// foreach ($exchangeInfo['symbols'] as $key => $value) {
//     if ($value['status'] == 'TRADING') {
//         echo $value['symbol'], ' - ', $value['status'], "<br/>";
//     }
// }

    //Статистика изменения цены за 24 часа
$symbolArray2 = array();
foreach ($ticker24hr as $key => $value) {
    if ($value['count'] < 50000) continue;

    if ($value['priceChangePercent'] > 0) {
        $symbolArray2[]= $value['symbol'];
       // echo $value['symbol'], ': ' , $value['priceChangePercent'],  " Растет<br/>";
    }
}
   

//     // $Bin->show($ticker24hr);
    // $Bin->show($symbolArray2); 



// die();



//-------------------------- Получение ПОСЛЕДНИХ ДАНЫХ -----------------------------
//Получаем цену в доларе базовых валют
$usdPrice['BNB'] = array('symbol'=>'BNBUSDT');
$usdPrice['BTC'] = array('symbol'=>'BTCUSDT');
$usdPrice['ETH'] = array('symbol'=>'ETHUSDT');
$usdPrice['TRX'] = array('symbol'=>'TRXUSDT');
$usdPrice['XRP'] = array('symbol'=>'XRPUSDT');
// $usdPrice['BNB'] = array('symbol'=>'BNBUSDT');

foreach ($usdPrice as $key => $value){
    $usdPrice[$key]['USD']= $Bin->avgPrice($value)['price'];
}
$usdPrice['USDT']['USD'] = 1;

 // $Bin->show($usdPrice);
//Получить свободный баланс АКТИВОВ
$accountBalance= $Bin->accountBalance();
// echo "Остатки кошелька<br/>";
// $Bin->showArrayTable($accountBalance);
// $Bin->show($accountBalance);

  
//Смотрим информацию о включеных рынках
// $Bin->showArrayTable($symbolArray);

//Последняя цена за символ
// $tickerPrice= $Bin->tickerPrice();
// $Bin->show($tickerPrice);

//Текущая средняя цена
// $avgPrice= $Bin->avgPrice();
// $Bin->show($avgPrice);

//Лучшая цена / кол-во в книге заказов на символ или символы
// $bookTicker= $Bin->bookTicker($symbol);
// $Bin->show($bookTicker);



//Анализ и торги в цикле
$AllOrdersFILLED= array();
$sum_total_1 = $sum_total_12 = $sum_total_24 = $sum_total_All='';

foreach ($symbolArray as $key => $symbol) { 
    echo $symbol['symbol'], ' * ';

    if ($accountBalance[$symbol['asset']]) {
        $symbol['free']= $accountBalance[$symbol['asset']]['free'];
        $symbol['locked']= $accountBalance[$symbol['asset']]['locked'];
        $symbol['total']= $accountBalance[$symbol['asset']]['total'];
        $symbol['total_USD']= $accountBalance[$symbol['asset']]['total_USD'];


    }else{
        $symbol['free']= '';
        $symbol['locked']= '';
        $symbol['total']= '';
        $symbol['total_USD']= '';
    }
    

    //смотрим информацию о symbol
    // $Bin->show($exchangeInfo);
    $symbolInfo = array_values($Bin->multiSearch($exchangeInfo['symbols'], array('symbol' => $symbol['symbol'])));        
    // $Bin->show($symbolInfo);
    
    // echo $symbolInfo[0]['filters'][2]['minQty'], '<br/>';
    // echo $symbolInfo[0]['filters'][0]['minPrice'], '<br/>';

    //Поcледняя моя сделка
    // $myTrades= $Bin->myTrades($symbol);
    // $Bin->show($myTrades);   

    //Наростающая груперовка открытых заказов 0(лучшая цена), 5, 10,25,50,99
    $depthTotal= $Bin->depthTotal($symbol);
    // $Bin->show($depthTotal);

    //Определяем бал по спросу по груперовке
    $ball_depthTotal = $Bin->ball_depthTotal($symbol, $depthTotal);
    $symbol['spros'] = $ball_depthTotal['spros'];
    // $Bin->show($ball_depthTotal);
    
    //Список последних сделок
    // $trades= $Bin->trades($symbol);
    // $Bin->show($trades);

    //Список
    // $historicalTrades= $Bin->historicalTrades($symbol);
    // $Bin->show($historicalTrades);

    //Список
    // $aggTrades= $Bin->aggTrades($symbol);
    // $Bin->show($aggTrades);

    //Подсвечники для символ интервал 1m/3m/5m/15m/30m/1h/2h/4h/6h/8h/12h/1d/3d/1w/1M
    $Params = $symbol;
    $Params['interval']= '1m';
    $klines = array_reverse($Bin->klines($Params));
    // $Bin->showArrayTable($klines);

    $klines_trend= array();
    for ($i=0; $i < 5 ; $i++) {
        //находим среднее максимума и минима текущей свечи
        $average = ($klines[$i][2]+$klines[$i][3])/2;
        //находим среднее максимума и минима попередней свечи    
        $average_old = ($klines[$i+1][2]+$klines[$i+1][3])/2;
        $klines_trend[]=$average - $average_old;

        // echo date("Y-m-d H:i:s", $klines[$i][0]/1000), '  ***  ', $average, ' было ', $average_old, " -> ";
        // if ($average>$average_old ) {
        //     echo "растет ", ($average - $average_old), "<br/>";
        // }else{
        //     echo "падает ", ($average - $average_old), "<br/>";
        // }
    }

    // $Bin->show($klines_trend);
    // echo $klines_trend[0];


    //Определяем максимум минимум за периоды 0,5,10,25,50,100,200,300,500 мин 
    $max_min = $Bin->max_min($klines);  
     
    // $Bin->show($max_min);

// die();
    //Определяем бал свечам
    // $ball_trades = $Bin->ball_klines($symbol);
    // die();
    // $Bin->show($ball_trades);

    //Определяем бал по истории сделок
    // $ball_trades = $Bin->ball_trades($symbol);
    // $Bin->show($ball_trades);

    //Получить все заказы на счетах; активный, отмененный или заполненный
    // $sm="-3";
    // $time=strtotime("now".$sm." hour");
    // $symbol['startTime'] = round(microtime(true) * 1000)-43200000;
    $allOrders= $Bin->allOrders($symbol);
    // $Bin->show($allOrders);

    //Считаем доходность слделок и обеденяем в один масив
    if ($OrdersFILLED = $Bin->multiSearch($allOrders, array('status' => 'FILLED'))) {
        $lastpriceBUY = $lastpriceSELL = '';
        $total_1 = $total_12 = $total_24 = $total_All='';
        foreach ($OrdersFILLED as $key => $value) {
            //запоминаем последнюю закупку
            if ($value['side']!='SELL') {
                $lastpriceSELL = (float)$value['cummulativeQuoteQty']/$value['executedQty'];
                $OrdersFILLED[$key]['sumUSD'] = $value['cummulativeQuoteQty']* $usdPrice[$symbol['base']]['USD'];
                $OrdersFILLED[$key]['marginUSD'] = '';
                $OrdersFILLED[$key]['lastpriceSELL'] = '';                
                
            }
            //запоминаем последнюю продажу и высчитываем доходность
            if ($value['side']!='BUY') {
                $lastpriceBUY = (float)$value['cummulativeQuoteQty']/$value['executedQty'];
                $OrdersFILLED[$key]['sumUSD'] = $value['cummulativeQuoteQty']* $usdPrice[$symbol['base']]['USD'];
                $OrdersFILLED[$key]['marginUSD'] = round(($value['executedQty']*($lastpriceBUY-$lastpriceSELL)) * $usdPrice[$symbol['base']]['USD'], 2);
                $OrdersFILLED[$key]['lastpriceSELL'] = $Bin->round_min($lastpriceSELL, $symbolInfo[0]['filters'][0]['minPrice']);                
                
            } 

            //итог 1 часа
            if ($value['time'] > round(microtime(true) * 1000)-3600000) {
                $total_1 += $OrdersFILLED[$key]['marginUSD'];
            }
            //итог 12 часа
            if ($value['time'] > round(microtime(true) * 1000)-43200000) {
                $total_12 += $OrdersFILLED[$key]['marginUSD'];
            }
            //итог 24 часа
            if ($value['time'] > round(microtime(true) * 1000)-86400000) {
                $total_24 += $OrdersFILLED[$key]['marginUSD'];
            }
            //итог 10.01.2020
            if ($value['time'] > 1578614400000) {
                $total_All += $OrdersFILLED[$key]['marginUSD'];
            } 
        }
        //смотрим последние сделки и итоги по symbol
        // $Bin->showArrayTableOrders(array_reverse($OrdersFILLED), $usdPrice);
        // echo '1ч:', $total_1, ', 12ч: ', $total_12, ', 24ч: ', $total_24, ', All: ', $total_All, "<br/>";
        //умируем итоги
        $sum_total_1 += $total_1;
        $sum_total_12 += $total_12;        
        $sum_total_24 +=  $total_24;
        $sum_total_ALL += $symbol['total_All'] = $total_All; 
     

        //обеденяем сделки в один масив
        $AllOrdersFILLED = array_merge($AllOrdersFILLED, $OrdersFILLED);        
    }

        //Выбераем последнюю закупку 
        $OrdersBUY = $Bin->multiSearch($allOrders, array('status' => 'FILLED', 'side' => 'BUY'));
        // $Bin->showArrayTable($OrdersBUY);
        // $Bin->show(max($OrdersFILLED)); 
        
   

    //=======================ТОРГИ=========================== 
    //количество для покупки
    $quantityBUY = $Bin->round_min($symbol['limitUSD']/round($depthTotal[0]['askPrice']*$usdPrice[$symbol['base']]['USD'],8), $symbolInfo[0]['filters'][2]['minQty']);



    //Продаем (Если есть что)
    if ($accountBalance[$symbol['asset']]['total'] > $symbolInfo[0]['filters'][2]['minQty'] && count($OrdersBUY)&& max($OrdersFILLED)['side'] != 'SELL') {

        //Цена для продажи
       $bidPrice = $depthTotal[0]['bidPrice'];  
        // $symbol['bidPriceUSD'] = round($depthTotal[0]['bidPrice']*$usdPrice[$symbol['base']]['USD'],8);

         //Определяем маржу
        if (count($OrdersBUY)) {
            $symbol['mybidPrice']= max($OrdersBUY)['price'];
            $symbol['timeTrades']= date("Y-m-d H:i:s", max($OrdersBUY)['time']/1000);
            $margin= 1*strval($bidPrice - $symbol['mybidPrice']);
            $symbol['margin_%'] = round(($bidPrice-$symbol['mybidPrice']) / $symbol['mybidPrice'] *100, 2);

        }else{
            $symbol['mybidPrice']= '';
            $symbol['timeTrades']= '';
            $symbol['margin_%'] = '';
        }  

         //Количество для продажи 
            $quantitySELL_OrdersBUY = $Bin->round_min(max($OrdersBUY)['origQty'], $symbolInfo[0]['filters'][2]['minQty']);
            $quantitySELL_accountBalance = $Bin->round_min($accountBalance[$symbol['asset']]['total'], $symbolInfo[0]['filters'][2]['minQty']);        
        
            if ($quantitySELL_OrdersBUY < $quantitySELL_accountBalance) {

                $quantitySELL = $quantitySELL_OrdersBUY;

            }else{
                $quantitySELL = $quantitySELL_accountBalance;
                if ($symbol['asset'] =='BNB') {
                    $quantitySELL = $quantitySELL_accountBalance-10;
                }                

            }   
            

        if ($symbol['base'] == 'USDT') {
            
            $asksum =  round($accountBalance[$symbol['asset']]['total_BTC']*$bidPrice,8);
            $mybidsum = $accountBalance[$symbol['asset']]['total_BTC']*$symbol['mybidPrice'];     
            //Определяем комисии биржи
            $commission = round($tradeFeeKom[$symbol['symbol']]['taker'] * $asksum,8);
            //Определяем потенциальную МАРЖУ при продаже        
            $marginsum_base =  round(($quantitySELL*$margin) - $commission, 5);
           
        }else{
                        
            $asksum = $accountBalance[$symbol['asset']]['total']*$bidPrice;
            $mybidsum = $accountBalance[$symbol['asset']]['total']*$symbol['mybidPrice'];        
            //Определяем комисии биржи
            $commission = round($tradeFeeKom[$symbol['symbol']]['taker'] * $asksum,8); 
            //Определяем потенциальную МАРЖУ при продаже        
            $marginsum_base =  round(($quantitySELL*$margin) - $commission, 5);

        }

            //Определяем потенциальную МАРЖУ при продаже  в USD
            $symbol['marginsumUSD'] = round($marginsum_base * $usdPrice[$symbol['base']]['USD'], 6);

   

     


            //масив параметров для продажи
            $ParamsSELL = array('symbol'=>$symbol['symbol'], 
                            'side' => 'SELL', 
                            'type' => 'LIMIT', 
                            'quantity' => $quantitySELL,
                            'timeInForce' => 'GTC', 
                            'price' => $depthTotal[0]['bidPrice']);       
        

        //ПРИНИМАЕМ РЕШЕНИЕ о ПРОДАЖЕ  
        $sell = '';
        if($symbol['marginsum']['USD'] < 0 ){
            $sell = 'Ждем нет ПРОФИТА';

        }elseif($symbol['marginsum']['USD'] < 0 && $ball_depthTotal['spros'] < 1){
            $sell = 'ЖДЕМ СПРОСА НЕТ профита нет';

        }elseif($ball_depthTotal['spros']>=1.25) {
            $sell = 'Ждем СПРОС ЕСТЬ цена растет';

        }elseif($symbol['margin_%'] < -0.1 && $symbol['position'] == 'short' && $ball_depthTotal['spros'] < 1 && $klines_trend[0]<0 && $klines_trend[1]<0){
            $sell = 'Продаем Stop Loss'; 

            //Выбераем открытые ордера
            if ($orderOPEN = $Bin->multiSearch($allOrders, array('status' => 'NEW', 'side' => 'SELL'))) {
                // $Bin->showArrayTable($orderOPEN);
                //удаляем открытые ордера
                foreach ($orderOPEN as $key => $order) {
                        $orderDELETE = $Bin->orderDELETE($order);                   
                        // $Bin->show($orderDELETE);
                }
            } 
             
            // Создаем ордер на продажу по рыночным ценам
            $ParamsSELL['type'] = 'MARKET';
                // $Bin->show($ParamsSELL);
            if ($order = $Bin->orderNEW($ParamsSELL)) {
                # code...
            }            

        }elseif($ball_depthTotal['spros'] < 1.5 && $symbol['margin_%'] > 0.25 && $klines_trend[0]<0){
            $sell = 'Продаем';   
            //Выбераем открытые ордера
            if ($orderOPEN = $Bin->multiSearch($allOrders, array('status' => 'NEW', 'side' => 'SELL'))) {
                // $Bin->showArrayTable($orderOPEN);
                //удаляем открытые ордера
                foreach ($orderOPEN as $key => $order) {
                    $orderDELETE = $Bin->orderDELETE($order);                    
                    // $Bin->show($orderDELETE);
                }
            }    
             
            // Создаем ордер на продажу
            if ($order = $Bin->orderNEW($ParamsSELL)) {
                # code...
            }
               

        }else{
            $sell = 'растерялся';

        }
        $symbol['sell'] = $sell;
        //параметры продажи добавив в масив
        $symbol+= $ParamsSELL;
        // $Bin->show($ParamsSELL);
        // $symbol['minQty'] = $symbolInfo[0]['filters'][2]['minQty'];
        // $symbol['minPrice'] = $symbolInfo[0]['filters'][0]['minPrice'];
        $arraysell[] = $symbol;

        //записываем даные в файл

    }elseif($accountBalance[$symbol['base']]['total'] > $quantityBUY*$depthTotal[0]['askPrice'] && max($OrdersFILLED)['side'] != 'BUY' ) {//Покупаем (Если есть за что)
   

        //Выбераем последнюю продажу
        $OrdersSELL = $Bin->multiSearch($allOrders, array('status' => 'FILLED', 'side' => 'SELL'));
        // $Bin->showArrayTable($OrdersSELL);

        // if (count($OrdersSELL)) {
        //     $symbol['myaskPrice']= max($OrdersSELL)['price'];
        //     $symbol['timeTrades']= date("Y-m-d H:i:s", max($OrdersSELL)['time']/1000);
        // }else{
        //     $symbol['myaskPrice']= '';
        //     $symbol['timeTrades']= '';
        // }
        

          

        

        //масив параметров для покупки
            $ParamsBUY = array('symbol'=>$symbol['symbol'], 
                            'side' => 'BUY', 
                            'type' => 'LIMIT', 
                            'quantity' => $quantityBUY, 
                            'timeInForce' => 'GTC', 
                            'price' => $depthTotal[0]['askPrice']);
        


         //Некоторые параметры покупки добавив в масив 
        $symbol['kt_0m']=$klines_trend[0]>0?'+':'-';
        $symbol['kt_1m']=$klines_trend[1]>0?'+':'-';
        $symbol['kt_2m']=$klines_trend[2]>0?'+':'-';        

        $symbol['quantity']= $ParamsBUY['quantity'];
        $symbol['price']= $ParamsBUY['price'];
        $symbol['controlPrice'] = $max_min['240']['average']; 
        $symbol['cont'] = 'average';
        if ($OrdersFILLED[$key]['marginUSD']<0) {
            $symbol['controlPrice'] = $lastpriceBUY;
            $symbol['cont'] = 'lastpric';
        }
        $symbol['control'] = $ParamsBUY['price']-$symbol['controlPrice']>0?'not':'yes';

        //ПРИНИМАЕМ РЕШЕНИЕ О ПОКУПКЕ
        $buy ='';
        if($ball_depthTotal['spros'] < 0.5){   

            $buy =  'Ждем спроса нет цена падает';

        }elseif($ball_depthTotal['spros'] > 0.5 && $ball_depthTotal['spros'] < 1){

            $buy =  'Ждем БОКОВИК (КОНСОЛИДАЦИЯ)';

        }elseif($ball_depthTotal['spros'] > 1 && $symbol['controlPrice'] > $depthTotal[0]['askPrice']&& $klines_trend[0] > 0 && $klines_trend[1]<0 && $klines_trend[2]<0){

            $buy =  'Покупаем';

            //Выбераем открытые ордера
            if ($orderOPEN = $Bin->multiSearch($allOrders, array('status' => 'NEW', 'side' => 'BUY'))) {
                // $Bin->showArrayTable($orderOPEN);
                //удаляем открытые ордера
                foreach ($orderOPEN as $key => $order) {
                    $orderDELETE = $Bin->orderDELETE($order);                   
                    // $Bin->show($orderDELETE);
                }
            } 
  
            //Создаем ордер на покупку
            if ($order = $Bin->orderNEW($ParamsBUY)) {
                # code...
            }
             

        }else{

            $buy =  'растерялся';

        }
        $symbol['buy'] = $buy;

        // $symbol['minQty'] = $symbolInfo[0]['filters'][2]['minQty'];
        // $symbol['minPrice'] = $symbolInfo[0]['filters'][0]['minPrice'];
        // $Bin->show($ParamsBUY);
        
        $arraybuy[]= $symbol;
        //записываем даные в файл

    }else{

       $arrayexception[]= $symbol; 
      // $Bin->show($symbol);  
    }

        
        
}

echo "Продажи<br/>";
if (count($arraysell)) {
    $Bin->showArrayTable($arraysell);
}

echo "Закупки<br/>";
if (count($arraybuy)) {
    $Bin->showArrayTable($arraybuy);
}

echo "Исключения<br/>";
if (count($arrayexception)) {
    $Bin->showArrayTable($arrayexception);
}

echo "ПОСЛЕДНИЕ сделки<br/>";
usort($AllOrdersFILLED, function($a, $b) {
    return $a['time'] - $b['time'];
});
$Bin->showArrayTableOrders(array_reverse($AllOrdersFILLED), $usdPrice);

echo ' ИТОГО 1 час -> ', $sum_total_1,'$ <br/>';
echo ' ИТОГО 12 часа -> ', $sum_total_12,'$ <br/>';
echo ' ИТОГО 24 часа -> ', $sum_total_24,'$ <br/>';
echo ' ИТОГО c 10.01.2020 -> ', $sum_total_ALL,'$ <br/>';

echo "*** " . round($accountBalance['sumtotal_BTC'],2) ." *** " . $accountBalance['sumtotal_USD'] . " ***<br/>";


$time = microtime(true) - $start;
echo 'Время выполнения скрипта: '.round($time, 4).' сек.<br/><br/><br/>';

 


   


// $price = $depthTotal[0]['askPrice'];
// for ($i=0; $i < 3 ; $i++) {  
//    $price += '0.00000001';
//     $Params = array('symbol'=>$symbol['symbol'], 
//                     'side' => 'SELL', 
//                     'type' => 'LIMIT', 
//                     'quantity' => 20, 
//                     'timeInForce' => 'GTC', 
//                     'price' => $price);
//     $Bin->show($Params);
//     //Создаем ордер на продажу
//     // if ($order = $Bin->orderNEW($Params)) {
//     //     $Bin->show($order);
//     // }
    
// }

        // if ($orderOPEN = $Bin->orderOPEN($symbol)) {
        //     $Bin->show($orderOPEN);
        //     foreach ($orderOPEN as $key => $order) {
        //         // $orderSTATUS = $Bin->orderSTATUS($order);                   
        //         // $Bin->show($orderSTATUS);
        //         if ($order['side']== 'SELL') {
        //            // $Bin->show($order);
        //             $orderDELETE = $Bin->orderDELETE($order);                   
        //             $Bin->show($orderDELETE);
        //         }else{
        //             // $orderSTATUS = $Bin->orderSTATUS($order);                   
        //             // $Bin->show($orderSTATUS);
        //         }
        //     }

        // }





exit();
?>