<?php

$start = microtime(true); 
echo "Привет! Меня зовут BUCKS (v2)<br/>";
//Устанавливаем настройки памяти
    // echo "memory_limit ", ini_get('memory_limit'), "<br />";
    // ini_set('memory_limit', '1024M');   
    // echo "memory_limit ", ini_get('memory_limit'), "<br />";
// die();
    //Устанавливаем настройки времени
    // echo "max_execution_time ", ini_get('max_execution_time'), "<br />";
    ini_set('max_execution_time', 1000);    //  одно и тоже что set_time_limit(6000);
    // echo "max_execution_time ", ini_get('max_execution_time'), "<br />";

    // ob_implicit_flush(1);
     
    // ob_start();
    // ob_get_contents();
    // ob_get_clean();
    // ob_end_flush();
header('refresh: 1');

 

//$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
//минимальное количество сделок за 24 час
$settings['countTrends'] = $countTrends= 1000; 
//минимальное обем продаж за 24 час
$settings['quoteVolume'] = $quoteVolume = 100000;



// максимальный % изминения цены за 24 час
$settings['priceChangePercent'] = $priceChangePercent= 30; 

//стартовый лимит закупки
$settings['trade_limit'] = $trade_limit = 12;

//запустить тестовою закупку $orderBUY == 0
$settings['order_Test'] = $order_Test = '0';


//Отключить создания ордеров покупку
$settings['orderBUY'] = $orderBUY = 0;

// $symbolBUY =  array('ETHUSDT');
$symbolBUY =  array('ETHBULLUSDT','XRPBULLUSDT','EOSBULLUSDT','BULLUSDT','WINUSDT','NPXSUSDT','DENTUSDT','BTTUSDT','COCOSUSDT','HOTUSDT','MFTUSDT','ERDUSDT','KEYUSDT','MBLUSDT','ANKRUSDT','TFUELUSDT','DREPUSDT','FUNUSDT','CELRUSDT','IOTXUSDT','TROYUSDT','ONEUSDT','IOSTUSDT','VETUSDT','ZILUSDT','MITHUSDT','DOCKUSDT','FTMUSDT','GTOUSDT','TCTUSDT','COSUSDT','ARPAUSDT','CHZUSDT','TRXUSDT','VITEUSDT','NKNUSDT','BTSUSDT','MATICUSDT','RVNUSDT','FETUSDT','PERLUSDT','DUSKUSDT','ADAUSDT','XLMUSDT','CTXCUSDT','ENJUSDT','ONGUSDT','THETAUSDT','STXUSDT','AIONUSDT','IOTAUSDT','XRPUSDT','BATUSDT','WANUSDT','ZRXUSDT','NULSUSDT','BNTUSDT','MTLUSDT','ALGOUSDT','ICXUSDT','STRATUSDT','TOMOUSDT','BEAMUSDT','RLCUSDT','ONTUSDT','NANOUSDT','OMGUSDT','KAVAUSDT','HCUSDT','QTUMUSDT','XTZUSDT','EOSUSDT','ATOMUSDT','LINKUSDT','MCOUSDT','ETCUSDT','NEOUSDT','BNBUSDT','ZECUSDT','LTCUSDT','XMRUSDT','EOSBULLUSDT','DASHUSDT','XRPBULLUSDT','ETHUSDT','BCHUSDT','ETHBULLUSDT','BNBBULLUSDT','BTCUSDT','BULLUSDT');

//Включить создания ордеров продажи TakeProfit
$settings['orderSELL'] = $orderSELL = 0;
//минимальная маржа % TakeProfit
$settings['TakeProfit_p'] = $TakeProfit_p = 0.3;

//Включить создания ордеров Stop Loss
$settings['orderSELL_SL'] = $orderSELL_SL = 0;
//Увеличить моментальную потерю маржи
$settings['addPrice_loss_p'] = $addPrice_loss_p = 0.01;
//потеря маржи % при Stop Loss
$settings['lossМargin_p'] = $lossМargin_p = -0.2;


//процент контроля роста цены от минимальной для покупки
$settings['controltopPrice_p'] = $controltopPrice_p = 1;

//процент контроля минимальной и максимальной цен
$settings['controlChangePrice_p'] = $controlChangePrice_p = 5;
//минимальный фактический мониторинг процент контроля для покупки
$settings['BUYcontrolChangePrice_p'] = $BUYcontrolChangePrice_p = 0.5;




//Определяем базове валют
$base['USDT']= array('minBalans'=>800, 'minPriceBuy'=>0.00000100);
// $base['BTC']= array('minBalans'=>0.5, 'minPriceBuy'=>0.00000100);
// $base['BNB']= array('minBalans'=>10, 'minPriceBuy'=>0.00000100);
// $base['ETH']= array('minBalans'=>10, 'minPriceBuy'=>0.00000100);
// $base['TRX']= array('minBalans'=>100, 'minPriceBuy'=>0.00000100);
// $base['XRP']= array('minBalans'=>100, 'minPriceBuy'=>0.00000100);
// $base['EUR']= array('minBalans'=>1000, 'minPriceBuy'=>0.00000100);

//$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$




//Создаем класс
$Bin = new binance();
//Проверяем торговый статус API аккаунта
// $apiTradingStatus= $Bin->apiTradingStatus(array());
// $Bin->show($apiTradingStatus);

//************************ОБЩИЕ ДАНЫЕ БИРЖИ (загружаем или читаем с файла)**************
$filetradeFeeKom = 'E:\binance\tradeFeeKom.txt';
$fileexchangeInfo = 'E:\binance\exchangeInfo.txt';
if(time()-filemtime($filetradeFeeKom) > 3600 || time()-filemtime($fileexchangeInfo) > 3600){
    //Даные в файле устарели УДАЛЯЕМ 
    unlink($filetradeFeeKom);
    unlink($fileexchangeInfo);

    //Получаем актуальную информацию
    $tradeFeeKom= $Bin->tradeFeeKom();      //О комисиях
    $exchangeInfo = $Bin->exchangeInfo();   //Правила биржевой торговли и символьная информация
    //записиваем даные в файл
    $Bin->saveFile($tradeFeeKom, $filetradeFeeKom);
    $Bin->saveFile($exchangeInfo, $fileexchangeInfo);

    $time = microtime(true) - $start;
    echo 'Файлы ОБНОВЛЕНЫ. время: '.round($time, 4).' сек.<br/>';
}else{    
    //Даные в файле актуальны читаем
    $tradeFeeKom = $Bin->readFile($filetradeFeeKom);
    $exchangeInfo = $Bin->readFile($fileexchangeInfo);

    $time = microtime(true) - $start;
    echo 'Файлы прочитаны. время: '.round($time, 4).' сек.<br/>';
}
//Смотрим
// $Bin->show($tradeFeeKom);
// $Bin->show($exchangeInfo);

//Получение ПОСЛЕДНИХ ДАНЫХ  symbol-----------------------------
//Получить изминения за 24 часса
$ticker24hr = $Bin->ticker24hr();
$Bin->show($ticker24hr[0]);

//Получить свободный баланс АКТИВОВ
$accountBalance= $Bin->accountBalance($ticker24hr, $base);
// $Bin->showArrayTable($accountBalance);

//сохраняем файлы с именем времени первого символа
$fileTicker24hr = 'E:\binance\ticker24hr\\'.$ticker24hr[0]['closeTime'].'.txt';
$Bin->saveFile($ticker24hr, $fileTicker24hr);

//Читаем нужные файлы  и удаляем старые***********
//сканируем директорию с файлами
$dir = 'E:\binance\ticker24hr\\';
$files = scandir($dir, 1);



$time = time();
$i=1;
foreach ($files as $key => $name) {
    $file = $dir.$name;
    //проверяем существование файла
    if (!is_file($file)) continue;      
      $filemtime = filemtime($file); 
      $today = getdate($filemtime);
      // $Bin->show($today);

        if ($time - $filemtime < 60*3) {             //выбераем даные за  60 с
            if ((($key+1)%5)==0) {
                if ($ticker24hrfile = $Bin->readFile($file)){
              $ticker24hrfile['filemtime'] = $time - $filemtime;

              $historyTicker24hr_ALL[] = $ticker24hrfile;
            //мотрим возраст файлов
                echo $i, ' -> ' , date("Y-m-d H:i:s", $filemtime), ' возраст: ', date("H:i:s", mktime(0, 0, $time - $filemtime)), ' -> ', $time - $filemtime,  "<br/>";
          }
            }
          
        }elseif ((($key+1)%50)==0) {
            if ($ticker24hrfile = $Bin->readFile($file)){
                $ticker24hrfile['filemtime'] = $time - $filemtime;
                $historyTicker24hr_ALL[] = $ticker24hrfile;
                //смотрим возраст файлов
                echo $i, ' -> ' , date("Y-m-d H:i:s", $filemtime), ' возраст: ', date("H:i:s", mktime(0, 0, $time - $filemtime)),  "<br/>";
            }                 
        }


      //удаляем старые файлы по условию возраста файла60*60
    if ($time - $filemtime >= 4000) { 
        unlink($file);
    }

    $i++; 
}

//ПРОДАЖА и Покупка symbol-----------------------------
$historyTicker24hr_ALL = array_reverse($historyTicker24hr_ALL);
$select = array(); 
$arrayPrice = array();
$BUY = $showOpenSymbol=array();
foreach ($ticker24hr as $key => $value) {
        //Получаем информацию о symbol и исключаем неактивные пары
        if (!$symbolInfo = array_values($Bin->multiSearch($exchangeInfo['symbols'], array('symbol' => $value['symbol'], 'status'=>'TRADING', 'ocoAllowed' => 1))) ) continue;        
        //Получаем курс BTC USD
        $kurs = $Bin->kurs($symbolInfo['0']['quoteAsset'], $ticker24hr);


        //исключаем все кроме разрешоного
        // if (!in_array($value['symbol'], $symbolBUY))  continue;

         // Исключаем если база не USDT 
        if ($symbolInfo[0]['quoteAsset']!='USDT') continue;

        // Исключаем пары с неутвержденой базой
        // if (!array_key_exists($symbolInfo['0']['quoteAsset'], $base)) continue;


        // //Исключаем с обемом продаж за сутки менее (24 часа) 
        // if (($value['quoteVolume']*$kurs['kursUSD']) < $quoteVolume) continue;

        // Исключаем с количеством операций меньше $countTrends (24 часа) 
        // if ($value['count'] < $countTrends) continue;

        //Исключаем с процентом изминения цены до и от (24 часа) 
        // if (-1== bccomp((string)$value['priceChangePercent'], (string)$priceChangePercent[0], 8) || 1== bccomp((string)$value['priceChangePercent'], (string)$priceChangePercent[1], 8)) continue; 

        //Исключаем пары кторые не могу купить НЕТ ДЕНЕГ
        // if ($accountBalance[$symbolInfo['0']['quoteAsset']]['total_USD'] < $trade_limit) continue;
        
        //Исключаем пары кторые не нужны ЕСТЬ НА БАЛАНСЕ и неявляются базой
        // if ($accountBalance[$symbolInfo['0']['baseAsset']]['total_USD'] >= $trade_limit && !array_key_exists($symbolInfo['0']['baseAsset'], $base)) continue;

        //Исключаем пары кторые не нужны ЕСТЬ НА БАЛАНСЕ  меньше минимума и являются базой
        // if ($accountBalance[$symbolInfo['0']['baseAsset']]['total_USD'] <= $accountBalance[$symbolInfo['0']['baseAsset']]['minBalans'] && array_key_exists($symbolInfo['0']['baseAsset'], $base)) continue; 

        // Исключаем пары с ценой меньше рекомендованой более 100 сатошей
        // if (-1== bccomp((string)$value['askPrice'], (string)number_format($base[$symbolInfo['0']['quoteAsset']]['minPriceBuy'], 8, '.', ''), 8)) continue;

        //Исключаем если моминтальная потеря цены при покупке больше //потеря маржи % при Stop Loss    $lossМargin_p



                    $spred = bcsub($value['askPrice'], $value['bidPrice'], 8);
                    $spred_p = bcmul(bcdiv($spred, $value['bidPrice'], 8), 100, 8);
                    //находим моментальную потерю цены при покупке
                    $takerask =  bcmul($value['askPrice'], $tradeFeeKom[$value['symbol']]['taker'], 8);
                    $price_loss = bcsub($value['askPrice'], bcsub($value['bidPrice'], $takerask, 8), 8);
                    $price_loss_p = bcadd(bcmul(bcdiv($price_loss, $value['bidPrice'], 8),100, 8), $addPrice_loss_p, 8);         
        // if (1== bccomp((string)$price_loss_p, (string)abs($lossМargin_p), 8))  continue; 


        $symbol = array();
        $symbol['symbol'] = $value['symbol'];
        $symbol['asset'] = $symbolInfo['0']['baseAsset']; 

        $symbol['*']=' '; 

        $symbol['ALL_p'] = '';
        $symbol['ALL_p_st'] = '-';

        $symbol['sub_p24'] ='';
        $symbol['sub_p24_st'] ='-';

        $symbol['trend_M'] = '';
        $symbol['trend_M_st'] = '-';

        $symbol['trend_S'] = '';
        $symbol['trend_S_st'] = '-';
        
        $symbol['stop_loss_p']= $price_loss_p; 
        $symbol['stop_loss_p_st']= '-'; 

    
        $symbol['**']=' ';
        $symbol['spred_p'] =  $spred_p;
        $symbol['askPrice'] =$value['askPrice'];
        $symbol['bidPrice'] = $value['bidPrice'];
        

       
         $symbol['***']=' ';
        $symbol['buy']='';
        $symbol['statusTREND']='';
        $symbol['margin_p'] =  bcmul($spred, 20, 8);
              
          
        $symbol['****']=' '; 
        

        foreach ($historyTicker24hr_ALL as $keyHT24hr => $historyTicker24hr) {   
// $Bin->show($historyTicker24hr);
// echo $historyTicker24hr['filemtime'],"<br/>";

          if ($value['symbol'] != $historyTicker24hr[$key]['symbol'] ) continue; 
          if ($keyHT24hr==0) {
              $symbol[$keyHT24hr.'_p24'] = $historyTicker24hr[$key]['priceChangePercent']; 
              $symbol[$keyHT24hr] = $historyTicker24hr[$key]['askPrice']; 
              continue;
          }
            $symbol[$keyHT24hr.'_p24'] = $historyTicker24hr[$key]['priceChangePercent'];
            $symbol[$keyHT24hr] = $historyTicker24hr[$key]['askPrice'];
                $trend = bcsub($symbol[$keyHT24hr], $symbol[$keyHT24hr-1], 8);
            $symbol[$keyHT24hr.'_p'] =  $trend_p = bcmul(bcdiv($trend, $symbol[$keyHT24hr-1], 8), 100, 2);


            if (1==bccomp($trend_p, 0, 8)) {
                if ($historyTicker24hr['filemtime']<60*3) {
                    $symbol['trend_S'] .= '+';
                }else{
                    $symbol['trend_M'] .= '+';
                }
            }
            if (0==bccomp($trend_p, 0, 8)) {
                if ($historyTicker24hr['filemtime']<60*3) {
                    $symbol['trend_S'] .= '#';
                }else{
                    $symbol['trend_M'] .= '#';
                }
            }

            if (-1==bccomp($trend_p, 0, 8)){
                if ($historyTicker24hr['filemtime']<60*3) {
                    $symbol['trend_S'] .= '-';
                }else{
                    $symbol['trend_M'] .= '-';
                }  
            } 
         
        }

            $trend = bcsub($value['askPrice'], $symbol[0], 8);
            $symbol['ALL_p'] = bcmul(bcdiv($trend, $value['askPrice'], 8), 100, 8);
            $symbol['sub_p24'] = bcsub($value['priceChangePercent'], $symbol['0_p24'], 8);


//принимаем РИШЕНИЕ
    $buy=0;
    //
    if (1==bccomp($symbol['ALL_p'], 0, 8)) {
        $symbol['ALL_p_st'] = '+';
        $buy++;        
    }
    //
    if (1==bccomp($symbol['sub_p24'], 0, 8))  {
        $symbol['sub_p24_st'] ='+';
        $buy++;        
    }
    //
    if (substr($symbol['trend_S'], -2) == '-+' && strlen($symbol['trend_S'])>3)  {
        $symbol['trend_S_st'] = '+';
        $buy++;        
    }
    //
    if (substr($symbol['trend_M'], -2) == '-+' && strlen($symbol['trend_M'])>3)  {
        $symbol['trend_M_st'] = '+';
        $buy++;        
    }
    //
    if (-1== bccomp((string)$price_loss_p, (string)abs($lossМargin_p), 8))  {
        $symbol['stop_loss_p_st']= '+'; 
        $buy++;        
    }

$symbol['buy']= $buy;


    if ($buy==5) {
    
            //
            if (1 == bccomp((string)$value['priceChangePercent'] , (string)0, 8)) {
              $priceBUY = $value['bidPrice'];
            }else{
              $priceBUY = $Bin->round_min(bcmul($value['bidPrice'], 0.999, 8), $symbolInfo['0']['filters'][0]['minPrice']);
            }   

                    //параметры ПОКУПКА LIMIT 
                    // $symbol['statusTREND']= 'BUY_LIMIT';
                    // $Params_BUY = array('symbol'=>$value['symbol'], 
                    //                 'side' => 'BUY', 
                    //                 'type' => 'LIMIT', 
                    //                 'quantity' => $Bin->round_min(bcdiv($trade_limit, bcmul($value['askPrice'], $kurs['kursUSD'], 8), 8), $symbolInfo['0']['filters'][2]['minQty']), 
                    //                 'timeInForce' => 'GTC', 
                    //                 'price' => $priceBUY);

                    //параметры ПОКУПКА OCO 
                    // $symbol['statusTREND']= 'BUY_OCO';                             
                    // $Params_BUY = array('symbol'=>$value['symbol'], 
                    //                 'side' => 'BUY', 
                    //                 'quantity' => $Bin->round_min(bcdiv($trade_limit, bcmul($value['askPrice'], $kurs['kursUSD'], 8), 8), $symbolInfo['0']['filters'][2]['minQty']),
                    //                 'price' => $Bin->round_min(bcmul($value['bidPrice'], 0.995, 8), $symbolInfo['0']['filters'][0]['minPrice']),
                    //                 'stopPrice' => $value['askPrice'], 
                    //                 'timeInForce' => 'GTC');

                    //масив параметров BUY_MARKET
                    $symbol['statusTREND']= 'BUY_MARKET';
                    $Params_BUY = array('symbol'=>$value['symbol'], 
                                        'side' => 'BUY', 
                                        'type' => 'MARKET', 
                                        'quantity' => $Bin->round_min(bcdiv($trade_limit, bcmul($value['askPrice'], $kurs['kursUSD'], 8), 8), $symbolInfo['0']['filters'][2]['minQty']),
                                        'timeInForce' => 'IOC', 
                                        'price' => $priceBUY);   

                    // $Bin->show($Params_BUY);

                    //если создаем лемитные ордера то проверяем и удаляем устаревшие
                    if ($Params_BUY['timeInForce'] == 'GTC') {
                        $ParamsOpen = array('symbol'=>$value['symbol']); 
                        if ($orderOpen = $Bin->orderOPEN($ParamsOpen)) {
                          
                          foreach ($orderOpen as $key => $order) {
                            $orderOpen[$key]['status'] = '';
                            if (1 == bccomp((string)$orderOpen['price'] , (string)$priceBUY, 8)) {
                              echo $value['symbol'], ' ', $priceBUY, "<br/>";
                              $orderOpen[$key]['statusOpen'] = 'УДАЛЯЮ';
                              $ParamsDELETE = array('symbol'=>$value['symbol'], 'orderId'=>$order['orderId']);
                              if ($orderOpen = $Bin->orderDELETE($ParamsDELETE)) {
                                  $orderOpen[$key]['status'] = 'DEL';
                              }
                            }
                          }
                          echo "Открытые ордера<br/>";
                          $Bin->showArrayTable($orderOpen);                               
                        }
                    }


                    $testOrder[$value['closeTime']]['Time'] =$value['closeTime'];                            
                    $testOrder[$value['closeTime']]['symbol'] =$value['symbol'];
                    // $testOrder[$value['closeTime']]['max_date'] =$symbol['max_date'];
                    // $testOrder[$value['closeTime']]['max'] = $symbol['max'];
                    $testOrder[$value['closeTime']]['trend_max_p'] = $symbol['trend_max_p'];
                    // $testOrder[$value['closeTime']]['mix_date'] =$symbol['max_date'];
                    // $testOrder[$value['closeTime']]['mix'] = $symbol['max'];
                    $testOrder[$value['closeTime']]['trend_min_p'] = $symbol['trend_min_p'];
                    $testOrder[$value['closeTime']]['askPrice'] =$value['askPrice'];
                    $testOrder[$value['closeTime']]['bidPrice'] = $value['bidPrice'];
                    $testOrder[$value['closeTime']]['sum'] = bcmul($Params_BUY['quantity'], $Params_BUY['price'], 8);
                    $testOrder[$value['closeTime']]['***'] = '***';
                    $testOrder[$value['closeTime']] += $Params_BUY;


                    // $Bin->show($ParamsBUY); 

                    if ($orderBUY == '1' && count($orderOpen)==0) {
                        if ($order = $Bin->orderNEW($Params_BUY)) {
                          $Bin->show($order); 
                            // $testOrder[$value['closeTime']]+= $order;
                            if (0 != bccomp((string)$order['executedQty'], (string)0, 8)) {                                                             

                              //заполяем историю покупок
                              // $historyBUY[$symbolInfo['0']['baseAsset']]['symbol'] =  $value['symbol'];
                              // $historyBUY[$symbolInfo['0']['baseAsset']]['asset'] =  $symbolInfo['0']['baseAsset'];                
                              // $historyBUY[$symbolInfo['0']['baseAsset']]['base'] =  $symbolInfo['0']['quoteAsset'];                                    
                              // $historyBUY[$symbolInfo['0']['baseAsset']]['time'] =  $value['closeTime'];

                              // $historyBUY[$symbolInfo['0']['baseAsset']]['priceUSD'] = bcmul($value['askPrice'],$kurs['kursUSD'], 8);                
                              // $historyBUY[$symbolInfo['0']['baseAsset']]['BUYkursUSD'] = $kurs['kursUSD'];
                              // $historyBUY[$symbolInfo['0']['baseAsset']]['kursUSD'] = $kurs['kursUSD'];
                              // $historyBUY[$symbolInfo['0']['baseAsset']]['correctionUSD'] = 1;
                              
                              // $historyBUY[$symbolInfo['0']['baseAsset']]['priceBTC'] = bcmul($value['askPrice'],$kurs['kursBTC'], 8);
                              // $historyBUY[$symbolInfo['0']['baseAsset']]['BUYkursBTC'] = $kurs['kursBTC'];
                              // $historyBUY[$symbolInfo['0']['baseAsset']]['kursBTC'] = $kurs['kursBTC'];
                              // $historyBUY[$symbolInfo['0']['baseAsset']]['correctionBTC'] = 1;
                              // // $Bin->show($order);
                              // $order['kurs'] = $kurs;
                              // $historyBUY[$symbolInfo['0']['baseAsset']]['status'] = $order['status'];

                              // $symbol['statusBUY']='BUY';
                              // $historyBUY[$symbolInfo['0']['baseAsset']]['buy'][] = $order;
                              // $Bin->saveFile($historyBUY, $filehistoryBUY); 
                             }         
                        }
                    }
                    // $Bin->showArrayTable($historyBUY); 

            // $symbol+=$ParamsBUY;
    }

    $select[] = $symbol; 
}
//Сохраняем историю цены
// $Bin->saveFile($historyPrice, $filehistoryPrice);
//Сохраняем окрытые сделки
// $Bin->saveFile($OpenSymbol, $fileOpenSymbol);

//cмотрим настройки
// print_r($settings);
// $Bin->show($settings);


//сортирум и смотрим БАЛАНС
usort($accountBalance, function($a, $b) {
    return $b['total_USD'] - $a['total_USD'];
});
$Bin->showArrayTable($accountBalance);

//ОТОБРАЛ
echo "<br/>ОТОБРАЛ ", count($select), '<br/>';
// //Сортируем и смотрим
usort($select, function($a, $b) {
    return abs($b['buy']) - abs($a['buy']);
});
$Bin->showArrayTable($select);


// echo 'За сегодня: <font size="20" color="green" face="Arial">', round(array_sum(array_column($ArhivorderSELL, 'marginUSD')),2), ' $</font>  баланс ', round(array_sum(array_column($accountBalance, 'total_USD')),2), ' <br/>';

echo '<br/>Открытые сделоки ',count($OpenSymbol), '<br/>'; 
//сортирум и смотрим
usort($showOpenSymbol, function($a, $b) {
    return $b['margin_p']*100000000 - $a['margin_p']*100000000;
});
$Bin->showArrayTable($showOpenSymbol);



$time = microtime(true) - $start;
echo 'Время выполнения скрипта: '.round($time, 4).' сек.<br/><br/><br/>';
exit();
?>