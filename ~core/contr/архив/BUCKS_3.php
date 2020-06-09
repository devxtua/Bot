<?php
sleep(5);
echo "BUCKS_3 quality control<br/>";
$start = microtime(true);
$mem_start = memory_get_usage();
//$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
header('refresh: 1');

 
//ВКЛЮЧИТЬ создания ордеров покупку
// $orderBUY = 0; //0-нет, 1 да
$BUY_OCO = array('Price' => 0.98, 'S_Price' => 1.005, 'SL_Price' => 1.0055);      //стартовые условия
$distance_END_BUY_OCO = 0.9985;                                                   //астояние перехода на новые условия
$END_BUY_OCO = array('Price' => 0.995, 'S_Price' => 1.001, 'SL_Price' => 1.0015); //финишные условия

//Стартовые условия продажи
// $orderSELL_OCO = 0; //0-нет, 1 да
$SELL_OCO = array('Price' => 1.01, 'S_Price' => 0.973, 'SL_Price' => 0.97);       //стартовые условия
$distance_END_SELL_OCO = 0.999;                                                    //астояние перехода на новые условия
$END_SELL_OCO = array('Price' => 1.005, 'S_Price' => 0.9991, 'SL_Price' => 0.999); //финишные условия

//Определяем базове валют
$base['USDT']= array('minBalans'=>100, 'minPriceBuy'=>0.00000100);
// $base['BTC']= array('minBalans'=>0.5, 'minPriceBuy'=>0.00000100);
$base['BNB']= array('minBalans'=>1, 'minPriceBuy'=>0.00000100);
// $base['ETH']= array('minBalans'=>10, 'minPriceBuy'=>0.00000100);
// $base['TRX']= array('minBalans'=>100, 'minPriceBuy'=>0.00000100);
// $base['XRP']= array('minBalans'=>100, 'minPriceBuy'=>0.00000100);
// $base['EUR']= array('minBalans'=>1000, 'minPriceBuy'=>0.00000100);

//Создаем класс
$KEY = 'irieuC5kOGznjzpllwnxx2sDMzdLKPCS42SB8YGZ4Y8eSUz6mDtfWDMclrpUh633';
$SEC = 'FpCQWroQgIh9KyV3Jn7A25tbbpMB93eaK2FbKFXZv7YoMCmVDn5gBoMrwHaSpPUJ'; 
$Bin = new binance($KEY, $SEC);
sleep(2);
$KEY = '7WDYbxAytNjWRo5jqz4ZFZp2v2J5UahQirEcOFlacaE7ykUQxQcGwQZcBwcFUUvH';
$SEC = '0Ql01kwiBjx8jWzm7iA5CMfj4ODhBuUq8VutXdGTkNe8OsHTBoUWckQBr2LITseg'; 
$BinL = new binance($KEY, $SEC);

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
$time = time();
$today = getdate();
// $Bin->show($today);
echo 'ВРЕМЯ : ', '  <font size="20" color=blue face="Arial">', date("H:i:s", $time), '</font> <br/>';

//Получить изминения за 24 часса
$ticker24hr = $Bin->ticker24hr();
// $Bin->show($ticker24hr[0]);
//сохраняем файлы с именем времени первого символа
$fileTicker24hr = 'E:\binance\ticker24hr\\'.$ticker24hr[0]['closeTime'].'.txt';
$Bin->saveFile($ticker24hr, $fileTicker24hr);
//открытые ордера
if ($orderOPEN_L = $BinL->orderOPEN(array())){
      $Bin->showArrayTable($orderOPEN_L);

}

if (!$accountBalanceL = $BinL->accountBalance($ticker24hr, $base)) die('БАЛАНС НЕ ПОЛУЧЕН');
$BinL->showArrayTable($accountBalanceL);
//Получить свободный баланс АКТИВОВ
if (!$accountBalance = $Bin->accountBalance($ticker24hr, $base)) die('БАЛАНС НЕ ПОЛУЧЕН');
//Получить свободный баланс АКТИВОВ

//покупаем BNB если их баланс менее 1 
if ($accountBalance['BNB']['total']<1) {    
    //масив параметров для покупки
    $ParamsBUY = array('symbol'=>'BNBUSDT', 
                      'side' => 'BUY', 
                      'type' => 'MARKET', 
                      'quantity' => 1, 
                      'timeInForce' => 'IOC'); 

    //ПОКУПКА BNB
    if ($order = $Bin->orderNEW($ParamsBUY)) {
      $Bin->show($order);
    }
}


// if ($allOCO = $Bin->allOCO(array())){
//       $Bin->showArrayTable($allOCO);
// }

// if ($openOCO = $Bin->openOCO(array())){
//       $Bin->showArrayTable($openOCO);
// }

//открытые ордера
if ($orderOPEN = $Bin->orderOPEN(array())){
      $Bin->showArrayTable($orderOPEN);

}

//проверяем free балансы и ставим ОСО
  foreach ($accountBalance as $key => $value) {
      //Получаем информацию о symbol и исключаем неактивные пары
        if (!$symbolInfo = array_values($Bin->multiSearch($exchangeInfo['symbols'], array('symbol' => $value['asset'].'USDT', 'status'=>'TRADING')))) {
            $accountBalance[$key]['update'] = 'USDT not trading';
            continue;
        }
        if (!$ticker24hr_symbol = array_values($Bin->multiSearch($ticker24hr, array('symbol' => $value['asset'].'USDT')))) {
            $accountBalance[$key]['update'] = 'ticker24hr НЕТ даных';
            continue;
        }
      //проверяем и активные ордера и сравневаем с текущей ценой и пересталяем ордер если нужно     
      if ($orderOPEN_symbol = array_values($Bin->multiSearch($orderOPEN, array('symbol' => $value['asset'].'USDT', 'type'=>'LIMIT_MAKER', 'side'=>'SELL')))) {
          usort($orderOPEN_symbol, function($a, $b) {
              return abs($a['price']*100) - abs($b['price']*100);
          });
          $ticker24hr_symbol = array_values($Bin->multiSearch($ticker24hr, array('symbol' => $value['asset'].'USDT')));
          $distance_profit = bcdiv($ticker24hr_symbol[0]['lastPrice'], $orderOPEN_symbol[0]['price'], 8);
          echo $value['asset'].'USDT', ' ', $orderOPEN_symbol[0]['price'], ' ', $ticker24hr_symbol[0]['lastPrice'], ' = ' , $distance_profit, ' < ', $distance_END_SELL_OCO ,"<br/>";
          if (1 == bccomp($distance_profit, $distance_END_SELL_OCO, 5)) {
              $ParamsDELETE = array('symbol'=>$value['asset'].'USDT', 'orderId'=>$orderOPEN_symbol[0]['orderId']);
              if ($orderDELETE = $Bin->orderDELETE($ParamsDELETE)) {
                  // $Bin->show($orderDELETE);
                  $Params_END_SELL_OCO = array('symbol'=>$value['asset'].'USDT', 
                                'side' => 'SELL', 
                                'quantity' => $orderDELETE['orderReports'][1]['origQty'],
                                'price' => $Bin->round_min(bcmul($ticker24hr_symbol[0]['lastPrice'], $END_SELL_OCO['Price'], 8), $symbolInfo['0']['filters'][0]['minPrice']),
                                'stopPrice' => $Bin->round_min(bcmul($ticker24hr_symbol[0]['lastPrice'], $END_SELL_OCO['S_Price'], 8), $symbolInfo['0']['filters'][0]['minPrice']),
                                'stopLimitPrice' => $Bin->round_min(bcmul($ticker24hr_symbol[0]['lastPrice'], $END_SELL_OCO['SL_Price'], 8), $symbolInfo['0']['filters'][0]['minPrice']), 
                                'stopLimitTimeInForce' => 'GTC'); 
                  if ($orderOCO = $Bin->OCO($Params_END_SELL_OCO)) {
                      $accountBalance[$key]['update'] = 'DELET + OCO';
                      // $Bin->show($orderOCO);
                  }
              }
          }
      }

      //На free активы ставим OCO
      $balancequantitySELL = $value['total'] - $value['locked'] - $value['min'];
      if (bcmul($balancequantitySELL, $ticker24hr_symbol[0]['lastPrice'], 8)<10) {
          $accountBalance[$key]['update'] = 'sum < 10';
          continue;
      }
      $Params_SELL_OCO = array('symbol'=>$value['asset'].'USDT', 
                    'side' => 'SELL', 
                    'quantity' => $Bin->round_min($balancequantitySELL, $symbolInfo['0']['filters'][2]['minQty']),
                    'price' => $Bin->round_min(bcmul($ticker24hr_symbol[0]['lastPrice'], $SELL_OCO['Price'], 8), $symbolInfo['0']['filters'][0]['minPrice']),
                    'stopPrice' => $Bin->round_min(bcmul($ticker24hr_symbol[0]['lastPrice'], $SELL_OCO['S_Price'], 8), $symbolInfo['0']['filters'][0]['minPrice']),
                    'stopLimitPrice' => $Bin->round_min(bcmul($ticker24hr_symbol[0]['lastPrice'], $SELL_OCO['SL_Price'], 8), $symbolInfo['0']['filters'][0]['minPrice']), 
                    'stopLimitTimeInForce' => 'GTC'); 
      if ($orderOCO = $Bin->OCO($Params_SELL_OCO)) {
          $accountBalance[$key]['update'] = 'OCO';
          // $Bin->show($orderOCO);
      }
  }
//сохраняем баланс в файл
$filaccountBalance = 'E:\binance\V5AccountBalance_'.date("m-d-Y", mktime(0, 0, 0, date('m'), date('d'), date('Y'))).'.txt';
$Bin->saveFile($accountBalance, $filaccountBalance);
$Bin->showArrayTable($accountBalance);




// $Bin->show(array_column($accountBalance, 'asset'));

$filaccountBalanceyesterday = 'E:\binance\V5AccountBalance_'.date("m-d-Y", mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'))).'.txt';
$accountBalanceYesterday = $Bin->readFile($filaccountBalanceyesterday);
// $Bin->showArrayTable($accountBalanceYesterday);
$Balance = array_sum(array_column($accountBalance, 'total_USD'));
$BalanceYesterday = array_sum(array_column($accountBalanceYesterday, 'total_USD'));

$balansDay = round($Balance-$BalanceYesterday,2);
$balansDay_p = bcmul(bcdiv($balansDay, $BalanceYesterday, 8), 100, 8);
$colorDay = $balansDay>0?"green":"red";

echo 'Текущий баланс: ', round(array_sum(array_column($accountBalance, 'total_USD')),2), ' Вчера конец дня: ',  round(array_sum(array_column($accountBalanceYesterday, 'total_USD')),2), ' <br/>';
echo 'СЕГОДНЯ : ', '  <font size="20" color='.$colorDay.' face="Arial">', round($balansDay_p,2), ' % </font> Profit:', round($balansDay,2), ' $   <br/>';


$filetestOrder = 'E:\binance\V5testOrder.txt';
if ($testOrder = $Bin->readFile($filetestOrder)){
   
   // $Bin->show($open);
}else{
  $testOrder = $open = array();
}




echo 'Обем памяти: ', (memory_get_usage() - $mem_start)/1000000, ' мегабайта.<br/>';
$time = microtime(true) - $start;
echo 'Время выполнения скрипта: ', round($time, 4), ' сек.<br/>';







die('STOP');
//*****************************************************************************************************************************************************************
//*****************************************************************************************************************************************************************
//*****************************************************************************************************************************************************************
//*****************************************************************************************************************************************************************
//*****************************************************************************************************************************************************************
//*****************************************************************************************************************************************************************
//*****************************************************************************************************************************************************************
//*****************************************************************************************************************************************************************

// СТАРАЯ версия

echo "BUCKS (v5) ПРОДАЖА<br/>";
//запустить тестовою покупку $orderBUY == 0
$settings['order_Test'] = $order_Test = '0';


//ВКЛЮЧИТЬ создания ордеров покупку
// $settings['orderBUY'] = $orderBUY = 0;
$invis = 18221;

//минимальное количество сделок за 24 час
$settings['countTrends'] = $countTrends= 1000; 
// максимальный % изминения цены за 24 час
$settings['priceChangePercent'] = $priceChangePercent= 0; 

//Включить создания ордеров продажи TakeProfit
$settings['orderSELL'] = $orderSELL = 0;
//минимальная маржа % TakeProfit
$settings['TakeProfit_p'] = $TakeProfit_p = 5;
//сниженеи (потеря) маржи % при TakeProfit
$settings['declinem_p'] = $declinem_p = -0.03;

//стартовый лимит закупки
// $settings['trade_limit'] = $trade_limit = 15;


//Включить создания ордеров Stop Loss
$settings['orderSELL_SL'] = $orderSELL_SL = 0;

//Увеличить моментальную потерю маржи
$settings['addPrice_loss_p'] = $addPrice_loss_p = -0.3;

//потеря маржи % при Stop Loss
$settings['lossМargin_p'] = $lossМargin_p = 0;




$updateBUYPrice = 0;
//КОНТРОЛЬНЫЙ интервал 1m/3m/5m/15m/30m/1h/2h/4h/6h/8h/12h/1d/3d/1w/1M
$settings['IntervalControl'] = $IntervalControl = '1h';
//количество свечей
$settings['countIntervalControl'] = $countIntervalControl = 24;

//РАБОЧИЙ интервал 1m/3m/5m/15m/30m/1h/2h/4h/6h/8h/12h/1d/3d/1w/1M
$settings['IntervalBUY'] = $IntervalBUY = '1m';
//количество свечей
$settings['countIntervalBUY'] = $countIntervalBUY = 100;

//минимальный процент  Волонтильность за последние countKlines свечей
$settings['BUYcontrolChangePrice_p'] = $BUYcontrolChangePrice_p = 2;
//процент контроля роста цены от минимальной для покупки
$settings['controltopPrice_p'] = $controltopPrice_p = 0.3;


//Определяем базове валют
// $base['BTC']= array('minBalans'=>0, 'minPriceBuy'=>0.00000100);
$base['USDT']= array('minBalans'=>0, 'minPriceBuy'=>0.00000100);
$base['BNB']= array('minBalans'=>2, 'minPriceBuy'=>0.00000100);
// $base['ETH']= array('minBalans'=>0, 'minPriceBuy'=>0.00000100);
// $base['TRX']= array('minBalans'=>0, 'minPriceBuy'=>0.00000100);
// $base['XRP']= array('minBalans'=>0, 'minPriceBuy'=>0.00000100);
// $base['EUR']= array('minBalans'=>0, 'minPriceBuy'=>0.00000100);
// $base['ETHBULL']= array('minBalans'=>5, 'minPriceBuy'=>0.00000100);


//positionlong
$positionlong = array('ETH');

//$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
header('refresh: 1');
//Устанавливаем настройки времени
// echo "max_execution_time ", ini_get('max_execution_time'), "<br />";
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
// if ($apiTradingStatus['status']['isLocked'] === true) {
//    echo ' Статус:   <font size="20" color="red" face="Arial">ЗАБЛОКИРОВАН</font> на', $apiTradingStatus['status']['plannedRecoverTime'], '<br/>' ; 
//    sleep(5);
// }else{
//     echo ' Статус:   <font size="20" color="green" face="Arial">OK</font> orderSELL = ', $orderSELL, ' TakeProfit_p = ', $TakeProfit_p, ' declinem_p = ', $declinem_p, " || orderSELL_SL = ", $orderSELL_SL, ' lossМargin_p = ', $lossМargin_p?$lossМargin_p:'spred+'.$addPrice_loss_p, '<br/>';

// }

// $Bin->show($apiTradingStatus);





//************************ОБЩИЕ ДАНЫЕ БИРЖИ (загружаем или читаем с файла)**************

//Открываем файл

$filetradeFeeKom = 'E:\binance\tradeFeeKom.txt';
$fileexchangeInfo = 'E:\binance\exchangeInfo.txt';
if(time()-filemtime($filetradeFeeKom) > 3600 || time()-filemtime($fileexchangeInfo) > 3600){
    //Даные в файле устарели УДАЛЯЕМ 
    unlink($filetradeFeeKom);
    unlink($fileexchangeInfo);

    //Получаем актуальную информацию
    $tradeFeeKom= $Bin->tradeFeeKom();      //О комисиях
    $exchangeInfo = $Bin->exchangeInfo();   //Правила биржевой торговли и символьная информация+

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
// $Bin->show($ticker24hr);


//Получение ПОСЛЕДНИХ ДАНЫХ  symbol-----------------------------

//Получить изминения за 24 часса
$ticker24hr = $Bin->ticker24hr();
// $Bin->show($ticker24hr[0]);

//сохраняем файлы с именем времени первого символа
$fileTicker24hr = 'E:\binance\ticker24hr\\'.$ticker24hr[0]['closeTime'].'.txt';
$Bin->saveFile($ticker24hr, $fileTicker24hr);


//Читаем файл истории стаканов
// $filehistoryKlines = 'E:\binance\V5historyKlines.txt';
// if (!$historyKlines = $Bin->readFile($filehistoryKlines)) $historyKlines = array();
// $Bin->showArrayTable($historyKlines);

//Читаем файл архива покупок
$filehistoryBUY = 'E:\binance\V5historyBUY.txt';
if (!$historyBUY  = $Bin->readFile($filehistoryBUY )) $historyBUY  = array();
    foreach ($historyBUY as $key => $value) {
    // echo $value['asset'], "<br/>";
      $kurs = $Bin->kurs($value['base'], $ticker24hr);
      $historyBUY[$key]['kursUSD'] = $kurs['kursUSD'];
      $historyBUY[$key]['correctionUSD']= bcdiv($value['BUYkursUSD'], $kurs['kursUSD'], 8);
      $historyBUY[$key]['kursBTC'] = $kurs['kursBTC'];
      $historyBUY[$key]['correctionBTC']= bcdiv($value['BUYkursBTC'], $kurs['kursBTC'], 8); 

// $Bin->showArrayTable($value['buy']);

    if (count($value['buy'])>1) {
       $order = end($value['buy']);
    }else{
        $order = $value['buy'][0];
    }     
  
      if ($order['executedQty'] == 0) continue;
      // $Bin->show($order);
      $historyBUY[$key]['quantityBUY'] = $order['executedQty'];
      $historyBUY[$key]['price'] = bcdiv($order['cummulativeQuoteQty'], $order['executedQty'], 8);
      $TEMPpriceUSD = bcmul($historyBUY[$key]['price'], $kurs['kursUSD'], 8);
      $historyBUY[$key]['priceUSD'] = bcmul($TEMPpriceUSD, $historyBUY[$key]['correctionUSD'], 8);


    }

$Bin->showArrayTable($historyBUY);



//Получить свободный баланс АКТИВОВ
if (!$accountBalance = $Bin->accountBalance($ticker24hr, $base)){
    die('БАЛАНС НЕ ПОЛУЧЕН');
}
// $Bin->show($accountBalance);

//покупаем BNB если бих баланс менее 1
if ($accountBalance['BNB']['total']<1) {
    
    //масив параметров для покупки
      $ParamsBUY = array('symbol'=>'BNBUSDT', 
                      'side' => 'BUY', 
                      'type' => 'MARKET', 
                      'quantity' => 1, 
                      'timeInForce' => 'IOC'); 

      //ПОКУПКА    
  if ($order = $Bin->orderNEW($ParamsBUY)) {
    $Bin->show($accountBalance['BNB']);
    $Bin->show($order);
  }
}



$filaccountBalance = 'E:\binance\V5AccountBalance_'.date("m-d-Y", mktime(0, 0, 0, date('m'), date('d'), date('Y'))).'.txt';
$accountBalanceOld = $Bin->readFile($filaccountBalance);
// $Bin->showArrayTable($accountBalanceOld);
// die();

$filarchiveorderSELL = 'E:\binance\archiveorderSELL_'.date("m.d.y").'.txt';
if ($archiveorderSELL = $Bin->readFile($filarchiveorderSELL)) { 
    array_reverse($archiveorderSELL);  
}



// die();
//Заполнение Цены закупки************************************************************************************
    foreach ($accountBalance as $key => $valueAccoun) {
        // echo $valueAccoun['asset'],"<br/>";  
        // break;      
        //Исключаем если asset пусто
        if (!isset($valueAccoun['asset'])) continue;
        //Исключаем  asset USDT
        // if ($valueAccoun['asset']=='BNB') continue;
        if ($valueAccoun['asset']=='USDT') continue;


        if ($valueAccoun['total_USD']<10) continue;

        if (0== bccomp((string)$accountBalanceOld[$valueAccoun['asset']]['total'], (string)$accountBalance[$valueAccoun['asset']]['total'], 8) && isset($accountBalanceOld[$valueAccoun['asset']]['BUYPriceUSD']) && $accountBalanceOld[$valueAccoun['asset']]['BUYPriceUSD'] !== '') {
            $accountBalance[$valueAccoun['asset']]['BUYsymbol'] = $accountBalanceOld[$valueAccoun['asset']]['BUYsymbol'];
            // $accountBalance[$valueAccoun['asset']]['BUYside'] = $accountBalanceOld[$valueAccoun['asset']]['BUYside'];
            $accountBalance[$valueAccoun['asset']]['BUYTime'] = $accountBalanceOld[$valueAccoun['asset']]['BUYTime'];
            // $accountBalance[$valueAccoun['asset']]['executedQty'] = $accountBalanceOld[$valueAccoun['asset']]['$executedQty'];
            // $accountBalance[$valueAccoun['asset']]['BUYPrice'] = $accountBalanceOld[$valueAccoun['asset']]['BUYPrice'];
            $accountBalance[$valueAccoun['asset']]['BUYPriceUSD'] = $accountBalanceOld[$valueAccoun['asset']]['BUYPriceUSD'];
            // $accountBalance[$valueAccoun['asset']]['BUYPriceBTC'] = $accountBalanceOld[$valueAccoun['asset']]['BUYPriceBTC'];
            // $accountBalance[$valueAccoun['asset']]['max_p'] = $accountBalanceOld[$valueAccoun['asset']]['max_p'];
            $accountBalance[$valueAccoun['asset']]['update']= 'BalanceOld';
 
        }elseif(isset($historyBUY[$valueAccoun['asset']]) && $historyBUY[$valueAccoun['asset']] !== '' && $accountBalance[$valueAccoun['asset']]['BUYPriceUSD'] = ''){
            $order = $historyBUY[$valueAccoun['asset']];
            $accountBalance[$valueAccoun['asset']]['BUYsymbol'] = $order['symbol'];
            // $accountBalance[$valueAccoun['asset']]['BUYside'] = array_pop($order['buy'])['side'];
            $accountBalance[$valueAccoun['asset']]['BUYTime'] = $order['time'];
            // $accountBalance[$valueAccoun['asset']]['executedQty'] = array_pop($order['buy'])['executedQty'];
            // $accountBalance[$valueAccoun['asset']]['BUYPrice'] = $order['price'];
            $accountBalance[$valueAccoun['asset']]['BUYPriceUSD'] =  $order['priceUSD'];
            // $accountBalance[$valueAccoun['asset']]['BUYPriceBTC'] =  $order['priceBTC'];
            $accountBalance[$valueAccoun['asset']]['max_p'] = ''; 
            $accountBalance[$valueAccoun['asset']]['update']= 'SymbolBUY';

        }else{  

        $arrayOrders =  $arrayemaxOrders=array();
        //выбераем ордера прожи за эту валюту
        if ($arraysymbolInfoBase = array_values($Bin->multiSearch($exchangeInfo['symbols'], array('quoteAsset' => $valueAccoun['asset'], 'status'=>'TRADING')))) {
            // echo '<br/>      Может быть базой количество:', count($arraysymbolInfoBase),'<br/>';
            foreach ($arraysymbolInfoBase as $key => $value) {                
                //Получить все заказы
                $allOrders= $Bin->allOrders(array('symbol' => $value['symbol'], 'limit' =>'500'));
                if (count($allOrders)==0) continue;
                //Выбераем продажи за asset
                $OrdersSELL = array_values($Bin->multiSearch($allOrders, array('status' => 'EXPIRED','status' => 'FILLED',  'side' => 'SELL')));
                if (count($OrdersSELL)==0) continue;
                // $Bin->showArrayTable($OrdersSELL);

                //Запоминаем последний ордер
                $arrayOrders = array_merge((array)$arrayOrders, (array)$OrdersSELL);                
                $arrayemaxOrders[]= array_pop($OrdersSELL);
               // echo $value['symbol'], ' Orders:', count($allOrders);
            }
        }
        //выбераем ордера покупки этой валюты
        if($arraysymbolInfoAsset = array_values($Bin->multiSearch($exchangeInfo['symbols'], array('baseAsset' => $valueAccoun['asset'], 'status'=>'TRADING')))){
            // echo '<br/>      asset можем купить:', count($arraysymbolInfoAsset),'<br/>';
            foreach ($arraysymbolInfoAsset as $key => $value) {
                //Получить все заказы
                $allOrders= $Bin->allOrders(array('symbol' => $value['symbol'], 'limit' =>'100'));
                if (count($allOrders)==0) continue;
                //Выбераем закупки                
                $OrdersBUY = array_values($Bin->multiSearch($allOrders, array( 'status' => 'EXPIRED','status' => 'FILLED', 'side' => 'BUY')));
                if (count($OrdersBUY)==0) continue;
                // $Bin->showArrayTable($OrdersBUY);

                //Запоминаем последний ордер
                $arrayOrders = array_merge((array)$arrayOrders, (array)$OrdersBUY);   
                $arrayemaxOrders[]=array_pop($OrdersBUY);
                // echo $value['symbol'], ' Orders:', count($allOrders); 
            }


        }

    //расчет средней цены закупки---------------------------------------------

        //получаем баланс
        $total = $accountBalanceOld[$valueAccoun['asset']]['total'];


        if (count($arrayOrders)==0) continue; 
        usort($arrayOrders, function($a, $b) {
            return $b['time']/1000 - $a['time']/1000;
        });

        $slice_key = '';
        foreach ($arrayOrders as $key => $value) {
            $symbolInfoValue = array_values($Bin->multiSearch($exchangeInfo['symbols'], array('symbol' => $value['symbol'], 'status'=>'TRADING')));
            $kurs = $Bin->kurs($symbolInfoValue[0]['quoteAsset'], $ticker24hr);            

            if ($value['side'] == 'BUY') {                 
                $executedQty = $value['executedQty'];
                $avgpriceBUY = bcdiv($value['cummulativeQuoteQty'], $value['executedQty'], 8);
                $BUYPriceUSD = bcmul($avgpriceBUY, $kurs['kursUSD'], 8);
            }

            if ($value['side'] == 'SELL') {
                $executedQty = $value['cummulativeQuoteQty'];
                $BUYPriceUSD = $kurs['kursUSD']; 
            }


            $arrayOrders[$key]['BUYPriceUSD'] = $BUYPriceUSD;
            if ($total - $executedQty>=0) {
                $arrayOrders[$key]['Qty'] = $executedQty;                
                $arrayOrders[$key]['sumUSD'] = bcmul($BUYPriceUSD, $executedQty, 8);  
                $arrayOrders[$key]['total'] = $total -= $executedQty;              

            }
            if ($total ==0) {
                break;
            }
            if ($total - $executedQty<0) {
                $arrayOrders[$key]['Qty'] = $total;                
                $arrayOrders[$key]['sumUSD'] = bcmul($BUYPriceUSD, $total, 8);
                $arrayOrders[$key]['total'] = $total -= $total;

                $slice_key = $key;
                break;
            }
                
        }
        //удаляем ненужную часть масива
        $arrayOrders = array_slice($arrayOrders, 0, $slice_key+2);
        //находим усредненую цену закупки
        $avgpriceBUY = bcdiv(array_sum(array_column($arrayOrders, 'sumUSD')), $accountBalanceOld[$valueAccoun['asset']]['total'], 8);

        // $Bin->showArrayTable($arrayOrders);
        // echo array_sum(array_column($arrayOrders, 'sumUSD')), '  ', $accountBalanceOld[$valueAccoun['asset']]['total'], ' = ', $avgpriceBUY, "<br/>";

        //сохраняем нужные даные
        $accountBalance[$valueAccoun['asset']]['BUYsymbol'] =  $arrayOrders[0]['symbol'];
        // $accountBalance[$valueAccoun['asset']]['BUYside'] =  $arrayOrders[0]['side'];
        $accountBalance[$valueAccoun['asset']]['BUYTime']= $arrayOrders[0]['time'];
        $accountBalance[$valueAccoun['asset']]['BUYPriceUSD'] = $avgpriceBUY;
        // $accountBalance[$valueAccoun['asset']]['BUYPriceBTC'] = $BUYPriceBTC;
        $accountBalance[$valueAccoun['asset']]['max_p'] = ''; 
        $accountBalance[$valueAccoun['asset']]['update']= 'InfoAsset';
        // $Bin->show($maxOrdersBUY);

    //выбор последней цены закупки---------------------------------------------
        // echo "Последний из каждой пары<br/>";
        // if (count($arrayemaxOrders)==0) continue; 
        // usort($arrayemaxOrders, function($a, $b) {
        //     return $a['time']/1000 - $b['time']/1000;
        // });
        // // $Bin->showArrayTable($arrayemaxOrders);


        // //берем последний ордер  и выбераем
        // $maxOrdersBUY = array_pop($arrayemaxOrders);  
        // // $Bin->show($maxOrdersBUY);      
        // $maxOrdersBUYsymbolInfo = array_values($Bin->multiSearch($exchangeInfo['symbols'], array('symbol' => $maxOrdersBUY['symbol'], 'status'=>'TRADING')));
        // $avgpriceBUY = bcdiv($maxOrdersBUY['cummulativeQuoteQty'], $maxOrdersBUY['executedQty'], 8);

        // if ($maxOrdersBUY['side'] == 'BUY') {
        //     $kurs = $Bin->kurs($maxOrdersBUYsymbolInfo[0]['quoteAsset'], $ticker24hr); 
        //     $executedQty = number_format($maxOrdersBUY['executedQty'], 8, '.', '');

        //     // $accountBalance[$valueAccoun['asset']]['BUYkursUSD'] = $kurs['kursUSD'];
        //     // $accountBalance[$valueAccoun['asset']]['BUYkursBTC'] = $kurs['kursBTC'];             
             
        //     $BUYPriceUSD = bcmul($avgpriceBUY, $kurs['kursUSD'], 8);
        //     $BUYPriceBTC = bcmul($avgpriceBUY, $kurs['kursBTC'], 8);
        // }
        // if ($maxOrdersBUY['side'] == 'SELL') {
        //     $kurs = $Bin->kurs($maxOrdersBUYsymbolInfo[0]['quoteAsset'], $ticker24hr);
        //     $executedQty = number_format($maxOrdersBUY['executedQty'], 8, '.', '');

        //     // $accountBalance[$valueAccoun['asset']]['BUYkursUSD'] = $kurs['kursUSD'];
        //     // $accountBalance[$valueAccoun['asset']]['BUYkursBTC'] = $kurs['kursBTC'];
        //     $BUYPriceUSD = $kurs['kursUSD']; 
        //     $BUYPriceBTC = $kurs['kursBTC']; 
        // }

        // //сохраняем нужные даные
        // $accountBalance[$valueAccoun['asset']]['BUYsymbol'] =  $maxOrdersBUY['symbol'];
        // // $accountBalance[$valueAccoun['asset']]['BUYside'] =  $maxOrdersBUY['side'];
        // $accountBalance[$valueAccoun['asset']]['BUYTime']= $maxOrdersBUY['time'];
        // // $accountBalance[$valueAccoun['asset']]['executedQty'] = $executedQty;
        // // $accountBalance[$valueAccoun['asset']]['BUYPrice'] = $avgpriceBUY;
        // $accountBalance[$valueAccoun['asset']]['BUYPriceUSD'] = $BUYPriceUSD;
        // // $accountBalance[$valueAccoun['asset']]['BUYPriceBTC'] = $BUYPriceBTC;
        // $accountBalance[$valueAccoun['asset']]['max_p'] = ''; 
        // $accountBalance[$valueAccoun['asset']]['update']= 'InfoAsset';
        // // echo $maxOrdersBUY['symbol'], ' ', $valueAccoun['asset'], ' avgpriceBUY: ', $avgpriceBUY, ' kursUSD: ', $accountBalance[$valueAccoun['asset']]['kursUSD'],'<br/>';
        // // $Bin->show($maxOrdersBUY);

        
    }
}
//сохроняем обновленые остатки

// $Bin->showArrayTable($accountBalance);
// die('STOP STOP');


//Анализируем остатки и ПРОДАЕМ**************************************************
foreach ($accountBalance as $keyAccoun => $valueAccoun) {
    echo $valueAccoun['asset'], '<br/>';
            
    //Удаляем  пустоту
    if (!isset($valueAccoun['asset'])) {
        continue;  
    }
    //Удаляем строки с балагсом менее
    if ($valueAccoun['total_USD'] < 0.1){
        $accountBalance[$valueAccoun['asset']]['quantitySELL'] = '0';
       continue; 
    } 
    //Удаляем строки с балагсом менее минимального
    if ($valueAccoun['total'] < $valueAccoun['min']){
        $accountBalance[$valueAccoun['asset']]['quantitySELL'] = '0';
       continue; 
    } 

    //Исключаем  asset USDT его нельзя продать
    if ($valueAccoun['asset']=='USDT') {
        $accountBalance[$valueAccoun['asset']]['quantitySELL'] = '0';
        continue;
    } 

    //находим варианты символов с утвержденой базой и самый выгодный вариант для продажи

    //который покупали
    // $maxSELL = '';
    // if ($symbolN = array_values($Bin->multiSearch($ticker24hr, array('symbol' => $accountBalance[$valueAccoun['asset']]['BUYsymbol'])))) {
    //         if (!$symbolInfo = array_values($Bin->multiSearch($exchangeInfo['symbols'], array('symbol' => $accountBalance[$valueAccoun['asset']]['BUYsymbol'], 'status'=>'TRADING')))) continue;
    //         //находим курс
    //         $kurs = $Bin->kurs($symbolInfo['0']['quoteAsset'], $ticker24hr);
    //         // $symbolN[0]['BUYpriceUSD'] = $historyBUY[$symbolInfo['0']['baseAsset']]['priceUSD'];
    //         $symbolN[0]['symbolInfo'] =  $symbolInfo[0];
    //         $symbolN[0]['kursUSD'] = $kurs['kursUSD'];
    //         $symbolN[0]['priceUSD'] =  bcmul($symbolN[0]['bidPrice'], $kurs['kursUSD'], 8);     
    // }
    // $maxSELL = $symbolN;
    // $Bin->show($maxSELL);

    //самый выгодный
    $maxSELL = '';
    $arraySELL = array();
    
    foreach ($base as $key => $value) {
        // echo $key, '<br/>';
        if ($symbolN = array_values($Bin->multiSearch($ticker24hr, array('symbol' => $valueAccoun['asset'].$key)))) {
            //Получаем информацию о symbol и исключаем неактивные пары
            if (!$symbolInfo = array_values($Bin->multiSearch($exchangeInfo['symbols'], array('symbol' => $valueAccoun['asset'].$key, 'status'=>'TRADING')))) continue;
            //находим курс
            $kurs = $Bin->kurs($symbolInfo['0']['quoteAsset'], $ticker24hr);
            // $symbolN[0]['BUYpriceUSD'] = $historyBUY[$symbolInfo['0']['baseAsset']]['priceUSD'];
            $symbolN[0]['symbolInfo'] =  $symbolInfo[0];
            $symbolN[0]['kursUSD'] = $kurs['kursUSD'];
            $symbolN[0]['priceUSD'] =  bcmul($symbolN[0]['bidPrice'], $kurs['kursUSD'], 8);            
            
            //выбераем первый
            if (empty($maxSELL))  $maxSELL = $symbolN;

            //проверяем варианты и выбераем самый выгодный
            if (1== bccomp((string)$symbolN[0]['priceUSD'], (string)$maxSELL[0]['priceUSD'], 8)) {
                $maxSELL = $symbolN;
            } 

            $arraySELL[] =  $symbolN[0];
        }

    }
    // $Bin->showArrayTable($arraySELL);
    // $Bin->show($maxSELL);


    //ПРОДАЖА*******************************

        //Определяем количество для продажи   
        if(isset($trade_limit) && $trade_limit !== '')  {
            $accountBalance[$valueAccoun['asset']]['quantitySELL'] = $quantitySELL = $Bin->round_min(bcdiv($trade_limit, $maxSELL[0]['priceUSD'], 8), $maxSELL[0]['symbolInfo']['filters'][2]['minQty']);
        }else{
                $balancequantitySELL = $valueAccoun['total'] - $valueAccoun['locked'] - $valueAccoun['min'];
                $accountBalance[$valueAccoun['asset']]['quantitySELL'] = $quantitySELL = $Bin->round_min($balancequantitySELL>0?$balancequantitySELL:0, $maxSELL[0]['symbolInfo']['filters'][2]['minQty']);
        }


        //если есть цена закупки
    if (isset($accountBalance[$valueAccoun['asset']]['BUYPriceUSD']) && $accountBalance[$valueAccoun['asset']]['BUYPriceUSD'] !== '') {   

                    //Находим сумы покупки и продажи 
                    $sumBUY = bcmul($quantitySELL, $accountBalance[$valueAccoun['asset']]['BUYPriceUSD'], 8);       
                    $sumSELL = bcmul($quantitySELL, $maxSELL[0]['priceUSD'], 8);

                    if ($sumSELL< 10) {
                        $accountBalance[$valueAccoun['asset']]['TRADE'] = 'баланс < 10';
                        continue;
                    }
                    
                    //Находим комисию биржи
                    $sumtaker = bcmul($sumBUY, $tradeFeeKom[$maxSELL[0]['symbol']]['taker'], 8);
                    $summaker = bcmul($sumSELL, $tradeFeeKom[$maxSELL[0]['symbol']]['maker'], 8);

                    //Считаем маржу 
                    $margin_sum = bcsub(bcsub($sumSELL, $summaker, 8), bcsub($sumBUY, $sumtaker, 8), 8);         
                    //Считаем % маржи 
                    $margin_p = bcmul(bcdiv($margin_sum, $sumBUY, 8), 100, 8);

                        
                        $accountBalance[$valueAccoun['asset']]['SELLsymbol'] = $maxSELL[0]['symbol'];
                        $accountBalance[$valueAccoun['asset']]['SELLpriceUSD'] = $maxSELL[0]['priceUSD'];
                        $accountBalance[$valueAccoun['asset']]['BUYsumUSD'] = $sumBUY;
                        $accountBalance[$valueAccoun['asset']]['SELLsumUSD'] = $sumSELL;
                        $accountBalance[$valueAccoun['asset']]['margin_sumUSD'] = $margin_sum; 
                        $accountBalance[$valueAccoun['asset']]['margin_p'] = $margin_p;  

                    //выщитываем изминение % маржи    
                    $accountBalance[$valueAccoun['asset']]['change_p'] = $change_p = bcsub($margin_p, $accountBalanceOld[$valueAccoun['asset']]['max_p'], 4);


                    //Запоминмем максимальный % маржи


                    if ($accountBalanceOld[$valueAccoun['asset']]['max_p'] == '' || -1 == bccomp((string)$accountBalanceOld[$valueAccoun['asset']]['max_p'], (string)$margin_p, 8)) {
                        $accountBalance[$valueAccoun['asset']]['max_p'] = $margin_p;  
                    }else{
                       $accountBalance[$valueAccoun['asset']]['max_p'] = $accountBalanceOld[$valueAccoun['asset']]['max_p'];  
                    }

                    if(-1 == bccomp((string)$margin_p, (string)0, 8)){
                        $accountBalance[$valueAccoun['asset']]['max_p'] = 0; 
                    }

                    //Запоминмем Stoploss_p
                    if (empty($lossМargin_p) && $accountBalanceOld[$valueAccoun['asset']]['Stoploss_p'] == '') {
                        $accountBalance[$valueAccoun['asset']]['Stoploss_p'] = bcadd($margin_p, $addPrice_loss_p, 8);
                        $accountBalance[$valueAccoun['asset']]['SL'] = 'A';
                    }else{
                        $accountBalance[$valueAccoun['asset']]['Stoploss_p'] = $accountBalanceOld[$valueAccoun['asset']]['Stoploss_p'];
                        $accountBalance[$valueAccoun['asset']]['SL'] = $accountBalanceOld[$valueAccoun['asset']]['SL'];
                    }
                    // if(!empty($lossМargin_p) && $accountBalanceOld[$valueAccoun['asset']]['Stoploss_p'] == '') {
                    //     $accountBalance[$valueAccoun['asset']]['Stoploss_p'] = $lossМargin_p;
                    //     $accountBalance[$valueAccoun['asset']]['SL'] = 'S';
                    // }
                    // if(empty($lossМargin_p) && $accountBalance[$valueAccoun['asset']]['SL'] == 'S') {
                    //     $accountBalance[$valueAccoun['asset']]['Stoploss_p'] = bcadd($margin_p, $addPrice_loss_p, 8);
                    //     $accountBalance[$valueAccoun['asset']]['SL'] = 'A';
                    // }
                    // if(!empty($lossМargin_p) && $accountBalance[$valueAccoun['asset']]['SL'] == 'A') {
                    //     $accountBalance[$valueAccoun['asset']]['Stoploss_p'] = $lossМargin_p;
                    //     $accountBalance[$valueAccoun['asset']]['SL'] = 'S';
                    // }
                    // if (empty($lossМargin_p) && $accountBalance[$valueAccoun['asset']]['SL'] == 'A') {
                    //     $accountBalance[$valueAccoun['asset']]['Stoploss_p'] = $accountBalanceOld[$valueAccoun['asset']]['Stoploss_p'];
                    //     $accountBalance[$valueAccoun['asset']]['SL'] = $accountBalanceOld[$valueAccoun['asset']]['SL'];
                    // }
                    // if (empty($lossМargin_p) && $accountBalance[$valueAccoun['asset']]['SL'] == 'S') {
                    //     $accountBalance[$valueAccoun['asset']]['Stoploss_p'] = $accountBalanceOld[$valueAccoun['asset']]['Stoploss_p'];
                    //     $accountBalance[$valueAccoun['asset']]['SL'] = $accountBalanceOld[$valueAccoun['asset']]['SL'];
                    // }


                    //Запоминмем максимальный балан
                    // if ($accountBalanceOld[$valueAccoun['asset']]['balans_max'] == '' || -1 == bccomp((string)$accountBalanceOld[$valueAccoun['asset']]['balans_max'], (string)$accountBalance[$valueAccoun['asset']]['total_USD'], 8)) {
                    //     $accountBalance[$valueAccoun['asset']]['balans_max'] = $accountBalance[$valueAccoun['asset']]['total_USD'];  
                    // }elseif(1==2){
                    //     // $accountBalance[$valueAccoun['asset']]['balans_max'] = $accountBalance[$valueAccoun['asset']]['total_USD']; 
                    // }else{
                    //    $accountBalance[$valueAccoun['asset']]['balans_max'] = $accountBalanceOld[$valueAccoun['asset']]['balans_max'];  
                    //     // $accountBalance[$valueAccoun['asset']]['max_p'] = 0; 
                    // }
                    // $accountBalance[$valueAccoun['asset']]['balans_change'] = bcsub($accountBalance[$valueAccoun['asset']]['total_USD'], $accountBalance[$valueAccoun['asset']]['balans_max'], 8);
                    // $accountBalance[$valueAccoun['asset']]['balans_change_p'] =  bcmul(bcdiv($accountBalance[$valueAccoun['asset']]['balans_change'], $accountBalance[$valueAccoun['asset']]['total_USD'], 8), 100, 8);




        


            //Получаем курс BTC USD
            $kurs = $Bin->kurs($maxSELL[0]['symbolInfo']['quoteAsset'], $ticker24hr);
            //&& -1== bccomp((string)$maxSELL[0]['priceChangePercent'], (string)0, 8)
        if(-1== bccomp((string)$margin_p, (string)$accountBalance[$valueAccoun['asset']]['Stoploss_p'], 8) ){

            if (in_array($valueAccoun['asset'], $positionlong)){
                $accountBalance[$valueAccoun['asset']]['TRADE'] = "Stop Loss LONG";
                continue;
            }

            // echo $value['symbol'], "Stop Loss ", 'margin_p:', $margin_p, "<br/>";
            $accountBalance[$valueAccoun['asset']]['TRADE'] = "Stop Loss OFF";


            //масив параметров для продажи
            $ParamsSELL = array('symbol'=>$maxSELL[0]['symbol'], 
                        'side' => 'SELL', 
                        'type' => 'MARKET', 
                        'quantity' => $quantitySELL,
                        'timeInForce' => 'IOC', 
                        'price' => $maxSELL[0]['bidPrice']);   
           // $Bin->show($ParamsSELL);
            // Создаем ордер на продажу по рыночным ценам
             if ($orderSELL_SL == '1') {
                $accountBalance[$valueAccoun['asset']]['TRADE'] = "Stop Loss";

                
                if ($order = $Bin->orderNEW($ParamsSELL)) {
                    // $Bin->show($order);
                    if (0 != bccomp((string)$order['executedQty'], (string)0, 8)) {
                       $order['asset']=$maxSELL[0]['symbolInfo']['baseAsset'];
                        $order['base']=$maxSELL[0]['symbolInfo']['quoteAsset'];

                        $order['BUYSymbol']=$accountBalance[$valueAccoun['asset']]['BUYsymbol'];
                        $order['BUYTime']=$accountBalance[$valueAccoun['asset']]['BUYsymbol'];

                        $order['BUYpriceUSD']=$accountBalance[$valueAccoun['asset']]['BUYPriceUSD'];
                        $order['bidPriceUSD']=$accountBalance[$valueAccoun['asset']]['SELLpriceUSD'];
                        //
                        $order['orderPrice']=$order['cummulativeQuoteQty']/$order['executedQty'];
                        $order['orderPriceUSD']=$order['cummulativeQuoteQty']/$order['executedQty']*$kurs['kursUSD'];

                        $order['BUYsum'] = bcmul($order['BUYpriceUSD'], $order['executedQty'], 8);
                        $order['orderSum'] = bcmul($order['cummulativeQuoteQty'], $kurs['kursUSD'], 8);

                        $order['sumtaker'] = bcmul($order['BUYsum'], $tradeFeeKom[$maxSELL[0]['symbol']]['taker'], 8);
                        $order['summaker'] = bcmul($order['orderSum'], $tradeFeeKom[$maxSELL[0]['symbol']]['maker'], 8);

                        $order['marginUSD']=bcsub(bcsub($order['orderSum'], $order['summaker'], 8), bcsub($order['BUYsum'], $order['sumtaker'], 8), 8);
                        $order['kurs']= $kurs;
                        $archiveorderSELL[] = $order;
                        $Bin->saveFile($archiveorderSELL, $filarchiveorderSELL);
                    }
                }
            }
        }
        //Take profit   &&  -1== bccomp((string)$maxSELL['bidPrice'], (string)$maxSELL['lastPrice'], 8) || 1== bccomp((string)$margin_p, (string)5, 8)
        if ((1== bccomp((string)$margin_p, (string)$TakeProfit_p, 8) && -1== bccomp((string)$change_p, (string)$declinem_p, 8))) {
                // if (in_array($valueAccoun['asset'], $positionlong)){
                //     $accountBalance[$valueAccoun['asset']]['TRADE'] = "Take profit LONG";
                //     continue;
                // }
        // echo $maxSELL[0]['symbol'], "Take profit ", 'margin_p:', $margin_p, "<br/>";
        $accountBalance[$valueAccoun['asset']]['TRADE'] = "Take profit OFF";
            //масив параметров для продажи
            $ParamsSELL = array('symbol'=>$maxSELL[0]['symbol'], 
                        'side' => 'SELL', 
                        'type' => 'LIMIT', 
                        'quantity' => $quantitySELL,
                        'timeInForce' => 'IOC', 
                        'price' => $maxSELL[0]['bidPrice']); 
            // $Bin->show($ParamsSELL);
            // Создаем ордер на продажу
            if ($orderSELL == '1') {
                $accountBalance[$valueAccoun['asset']]['TRADE'] = "Take profit";

                
                if ($order = $Bin->orderNEW($ParamsSELL)) {
                    // $Bin->show($order);
                    if (0 != bccomp((string)$order['executedQty'], (string)0, 8)) {
                       $order['asset']=$maxSELL[0]['symbolInfo']['baseAsset'];
                        $order['base']=$maxSELL[0]['symbolInfo']['quoteAsset'];

                        $order['BUYSymbol']=$accountBalance[$valueAccoun['asset']]['BUYsymbol'];
                        $order['BUYTime']=$accountBalance[$valueAccoun['asset']]['BUYsymbol'];

                        $order['BUYpriceUSD']=$accountBalance[$valueAccoun['asset']]['BUYPriceUSD'];
                        $order['bidPriceUSD']=$accountBalance[$valueAccoun['asset']]['SELLpriceUSD'];
                        //
                        $order['orderPrice']=$order['cummulativeQuoteQty']/$order['executedQty'];
                        $order['orderPriceUSD']=$order['cummulativeQuoteQty']/$order['executedQty']*$kurs['kursUSD'];

                        $order['BUYsum'] = bcmul($order['BUYpriceUSD'], $order['executedQty'], 8);
                        $order['orderSum'] = bcmul($order['cummulativeQuoteQty'], $kurs['kursUSD'], 8);

                        $order['sumtaker'] = bcmul($order['BUYsum'], $tradeFeeKom[$maxSELL[0]['symbol']]['taker'], 8);
                        $order['summaker'] = bcmul($order['orderSum'], $tradeFeeKom[$maxSELL[0]['symbol']]['maker'], 8);

                        $order['marginUSD']=bcsub(bcsub($order['orderSum'], $order['summaker'], 8), bcsub($order['BUYsum'], $order['sumtaker'], 8), 8);

                      

                        $order['kurs']= $kurs;
                        $archiveorderSELL[] = $order;
                        $Bin->saveFile($archiveorderSELL, $filarchiveorderSELL);

                    }
                }
            }
        }
    }  
}  


$Bin->saveFile($accountBalance, $filaccountBalance);
//сохраняем историю продаж
$Bin->saveFile($archiveorderSELL, $filarchiveorderSELL);

// 
$filaccountBalanceyesterday = 'E:\binance\V5AccountBalance_'.date("m-d-Y", mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'))).'.txt';
$accountBalanceYesterday = $Bin->readFile($filaccountBalanceyesterday);
// $Bin->showArrayTable($accountBalanceYesterday);




//Остатки Сортируем и смотрим на ПРОДАЖУ 
echo '<br/>Остатки на ПРОДАЖУ выбрал : ', count($accountBalance), '<br/>';
usort($accountBalance, function($a, $b) {
    return $b['margin_p']*100000 - $a['margin_p']*100000;
});
$Bin->showArrayTable($accountBalance);
//round(array_sum(array_column($archiveorderSELL, 'marginUSD')),2)
                    //Считаем маржу 

$Balance = array_sum(array_column($accountBalance, 'total_USD'));
$BalanceYesterday = array_sum(array_column($accountBalanceYesterday, 'total_USD'));

$balansDay = round($Balance-$BalanceYesterday,2);
$balansDay_p = bcmul(bcdiv($balansDay, $BalanceYesterday, 8), 100, 8);
$colorDay = $balansDay>0?"green":"red";



echo 'Текущий баланс: ', round(array_sum(array_column($accountBalance, 'total_USD')),2), ' Вчера конец дня: ',  round(array_sum(array_column($accountBalanceYesterday, 'total_USD')),2), ' <br/>';
echo 'СЕГОДНЯ : ', '  <font size="20" color='.$colorDay.' face="Arial">', round($balansDay_p,2), ' % </font> Profit:', round($balansDay,2), ' $   <br/>';


// $balans_invis = bcsub(array_sum(array_column($accountBalance, 'total_USD')),$invis, 8);         
// $roi_p = bcmul(bcdiv($balans_invis, $invis, 8), 100, 8);
// $color = $roi_p>0?"green":"red";
// echo 'ROI инвистиции:   <font size="20" color='.$color.' face="Arial">', round($roi_p, 2), ' %  </font> Инвестировано:', $invis/100 ,' <br/>';


// $balansBUCKS = round(array_sum(array_column($archiveorderSELL, 'marginUSD')),2);
// $colorBUCKS = $balansBUCKS>0?"green":"red";
// echo 'СЕГОДНЯ BUCKS: ', count($archiveorderSELL), ' операций', '  <font size="20" color='.$colorBUCKS.' face="Arial">', $balansBUCKS, ' $</font><br/>';
// echo 'За сегодня: <font size="20" color="green" face="Arial">',  ' </font>  баланс ', round(array_sum(array_column($accountBalance, 'total_BTC')),8), '₿ <br/>';


//Остатки Сортируем и смотрим на ПРОДАЖУ 
// echo '<br/>Остатки на ПРОДАЖУ выбрал : ', count($accountBalanceYesterday), '<br/>';
// usort($accountBalance, function($a, $b) {
//     return $b['margin_p']*100000 - $a['margin_p']*100000;
// });
// $Bin->showArrayTable($accountBalanceYesterday);

echo '<br/>Архив продаж : ', count($archiveorderSELL), '<br/>';
$Bin->showArrayTableOrders(array_reverse($archiveorderSELL));
// $Bin->showArrayTable(array_reverse($archiveorderSELL));

// echo '  
//     <style type="text/css">
//        div { 
//         width: 10%; /* Ширина */

//        }
//     /style>';

$time = microtime(true) - $start;
echo 'Время выполнения скрипта: '.round($time, 4).' сек.<br/><br/><br/>';

sleep(1);
exit();
?>