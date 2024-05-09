<?php
// header('refresh: 1');
ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);


//Определяем базове валют
$base['USDT']= array('minBalans'=>100, 'minPriceBuy'=>0.00000100);
$base['BNB']= array('minBalans'=>1, 'minPriceBuy'=>0.00000100);

$trading_limit = 12;
// базовое OCO
$default_BUY_OCO = array('Distance' => 0.1, 'Price' => 0.95, 'S_Price' => 1.001, 'SL_Price' => 1.005);
$default_SELL_OCO = array('Distance' => -2.5, 'Price' => 1.006, 'S_Price' => 0.98, 'SL_Price' => 0.92);

$book_BUY_OCO = array( 'Distance' => 0.1, 'Price' => 0.95, 'S_Price' => 1.001, 'SL_Price' => 1.002);
$book_SELL_OCO = array('Distance' => -1, 'Price' => 1.01, 'S_Price' => 0.997, 'SL_Price' => 0.99);

//биржа
$exchange = 'binance';

$last_BUY_Price = [];

//зацикливаем процес
while (true):
    $start = microtime(true);
    // $mem_start = memory_get_usage();

    $bookTickerfile = __DIR__ . '/file/historyBookTicker/'.time().'.txt';
    $lastTickerfile = __DIR__ . '/file/bookTicker.txt';
    $dir_bookTicker = __DIR__ . '/file/historyBookTicker/';

    //
    require_once __DIR__ . '/model/binance_c.php';
    require_once __DIR__ . '/model/functions_c.php';
    require_once __DIR__ . '/model/indicators_c.php';
    require_once __DIR__ . '/model/user_c.php';
    // require "./libraries/binance_api/vendor/autoload.php";

    $Users = new Users('/home/pas/bot/strategies/');
    $Bin = new Binance('/home/pas/bot/file/');
    $Indicators = new Indicators();
    $audio = false;

    foreach ($Users->user_arrey as $key => $user) {

        $Bin->initialization($user[$exchange]['config']['KEY'], $user[$exchange]['config']['SEC']);
        if (!$bookTicker = Functions::readFile($lastTickerfile)) continue;

        //######################################################################################################################################

        //***** получаем открытые ордера и проверяем
        if ($orderOPEN = $Bin->orderOPEN(array())) {
            foreach ($orderOPEN as $key => $order) {
                if ($order['type'] != 'STOP_LOSS_LIMIT') continue;
                // echo $order['orderId' ], "<br/>";
                //Получаем информацию о symbol
                if (!$symbolInfo = Functions::multiSearch($Bin->exchangeInfo['symbols'], array('symbol' => $order['symbol'], 'status'=>'TRADING'))[0])  continue;
                if (!$ticker = Functions::multiSearch($bookTicker, array('symbol' => $order['symbol']))[0]) continue;

                $clientOrderId = explode('_', $order['clientOrderId']);
                $clientOrderId[2] ++;

                //если нужно переставляем ордера ПРОДАЖИ
                if ($order['side'] == 'SELL') {
                    //определяем дистанцию цены
                    $level_stopPrice = bcdiv(bcsub($order['stopPrice'], $ticker['bidPrice'],  8), $ticker['bidPrice'], 8)*100;
                    $SELL_OCO = $user[$exchange]['strategies'][$clientOrderId[0]]['SELL_OCO'];


                    if ($clientOrderId[0] == 'book'){
                        $orderOPEN[$key]['status_stopPrice'] =  $level_stopPrice.' < '. $book_SELL_OCO['Distance']. 'book step '. $clientOrderId[2];
                        if (-1 == bccomp($level_stopPrice, $book_SELL_OCO['Distance'], 8))  $param = $book_SELL_OCO;
                    }elseif($clientOrderId[0] == 'default' || $clientOrderId[0] == 'web'){
                        $orderOPEN[$key]['status_stopPrice'] =  $level_stopPrice.' < '. $default_SELL_OCO['Distance']. 'default will be step '. $clientOrderId[2];
                        if (-1 == bccomp($level_stopPrice, $default_SELL_OCO['Distance'], 8))  $param = $default_SELL_OCO;
                    }else{
                        if ($clientOrderId[2] == 1) $orderOPEN[$key]['status_stopPrice'] =  $level_stopPrice.' < '. $SELL_OCO[1]['Distance']. ' will be step '. $clientOrderId[2];
                        if (-1 == bccomp($level_stopPrice, $SELL_OCO[1]['Distance'], 8) && $clientOrderId[2] == 1)  $param = $SELL_OCO[1];


                        if ($clientOrderId[2] > 1) $orderOPEN[$key]['status_stopPrice'] =  $level_stopPrice.' < '. $SELL_OCO[2]['Distance']. ' will be step '. $clientOrderId[2];
                        if (-1 == bccomp($level_stopPrice, $SELL_OCO[2]['Distance'], 8) && $clientOrderId[2] > 1 )  $param = $SELL_OCO[2];
                    }



                    if (empty($param)) continue;
                    if ($orderDELETE = $Bin->orderDELETE(array('symbol'=>$order['symbol'], 'orderId'=>$order['orderId']))){
                        $minPrice = Functions::multiSearch($symbolInfo['filters'], array('filterType' => 'PRICE_FILTER'))[0]['tickSize'];
                        $Params_SELL_OCO = array('symbol'=>$order['symbol'],
                                                'side' => 'SELL',
                                                'quantity' => $orderDELETE['orderReports'][1]['origQty'],
                                                'price' => $Bin->round_min(bcmul($ticker['bidPrice'], $param['Price'], 8), $minPrice),
                                                'stopPrice' => $Bin->round_min(bcmul($ticker['bidPrice'], $param['S_Price'], 8), $minPrice),
                                                'stopLimitPrice' => $Bin->round_min(bcmul($ticker['bidPrice'], $param['SL_Price'], 8), $minPrice),
                                                'stopLimitTimeInForce' => 'GTC',
                                                'listClientOrderId'  => $clientOrderId[0].uniqid('_Pa').'_'.$clientOrderId[2],
                                                'limitClientOrderId'  => $clientOrderId[0].uniqid('_Pb').'_'.$clientOrderId[2],
                                                'stopClientOrderId'  => $clientOrderId[0].uniqid('_Pc').'_'.$clientOrderId[2]);
                        if ($orderOCO = $Bin->newOCO($Params_SELL_OCO)) {
                            //LOG
                        }

                    }
                unset($param);
                }//конец ордера ПРОДАЖИ

                //если нужно переставляем ордера ПОКУПКИ
                if ($order['side'] == 'BUY') {
                    //определяем дистанцию цены
                    $level_stopPrice = bcdiv(bcsub($order['stopPrice'], $ticker['askPrice'],  8), $ticker['askPrice'], 8)*100;
                    $BUY_OCO = $user[$exchange]['strategies'][$clientOrderId[0]]['BUY_OCO'];


                    if (1 == bccomp($level_stopPrice, $BUY_OCO[0]['Distance'], 8))  $param_BUY_OCO = $BUY_OCO[0];

                    $orderOPEN[$key]['status_stopPrice'] =  $level_stopPrice.' > '. $BUY_OCO[0]['Distance']. ' will be step '. $clientOrderId[2];

                    if (empty($param_BUY_OCO)) continue;
                    if ($orderDELETE = $Bin->orderDELETE(array('symbol'=>$order['symbol'], 'orderId'=>$order['orderId']))) {
                        $orderOPEN[$key]['status_stopPrice'] .= " удаляю";
                        $minPrice = Functions::multiSearch($symbolInfo['filters'], array('filterType' => 'PRICE_FILTER'))[0]['tickSize'];

                        $Params_END_BUY_OCO = array('symbol'=>$order['symbol'],
                                                    'side' => 'BUY',
                                                    'quantity' => $orderDELETE['orderReports'][1]['origQty'],
                                                    'price' => $Bin->round_min(bcmul($ticker['askPrice'], $param_BUY_OCO['Price'], 8), $minPrice),
                                                    'stopPrice' => $Bin->round_min(bcmul($ticker['askPrice'], $param_BUY_OCO['S_Price'], 8), $minPrice),
                                                    'stopLimitPrice' => $Bin->round_min(bcmul($ticker['askPrice'], $param_BUY_OCO['SL_Price'], 8), $minPrice),
                                                    'stopLimitTimeInForce' => 'GTC',
                                                    'listClientOrderId'  => $clientOrderId[0].uniqid('_Za').'_'.$clientOrderId[2],
                                                    'limitClientOrderId'  => $clientOrderId[0].uniqid('_Zb').'_'.$clientOrderId[2],
                                                    'stopClientOrderId'  => $clientOrderId[0].uniqid('_Zc').'_'.$clientOrderId[2]);
                        if ($orderOCO = $Bin->newOCO($Params_END_BUY_OCO)) {
                            //LOG
                        }
                    }
                unset($param_BUY_OCO);
                } //конец ордера ПОКУПКИ

            }//цикл ордера
        }//конец условие наличия открытые ордера

        //***** стираем память последней закупки
        if (count($last_BUY_Price) > 0 && count($orderOPEN)>0) {
            foreach($last_BUY_Price as $key_symbol => $BUY_Price){
                if (!in_array($key_symbol, array_column($orderOPEN, 'symbol'))) {
                    unset($last_BUY_Price[$key_symbol]);
                }
            }
        }

        //######################################################################################################################################

        //***** Получить свободный баланс
        if (!$accountBalance = $Bin->accountBalance($base)) continue;

        //проверяем free балансы и ставим ОСО
        foreach ($accountBalance as $key => $balans) {
            //Получаем информацию о symbol и исключаем неактивные пары
            if ($balans['asset'] == 'USDT') continue; // не USDT
            if (!$symbolInfo = Functions::multiSearch($Bin->exchangeInfo['symbols'], array('symbol' => $balans['asset'].'USDT', 'status'=>'TRADING'))[0])  continue; //не торгуется
            if (!$ticker = Functions::multiSearch($bookTicker, array('symbol' => $balans['asset'].'USDT'))) continue; //получаем последние цены

            //На free активы ставим OCO
            $balancequantitySELL = bcsub(bcsub($balans['total'], $balans['locked'], 4), $balans['min'], 4);
            if (bcmul($balancequantitySELL, $ticker[0]['bidPrice'], 8)<12) continue;


            //Получить все заказы
            if ($allOrders= $Bin->allOrders(array('symbol' => $balans['asset'].'USDT', 'limit' =>'100'))) {
                //Выбераем закупки
                if (count($allOrders)>0) $ordersBUY = Functions::multiSearch($allOrders, array( 'status' => 'EXPIRED','status' => 'FILLED', 'side' => 'BUY'));

                //Выбераем последний ордер закупки по symbol
                if (count($ordersBUY)>0){
                    $clientOrderId = explode('_', max($ordersBUY)['clientOrderId']);
                    $SELL_OCO = $user[$exchange]['strategies'][$clientOrderId[0]]['SELL_OCO'];
                }
            }
            if (empty($SELL_OCO[0])|| $SELL_OCO[0]==''){
                $SELL_OCO[0] = $default_SELL_OCO;
                if (empty($clientOrderId[0])|| $clientOrderId[0]=='') $clientOrderId[0] = 'default';
            }

            $minQty = Functions::multiSearch($symbolInfo['filters'], array('filterType' => 'LOT_SIZE'))['0']['stepSize'];
            $minPrice = Functions::multiSearch($symbolInfo['filters'], array('filterType' => 'PRICE_FILTER'))['0']['tickSize'];
            $Params_SELL_OCO = array('symbol'=>$balans['asset'].'USDT',
                                      'side' => 'SELL',
                                      'quantity' => $Bin->round_min($balancequantitySELL, $minQty),
                                      'price' => $Bin->round_min(bcmul($ticker[0]['bidPrice'], $SELL_OCO[0]['Price'], 8), $minPrice),
                                      'stopPrice' => $Bin->round_min(bcmul($ticker[0]['bidPrice'], $SELL_OCO[0]['S_Price'], 8), $minPrice),
                                      'stopLimitPrice' => $Bin->round_min(bcmul($ticker[0]['bidPrice'], $SELL_OCO[0]['SL_Price'], 8), $minPrice),
                                      'stopLimitTimeInForce' => 'GTC',
                                      'listClientOrderId'  => $clientOrderId[0].uniqid('_Fa').'_0',
                                      'limitClientOrderId'  => $clientOrderId[0].uniqid('_Fb').'_0',
                                      'stopClientOrderId'  => $clientOrderId[0].uniqid('_Fc').'_0');

            if ($orderOCO = $Bin->newOCO($Params_SELL_OCO)) {
                //LOG
            }
        }

        //***** Проверяем наличие USDT (если нет пропускаем)
        if ($accountBalance['USDT']['free'] < 11) goto end;

        //***** покупаем BNB если их баланс менее 0.3
        // if ($accountBalance['BNB']['free'] < 0.3) {
            
        //     //масив параметров для покупки  BNB
        //     $ParamsBUY = array('symbol'=>'BNBUSDT',
        //                     'side' => 'BUY',
        //                     'type' => 'MARKET',
        //                     'quantity' => 1,
        //                     'timeInForce' => 'IOC',
        //                     'newClientOrderId'=>uniqid('buyBNB_'));
        //     //ПОКУПКА BNB
        //     if (!$order = $Bin->orderNEW($ParamsBUY)) {
        //         //LOG
        //     }
        // }

        //#####################################################################################################################################
        $testSymbolsfile = __DIR__ . '/file/'.$user['login'].'/testSymbols.txt';
        $testBUYfile = __DIR__ . '/file/'.$user['login'].'/testBUY.txt';
        if (!$testBUY =  Functions::readFile($testBUYfile)){
            $testBUY = [];
        }
        //***** Контролируем скачки цены по bookTicker
        $All_analytics_file = __DIR__.'/file/analyticsSymbol/All_analytics.txt';
        if (!$All_analytics = Functions::readFile($All_analytics_file)) {
            echo "аналитики нет";
            goto next; //если аналитики нет пропукаем

        }
        //Читаем нужные файлы  и удаляем старые***********
        $files = array_reverse(scandir($dir_bookTicker, 1));//сканируем директорию с файлами
        // Functions::show($files);

        //*****ОТБИРАЕМ НУЖНЫЕ ФАЙЛЫ и смотрим возраст контрольных файлов
        $time = time();
        $today = getdate();
        $historyBookTicker =[];
        // Functions::show($today, 'filegetdate');

        foreach ($files as $key => $name) {
            $file = $dir_bookTicker.$name;
            if (!is_file($file)) continue;//проверяем существование файла

            $filemtime = filemtime($file);
            $filegetdate = getdate($filemtime);
            // Functions::show($filemtime, 'filegetdate');

            if ($time - $filemtime > 60*24) unlink($file);// удаляем ненужные более 10 дней

            $bookTickerHis['type'] ='';
            if ($today['minutes'] == $filegetdate['minutes'] && $today['hours'] == $filegetdate['hours'] && $today['mday'] == $filegetdate['mday']&&$today['wday'] == $filegetdate['wday']) { //выбераем даные за текущую менуту
                if ($bookTickerHis =  Functions::readFile($file)){
                    $bookTickerHis['current'] = 'current';
                    $bookTickerHis['type'] = 'minutes';
                    $bookTickerHis['filemtime'] = $filemtime;
                    $historyBookTicker[] = $bookTickerHis;
                }
            }elseif ($time - $filemtime < 60) {      //выбераем даные за 60 секунд
                if ($bookTickerHis =  Functions::readFile($file)){
                    $bookTickerHis['current'] = '';
                    $bookTickerHis['type'] = 'minutes';
                    $bookTickerHis['filemtime'] = $filemtime;
                    $historyBookTicker[] = $bookTickerHis;
                }
            }elseif(($key%10) == 0 && $today['hours'] == $filegetdate['hours'] && $today['mday'] == $filegetdate['mday'] && $today['wday'] == $filegetdate['wday']) {//даные текущий час
                if ($bookTickerHis =  Functions::readFile($file)){
                    $bookTickerHis['current'] = 'current';
                    $bookTickerHis['type'] = 'hours';
                    $bookTickerHis['filemtime'] = $filemtime;
                    $historyBookTicker[] = $bookTickerHis;
                }
            }elseif(($key%10) == 0 && $time - $filemtime < 60*60) {     //выбераем даные текущий последниие 60 мин
                if ($bookTickerHis =  Functions::readFile($file)){
                    $bookTickerHis['current'] = '';
                    $bookTickerHis['type'] = 'hours';
                    $bookTickerHis['filemtime'] = $filemtime;
                    $historyBookTicker[] = $bookTickerHis;
                }
            }elseif (($key%500) == 0 && $today['mday'] == $filegetdate['mday'] && $today['wday'] == $filegetdate['wday']) {   //выбераем даные за текущий день
                if ($bookTickerHis = Functions::readFile($file)){
                    $bookTickerHis['current'] = 'current';
                    $bookTickerHis['type'] = 'mday';
                    $bookTickerHis['filemtime'] = $filemtime;
                    $historyBookTicker[] = $bookTickerHis;
                }
            }elseif(($key%500) == 0 && $time - $filemtime < 60*60*24) {     //выбераем даные за последние 24 час
                if ($bookTickerHis = Functions::readFile($file)){
                    $bookTickerHis['current'] = '';
                    $bookTickerHis['type'] = 'mday';
                    $bookTickerHis['filemtime'] = $filemtime;
                    $historyBookTicker[] = $bookTickerHis;
                }
            }elseif ((($key)%1500)==0 && $today['wday'] == $filegetdate['wday']) {   //выбераем даные за текущую неделю
                if ($bookTickerHis = Functions::readFile($file)){
                    $bookTickerHis['current'] = 'current';
                    $bookTickerHis['type'] = 'wday';
                    $bookTickerHis['filemtime'] = $filemtime;
                    $historyBookTicker[] = $bookTickerHis;
                }
            }elseif ((($key)%1500)==0 && $time - $filemtime > 60*60*24*7) {   //выбераем даные за последние 7 дней
                if ($bookTickerHis = Functions::readFile($file)){
                    $bookTickerHis['current'] = '';
                    $bookTickerHis['type'] = 'mday';
                    $bookTickerHis['filemtime'] = $filemtime;
                    $historyBookTicker[] = $bookTickerHis;
                }
            }

            // //смотрим возраст файлов
            // if ($bookTickerHis['type'] !='') {
            //   echo $bookTickerHis['type'], ' ', $bookTickerHis['current'], ' ', date("H:i:s", $filemtime), ' возраст: ', date("H:i:s", mktime(0, 0, $time - $filemtime)),  "<br/>";
            // }
        }

        //проходим циклом по всем парам
        $arrey_symbols = [];
        foreach ($bookTicker as $key => $ticker) {
            //Получаем информацию о symbol
            if (!$symbolInfo = Functions::multiSearch($Bin->exchangeInfo['symbols'], array('symbol' => $ticker['symbol'], 'status'=>'TRADING')))  continue;
            // Исключаем если база не USDT
            if ($symbolInfo[0]['quoteAsset']!='USDT') continue;


            $type = '';
            $max = $min = 0;
            foreach ($historyBookTicker as $keyHis => $bookTickerHis) {
                if ($ticker['symbol'] != $bookTickerHis[$key]['symbol'] ) continue;
                if ($keyHis == 0 || $type!= $bookTickerHis['type']) {

                    $type = $bookTickerHis['type'];
                    $max = $bookTickerHis[$key]['askPrice'];
                    $min = $bookTickerHis[$key]['askPrice'];

                    $symbol['symbol'] = $ticker['symbol'];
                    $symbol['*'.$bookTickerHis['type'].'*'] = 0;
                    $symbol['time_'.$bookTickerHis['type']] = $bookTickerHis['filemtime'];
                    $symbol['open_'.$bookTickerHis['type']] = $bookTickerHis[$key]['askPrice'];
                }

                // echo $ticker['symbol'], ' ', $bookTickerHis['type'], ' ', date("H:i:s", $bookTickerHis['filemtime']), "<br/>";

                if ($bookTickerHis['type']=='wday') {
                        //находим максимум
                        if (-1 == bccomp((string)$max, (string)$bookTickerHis[$key]['askPrice'], 8)) {
                            $max = $bookTickerHis[$key]['askPrice'];
                        }
                        $symbol['max_wday'] = $max;

                        //находим минимут
                        if (1 == bccomp((string)$min, (string)$bookTickerHis[$key]['askPrice'], 8)) {
                            $min = $bookTickerHis[$key]['askPrice'];
                        }
                        $symbol['min_wday'] = $min;
                        $symbol['*'.$bookTickerHis['type'].'*']++;
                }elseif($bookTickerHis['type']=='mday') {
                        //находим максимум
                        if (-1 == bccomp((string)$max, (string)$bookTickerHis[$key]['askPrice'], 8)) {
                            $max = $bookTickerHis[$key]['askPrice'];
                        }
                        $symbol['max_mday'] = $max;

                        //находим минимут
                        if (1 == bccomp((string)$min, (string)$bookTickerHis[$key]['askPrice'], 8)) {
                            $min = $bookTickerHis[$key]['askPrice'];
                        }
                        $symbol['min_mday'] = $min;
                        $symbol['*'.$bookTickerHis['type'].'*']++;
                }elseif($bookTickerHis['type']=='hours') {
                        //находим максимум
                        if (-1 == bccomp((string)$max, (string)$bookTickerHis[$key]['askPrice'], 8)) {
                            $max = $bookTickerHis[$key]['askPrice'];
                        }
                        $symbol['max_hours'] = $max;

                        //находим минимут
                        if (1 == bccomp((string)$min, (string)$bookTickerHis[$key]['askPrice'], 8)) {
                            $min = $bookTickerHis[$key]['askPrice'];
                        }
                        $symbol['min_hours'] = $min;
                        $symbol['*'.$bookTickerHis['type'].'*']++;
                }elseif($bookTickerHis['type']=='minutes') {
                        //находим максимум
                        if (-1 == bccomp((string)$max, (string)$bookTickerHis[$key]['askPrice'], 8)) {
                            $max = $bookTickerHis[$key]['askPrice'];
                        }
                        $symbol['max_minutes'] = $max;

                        //находим минимут
                        if (1 == bccomp((string)$min, (string)$bookTickerHis[$key]['askPrice'], 8)) {
                            $min = $bookTickerHis[$key]['askPrice'];
                        }
                        $symbol['min_minutes'] = $min;
                        $symbol['*'.$bookTickerHis['type'].'*']++;
                }
            }
            $symbol['***'] = '';
            $symbol['volontil_h'] = bcdiv($symbol['max_hours'], $symbol['min_hours'], 4);

            $symbol['coef_wday'] = bcdiv($symbol['open_mday'], $symbol['open_wday'],  4);
            $symbol['coef_mday'] = bcdiv($symbol['open_hours'], $symbol['open_mday'],  4);
            $symbol['coef_hours'] = bcdiv($symbol['open_minutes'], $symbol['open_hours'],  4);
            $symbol['coef_minutes'] = bcdiv($ticker['askPrice'], $symbol['open_minutes'],  4);

            $symbol['askPrice'] = $ticker['askPrice'];
            $symbol['bidPrice'] = $ticker['bidPrice'];

            $symbol['spred_p'] = bcmul(bcdiv(bcsub($ticker['askPrice'], $ticker['bidPrice'], 8), $ticker['bidPrice'], 8), 100, 3);


            $book = ['min_volontil_h'=>1.005,
                    'min_count_operation'=>500,
                    'max_spred_p'=>0.05,
                    'max_count_order'=>20,
                    'next_order_Price_BUY'=>-0.001,

                    'min_coef_wday'=> 0.9,
                    'min_coef_mday'=> 0.95,
                    'min_coef_hours'=> 0.98,
                    'min_coef_minutes'=> 1.001];

            // if (!$book['min_coef_wday'] = $All_analytics['BTCUSDT']['indicator_klin_Coefficient_1m']['down_avg']) continue;
            // if (!$book['min_coef_mday'] = $All_analytics['BTCUSDT']['indicator_klin_Coefficient_1m']['down_avg']) continue;
            //
            // if (!$book['min_coef_hours'] = $All_analytics[$ticker['symbol']]['indicator_klin_Coefficient_1h']['down_min']) continue;


            if(isset($All_analytics[$ticker['symbol']]['indicator_klin_Coefficient_1h']['down_min']) && $All_analytics[$ticker['symbol']]['indicator_klin_Coefficient_1h']['down_min'] < 0){
                $down_min = $All_analytics[$ticker['symbol']]['indicator_klin_Coefficient_1h']['down_min'];
                $down_avg = $All_analytics[$ticker['symbol']]['indicator_klin_Coefficient_1h']['down_avg'];
                $delta = bcdiv(bcsub($down_avg, $down_min, 8), 3, 2);
                $book['min_coef_hours'] = bcsub($down_avg, $delta, 8);
            }else {
                continue;
            }
            // echo $book['min_coef_hours'], "<br/>";
            if (!$book['min_coef_minutes'] = $All_analytics[$ticker['symbol']]['indicator_klin_Coefficient_1m']['up_avg']) continue;

            //**************************************************************************
            //проверяем тестовые закупки
            if ($bookSymbolOpen = Functions::multiSearch($testBUY, array('symbol' => $ticker['symbol'], 'status'=>''))){
                // Functions::showArrayTable(max($bookSymbolOpen), 'testBUY');
                Functions::test_check_book_OCO($testBUY, $ticker);
                $lastBUY = max($bookSymbolOpen)['Price_BUY'];
            }else{
                $lastBUY = $symbol['askPrice'];
            }

            $symbolticker24hr = Functions::multiSearch($Bin->ticker24hr, array('symbol' => $ticker['symbol']))[0];
            // Исключаем с количеством операций (24 часа) меньше
            if ($symbolticker24hr['count'] < $book['min_count_operation']) continue;

            //условие минимальной волонтильности за последний час
            if (-1== bccomp($symbol['volontil_h'], $book['min_volontil_h'], 8))  continue;

            //условие есть открыт
            if (in_array($ticker['symbol'], array_column($orderOPEN, 'symbol'))) continue;

            //спред
            // if (1== bccomp((string)$symbol['spred_p'], $book['max_spred_p'], 8))  continue;

            //цена нового ниже последнего открытого
            // if (1 == bccomp(bcdiv($symbol['askPrice'], $lastBUY, 8), $book['next_order_Price_BUY'], 8) ) continue;

            //максимальное количество открытых
            if (count($bookSymbolOpen) >= $book['max_count_order']) continue;


            // if (1== bccomp($symbol['coef_wday'], $book['min_coef_wday'], 8))  continue;  //условие кофициент нидели
            // if (1== bccomp($symbol['coef_mday'], $book['min_coef_mday'], 8))  continue;  //условие кофициент дня
            if (1== bccomp($symbol['coef_hours'], $book['min_coef_hours'], 8))  continue;  //условие кофициент часа
            if (-1== bccomp($symbol['coef_minutes'], $book['min_coef_minutes'], 8))  continue;  //условие кофициент минуты


            // if ($ticker['symbol'] != 'ETHUSDT')  continue;

             $arrey_symbols[] = $symbol;
            // Functions::showArrayTable($arrey_symbols, 'arrey_symbols');

            //**************************************************************************
            Functions::test_book_OCO($testBUY, $symbol, $trading_limit, $book_SELL_OCO);
            //***************************************************************************

            //выставляем ОСО закупки
            if (false) {
                $minQty = Functions::multiSearch($symbolInfo[0]['filters'], array('filterType' => 'LOT_SIZE'))['0']['stepSize'];
                $minPrice = Functions::multiSearch($symbolInfo[0]['filters'], array('filterType' => 'PRICE_FILTER'))['0']['tickSize'];
                //масив параметров
                $Params_BUY_OCO = array('symbol'=>$value['symbol'],
                                            'side' => 'BUY',
                                            'quantity' => $Bin->round_min(bcdiv($trading_limit, bcmul($value['askPrice'], $kurs['kursUSD'], 8), 8), $minQty),
                                            'price' => $Bin->round_min(bcmul($value['askPrice'], $book_BUY_OCO['Price'], 8), $minPrice),
                                            'stopPrice' => $Bin->round_min(bcmul($value['askPrice'], $book_BUY_OCO['S_Price'], 8), $minPrice),
                                            'stopLimitPrice' => $Bin->round_min(bcmul($value['askPrice'], $book_BUY_OCO['SL_Price'], 8), $minPrice),
                                            'stopLimitTimeInForce' => 'GTC',
                                            'listClientOrderId'  => 'book'.uniqid('_Sa').'_0',
                                            'limitClientOrderId'  => 'book'.uniqid('_Sb').'_0',
                                            'stopClientOrderId'  => 'book'.uniqid('_Sc').'_0');
                if ($orderOCO = $Bin->newOCO($Params_BUY_OCO)) {
                    //LOG
                }
            }


        }

        // usort($arrey_symbols, function($a, $b) {
        //      return abs($a['coef_hours']*10000) - abs($b['coef_hours']*10000);
        // });

        // // $arrey_symbols = array_slice($arrey_symbols, 0, 0);
        // foreach ($arrey_symbols as $key => $value) {
        //     if (!$symbolInfo = Functions::multiSearch($Bin->exchangeInfo['symbols'], array('symbol' => $value['symbol'], 'status'=>'TRADING')))  continue;
        //     //Получаем курс BTC USD
        //     $kurs = $Bin->kurs($symbolInfo[0]['quoteAsset']);

        //     $arrey_symbols[$key]['status_BUY'] = " BUY";

        //     //купаем по MARKET и ставим ОСО
        //     if (2==1){
        //         //Получаем курс BTC USD
        //         $kurs = $Bin->kurs($symbolInfo['quoteAsset']);
        //         $minQty = Functions::multiSearch($symbolInfo['filters'], array('filterType' => 'LOT_SIZE'))['0']['stepSize'];
        //         //параметры BUY LIMIT IOC
        //         $Params_BUY = array('symbol'=>$strateg['symbol'],
        //                             'side' => 'BUY',
        //                             'type' => 'MARKET',
        //                             'quantity' => $Bin->round_min(bcdiv($trading_limit, bcmul($value['askPrice'], $kurs['kursUSD'], 8), 8), $minQty),
        //                             'timeInForce' => 'IOC',
        //                             'newClientOrderId'=> 'book'.uniqid('_'));
        //         //отправляем BUY ордер
        //         if ($order = $Bin->orderNEW($Params_BUY)) {
        //             //при успехе BUY выставляем ОСО ордера
        //             if (0 != bccomp((string)$order['executedQty'], (string)0, 8)) {
        //                 $priceBUY = bcdiv($order['cummulativeQuoteQty'], $order['executedQty'], 8);
        //                 $minPrice = Functions::multiSearch($symbolInfo['filters'], array('filterType' => 'PRICE_FILTER'))['0']['tickSize'];
        //                 //масив параметров SELL_OCO
        //                 $Params_SELL_OCO = array('symbol'=>$value['symbol'],
        //                                       'side' => 'SELL',
        //                                       'quantity' => $order['executedQty'],
        //                                       'price' => $Bin->round_min(bcmul($priceBUY, $book_SELL_OCO['Price'], 8), $minPrice),
        //                                       'stopPrice' => $Bin->round_min(bcmul($priceBUY, $book_SELL_OCO['S_Price'], 8), $minPrice),
        //                                       'stopLimitPrice' => $Bin->round_min(bcmul($priceBUY, $book_SELL_OCO['SL_Price'], 8), $minPrice),
        //                                       'stopLimitTimeInForce' => 'GTC',
        //                                       'listClientOrderId'  => 'book'.uniqid('_Sa').'_0',
        //                                       'limitClientOrderId'  => 'book'.uniqid('_Sb').'_0',
        //                                       'stopClientOrderId'  => 'book'.uniqid('_Sc').'_0');
        //                 //отправляем SELL ОСО
        //                 if ($orderOCO = $Bin->newOCO($Params_SELL_OCO)) {
        //                     //LOG
        //                 }
        //             }
        //         }
        //     }

        // }
        //сохраняем тестовые закупки
        Functions::saveFile($arrey_symbols, $testSymbolsfile);
        chown($arrey_symbols, 'pas');
        Functions::saveFile($testBUY, $testBUYfile);
        chown($testBUY, 'pas');


        next:
        //######################################################################################################################################
        //
        //***** проверяем наличие стратегий  (если нет пропускаем)
        if (count($user[$exchange]['strategies'])==0) goto end;

        //***** проверяем стратегии на покупку
        $strategies = &$user[$exchange]['strategies'];
        $last_strateg = [];
        foreach ($strategies as $key => &$strateg) {
            if ($strateg['status']=='OFF') continue; //Исключаем отключеные стратегии

            $strateg['status_indicator'] = '';
            $strateg['status_BUY'] = '';
            $strateg['open']= '';

            $status_ind = '';
            $status_BUY = '';

            //Получаем информацию о symbol
            if (!$symbolInfo = Functions::multiSearch($Bin->exchangeInfo['symbols'], array('symbol' => $strateg['symbol'], 'status'=>'TRADING'))[0]) continue;
            if (!$ticker = Functions::multiSearch($bookTicker, array('symbol' => $strateg['symbol']))[0]) continue;

            //отбыраем открытые ордера symbol стратегии проверяем условия
            if (count($orderOPEN)>0) {
                if ($open = Functions::multiSearch($orderOPEN, array('symbol' => $strateg['symbol']))){
                    //проверяем открыты ордира по стратегии
                    $openStrateg = 0;
                    foreach ($open as $key => $value) {
                        if (stristr($value['clientOrderId'], $strateg['key']) && $value['type'] == 'STOP_LOSS_LIMIT') {
                            $openStrateg ++;
                        }
                    }

                    $strateg['open'] = $openStrateg;
                    if ($openStrateg >= 5) continue;       //открытых по стратегии неболее
                    //проверяем общее число max_ALGO
                        $max_ALGO = Functions::multiSearch($symbolInfo['filters'], array('filterType' => 'MAX_NUM_ALGO_ORDERS'))['0']['maxNumAlgoOrders'];
                        $algo_orders = count(array_unique(array_column($open, 'orderListId')))+1;
                    if ($algo_orders >= $max_ALGO) continue;        //лимит открытых по symbol algo_orders

                    //проверяем общее число max_open
                        $max_open = Functions::multiSearch($symbolInfo['filters'], array('filterType' => 'MAX_NUM_ORDERS'))['0']['maxNumOrders'];
                    if (count($open)+1 >= $max_open) continue;      //лимит открытых по symbol

                    //проверяем цену последней закупки открытых ордеров
                    if (array_key_exists($strateg['symbol'], $last_BUY_Price)) {
                        if (bccomp(bcdiv($ticker['askPrice'], $last_BUY_Price[$strateg['symbol']]['askPrice'], 8), 0.002, 8)!=1) continue; //цена
                        if (bccomp(bcsub(microtime(true), $last_BUY_Price[$strateg['symbol']]['time'], 8), $Bin->interval[$strateg['interval']], 8)!=1) continue; //время
                    }

                }
            }

            //Получаем курс BTC USD
            $kurs = $Bin->kurs($symbolInfo['quoteAsset']);

            //получаем индикаторы торговой пары
            if ($last_strateg['symbol'] != $strateg['symbol'] || $last_strateg['interval'] != $strateg['interval']) {
                $all_indicator = $Indicators->all_indicator($strateg['symbol'], $strateg['interval']);
                $last_strateg = $strateg;
            }


            //Проверяем индикаторы стратегии
            $control = count($strateg['indicator_arrey']);
            $yes = 0;
            //Проверяем индикаторы и плюсуем подтверждение
            foreach ($strateg['indicator_arrey'] as $key=> $indicator) {
                $yes += Functions::comparison_indicator($all_indicator[$indicator['indicator']], $indicator['operator'], $indicator['value']);
                //формируем статус проверки индикатора
                $statuc = $yes == 1?'ДА':'НЕТ';
                $status_ind .= $statuc.' => '.$indicator['indicator']. ': '. $all_indicator[$indicator['indicator']]. ' '. $indicator['operator']. ' '. $indicator['value'].'<br/>';
            }
            //если сразу покупаем
            $status_BUY .= 'indicator '.$yes.' -> '.$control;

            //если выставляем ОСО закупки
            if ($control == $yes) {
                $status_BUY .= " OCO на BUY";
                $minQty = Functions::multiSearch($symbolInfo['filters'], array('filterType' => 'LOT_SIZE'))['0']['stepSize'];
                $minPrice = Functions::multiSearch($symbolInfo['filters'], array('filterType' => 'PRICE_FILTER'))['0']['tickSize'];
                $BUY_OCO = $user[$exchange]['strategies'][$strateg['key']]['BUY_OCO'];
                //масив параметров SELL_OCO
                $Params_BUY_OCO = array('symbol'=>$strateg['symbol'],
                                            'side' => 'BUY',
                                            'quantity' => $Bin->round_min(bcdiv($strateg['trading_limit'], bcmul($ticker['askPrice'], $kurs['kursUSD'], 8), 8), $minQty),
                                            'price' => $Bin->round_min(bcmul($ticker['askPrice'], $BUY_OCO[0]['Price'], 8), $minPrice),
                                            'stopPrice' => $Bin->round_min(bcmul($ticker['askPrice'], $BUY_OCO[0]['S_Price'], 8), $minPrice),
                                            'stopLimitPrice' => $Bin->round_min(bcmul($ticker['askPrice'], $BUY_OCO[0]['SL_Price'], 8), $minPrice),
                                            'stopLimitTimeInForce' => 'GTC',
                                            'listClientOrderId'  => $strateg['key'].uniqid('_Sa').'_0',
                                            'limitClientOrderId'  => $strateg['key'].uniqid('_Sb').'_0',
                                            'stopClientOrderId'  => $strateg['key'].uniqid('_Sc').'_0');
                if ($orderOCO = $Bin->newOCO($Params_BUY_OCO)) {
                    $last_BUY_Price[$strateg['symbol']]['askPrice'] = $ticker['askPrice'];
                    $last_BUY_Price[$strateg['symbol']]['time'] = microtime(true);

                    //LOG
                }
            }

            $strateg['status_indicator'] = $status_ind;
            $strateg['status_BUY'] = $status_BUY;
        }

        end:
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ////###################################################################################################################################### //
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //Отправка контрольной SMS
        $getdate = getdate();
        if (in_array($getdate['hours'], [7]) && $getdate['minutes'] == 100 && $getdate['seconds'] < 15) {
            require_once __DIR__ . '/model/apisms_c.php';
            $sms = 'Open '.count($orderOPEN). ' Balance '.round(array_sum(array_column($accountBalance, 'total_USD')),2).' Strateg'.count($strategies);
            $ApiSMS = new APISMS('843e5bec02b4c36e0202d4a0cf227eaa', 'd9c9f37cc5b86d4368da4cd7b3781a27', 'http://atompark.com/api/sms/', false);
            //отправка SMS
            $log = $ApiSMS->execCommad('sendSMS', array(
                                             'sender' => 'xt.ua',
                                             'text' => $sms,
                                             'phone' => $user['login'],
                                             'datetime' => null,
                                             'sms_lifetime' => 0));
        }
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ////###################################################################################################################################### //
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        //Смотрим даные если запустили с браузера
        // if ($_GET['action'] == 'show'){
            // echo '<link rel="stylesheet" href="../html/css/style.css">';
            // // echo '<script type="text/javascript" src="../html/script/script_bot.js"></script>';
            // // if (count($arrey_symbols)>0 && $audio == false) {
            // //     $audio = true;
            // //     // echo'<audio autoplay><source src="sirena.mp3"></audio>';
            // //     Functions::showArrayTable($arrey_symbols, 'bookTickerHis отобрал'.count($arrey_symbols));
            // // }

            // echo '<div style="text-align: right; background: #fc0;">', '  <font size="20" color=blue face="Arial">', date("H:i:s", time()), '</font></div>';
            // echo $user['login'], ' баланс: <font size="10" color="green" face="Arial">', round(array_sum(array_column($accountBalance, 'total_USD')),2),  '</font>$<br/>';

            // Functions::showArrayTable($accountBalance, 'accountBalance');
            // Functions::showArrayTable($user[$exchange]['strategies'], "СТРАТЕГИИ ");
            // Functions::showArrayTable($orderOPEN, 'OPEN order');
            // Functions::showArrayTable($testBUY, 'testBUY');

            //Время выполнения скрипта:
            echo 'Время: ', round(microtime(true) - $start, 4), ' сек.<br/>';
            // echo 'Обем памяти: ', (memory_get_usage() - $mem_start)/1000000, ' мегабайта.<br/><br/><br/><br/>';
            sleep(1);
        // }
    }
endwhile;
?>