<?php
$start = microtime(true);
$mem_start = memory_get_usage();
//$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
echo "BUCKS (v5) ЗАКУПКА<br/>";

//ВКЛЮЧИТЬ создания ордеров покупку
$settings['orderBUY'] = $orderBUY =0;
//стартовый лимит закупки
$settings['trade_limit'] = $trade_limit = 12;


//минимальное количество сделок за 24 час
$settings['countTrends'] = $countTrends= 1000; 
//минимальное обем продаж за 24 час
$settings['quoteVolume'] = $quoteVolume = 100000;
// максимальный % изминения цены за 24 час
$settings['priceChangePercent'] = $priceChangePercent= array(0, 1000); 


//НАСТРОЙКИ БЕРЕМ С ПРОДАЖИ
//Увеличить моментальную потерю маржи
$settings['addPrice_loss_p'] = $addPrice_loss_p = 0.1;
//потеря маржи % при Stop Loss
$settings['lossМargin_p'] = $lossМargin_p = -1;


//минимальный процент  Волонтильность за последние countKlines свечей
$settings['BUYcontrolChangePrice_p'] = $BUYcontrolChangePrice_p = 1;
//процент контроля роста цены от минимальной для покупки
$settings['controltopPrice_p'] = $controltopPrice_p = 0.3;

//Определяем базове валют
$base['BTC']= array('minBalans'=>0.5, 'minPriceBuy'=>0.00000100);
$base['USDT']= array('minBalans'=>1000, 'minPriceBuy'=>0.00000100);
$base['BNB']= array('minBalans'=>10, 'minPriceBuy'=>0.00000100);
$base['ETH']= array('minBalans'=>10, 'minPriceBuy'=>0.00000100);
$base['TRX']= array('minBalans'=>100, 'minPriceBuy'=>0.00000100);
$base['XRP']= array('minBalans'=>100, 'minPriceBuy'=>0.00000100);
$base['EUR']= array('minBalans'=>1000, 'minPriceBuy'=>0.00000100);

//$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
header('refresh: 1');
//Устанавливаем настройки времени
ini_set('max_execution_time', 1000);    //  одно и тоже что set_time_limit(6000);
// echo "max_execution_time ", ini_get('max_execution_time'), "<br />";
// ob_implicit_flush(1);     
// ob_start();
// ob_get_contents();
// ob_get_clean();
// ob_end_flush();  

//Создаем класс
$Bin = new binance();
//Проверяем торговый статус API аккаунта
// $apiTradingStatus= $Bin->apiTradingStatus(array());
// $Bin->show($apiTradingStatus);
// die();

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
    // die();
}else{    
    //Даные в файле актуальны читаем
    $tradeFeeKom = $Bin->readFile($filetradeFeeKom);
    $exchangeInfo = $Bin->readFile($fileexchangeInfo);   

    $time = microtime(true) - $start;
    // echo 'Файлы прочитаны. время: '.round($time, 4).' сек.<br/>';
}

//Смотрим
// $Bin->show($tradeFeeKom);
// $Bin->show($exchangeInfo);

//Получение ПОСЛЕДНИХ ДАНЫХ  symbol-----------------------------
$count = 10;

//Получить изминения за 24 часса
$ticker24hr = $Bin->ticker24hr();
$filehistoryTicker24hr = 'E:\binance\V5historyTicker24hr.txt';
if (!$historyTicker24hr = $Bin->readFile($filehistoryTicker24hr)){
  $historyTicker24hr[] = $ticker24hr;
}else{    
  array_unshift($historyTicker24hr, $ticker24hr);
  array_splice($historyTicker24hr, $count);
}
// $Bin->show($ticker24hr[0]);

//Получить свободный баланс АКТИВОВ
$accountBalance= $Bin->accountBalance($ticker24hr, $base);
// $Bin->showArrayTable($accountBalance);
// $Bin->show(array_column($accountBalance, 'asset'));

//Читаем файл архива покупок
$filehistoryBUY = 'E:\binance\V5historyBUY.txt';
if (!$historyBUY  = $Bin->readFile($filehistoryBUY )){
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



$fileBUYtestOrder = 'E:\binance\V5BUYtestOrder.txt';
if ($BUYtestOrder = $Bin->readFile($fileBUYtestOrder)){
      foreach ($BUYtestOrder as $key => $value) {
          if (-2>$value['N'])  unset($BUYtestOrder[$key]);
      }
}else{
  $BUYtestOrder = array();
}

// $Bin->show($BUYtestOrder);


//Номера контрольных проверок
$control = array(1, 2);


$select = array(); 
foreach ($ticker24hr as $key => $value) {

        //Получаем информацию о symbol и исключаем неактивные пары
        if (!$symbolInfo = array_values($Bin->multiSearch($exchangeInfo['symbols'], array('symbol' => $value['symbol'], 'status'=>'TRADING'))) ) continue;
        //Получаем курс BTC USD
        $kurs = $Bin->kurs($symbolInfo['0']['quoteAsset'], $ticker24hr);

        if (array_key_exists($value['symbol'], $BUYtestOrder) && $value['symbol'] == $historyTicker24hr[1][$key]['symbol']) {

                //Находим сумы покупки и продажи 
                  $BUYtestOrder[$value['symbol']]['closeTime'] = $value['closeTime'];
                  $quantitySELL = $BUYtestOrder[$value['symbol']]['quantity'];


                  $BUYtestOrder[$value['symbol']]['closeTime'] = $value['closeTime'];
                  $BUYtestOrder[$value['symbol']]['priceChangePercent'] = $value['priceChangePercent'];

                  $BUYtestOrder[$value['symbol']]['lastPrice'] = $value['lastPrice'];                      
                  $BUYtestOrder[$value['symbol']]['askPrice'] = $value['askPrice'];
                                       
                  $BUYtestOrder[$value['symbol']]['kursUSD']= $kurs['kursUSD'];
                  $BUYtestOrder[$value['symbol']]['kursBTC']= $kurs['kursBTC'];


                  $BUYtestOrder[$value['symbol']]['bidPrice']=$value['bidPrice'];                  
                  $BUYtestOrder[$value['symbol']]['sumBUY']= $sumBUY = bcmul($quantitySELL, $BUYtestOrder[$value['symbol']]['askPrice_1'], 8);       
                  $BUYtestOrder[$value['symbol']]['sumSELL']= $sumSELL = bcmul($quantitySELL, $value['bidPrice'], 8);

                  //Находим комисию биржи
                  $sumtaker = bcmul($sumBUY, $tradeFeeKom[$maxSELL[0]['symbol']]['taker'], 8);
                  $summaker = bcmul($sumSELL, $tradeFeeKom[$maxSELL[0]['symbol']]['maker'], 8);

                  //Считаем маржу 
                  $BUYtestOrder[$value['symbol']]['margin']= $margin_sum = bcsub(bcsub($sumSELL, $summaker, 8), bcsub($sumBUY, $sumtaker, 8), 8);               

                  //Считаем % маржи 
                  $BUYtestOrder[$value['symbol']]['margin_p']= $margin_p = bcmul(bcdiv($margin_sum, $sumBUY, 8), 100, 8);

                  

                  $K = bcdiv($BUYtestOrder[$value['symbol']]['kursUSD'], $kurs['kursUSD'], 8);
                  $kursUSD = bcmul($K, $kurs['kursUSD'], 8);
                  $BUYtestOrder[$value['symbol']]['marginUSD']=bcmul($margin_sum, $kursUSD, 8);


// && -1== bccomp((string)$value['askPrice_1'], (string)$BUYtestOrder[$value['symbol']]['askPrice_2'], 8)
              if (1== bccomp((string)$margin_p, (string)0, 8) && 1== bccomp((string)$value['askPrice'], (string)$BUYtestOrder[$value['symbol']]['askPrice_1'], 8)) {
                    $BUYtestOrder[$value['symbol']]['N']++;
                    $BUYtestOrder[$value['symbol']]['status'] = 'растет BUY OFF'; 

                    //масив параметров для покупки
                  $ParamsBUY = array('symbol'=>$value['symbol'], 
                                  'side' => 'BUY', 
                                  'type' => 'LIMIT', 
                                  'quantity' => $Bin->round_min(bcdiv($trade_limit, bcmul($value['askPrice'], $kurs['kursUSD'], 8), 8), $symbolInfo['0']['filters'][2]['minQty']), 
                                  'timeInForce' => 'IOC', 
                                  'price' => $value['askPrice']); 
                // $Bin->show($value);

                // $Bin->show($ParamsBUY);


                    //ПОКУПКА    
                    if ($orderBUY == '1') {
                      $BUYtestOrder[$value['symbol']]['status'] = ' BUY';
                        if ($order = $Bin->orderNEW($ParamsBUY)) {
                            // $Bin->show($order);
                          if (!empty($order['executedQty'])) {
                            //заполяем историю покупок
                            $historyBUY[$symbolInfo['0']['baseAsset']]['symbol'] =  $value['symbol'];
                            $historyBUY[$symbolInfo['0']['baseAsset']]['asset'] =  $symbolInfo['0']['baseAsset'];                
                            $historyBUY[$symbolInfo['0']['baseAsset']]['base'] =  $symbolInfo['0']['quoteAsset'];                            
                            $historyBUY[$symbolInfo['0']['baseAsset']]['time'] =  $value['closeTime'];
                            $historyBUY[$symbolInfo['0']['baseAsset']]['quantityBUY'] = $order['executedQty'];                            
                            $historyBUY[$symbolInfo['0']['baseAsset']]['askPrice'] = $value['askPrice'];
                            $historyBUY[$symbolInfo['0']['baseAsset']]['price'] = bcdiv($order['cummulativeQuoteQty'], $order['executedQty'], 8);
                            $historyBUY[$symbolInfo['0']['baseAsset']]['priceUSD'] = bcmul($historyBUY[$symbolInfo['0']['baseAsset']]['price'], $historyBUY[$symbolInfo['0']['baseAsset']]['correctionUSD'], 8);                
                            $historyBUY[$symbolInfo['0']['baseAsset']]['BUYkursUSD'] = $kurs['kursUSD'];
                            $historyBUY[$symbolInfo['0']['baseAsset']]['kursUSD'] = $kurs['kursUSD'];
                            $historyBUY[$symbolInfo['0']['baseAsset']]['correctionUSD'] = 1;
                            
                            $historyBUY[$symbolInfo['0']['baseAsset']]['priceBTC'] = bcmul($value['askPrice'],$kurs['kursBTC'], 8);
                            $historyBUY[$symbolInfo['0']['baseAsset']]['BUYkursBTC'] = $kurs['kursBTC'];
                            $historyBUY[$symbolInfo['0']['baseAsset']]['kursBTC'] = $kurs['kursBTC'];
                            $historyBUY[$symbolInfo['0']['baseAsset']]['correctionBTC'] = 1;

                            $order['kurs'] = $kurs;
                            $historyBUY[$symbolInfo['0']['baseAsset']]['buy'][] = $order;
                            $Bin->saveFile($historyBUY, $filehistoryBUY);  
                            // die($value['symbol']); 
                          }
       
                        }

                    }
              }

              if (0== bccomp((string)$margin_p, (string)0, 8)) {
                $BUYtestOrder[$value['symbol']]['status'] = ' стоит ';
              }

              if (-1== bccomp((string)$margin_p, (string)0, 8)) {

                    $BUYtestOrder[$value['symbol']]['N']--;
                    $BUYtestOrder[$value['symbol']]['status'] = 'падает';               

               }

        }






        //Исключаем с обемом продаж за сутки менее (24 часа) 
        // if (($value['quoteVolume']*$kurs['kursUSD']) < $quoteVolume) continue;

        //Исключаем с количеством операций меньше $countTrends (24 часа) 
        // if ($value['count'] < $countTrends) continue;

        //Исключаем с процентом изминения цены до и от (24 часа) 
        // if (-1== bccomp((string)$value['priceChangePercent'], (string)$priceChangePercent[0], 8) || 1== bccomp((string)$value['priceChangePercent'], (string)$priceChangePercent[1], 8)) continue; 

        // Исключаем пары с неутвержденой базой
        if (!array_key_exists($symbolInfo['0']['quoteAsset'], $base)) continue;

        //Исключаем пары кторые не могу купить НЕТ ДЕНЕГ
        if ($accountBalance[$symbolInfo['0']['quoteAsset']]['total_USD'] < $trade_limit) continue;
        
        //Исключаем пары кторые не нужны ЕСТЬ НА БАЛАНСЕ и неявляются базой
        if ($accountBalance[$symbolInfo['0']['baseAsset']]['total_USD'] >= $trade_limit*3 && !array_key_exists($symbolInfo['0']['baseAsset'], $base)) continue;

        //Исключаем пары кторые не нужны ЕСТЬ НА БАЛАНСЕ  меньше минимума и являются базой
        if ($accountBalance[$symbolInfo['0']['baseAsset']]['total_USD'] <= $accountBalance[$symbolInfo['0']['baseAsset']]['min'] && array_key_exists($symbolInfo['0']['baseAsset'], $base)) continue; 

        // Исключаем пары с ценой меньше рекомендованой более 100 сатошей
        if (-1== bccomp((string)$value['askPrice'], (string)number_format($base[$symbolInfo['0']['quoteAsset']]['minPriceBuy'], 8, '.', ''), 8)) continue;

                    //находим моментальную потерю цены при покупке
                    $takerask =  bcmul($value['askPrice'], $tradeFeeKom[$value['symbol']]['taker'], 8);
                    $price_loss = bcsub($value['askPrice'], bcsub($value['bidPrice'], $takerask, 8), 8);
                    //находи процент потери цены при покупке + коректировка
                    $price_loss_p = bcadd(bcmul(bcdiv($price_loss, $value['bidPrice'], 8),100, 8), $addPrice_loss_p, 8);  

        //Исключаем если моминтальная потеря цены при покупке больше //потеря маржи % при Stop Loss    $lossМargin_p
        if (1== bccomp((string)$price_loss_p, (string)abs($lossМargin_p), 8))  continue; 



  
  //&& 1==bccomp($value['askPrice'], $value['openPrice'], 8) 1==bccomp($value['askPrice'], $historyTicker24hr[1][$key]['askPrice'], 8) 
    if ( 1== bccomp((string)$value['askPrice'], (string)$historyTicker24hr[1][$key]['askPrice'], 8) && $value['symbol'] == $historyTicker24hr[1][$key]['symbol']) { 
                  if (!array_key_exists($value['symbol'], $BUYtestOrder)) {
                      $testOrder['symbol'] = $value['symbol'];
                      $testOrder['asset'] = $symbolInfo['0']['baseAsset'];
                      $testOrder['openTime'] = $value['openTime'];
                      $testOrder['closeTime'] = $value['closeTime'];
                      $testOrder['priceChangePercent'] = $value['priceChangePercent'];
                      $testOrder['quantity'] = $Bin->round_min(bcdiv($trade_limit, bcmul($value['askPrice'], $kurs['kursUSD'], 8), 8), $symbolInfo['0']['filters'][2]['minQty']);
                      $testOrder['lastPrice'] = $value['lastPrice'];                      
                      $testOrder['askPrice'] = $value['askPrice'];
                      $testOrder['bidPrice'] = $value['bidPrice'];                      
                      $testOrder['kursUSD']= $kurs['kursUSD'];
                      $testOrder['kursBTC']= $kurs['kursBTC'];

                      $testOrder['sumBUY']= '';  
                      $testOrder['sumSELL']='';
                      $testOrder['margin']= '';   
                      $testOrder['marginUSD'] = '';            
                      $testOrder['margin_p']= '';                      

                      $testOrder['status']= 'NEW';                  
                      $testOrder['N']=0;
                          foreach ($control as $n) {
                                $difference = bcsub($value['lastPrice'], $value['askPrice'],  8);
                                $difference_p = bcmul(bcdiv($difference, $historyTicker24hr[$n][$key]['lastPrice'], 8),100, 8);
                                $testOrder['lastPrice_'. $n] = $historyTicker24hr[$n][$key]['lastPrice'];
                                $testOrder['askPrice_'. $n] = $historyTicker24hr[$n][$key]['askPrice'];
                                $testOrder['bidPrice_'. $n] = $historyTicker24hr[$n][$key]['bidPrice'];                              
                                $testOrder[$n] = $difference_p;

                          }
                      $BUYtestOrder[$value['symbol']] = $testOrder;

                  }else{
                          foreach ($control as $n) {

                                $difference = bcsub($value['lastPrice'], $value['askPrice'],  8);
                                $difference_p = bcmul(bcdiv($difference, $historyTicker24hr[$n][$key]['lastPrice'], 8),100, 8);

                                // $value['askPrice_'. $n] = $historyTicker24hr[$n][$key]['askPrice'];
                                // $value['difference_p_'. $n] = $difference_p;
                                $BUYtestOrder[$value['symbol']]['lastPrice_'. $n] = $historyTicker24hr[$n][$key]['lastPrice'];
                                $BUYtestOrder[$value['symbol']]['askPrice_'. $n] = $historyTicker24hr[$n][$key]['askPrice'];
                                $BUYtestOrder[$value['symbol']]['bidPrice_'. $n] = $historyTicker24hr[$n][$key]['bidPrice'];



                                $BUYtestOrder[$value['symbol']][$n] = $difference_p;


                          }
                   
                    // $BUYtestOrder[$value['symbol']]['N']++;
                  }

    
     }             






}



//Сохраняем тестовых закупок
$Bin->saveFile($BUYtestOrder, $fileBUYtestOrder);

//Сохраняем историю.
$Bin->saveFile($historyTicker24hr, $filehistoryTicker24hr);

//Сохраняем историю покупок
$Bin->saveFile($historyBUY, $filehistoryBUY);

//Смотрим настройки
// $Bin->show($settings);

//Проверить даты
// foreach ($historyTicker24hr as $key => $value) {
//   echo $key, ' -> ', date("Y-m-d H:i:s", $value[0]['closeTime']/1000), '<br>';
  
// }

//ОТОБРАЛ
// echo "<br/>ОТОБРАЛ ", count($select), '<br/>';
// //Сортируем и смотрим
// usort($select, function($a, $b) {
//     return $b['p_1']*100000000 - $a['p_1']*100000000;
// });
// $Bin->showArrayTable($select);

echo "<br/>КОНТРОЛИРУЕМ ", count($BUYtestOrder), '<br/>';
//Сортируем и смотрим
usort($BUYtestOrder, function($a, $b) {
    return $b['margin_p']*100000000 - $a['margin_p']*100000000;
});
$Bin->showArrayTable($BUYtestOrder);

echo "<br/>АРХИВ покупок ", count($historyBUY), '<br/>';
$Bin->showArrayTable($historyBUY);





//Смотрим баланс
// echo "<br/>БАЛАНС АКТИВОВ количество ", count($accountBalance), '<br/>';
// $Bin->showArrayTable($accountBalance);


$time = microtime(true) - $start;
echo 'Время выполнения скрипта: ', round($time, 4), ' сек.<br/>';
echo 'Обем памяти: ', (memory_get_usage() - $mem_start)/1000000, ' мегабайта.<br/>';


exit();
?>