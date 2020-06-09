<?php
$start = microtime(true);
$mem_start = memory_get_usage();
//$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
echo "BUCKS (v5) ЗАКУПКА<br/>";

//ВКЛЮЧИТЬ создания ордеров покупку
$settings['orderBUY'] = $orderBUY =1;
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
$count = 30;

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
foreach ($ticker24hr as $key => $value) {//начало цикла 1
        if ($value['symbol'] !='BTCUSDT') {
          continue;
        }

        //Получаем информацию о symbol и исключаем неактивные пары
        if (!$symbolInfo = array_values($Bin->multiSearch($exchangeInfo['symbols'], array('symbol' => $value['symbol'], 'status'=>'TRADING'))) ) continue;
        //Получаем курс BTC USD
        $kurs = $Bin->kurs($symbolInfo['0']['quoteAsset'], $ticker24hr);
        



        // $quantitySELL = $Bin->round_min(bcdiv($trade_limit, bcmul($value['askPrice'], $kurs['kursUSD'], 8), 8), $symbolInfo['0']['filters'][2]['minQty']);
        $symbol = array();
        $symbol[$value['symbol']] = $value['symbol'];
        
        $sum = 0;
        for ($i=$count; $i > 0; $i--) {                   
              if ($historyTicker24hr[$i-1][$key]['symbol'] != $historyTicker24hr[0][$key]['symbol']) continue;

                //исключаю цена 0
              if (0== bccomp((string)$historyTicker24hr[$i][$key]['askPrice'], (string)0, 8)) {

                // $symbol[] = 0;

              }else{
                                //Считаем рост и % роста
                $trend = bcsub($historyTicker24hr[$i-1][$key]['askPrice'], $historyTicker24hr[$i][$key]['askPrice'], 8);
                $trend_p = bcmul(bcdiv($trend, $historyTicker24hr[$i][$key]['askPrice'], 8), 100, 8);
                // $symbol['askPrice_'.$i]  = $historyTicker24hr[$i][$key]['askPrice'];
                // $symbol['trend_'.$i] = $trend_p;
                $symbol['sum_'.$i] = $sum = $sum + $trend_p;
                
                
              }
             




                // if (1== bccomp((string)$historyTicker24hr[$i-1][$key]['askPrice'], (string)$historyTicker24hr[$i][$key]['askPrice'], 8)) {
                //   // $symbol++ ;
                // }

                // if (-1== bccomp((string)$historyTicker24hr[$i-1][$key]['askPrice'], (string)$historyTicker24hr[$i][$key]['askPrice'], 8)) {
                //    // $symbol-- ;

                // }
        }
         $symbol['askPrice'] = $historyTicker24hr[0][$key]['askPrice'];
// 1== bccomp((string)$symbol['trend_1'], (string)0, 8) && 
        if (-1== bccomp((string)$symbol['sum_2'], (string)-1, 8)) {
          $select[] = $symbol;
        }

          



       

}//конец цикла 1

              // if (array_key_exists($value['symbol'], $BUYtestOrder)) {
              //     $BUYtestOrder[$value['symbol']]['status']= 'растет'; 
              //     $BUYtestOrder[$value['symbol']]['N']++;
              // }
              // if (!array_key_exists($value['symbol'], $BUYtestOrder)) {
              //     $testOrder['symbol'] = $value['symbol'];
              //     $testOrder['status']= 'растет NEW';                  
              //     $testOrder['N']=0;
              //     // $BUYtestOrder[$value['symbol']] = $testOrder;
              // }




//Сохраняем тестовых закупок
$Bin->saveFile($BUYtestOrder, $fileBUYtestOrder);

//Сохраняем историю.
$Bin->saveFile($historyTicker24hr, $filehistoryTicker24hr);

//Сохраняем историю покупок
$Bin->saveFile($historyBUY, $filehistoryBUY);

//Смотрим настройки
// $Bin->show($settings);

//Проверить даты
foreach ($historyTicker24hr as $key => $value) {
  echo $key, ' -> ', date("Y-m-d H:i:s", $value[0]['closeTime']/1000), '<br>';
  
}

//ОТОБРАЛ
// echo "<br/>ОТОБРАЛ ", count($select), '<br/>';
// //Сортируем и смотрим
usort($select, function($a, $b) {
    return $b['trend_1']*100000000 - $a['trend_1']*100000000;
});
$Bin->showArrayTable($select);

// echo "<br/>КОНТРОЛИРУЕМ ", count($BUYtestOrder), '<br/>';
// //Сортируем и смотрим
// usort($BUYtestOrder, function($a, $b) {
//     return $b['N'] - $a['N'];
// });
// $Bin->showArrayTable($BUYtestOrder);

// echo "<br/>АРХИВ покупок ", count($historyBUY), '<br/>';
// $Bin->showArrayTable($historyBUY);





//Смотрим баланс
// echo "<br/>БАЛАНС АКТИВОВ количество ", count($accountBalance), '<br/>';
// $Bin->showArrayTable($accountBalance);


$time = microtime(true) - $start;
echo 'Время выполнения скрипта: ', round($time, 4), ' сек.<br/>';
echo 'Обем памяти: ', (memory_get_usage() - $mem_start)/1000000, ' мегабайта.<br/>';

sleep(2);
exit();
?>