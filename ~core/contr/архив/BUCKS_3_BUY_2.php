<?php
$start = microtime(true);
$mem_start = memory_get_usage();
//$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
echo "BUCKS_3_BUY_2 ЗАКУПКА<br/>";
//запустить тестовою покупку $orderBUY == 0
$settings['order_Test'] = $order_Test = '0';


//ВКЛЮЧИТЬ создания ордеров покупку
$settings['orderBUY'] = $orderBUY =0;


//минимальное количество сделок за 24 час
$settings['countTrends'] = $countTrends= 1000; 
//минимальное обем продаж за 24 час
$settings['quoteVolume'] = $quoteVolume = 100000;
// максимальный % изминения цены за 24 час
$settings['priceChangePercent'] = $priceChangePercent= array(0, 5); 


//стартовый лимит закупки
$settings['trade_limit'] = $trade_limit = 12;


//НАСТРОЙКИ БЕРЕМ С ПРОДАЖИ
//Увеличить моментальную потерю маржи
$settings['addPrice_loss_p'] = $addPrice_loss_p = 0.1;
//потеря маржи % при Stop Loss
$settings['lossМargin_p'] = $lossМargin_p = -1;


//только торговые пары  'BEARUSDT', 'ETHBEARUSDT', 'XRPBEARUSDT', 'EOSBEARUSDT', 'ETHBULLUSDT'
$symbolBUY =  array('XRPBEARUSDT');


//КОНТРОЛЬНЫЙ интервал 1m/3m/5m/15m/30m/1h/2h/4h/6h/8h/12h/1d/3d/1w/1M
$settings['IntervalControl'] = $IntervalControl = array('1m','3m','5m','15m','30m','1h','2h','4h','6h','8h','12h','1d','3d','1M');
//количество свечей
$settings['countIntervalControl'] = $countIntervalControl = 24;

//РАБОЧИЙ интервал 1m/3m/5m/15m/30m/1h/2h/4h/6h/8h/12h/1d/3d/1w/1M
$settings['IntervalBUY'] = $IntervalBUY = '1m';
//количество свечей
$settings['countIntervalBUY'] = $countIntervalBUY = 100;

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


//Получить изминения за 24 часса
$ticker24hr = $Bin->ticker24hr();
// $Bin->show($ticker24hr[0]);

//Получить свободный баланс АКТИВОВ
$accountBalance= $Bin->accountBalance($ticker24hr, $base);
// $Bin->showArrayTable($accountBalance);

//Читаем файл истории стаканов
$filehistoryKlines = 'E:\binance\V5historyKlines.txt';
if (!$historyKlines = $Bin->readFile($filehistoryKlines)) $historyKlines = array();
// $Bin->show($historyKlines);



//Читаем файл архива покупок
$filehistoryBUY = 'E:\binance\V5historyBUY .txt';
if (!$historyBUY  = $Bin->readFile($filehistoryBUY )) $historyBUY  = array();
    foreach ($historyBUY as $key => $value) {
      if (!in_array($key, array_column($accountBalance, 'asset'))) {
          unset($historyBUY[$key]);
          continue;
      }      
      $kurs = $Bin->kurs($value['base'], $ticker24hr);
      $historyBUY[$key]['kursUSD'] = $kurs['kursUSD'];
      $historyBUY[$key]['correctionUSD']= bcdiv($value['BUYkursUSD'], $kurs['kursUSD'], 8);
      $historyBUY[$key]['kursBTC'] = $kurs['kursBTC'];
      $historyBUY[$key]['correctionBTC']= bcdiv($value['BUYkursBTC'], $kurs['kursBTC'], 8);

    }
// $Bin->show($historyBUY);
// die();
//############################################
//Читаем файл истории 
// $fileV5historyPrice = 'E:\binance\V5historyPrice.txt';
// if (!$V5historyPrice = $Bin->readFile($fileV5historyPrice)){
//  $V5historyPrice = array();
// }else{
//   $B=$V5historyPrice['buy'];
// }

// $Bin->show($V5historyPrice);

$Bin->show($ticker24hr[0]);
// die();
// echo date("Y-m-d H:i:s", $ticker24hr[0]['closeTime']/1000), '<br/>';
// echo date("Y-m-d H:i:s", time()), '<br/>';
// echo date("Y-m-d H:i", time()), '<br/>';
// echo date("Y-m-d H", time()), '<br/>';
// echo date("Y-m-d", time()), '<br/>';
// echo  time() - $ticker24hr[0]['closeTime']/1000;

// $interval = array("Y-m" => 6, "Y-m-d" => 31, "Y-m-d H" => 60, "Y-m-d H:i" => 100, "Y-m-d H:i:s"=> 100);
 $select = array();
foreach ($ticker24hr as $key => $value) {
        $fileHistory = 'E:\binance\symbols\symbol_'.$value['symbol'].'.txt';
        if (!$History = $Bin->readFile($fileHistory)){
          $History = array();
        }else{
           // $Bin->show($History);
           // die();
        }
   



        //Получаем информацию о symbol и исключаем неактивные пары
        if (!$symbolInfo = array_values($Bin->multiSearch($exchangeInfo['symbols'], array('symbol' => $value['symbol'], 'status'=>'TRADING'))) ) continue;
        //Получаем курс BTC USD
        $kurs = $Bin->kurs($symbolInfo['0']['quoteAsset'], $ticker24hr);


        if (!isset($History['Price'])) {

          $History['difference_p']='';
          $History['Price'][] = array($value['Close'] => $value['askPrice']);
        }else{
          //добавляем цену
          array_unshift($History['Price'], array(time() => $value['askPrice']));
          $History['symbol'] =  $value['symbol'];
          $History['asset'] =  $symbolInfo['0']['baseAsset'];                
          $History['base'] =  $symbolInfo['0']['quoteAsset'];


          // $difference_lastPrice = bcsub($value['askPrice'], $value['lastPrice'],  8);
          // $difference_p_LP = bcmul(bcdiv($value['lastPrice'], $difference_lastPrice, 8),100, 8);
          // $V5historyPrice[$symbolInfo['0']['quoteAsset']][$value['symbol']]['difference_p_LP'] = $difference_p_LP;

          // $startKey = 1;
          // $startPrice = $History['Price'][$startKey];
          // $difference = bcsub($value['askPrice'], $startPrice,  8);
          // $difference_p = bcmul(bcdiv($difference, $startPrice, 8),100, 8);
          // $V5historyPrice[$symbolInfo['0']['quoteAsset']][$value['symbol']]['differenceMax_p'] = $difference_p;


          // $startKeyMax = max(array_keys($V5historyPrice[$symbolInfo['0']['quoteAsset']][$value['symbol']]['Price']));
          // $startPriceMax = $V5historyPrice[$symbolInfo['0']['quoteAsset']][$value['symbol']]['Price'][$startKeyMax];
          // $differenceMax = bcsub($value['askPrice'], $startPriceMax, 8);
          // $differenceMax_p = bcmul(bcdiv($differenceMax, $startPriceMax, 8),100, 8);

          // $V5historyPrice[$symbolInfo['0']['quoteAsset']][$value['symbol']]['differenceMax_p'] = $difference_p;

          array_splice($History['Price'], 43200);
        }


          // && 1==bccomp($value['askPrice'], $value['lastPrice'], 8)

         if (1==bccomp($difference_p, '0.5', 8) && 1==bccomp($differenceMax_p, '1', 8)) {
          $value['difference_p'] = $difference_p;
          $value['differenceMax_p'] = $difference_p;
          $select[$value['symbol']]=$value;
          // $B[$value['symbol']]['lastPrice']=  $value['lastPrice'];
          // $B[$value['symbol']]['difference_p_LP']= $difference_p_LP;
          $B[$value['symbol']]['startPrice']= $startPrice;
          $B[$value['symbol']]['difference_p']= $difference_p;
          $B[$value['symbol']]['startPriceMax']= $startPriceMax;
          $B[$value['symbol']]['differenceMax_p']= $differenceMax_p;
          array_unshift($B[$value['symbol']], $value['askPrice']);


        //******************* ИСКЛЮЧАЕМ ДЛЯ ПОКУПКИ********************
        //Исключаем с обемом продаж за сутки менее (24 часа) 
        if (($value['quoteVolume']*$kurs['kursUSD']) < $quoteVolume) continue;

        //Исключаем с количеством операций меньше $countTrends (24 часа) 
        // if ($value['count'] < $countTrends) continue;

        //Исключаем с процентом изминения цены до и от (24 часа) 
        // if (-1== bccomp((string)$value['priceChangePercent'], (string)$priceChangePercent[0], 8) || 1== bccomp((string)$value['priceChangePercent'], (string)$priceChangePercent[1], 8)) continue; 

        // Исключаем пары с неутвержденой базой
        if (!array_key_exists($symbolInfo['0']['quoteAsset'], $base)) continue;

        //Исключаем пары кторые не могу купить НЕТ ДЕНЕГ
        if ($accountBalance[$symbolInfo['0']['quoteAsset']]['total_USD'] < $trade_limit) continue;
        
        //Исключаем пары кторые не нужны ЕСТЬ НА БАЛАНСЕ и неявляются базой
        // if ($accountBalance[$symbolInfo['0']['baseAsset']]['total_USD'] >= $trade_limit*3 && !array_key_exists($symbolInfo['0']['baseAsset'], $base)) continue;

        //Исключаем пары кторые не нужны ЕСТЬ НА БАЛАНСЕ  меньше минимума и являются базой
        // if ($accountBalance[$symbolInfo['0']['baseAsset']]['total_USD'] <= $accountBalance[$symbolInfo['0']['baseAsset']]['minBalans'] && array_key_exists($symbolInfo['0']['baseAsset'], $base)) continue; 

        // Исключаем пары с ценой меньше рекомендованой более 100 сатошей
        // if (-1== bccomp((string)$value['askPrice'], (string)number_format($base[$symbolInfo['0']['quoteAsset']]['minPriceBuy'], 8, '.', ''), 8)) continue;
          echo $value['symbol'], "   ПОКУПАЕМ<br/>";


                       //масив параметров для покупки
                      $ParamsBUY = array('symbol'=>$value['symbol'], 
                                      'side' => 'BUY', 
                                      'type' => 'LIMIT', 
                                      'quantity' => $Bin->round_min(bcdiv($trade_limit, bcmul($value['askPrice'], $kurs['kursUSD'], 8), 8), $symbolInfo['0']['filters'][2]['minQty']), 
                                      'timeInForce' => 'IOC', 
                                      'price' => $value['askPrice']); 
                
                      //ПОКУПКА    
                      if ($orderBUY == '1') {
                          if ($order = $Bin->orderNEW($ParamsBUY)) {
                              // $Bin->show($order);
                              $order['kurs'] = $kurs;
                              $historyBUY[$symbolInfo['0']['baseAsset']]['buy'][]  = $History['order'] = $order;
                              $Bin->saveFile($historyBUY, $filehistoryBUY);          
                          }

                      }
                      //СОХРАНЯЕМ ВЫБРАНОЕ ДЛЯ ПРОСМОТРА
                      $BUY[$value['symbol']]['count_24'] =  $value['count'];
                      $BUY[$value['symbol']]['priceChangePercent_24'] =  $value['priceChangePercent'];
                      $BUY[$value['symbol']]['closeTime_24'] =  $value['closeTime'];
                      $BUY[$value['symbol']]['lastPrice_24'] =  $value['lastPrice'];
                      $BUY[$value['symbol']]['weightedAvgPrice_24'] =  $value['weightedAvgPrice'];
                      $BUY[$value['symbol']]['askPrice_24'] =  $value['askPrice'];
                      $BUY[$value['symbol']]['maxPrice'] =  $klines_max_min[$countIntervalBUY]['max'];
                      $BUY[$value['symbol']]['minPrice'] =  $klines_max_min[$countIntervalBUY]['min'];  
                      $BUY[$value['symbol']]['ChangePrice_p('.$BUYcontrolChangePrice_p.')'] =  $ChangePrice_p;
                      $BUY[$value['symbol']]['topPrice_p('.$controltopPrice_p.')'] =  $topPrice_p; 

    //заносим последнюю цену в масив истории
    $historyKlines[$value['symbol']]['lastChange'] = $value;

        }else{
            unset($B[$value['symbol']]);
        }



      $Bin->saveFile($History, $fileHistory);  


}


//Сохраняем историю покупок
// $V5historyPrice['buy'] = $B;
// $Bin->saveFile($V5historyPrice, $fileV5historyPrice);
echo "<br/>На ПОКУПКу ", count($B), '<br/>';
$Bin->show($B);



// $Bin->show($V5historyPrice);


//Сохраняем историю стаканов.
$Bin->saveFile($historyKlines, $filehistoryKlines);
//Сохраняем историю покупок
$Bin->saveFile($historyBUY, $filehistoryBUY);



//Смотрим настройки
// $Bin->show($settings);

//Смотрим баланс
// echo "<br/>ОТОБРАЛ ", count($select), '<br/>';
// $Bin->showArrayTable($select);


//Смотрим баланс
echo "<br/>БАЛАНС АКТИВОВ количество ", count($accountBalance), '<br/>';
$Bin->showArrayTable($accountBalance);

//Смотрим ПОКУПКУ
echo "<br/>ПОКУПКА ", count($BUY), '<br/>';
$Bin->showArrayTable($BUY);

echo "<br/>АРХИВ покупок ", count($historyBUY), '<br/>';
$Bin->showArrayTable($historyBUY);

$time = microtime(true) - $start;
echo 'Время выполнения скрипта: ', round($time, 4), ' сек.<br/>';
echo 'Обем памяти: ', (memory_get_usage() - $mem_start)/1000000, ' мегабайта.<br/>';
die();
//############################################
//ПОКУПКА *****************************************

  $select = array();
foreach ($ticker24hr as $key => $value) { 
// echo $value['symbol'], "<br/>";
        // Исключаем все кроме 'BEARUSDT', 'ETHBEARUSDT', 'LTCUSDT', 'BTCUSDT'
        if (!in_array($value['symbol'], array( 'BNBUSDT'))) continue;

        // //Получаем информацию о symbol и исключаем неактивные пары
        if (!$symbolInfo = array_values($Bin->multiSearch($exchangeInfo['symbols'], array('symbol' => $value['symbol'], 'status'=>'TRADING'))) ) continue;
        // //Получаем курс BTC USD
        $kurs = $Bin->kurs($symbolInfo['0']['quoteAsset'], $ticker24hr);

                //исключаем все кроме разрешоного
        if (!in_array($value['symbol'], $symbolBUY))  continue;

        // //Исключаем с обемом продаж за сутки менее (24 часа) 
        // if (($value['quoteVolume']*$kurs['kursUSD']) < $quoteVolume) continue;

        // //Исключаем с количеством операций меньше $countTrends (24 часа) 
        // if ($value['count'] < $countTrends) continue;

        //Исключаем с процентом изминения цены до и от (24 часа) 
        // if (-1== bccomp((string)$value['priceChangePercent'], (string)$priceChangePercent[0], 8) || 1== bccomp((string)$value['priceChangePercent'], (string)$priceChangePercent[1], 8)) continue; 

        // // Исключаем пары с неутвержденой базой
        // if (!array_key_exists($symbolInfo['0']['quoteAsset'], $base)) continue;

        // //Исключаем пары кторые не могу купить НЕТ ДЕНЕГ
        // if ($accountBalance[$symbolInfo['0']['quoteAsset']]['total_USD'] < $trade_limit) continue;
        
        // //Исключаем пары кторые не нужны ЕСТЬ НА БАЛАНСЕ и неявляются базой
        // if ($accountBalance[$symbolInfo['0']['baseAsset']]['total_USD'] >= $trade_limit*3 && !array_key_exists($symbolInfo['0']['baseAsset'], $base)) continue;

        // //Исключаем пары кторые не нужны ЕСТЬ НА БАЛАНСЕ  меньше минимума и являются базой
        // if ($accountBalance[$symbolInfo['0']['baseAsset']]['total_USD'] <= $accountBalance[$symbolInfo['0']['baseAsset']]['minBalans'] && array_key_exists($symbolInfo['0']['baseAsset'], $base)) continue; 

        // // Исключаем пары с ценой меньше рекомендованой более 100 сатошей
        // if (-1== bccomp((string)$value['askPrice'], (string)number_format($base[$symbolInfo['0']['quoteAsset']]['minPriceBuy'], 8, '.', ''), 8)) continue;
        

$select[] = $value;
// continue;
               
                $temp = $t = array();
                foreach ($IntervalControl as $key => $interval) {
                  // if (microtime()*1000-$historyKlines[$value['symbol']]['microtime'] > 300 || empty($historyKlines[$value['symbol']]['microtime'])) {
                    // echo  $interval, '<br/>';
                    
                      if ($klines = array_reverse($Bin->klines(array('symbol'=>$value['symbol'], 'interval' => $interval)))) {
                        //находи изминения последней свечи
                        $difference = bcsub($klines[0][4], $klines[0][1], 8);
                        $difference_p = bcmul(bcdiv($difference, $klines[0][1], 8), 100, 8);

                        //находим процент увеличения цены от минимальной
                        $top = bcsub($klines[0][4], $klines[0][3], 8);
                        $topPrice_p = bcmul(bcdiv($top, $klines[0][3], 8),100, 8);

                        $t['symbol'] = $value['symbol'];
                        $t['interval'] = $interval;
                        $t['dateOpen'] = date("Y-m-d H:i:s", $klines[0][0]/1000);
                        $t['dateClose'] = date("Y-m-d H:i:s", $klines[0][6]/1000);
                        
                        $t['priceOpen'] =  $klines[0][1];
                        $t['priceClose'] = $klines[0][4];
                        $t['difference'] = $difference;
                        $t['difference_p'] = $difference_p;
                        // $t['Low'] = $klines[0][3];
                        // $t['top'] = $top;
                        // $t['topPrice_p'] = $topPrice_p;
                        
                        // $array_day = array(0,5,15,30,60,120,180,240,480);
                        // $klines_max_min = $Bin->max_min($klines, $countIntervalBUY);
                        // $historyKlines[$value['symbol']][$interval] = $klines;
                      } 
                  // }else{                    
                  //   // $klines_max_min  = $historyKlines[$value['symbol']]['klines_max_min'];
                  // }
                  $temp[]=$t;
                }
              $Bin->showArrayTable($temp);                
continue;       

        //Исключаем пары не соответствующие тренду klinesControl
        // if (1== bccomp((string)$BUYcontrolChangePrice_p, (string)$ChangePrice_p, 8)) continue; 



        // Исключаем пары цена выше среднего (24 часа) 
        // if (-1!= bccomp((string)$value['askPrice'], (string)$value['weightedAvgPrice'], 8)) continue;


                    // //находим моментальную потерю цены при покупке
                    // $takerask =  bcmul($value['askPrice'], $tradeFeeKom[$value['symbol']]['taker'], 8);
                    // $price_loss = bcsub($value['askPrice'], bcsub($value['bidPrice'], $takerask, 8), 8);
                    // //находи процент потери цены при покупке + коректировка
                    // $price_loss_p = bcadd(bcmul(bcdiv($price_loss, $value['bidPrice'], 8),100, 8), $addPrice_loss_p, 8);  

        //Исключаем если моминтальная потеря цены при покупке больше //потеря маржи % при Stop Loss    $lossМargin_p
        // if (1== bccomp((string)$price_loss_p, (string)abs($lossМargin_p), 8))  continue;  


                  // if (microtime()*1000-$historyKlines[$value['symbol']]['microtime'] > 300 || empty($historyKlines[$value['symbol']]['microtime'])) {
                  //   echo $value['symbol'], 'обновляю<br/>';
                  //     if ($klines = array_reverse($Bin->klines(array('symbol'=>$value['symbol'], 'interval' => $IntervalBUY)))) {
                  //       // $array_day = array(0,5,15,30,60,120,180,240,480);
                  //       $klines_max_min = $Bin->max_min($klines, $countIntervalBUY);
                  //       $historyKlines[$value['symbol']]['microtime'] = microtime()*1000;
                  //       $historyKlines[$value['symbol']]['klines_max_min'] = $klines_max_min;
                  //     } 
                  // }else{                    
                  //   $klines_max_min  = $historyKlines[$value['symbol']]['klines_max_min'];
                  // }

        //Исключаем пары с МАЛОЙ ВОЛОНТИЛЬНОСТЮ за последние $countIntervalBUY минут
        // if (1== bccomp((string)$BUYcontrolChangePrice_p, (string)$klines_max_min[$countIntervalBUY]['spred_%'], 8)) continue;

                  // if (empty($historyKlines[$value['symbol']]['lastChange'])) {
                  //   $historyKlines[$value['symbol']]['lastChange'] = $lastChange = $value; 
                  // }else{
                  //   $lastChange = $historyKlines[$value['symbol']]['lastChange'];
                  // }

        // Исключаем пары цена ниже или равна последней 
        // if (1!= bccomp((string)$value['askPrice'], (string)$lastChange['askPrice'], 8)) continue;

        //Исключаем если предпоследняя цена не является минимальной 
        // if (0!= bccomp((string)$minPrice, (string)$historyPrice[$value['symbol']]['1'], 8))  continue;

                      //находим процент увеличения цены от минимальной
                      // $top = bcsub($value['askPrice'], $klines_max_min[$countIntervalBUY]['min'], 8);
                      // $topPrice_p = bcmul(bcdiv($top, $klines_max_min[$countIntervalBUY]['min'], 8),100, 8);
        // //Исключаем если текущая цена больше минимальной на controltopPrice_p (topPrice_p больше controltopPrice_p)
        // if (1== bccomp((string)$topPrice_p, (string)$controltopPrice_p, 8))  continue;

                      
                      //заполяем историю покупок
                      $historyBUY[$symbolInfo['0']['baseAsset']]['asset'] =  $symbolInfo['0']['baseAsset'];                
                      $historyBUY[$symbolInfo['0']['baseAsset']]['base'] =  $symbolInfo['0']['quoteAsset'];
                      $historyBUY[$symbolInfo['0']['baseAsset']]['symbol'] =  $value['symbol'];
                      $historyBUY[$symbolInfo['0']['baseAsset']]['time'] =  $value['closeTime'];

                      $historyBUY[$symbolInfo['0']['baseAsset']]['priceUSD'] = bcmul($value['askPrice'],$kurs['kursUSD'], 8);                
                      $historyBUY[$symbolInfo['0']['baseAsset']]['BUYkursUSD'] = $kurs['kursUSD'];
                      $historyBUY[$symbolInfo['0']['baseAsset']]['kursUSD'] = $kurs['kursUSD'];
                      $historyBUY[$symbolInfo['0']['baseAsset']]['correctionUSD'] = 1;
                      
                      $historyBUY[$symbolInfo['0']['baseAsset']]['priceBTC'] = bcmul($value['askPrice'],$kurs['kursBTC'], 8);
                      $historyBUY[$symbolInfo['0']['baseAsset']]['BUYkursBTC'] = $kurs['kursBTC'];
                      $historyBUY[$symbolInfo['0']['baseAsset']]['kursBTC'] = $kurs['kursBTC'];
                      $historyBUY[$symbolInfo['0']['baseAsset']]['correctionBTC'] = 1;
// $Bin->showArrayTable($historyBUY);

                       //масив параметров для покупки
                      $ParamsBUY = array('symbol'=>$value['symbol'], 
                                      'side' => 'BUY', 
                                      'type' => 'LIMIT', 
                                      'quantity' => $Bin->round_min(bcdiv($trade_limit, bcmul($value['askPrice'], $kurs['kursUSD'], 8), 8), $symbolInfo['0']['filters'][2]['minQty']), 
                                      'timeInForce' => 'IOC', 
                                      'price' => $value['askPrice']); 
                
                      //ПОКУПКА    
                      if ($orderBUY == '1') {
                          if ($order = $Bin->orderNEW($ParamsBUY)) {
                              // $Bin->show($order);
                              $order['kurs'] = $kurs;
                              $historyBUY[$symbolInfo['0']['baseAsset']]['buy'][] = $order;
                              $Bin->saveFile($historyBUY, $filehistoryBUY);          
                          }

                      }
                      //СОХРАНЯЕМ ВЫБРАНОЕ ДЛЯ ПРОСМОТРА
                      $BUY[$value['symbol']]['symbol'] =  $value['symbol'];                
                      $BUY[$value['symbol']]['asset'] = $symbolInfo['0']['baseAsset'];
                      $BUY[$value['symbol']]['base'] =  $symbolInfo['0']['quoteAsset'];
                      $BUY[$value['symbol']]['count_24'] =  $value['count'];
                      $BUY[$value['symbol']]['priceChangePercent_24'] =  $value['priceChangePercent'];
                      $BUY[$value['symbol']]['closeTime_24'] =  $value['closeTime'];
                      $BUY[$value['symbol']]['lastPrice_24'] =  $value['lastPrice'];
                      $BUY[$value['symbol']]['weightedAvgPrice_24'] =  $value['weightedAvgPrice'];
                      $BUY[$value['symbol']]['askPrice_24'] =  $value['askPrice'];
                      $BUY[$value['symbol']]['maxPrice'] =  $klines_max_min[$countIntervalBUY]['max'];
                      $BUY[$value['symbol']]['minPrice'] =  $klines_max_min[$countIntervalBUY]['min'];  
                      $BUY[$value['symbol']]['ChangePrice_p('.$BUYcontrolChangePrice_p.')'] =  $ChangePrice_p;
                      $BUY[$value['symbol']]['topPrice_p('.$controltopPrice_p.')'] =  $topPrice_p; 

    //заносим последнюю цену в масив истории
    $historyKlines[$value['symbol']]['lastChange'] = $value;
  
}



//Сохраняем историю стаканов.
$Bin->saveFile($historyKlines, $filehistoryKlines);
//Сохраняем историю покупок
$Bin->saveFile($historyBUY, $filehistoryBUY);



//Смотрим настройки
$Bin->show($settings);

//Смотрим баланс
echo "<br/>ОТОБРАЛ ", count($select), '<br/>';
$Bin->showArrayTable($select);


//Смотрим баланс
echo "<br/>БАЛАНС АКТИВОВ количество ", count($accountBalance), '<br/>';
$Bin->showArrayTable($accountBalance);

//Смотрим ПОКУПКУ
echo "<br/>ПОКУПКА ", count($BUY), '<br/>';
$Bin->showArrayTable($BUY);

echo "<br/>АРХИВ покупок ", count($historyBUY), '<br/>';
$Bin->showArrayTable($historyBUY);

$time = microtime(true) - $start;
echo 'Время выполнения скрипта: ', round($time, 4), ' сек.<br/>';
echo 'Обем памяти: ', (memory_get_usage() - $mem_start)/1000000, ' мегабайта.<br/>';

// sleep(2);
exit();
?>