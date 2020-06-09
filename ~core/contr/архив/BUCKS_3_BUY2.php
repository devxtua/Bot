<?php



sleep(3);
$start = microtime(true);
$mem_start = memory_get_usage();
//$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$

//ВКЛЮЧИТЬ создания ордеров покупку
$orderBUY = 0; //0-нет, 1 да
$orderSELL_OCO = 0; //0-нет, 1 да


//только торговые пары  
$symbolBUY =  ['BTCUSDT'];
$symbolBUY =  array( 'BTCUSDT', 'ETHUSDT', 'BNBUSDT');
// $symbolBUY =  array('WINUSDT','NPXSUSDT','DENTUSDT','BTTUSDT','COCOSUSDT','HOTUSDT','MFTUSDT','ERDUSDT','KEYUSDT','MBLUSDT','ANKRUSDT','TFUELUSDT','DREPUSDT','FUNUSDT','CELRUSDT','IOTXUSDT','TROYUSDT','ONEUSDT','IOSTUSDT','VETUSDT','ZILUSDT','MITHUSDT','DOCKUSDT','FTMUSDT','GTOUSDT','TCTUSDT','COSUSDT','ARPAUSDT','CHZUSDT','TRXUSDT','VITEUSDT','NKNUSDT','BTSUSDT','MATICUSDT','RVNUSDT','FETUSDT','PERLUSDT','DUSKUSDT','ADAUSDT','XLMUSDT','CTXCUSDT','ENJUSDT','ONGUSDT','THETAUSDT','STXUSDT','AIONUSDT','IOTAUSDT','XRPUSDT','BATUSDT','WANUSDT','ZRXUSDT','NULSUSDT','BNTUSDT','MTLUSDT','ALGOUSDT','ICXUSDT','STRATUSDT','TOMOUSDT','BEAMUSDT','RLCUSDT','ONTUSDT','NANOUSDT','OMGUSDT','KAVAUSDT','HCUSDT','QTUMUSDT','XTZUSDT','EOSUSDT','ATOMUSDT','LINKUSDT','MCOUSDT','ETCUSDT','NEOUSDT','BNBUSDT','ZECUSDT','LTCUSDT','XMRUSDT','DASHUSDT','ETHUSDT','BCHUSDT','BTCUSDT');



//Определяем базове валют
$base['USDT']= array('minBalans'=>100, 'minPriceBuy'=>0.00000100);
$base['BNB']= array('minBalans'=>3, 'minPriceBuy'=>0.00000100);
// $base['BTC']= array('minBalans'=>0.5, 'minPriceBuy'=>0.00000100);
// $base['ETH']= array('minBalans'=>10, 'minPriceBuy'=>0.00000100);
// $base['TRX']= array('minBalans'=>100, 'minPriceBuy'=>0.00000100);
// $base['XRP']= array('minBalans'=>100, 'minPriceBuy'=>0.00000100);
// $base['EUR']= array('minBalans'=>1000, 'minPriceBuy'=>0.00000100);

//Создаем класс
$KEY = 'irieuC5kOGznjzpllwnxx2sDMzdLKPCS42SB8YGZ4Y8eSUz6mDtfWDMclrpUh633';
$SEC = 'FpCQWroQgIh9KyV3Jn7A25tbbpMB93eaK2FbKFXZv7YoMCmVDn5gBoMrwHaSpPUJ'; 
$Bin = new binance($KEY, $SEC);



//Проверяем торговый статус API аккаунта
// $apiTradingStatus= $Bin->apiTradingStatus(array());
// $Bin->show($apiTradingStatus);
// die();


//Смотрим
// $Bin->show($tradeFeeKom);
// $Bin->show($exchangeInfo);



//******************************************************************
//******************************************************************

//Получение ПОСЛЕДНИХ ДАНЫХ  symbol-----------------------------
$time = time();
$today = getdate();
// $Bin->show($today);
echo 'ВРЕМЯ : ', '  <font size="20" color=blue face="Arial">', date("H:i:s", $time), '</font> <br/>';

//Получить изминения за 24 часса
$ticker24hr = $Bin->ticker24hr();
// $Bin->show($ticker24hr[0]);

//Получить свободный баланс АКТИВОВ
if (!$accountBalance = $Bin->accountBalance($ticker24hr, $base)){
    die('БАЛАНС НЕ ПОЛУЧЕН');
}
Functions::showArrayTable($accountBalance);
echo 'total : ', round($accountBalance['USDT']['total'],2), ' USDT<br/>';
echo 'locked : ',  round($accountBalance['USDT']['locked'],2), ' USDT <br/>';
echo 'free : ', '  <font size="20" color = green face="Arial">', round($accountBalance['USDT']['free'],2), ' USDT </font><br/>';

// if ($allOCO = $Bin->allOCO(array())){
//       $Bin->showArrayTable($allOCO);
// }

// if ($openOCO = $Bin->openOCO(array())){
//       $Bin->showArrayTable($openOCO);
// }

//открытые ордера
if ($orderOPEN = $Bin->orderOPEN(array())){      
      Functions::showArrayTable($orderOPEN);
}



// die();

//сохраняем файлы с именем времени первого символа
$fileTicker24hr = 'E:\binance\ticker24hr\\'.$ticker24hr[0]['closeTime'].'.txt';
Functions::saveFile($ticker24hr, $fileTicker24hr);

//Читаем нужные файлы  и удаляем старые***********
//сканируем директорию с файлами
$dir = 'E:\binance\ticker24hr\\';
$files = array_reverse(scandir($dir, 1));
echo 'Итого файлов: ', count($files),  "<br/><br/>";




//Читаем файл архива покупок
$filehistoryBUY = 'E:\binance\V5historyBUY.txt';
if (!$historyBUY  = Functions::readFile($filehistoryBUY )){
 $historyBUY  = array();
}else{ 
    // foreach ($historyBUY as $key => $value) { 

    //   if (!in_array($key, array_column($accountBalance, 'asset'))) {
    //       unset($historyBUY[$key]);
    //       continue;
    //   } 
    //   if (count($value['buy'])==0) {
    //       unset($historyBUY[$key]);
    //       continue;
    //   }
    //     foreach ($value['buy'] as $key2 => $value2) {
    //       if ($value2['executedQty']==0) {
    //         unset($value['buy'][$key2]);
    //       }
    //     }   
    //   $historyBUY[$key]['buy'] = $value2['buy']; 
    // }

    // $Bin->saveFile($historyBUY, $filehistoryBUY);
}



$filetestOrder = 'E:\binance\V5testOrder.txt';
if ($testOrder = Functions::readFile($filetestOrder)){
   
   // $Bin->show($open);
}else{
  $testOrder = $open = array();
}



// die();
// $count = count($historyTicker24hr);
// $historyTicker24hr_ALL = array_reverse($historyTicker24hr_ALL);
// if ($historyTicker24hr_ALL[0]['type'] != 'control' || count($historyTicker24hr_ALL)<10) {
//   die("МАЛО ДАНЫХ");
// }

$select = array(); 
$arrayPrice = array();
foreach ($ticker24hr as $key => $value) {//начало цикла 1 
        //Получаем информацию о symbol и исключаем неактивные пары
        if (!$symbolInfo = array_values(Functions::multiSearch($exchangeInfo['symbols'], array('symbol' => $value['symbol'], 'status'=>'TRADING'))) ) continue;
        //Получаем курс BTC USD
        $kurs = $Bin->kurs($symbolInfo['0']['quoteAsset'], $ticker24hr);




        //тмечаем тестовые ордера
        if (in_array($value['symbol'], array_column($testOrder, 'symbol'))) {
          foreach ($testOrder as $keytestOrder => $vtestOrder) {
              if ($value['symbol'] != $testOrder[$keytestOrder]['symbol'] ) continue;
             if (1== bccomp((string)$value['lastPrice'], (string)$testOrder[$keytestOrder]['M_Price'], 8) && $testOrder[$keytestOrder]['status'] == '') {
                  $testOrder[$keytestOrder]['status'] = 'MARGIN';
                  $testOrder[$keytestOrder]['statusTime'] = $value['closeTime'];

             }
             if (-1== bccomp((string)$value['lastPrice'], (string)$testOrder[$keytestOrder]['SL_Price'], 8) && $testOrder[$keytestOrder]['status'] == '') {
                  $testOrder[$keytestOrder]['status'] = 'SL';
                  $testOrder[$keytestOrder]['statusTime'] = $value['closeTime'];

             }
             // $testOrder[$keytestOrder]['lastPrice'] = $value['lastPrice'];
          }
        }

        //исключаем все кроме разрешоного
        if (!in_array($value['symbol'], $symbolBUY))  continue;

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
                    //находим моментальную потерю цены при покупке
                    $takerask =  bcmul($value['askPrice'], $tradeFeeKom[$value['symbol']]['taker'], 8);
                    $price_loss = bcsub($value['askPrice'], bcsub($value['bidPrice'], $takerask, 8), 8);
                    $price_loss_p = bcmul(bcdiv($price_loss, $value['bidPrice'], 8),100, 8);         
        // if (1== bccomp((string)$price_loss_p, (string)abs($lossМargin_p), 8))  continue; 
        
//=================================================================================================
//=================================================================================================
//=================================================================================================

    
        $symbol = array();
        $symbol['symbol'] = $value['symbol'];
        $symbol['asset'] = $symbolInfo['0']['baseAsset'];  
        $symbol['askPrice'] =$value['askPrice']; 
        // $symbol['klinesPrice'] = '';




               $interval = '1m';        

                if (!$klines = $Bin->klines(array('symbol'=>$value['symbol'], 'interval' => $interval, 'limit' => 1000))) {
                  $symbol['statusTREND'] = 'NOT klines';
                  continue;
                }
                // $Bin->showArrayTable($klines);
                
                $filefunded = 'E:\binance\symbols\\'.$value['symbol'].'_klines_'.$interval.'_funded.txt';

                if ($funded = Functions::readFile($filefunded)){
                   foreach ($klines as $k => $v) {
                      $time = array_column($funded, 0);
                      if (!in_array($v[0], $time)) {
                        $funded[] = $v;
                      }
                   }
                }else{
                    $funded = $klines;

                }



                $symbol['klines'] = count($funded); 

                  $settings['trade_limit'] = 11;

                  $settings['quote_volume'] = 312568; 
                  $settings['TakeProfit'] = 1.013;                  
                  $settings['StopLoss'] = 0.94;
                  $settings['dump'] = 0.999;
                                   
                  $settings['thresholdBuy'] = 0.999;

                   $symbol += $settings;
                
            
                  // $Bin->show($funded[0]);                   

                    $test = array();

                
            foreach ($funded as $keyK => $value_klines) {                      
                      $getdate = getdate($value_klines[0]/1000);
                      $funded[$keyK]['mday'] = $getdate['mday'];
                      $funded[$keyK]['hours'] = $getdate['hours'];
                      $funded[$keyK]['minutes'] = $getdate['minutes'];

                      $funded[$keyK]['klin_c'] = $q = bcdiv($value_klines[4], $value_klines[1], 5);


                      $lastBUY = $funded[$keyK][2];
                $open= 0;
                foreach ($test as $testK => $testV) { //***

                    if ($testV['status'] != '') continue;

                    //продажа profit
                    if (1 == bccomp($value_klines[2], $testV['Price_TP'], 8)) {
                        $test[$testK]['status'] = 'TP';
                        $test[$testK]['timeSELL'] = $value_klines[0];
                        $test[$testK]['age'] = '';
                        
                        $test[$testK]['sum_SELL'] = bcmul($test[$testK]['Price_TP'], $test[$testK]['quantity'], 8);
                        $test[$testK]['maker'] = bcmul($test[$testK]['sum_SELL'], $tradeFeeKom[$value['symbol']]['maker'], 8);

                        $test[$testK]['margin'] = bcsub($test[$testK]['sum_SELL'], $test[$testK]['sum_BUY'], 8);
                        $test[$testK]['taker+maker'] =  bcadd($test[$testK]['taker'], $test[$testK]['maker'], 8);
                        $test[$testK]['profit(loss)'] = bcsub($test[$testK]['margin'], $test[$testK]['taker+maker'], 8);
                        continue;
                    }
                    //продажа sl
                    if (-1 == bccomp($value_klines[3], $testV['Price_SL'], 8)) {
                        $test[$testK]['status'] = 'SL';
                        $test[$testK]['timeSELL'] = $value_klines[0];
                        $test[$testK]['age'] = '';
                        
                        $test[$testK]['sum_SELL'] = bcmul($test[$testK]['Price_SL'], $test[$testK]['quantity'], 8);
                        $test[$testK]['maker'] = bcmul($test[$testK]['sum_SELL'], $tradeFeeKom[$value['symbol']]['maker'], 8);

                        $test[$testK]['margin'] = bcsub($test[$testK]['sum_SELL'], $test[$testK]['sum_BUY'], 8);
                        $test[$testK]['taker+maker'] =  bcadd($test[$testK]['taker'], $test[$testK]['maker'], 8);
                        $test[$testK]['profit(loss)'] = bcsub($test[$testK]['margin'], $test[$testK]['taker+maker'], 8);
                        continue;
                    }
                    //распродажа открытых
                    if ($keyK == count($funded)-1) {
                        $test[$testK]['status'] = 'out';
                        $test[$testK]['timeSELL'] = $value_klines[0];
                        $test[$testK]['age'] = '';
                        
                        $test[$testK]['sum_SELL'] = bcmul($value_klines[4], $test[$testK]['quantity'], 8);
                        $test[$testK]['maker'] = bcmul($test[$testK]['sum_SELL'], $tradeFeeKom[$value['symbol']]['maker'], 8);

                        $test[$testK]['margin'] = bcsub($test[$testK]['sum_SELL'], $test[$testK]['sum_BUY'], 8);
                        $test[$testK]['taker+maker'] =  bcadd($test[$testK]['taker'], $test[$testK]['maker'], 8);
                        $test[$testK]['profit(loss)'] = bcsub($test[$testK]['margin'], $test[$testK]['taker+maker'], 8);

                    }
                    $open++;
                    //запоменаем цену окрытого ордера
                    $lastBUY = bcmul($test[$testK]['Price_BUY'], $settings['thresholdBuy'], 8);

                }
                if ($keyK == count($funded)-1) break;



                 //##### ЗАКУПКА при top И выставление ОСО PROFIT SL   && -1 == bccomp($value_klines[4], $lastBUY, 8)
                if ($value_klines[10] > $settings['quote_volume'] &&  1 == bccomp($value_klines[4], $value_klines[1], 8)) {
                    $temp['time'] = $value_klines[0];
                    $temp['strateg'] = 'volumeTop';

                    $temp['Price_BUY'] = $value_klines[4];
                    $temp['Price_TP'] = bcmul($value_klines[4], $settings['TakeProfit'], 8);
                    $temp['Price_SL'] = bcmul($value_klines[4], $settings['StopLoss'], 8); 
                    $temp['quantity'] = $Bin->round_min(bcdiv($settings['trade_limit'], $value_klines[4], 8), $symbolInfo['0']['filters'][2]['minQty']);

                    $temp['sum_BUY'] = bcmul($temp['Price_BUY'], $temp['quantity'], 8);
                    $temp['taker'] = bcmul($temp['sum_BUY'], $tradeFeeKom[$value['symbol']]['taker'], 8);

                    $temp['sum_SELL'] = '';
                    $temp['maker'] = '';

                    $temp['open'] = $open;
                    $temp['status'] = '';
                    $temp['timeSELL'] = '';
                    $temp['age'] = '';

                    $temp['margin'] = '';
                    $temp['taker+maker'] = '';                
                    $temp['profit'] = '';                 

                    $test[] = $temp;
                }
                //##### ЗАКУПКА при Bottom И выставление ОСО PROFIT SL   && -1 == bccomp($value_klines[4], $lastBUY, 8)
                if ($value_klines[10] > $settings['quote_volume'] &&  -1 == bccomp($value_klines[4], $value_klines[1], 8)) {
                    $temp['time'] = $value_klines[0];
                    $temp['strateg'] = 'volumeBottom';

                    $temp['Price_BUY'] = $value_klines[4];
                    $temp['Price_TP'] = bcmul($value_klines[4], $settings['TakeProfit'], 8);
                    $temp['Price_SL'] = bcmul($value_klines[4], $settings['StopLoss'], 8); 
                    $temp['quantity'] = $Bin->round_min(bcdiv($settings['trade_limit'], $value_klines[4], 8), $symbolInfo['0']['filters'][2]['minQty']);

                    $temp['sum_BUY'] = bcmul($temp['Price_BUY'], $temp['quantity'], 8);
                    $temp['taker'] = bcmul($temp['sum_BUY'], $tradeFeeKom[$value['symbol']]['taker'], 8);

                    $temp['sum_SELL'] = '';
                    $temp['maker'] = '';

                    $temp['open'] = $open;
                    $temp['status'] = '';
                    $temp['timeSELL'] = '';
                    $temp['age'] = '';

                    $temp['margin'] = '';
                    $temp['taker+maker'] = '';                
                    $temp['profit'] = '';                 

                    $test[] = $temp;
                }
                //##### ЗАКУПКА при TOP И выставление ОСО PROFIT SL  
                if (-1 == bccomp(bcdiv($value_klines[4], $value_klines[1], 8), $settings['dump'], 8) &&  -1 == bccomp($value_klines[4], $value_klines[1], 8)) {
                    $temp['time'] = $value_klines[0];
                    $temp['strateg'] = 'Dump';

                    $temp['Price_BUY'] = $value_klines[4];
                    $temp['Price_TP'] = bcmul($value_klines[4], $settings['TakeProfit'], 8);
                    $temp['Price_SL'] = bcmul($value_klines[4], $settings['StopLoss'], 8); 
                    $temp['quantity'] = $Bin->round_min(bcdiv($settings['trade_limit'], $value_klines[4], 8), $symbolInfo['0']['filters'][2]['minQty']);

                    $temp['sum_BUY'] = bcmul($temp['Price_BUY'], $temp['quantity'], 8);
                    $temp['taker'] = bcmul($temp['sum_BUY'], $tradeFeeKom[$value['symbol']]['taker'], 8);

                    $temp['sum_SELL'] = '';
                    $temp['maker'] = '';

                    $temp['open'] = $open;
                    $temp['status'] = '';
                    $temp['timeSELL'] = '';
                    $temp['age'] = '';

                    $temp['margin'] = '';
                    $temp['taker+maker'] = '';                
                    $temp['profit'] = '';                 

                    $test[] = $temp;
                }     
                     
                // $testOrderT[] = $funded[$keyK];                    
            }// онец цикла  funded              

                    // array_multisort($array_klin_p);
                

                $strateg = array_unique( array_column($test, 'strateg'));
                $variant = ['TP', 'SL', 'out'];
                foreach ($strateg as $key1 => $str) { 
                  $temp = [];
                  $temp['strateg'] = $str;
                  $strateg_values = array_values(Functions::multiSearch($test, array('strateg'=>$str)));
                  // $Bin->showArrayTable($strateg_values);
                  foreach ($variant as $key2 => $var) {
                      $values = array_values(Functions::multiSearch($strateg_values, array('status' => $var)));
                      $temp[$var] = count($values);
                      $temp[$var.'_∑'] = round(array_sum(array_column($values, 'profit(loss)')),2); 

                  }

                  $temp['PROFIT_∑'] = $temp['TP_∑'] + $temp['SL_∑'] + $temp['out_∑'];
                  $temp['max_open'] = max(array_column($strateg_values, 'open'));

                  // $Bin->show($symbol);
                  $strat = $symbol; 
                  $strat += $temp;
                  

                  $select[] = $strat; 

                }

                
                Functions::showArrayTable($test);
                // $Bin->showArrayTable($funded);
                unset($key_BUY, $klines, $funded);


 
     
        // $open = array_column(array_values($Bin->multiSearch($testOrder, array('status' => ''))), 'asset');
       //####################################################################################################################################################

        $buy = 0;
        
        // if (in_array($g_trend, $arra_g_trend)) {
        //     // $symbol['g_trend_st'] = '+';
        //     $buy++;        
        // }


        //
        // if (0==bccomp($symbol['lastPrice'], $symbol['askPrice'], 8))  {
        //     $symbol['ask_last_st'] ='+';
        //     $buy++;        
        // }

        //
        // if (!in_array($symbol['asset'], $open))   {
        //     $symbol['open_st']= '#'; 
        //     // $buy++;        
        // }
        //
        // if (count($open)<100)   {
        //     // $symbol['open_count_st']= '+'; 
        //     $buy++;        
        // }

    // $symbol['Yes']= $buy; 


     // ПОКУПКА
      //####################################################################################################################################################
    if ($buy == 1000) {
        $symbol['statusTREND']= 'TEST BUY';

        //определяем цену за которую хотим  купить
        if (-1==bccomp($symbol['Start_p'], -1, 8)) {
           $priceBUY = $Bin->round_min(bcmul($value['bidPrice'], 0.999, 8), $symbolInfo['0']['filters'][0]['minPrice']);
        }else{
           $priceBUY = $funded[$k_n-1][4];              
        }
        //масив параметров BUY_MARKET
        $Params_BUY_MARKET = array('symbol'=>$value['symbol'], 
                          'side' => 'BUY', 
                          'type' => 'MARKET', 
                          'quantity' => $Bin->round_min(bcdiv($trade_limit, bcmul($value['askPrice'], $kurs['kursUSD'], 8), 8),$symbolInfo['0']['filters'][2]['minQty']),
                          'timeInForce' => 'IOC', 
                          'price' => $priceBUY);  

        //параметры BUY_OCO                                            
        $Params_BUY_OCO = array('symbol'=>$value['symbol'], 
                          'side' => 'BUY', 
                          'quantity' => $Bin->round_min($order['executedQty'], $symbolInfo['0']['filters'][2]['minQty']),
                          'price' => $Bin->round_min(bcmul($priceBUY, $BUY_OCO['Price'], 8), $symbolInfo['0']['filters'][0]['minPrice']),
                          'stopPrice' => $Bin->round_min(bcmul($priceBUY, $BUY_OCO['S_Price'], 8), $symbolInfo['0']['filters'][0]['minPrice']),
                          'stopLimitPrice' => $Bin->round_min(bcmul($priceBUY, $BUY_OCO['SL_Price'], 8), $symbolInfo['0']['filters'][0]['minPrice']), 
                          'stopLimitTimeInForce' => 'GTC'); 




        //ПОКУПКА LIMIT IOC 
        if ($orderBUY == '1') {
            //если создаем лемитные ордера то проверяем и удаляем устаревшие
            $orderOpen = array_values(Functions::multiSearch($orderOPEN, array('symbol' => $value['asset'].'USDT', 'type'=>'LIMIT', 'side'=>'BUY')));
            if (count($orderOpen)>0){
                foreach ($orderOpen as $key => $order) {
                    $orderOpen[$key]['status'] = '';
                    if (1 == bccomp((string)$orderOpen['price'] , (string)$priceBUY, 8)) {
                        echo $value['symbol'], ' ', $priceBUY, "<br/>";
                        $orderOpen[$key]['statusOpen'] = 'УДАЛЯЮ';
                        $ParamsDELETE = array('symbol'=>$value['symbol'], 'orderId'=>$order['orderId']);
                        if ($orderDELETE = $Bin->orderDELETE($ParamsDELETE)) {
                            $orderOpen[$key]['status'] = 'DEL';
                        }
                    }
                }
            }
            //параметры ПОКУПКА LIMIT IOC 
            $Params_BUY = array('symbol'=>$value['symbol'], 
                        'side' => 'BUY', 
                        'type' => 'LIMIT', 
                        'quantity' => $Bin->round_min(bcdiv($trade_limit, bcmul($value['askPrice'], $kurs['kursUSD'], 8), 8), $symbolInfo['0']['filters'][2]['minQty']), 
                        'timeInForce' => 'IOC',
                        'recvWindow' => '30000', 
                        'price' => $priceBUY);
            //отправляем BUY ордер
            if ($order = $Bin->orderNEW($Params_BUY)) { 
                $symbol['statusTREND'] = 'BUY_LIMIT NO';
                if (0 != bccomp((string)$order['executedQty'], (string)0, 8)) {
                  $symbol['statusTREND'] = 'BUY_LIMIT';
                    //при успехе BUY выставляем SELL_OCO ордера
                    if ($orderSELL_OCO == '1') {
                        //параметры SELL OCO                                             
                        $Params_SELL_OCO = array('symbol'=>$value['symbol'], 
                                          'side' => 'SELL', 
                                          'quantity' => $order['executedQty'],
                                          'price' => $Bin->round_min(bcmul($priceBUY, $SELL_OCO['Price'], 8), $symbolInfo['0']['filters'][0]['minPrice']),
                                          'stopPrice' => $Bin->round_min(bcmul($priceBUY, $SELL_OCO['S_Price'], 8), $symbolInfo['0']['filters'][0]['minPrice']),
                                          'stopLimitPrice' => $Bin->round_min(bcmul($priceBUY, $SELL_OCO['SL_Price'], 8), $symbolInfo['0']['filters'][0]['minPrice']), 
                                          'stopLimitTimeInForce' => 'GTC');  
                         //отправляем BUY ордер                                                            
                        if ($orderOCO = $Bin->OCO($Params_SELL_OCO)) {
                          $symbol['statusTREND'] = 'BUY_LIMIT + SELL_OCO';
                        }
                    }
                }         
            }
        }
    }

   
}//конец цикла 1

//Сохраняем тестовых закупок
Functions::saveFile($testOrder, $filetestOrder);



// $Bin->showArrayTable(array_reverse($testOrderT, true));
//Сохраняем историю.
// $Bin->saveFile($historyTicker24hr, $filehistoryTicker24hr);

//Сохраняем историю покупок
// $Bin->saveFile($historyBUY, $filehistoryBUY);

//Смотрим настройки
// $Bin->show($settings);

//ОТОБРАЛ
echo "<br/>Проанализировал ", count($select), '<br/>';
// //Сортируем и смотрим
usort($select, function($a, $b) {
    return ($b['BALANCE']*100) - ($a['BALANCE']*100);
});
Functions::showArrayTable($select);

// echo 'N_all ', array_sum(array_column($select, 'N_all')), "<br/>";
// echo 'N_open ', array_sum(array_column($select, 'N_open')), "<br/>";
// echo 'N_profit ', array_sum(array_column($select, 'N_profit')), "<br/>";
// echo 'N_loss ', array_sum(array_column($select, 'N_loss')), "<br/>";

// echo 'BALANCE ', array_sum(array_column($select, 'BALANCE')), "<br/>";




$g_trend = array_unique(array_column($testOrder, 'g_trend'));
echo 'Стратегий  g_trend ', count($g_trend), '<br/>';
// $Bin->showArrayTable($g_trend);

$analiz =array();
$i =0;
    foreach ($g_trend as $keyGT => $valueGT) {
        $i++;
        // $analiz[$i]['symbol'] = $value; 
        $analiz[$i]['g_trend'] = $valueGT;

        $selectMARGIN = array_values(Functions::multiSearch($testOrder, array('g_trend' => $valueGT, 'status' => 'MARGIN')));
            $analiz[$i]['countM'] = count($selectMARGIN);
            $analiz[$i]['profit'] = array_sum(array_column($selectMARGIN, 'result'));

        $selectSL = array_values(Functions::multiSearch($testOrder, array('g_trend' => $valueGT, 'status' => 'SL')));
            $analiz[$i]['countSL'] = count($selectSL);
            $analiz[$i]['losses'] = array_sum(array_column($selectSL, 'result'));


        $analiz[$i]['balans'] = $analiz[$i]['profit'] +  $analiz[$i]['losses']; 


        $select_ = array_values(Functions::multiSearch($testOrder, array('g_trend' => $valueGT, 'status' => '')));
            $analiz[$i]['count_'] = count($select_);
  

        
// break;
}
usort($analiz, function($a, $b) {
    return abs($b['balans']*100) - abs($a['balans']*100);
});
Functions::showArrayTable($analiz);

// $Bin->showArrayTable(array_reverse($testOrder));

// echo "<br/>КОНТРОЛИРУЕМ ", count($BUYtestOrder), '<br/>';
// //Сортируем и смотрим
// usort($testOrder, function($a, $b) {
//     return $b['N'] - $a['N'];
// });

// echo "<br/>АРХИВ покупок ", count($historyBUY), '<br/>';
// $Bin->showArrayTableOrders($historyBUY);

//Смотрим баланс
// echo "<br/>БАЛАНС АКТИВОВ количество ", count($accountBalance), '<br/>';
// $Bin->showArrayTable($accountBalance);

// sleep(2);
$time = microtime(true) - $start;
echo 'Время выполнения скрипта: ', round($time, 4), ' сек.<br/>';
echo 'Обем памяти: ', (memory_get_usage() - $mem_start)/1000000, ' мегабайта.<br/><br/><br/><br/>';


exit();
?>