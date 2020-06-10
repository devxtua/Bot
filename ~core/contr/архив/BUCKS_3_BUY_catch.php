<?php

$start = microtime(true);
$mem_start = memory_get_usage();
// sleep(2);
//$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
header('refresh: 1');
//Устанавливаем настройки памяти
    // echo "memory_limit ", ini_get('memory_limit'), "<br />";
    ini_set('memory_limit', '512M');
    echo "memory_limit ", ini_get('memory_limit'), "<br />";
// die();
    //Устанавливаем настройки времени
    // echo "max_execution_time ", ini_get('max_execution_time'), "<br />";
    ini_set('max_execution_time', 1000);    //  одно и тоже что set_time_limit(6000);
    echo "max_execution_time ", ini_get('max_execution_time'), "<br />";

    // ob_implicit_flush(1);

    // ob_start();
    // ob_get_contents();
    // ob_get_clean();
    // ob_end_flush();
//$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
echo "BUCKS_3_BUY_catch<br/>";


//ВКЛЮЧИТЬ создания ордеров покупку
$orderBUY = 0;
$orderSELL_OCO = 0;
//условия OCO
$M_Price = 1.01;
$S_Price = 0.9901;
$SL_Price = 0.99;

//стартовый лимит закупки
$settings['trade_limit'] = $trade_limit = 25;

//только торговые пары  'ETHBULLUSDT', 'ETHBEARUSDT', 'ETHUSDT', 'XRPBULLUSDT',  'XRPBEARUSDT',  'XRPUSDT', 'EOSBULLUSDT', 'EOSBEARUSDT',  'BULLUSDT', 'BEARUSDT' ,  'BNBBULLUSDT', 'BNBBEARUSDT', 'BNBUSDT'
// $symbolBUY =  array('BNBUSDT','ETHUSDT', 'XRPUSDT', 'EOSUSDT','BTCUSDT');
$symbolBUY =  array('ETHUSDT');

// $symbolBUY =  array('ETHBULLUSDT','XRPBULLUSDT','EOSBULLUSDT','BULLUSDT','WINUSDT','NPXSUSDT','DENTUSDT','BTTUSDT','COCOSUSDT','HOTUSDT','MFTUSDT','ERDUSDT','KEYUSDT','MBLUSDT','ANKRUSDT','TFUELUSDT','DREPUSDT','FUNUSDT','CELRUSDT','IOTXUSDT','TROYUSDT','ONEUSDT','IOSTUSDT','VETUSDT','ZILUSDT','MITHUSDT','DOCKUSDT','FTMUSDT','GTOUSDT','TCTUSDT','COSUSDT','ARPAUSDT','CHZUSDT','TRXUSDT','VITEUSDT','NKNUSDT','BTSUSDT','MATICUSDT','RVNUSDT','FETUSDT','PERLUSDT','DUSKUSDT','ADAUSDT','XLMUSDT','CTXCUSDT','ENJUSDT','ONGUSDT','THETAUSDT','STXUSDT','AIONUSDT','IOTAUSDT','XRPUSDT','BATUSDT','WANUSDT','ZRXUSDT','NULSUSDT','BNTUSDT','MTLUSDT','ALGOUSDT','ICXUSDT','STRATUSDT','TOMOUSDT','BEAMUSDT','RLCUSDT','ONTUSDT','NANOUSDT','OMGUSDT','KAVAUSDT','HCUSDT','QTUMUSDT','XTZUSDT','EOSUSDT','ATOMUSDT','LINKUSDT','MCOUSDT','ETCUSDT','NEOUSDT','BNBUSDT','ZECUSDT','LTCUSDT','XMRUSDT','EOSBULLUSDT','DASHUSDT','XRPBULLUSDT','ETHUSDT','BCHUSDT','ETHBULLUSDT','BNBBULLUSDT','BTCUSDT','BULLUSDT');

//минимальное количество сделок за 24 час
$settings['countTrends'] = $countTrends= 500;
//минимальное обем продаж за 24 час
$settings['quoteVolume'] = $quoteVolume = 100000;
// максимальный % изминения цены за 24 час
$settings['priceChangePercent'] = $priceChangePercent= array(0, 50);





//НАСТРОЙКИ БЕРЕМ С ПРОДАЖИ
//Увеличить моментальную потерю маржи0
$settings['addPrice_loss_p'] = $addPrice_loss_p = 0;
//потеря маржи % при Stop Loss
$settings['lossМargin_p'] = $lossМargin_p = -0.25;


//минимальный процент  Волонтильность за последние countKlines свечей
$settings['BUYcontrolChangePrice_p'] = $BUYcontrolChangePrice_p = 1;
//процент контроля роста цены от минимальной для покупки
$settings['controltopPrice_p'] = $controltopPrice_p = 0.3;

//Определяем базове валют
$base['USDT']= array('minBalans'=>1000, 'minPriceBuy'=>0.00000100);
// $base['BTC']= array('minBalans'=>0.5, 'minPriceBuy'=>0.00000100);
// $base['BNB']= array('minBalans'=>10, 'minPriceBuy'=>0.00000100);
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

//Получить изминения за 24 часса
$ticker24hr = $Bin->ticker24hr();

//Получить свободный баланс АКТИВОВ
$accountBalance= $Bin->accountBalance($ticker24hr, $base);
$filaccountBalance = 'E:\binance\V5AccountBalance_'.date("m-d-Y", mktime(0, 0, 0, date('m'), date('d'), date('Y'))).'.txt';
$Bin->saveFile($accountBalance, $filaccountBalance);
$Bin->showArrayTable($accountBalance);

echo 'total : ', round($accountBalance['USDT']['total'],2), ' USDT<br/>';
echo 'locked : ',  round($accountBalance['USDT']['locked'],2), ' USDT <br/>';
echo 'free : ', '  <font size="20" color = green face="Arial">', round($accountBalance['USDT']['free'],2), ' USDT </font><br/>';


// $Bin->show($ticker24hr[0]);

//сохраняем файлы с именем времени первого символа
$fileTicker24hr = 'E:\binance\ticker24hr\\'.$ticker24hr[0]['closeTime'].'.txt';
$Bin->saveFile($ticker24hr, $fileTicker24hr);

//Читаем нужные файлы  и удаляем старые***********
//сканируем директорию с файлами
$dir = 'E:\binance\ticker24hr\\';
$files = array_reverse(scandir($dir, 1));
echo 'Итого файлов: ', count($files),  "<br/><br/>";

$time = time();
$today = getdate();
// $Bin->show($today);


// echo "ОТБИРАЕМ и смотрим возраст контрольных файлов<br/>";
foreach ($files as $key => $name) {
    $file = $dir.$name;
    //проверяем существование файла
    if (!is_file($file)) continue;
    $filemtime = filemtime($file);
    $filegetdate = getdate($filemtime);
    $ticker24hrfile['type'] = '';
    $ticker24hrfile['filemtime'] = $filemtime;
      // $Bin->show($filegetdate);
    // удаляем ненужные
    if ($time - $filemtime > 60*60*24) unlink($file);


    if ($today['minutes'] == $filegetdate['minutes'] && $today['hours'] == $filegetdate['hours'] && $today['mday'] == $filegetdate['mday']) { //выбераем даные за текущую менуту
    // if ($time - $filemtime < 20) {
      if ($ticker24hrfile = $Bin->readFile($file)){
          $ticker24hrfile['type'] = 'seconds';
          $historyTicker24hr_ALL[] = $ticker24hrfile;

      }
    }elseif(($key % 2) == 0 && $time - $filemtime < 60*30) {
      if ($ticker24hrfile = $Bin->readFile($file)){
          $ticker24hrfile['type'] = 'minutes';
          $historyTicker24hr_ALL[] = $ticker24hrfile;

      }
    }
    // elseif (($key%100) == 0 && $time - $filemtime < 60*60000) {
    //     if ($ticker24hrfile = $Bin->readFile($file)){
    //         $ticker24hrfile['type'] = 'hours';
    //         $historyTicker24hr_ALL[] = $ticker24hrfile;
    //     }
    // }
    //elseif ((($key)%10000)==0 && $time - $filemtime > 60*60*24) {
    //     if ($ticker24hrfile = $Bin->readFile($file)){
    //         $ticker24hrfile['type'] = 'mday';
    //         $historyTicker24hr_ALL[] = $ticker24hrfile;
    //     }
    // }

    //смотрим возраст файлов
    if ($ticker24hrfile['type'] !='') {
      echo $key, ' ', $ticker24hrfile['type'], ' ', date("H:i:s", $filemtime), ' возраст: ', date("H:i:s", mktime(0, 0, $time - $filemtime)),  "<br/>";
    }

}
 // $Bin->show($historyTicker24hr_ALL);
echo 'Обем памяти: ', (memory_get_usage() - $mem_start)/1000000, ' мегабайта.<br/>';



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



$filetestOrder = 'E:\binance\V5testOrder.txt';
if ($testOrder = $Bin->readFile($filetestOrder)){
   $open = array_unique(array_column(array_values($Bin->multiSearch($testOrder, array('status' => ''))), 'asset'));

}else{
  $testOrder = $open = array();
}

$filesymbolConfig = 'E:\binance\symbolConfig.txt';
if (!$symbolConfig = $Bin->readFile($filesymbolConfig)){
  $symbolConfig = array();
}

// die();
// $count = count($historyTicker24hr);
// $historyTicker24hr_ALL = array_reverse($historyTicker24hr_ALL);
// if ($historyTicker24hr_ALL[0]['type'] != 'control' || count($historyTicker24hr_ALL)<10) {
//   die("МАЛО ДАНЫХ");
// }

$select = array();
$arrayPrice = array();
$sConf=0;

foreach ($ticker24hr as $key => $value) {//начало цикла 1

        if (in_array($value['symbol'], array_column($testOrder, 'symbol'))) {
          foreach ($testOrder as $keytestOrder => $vtestOrder) {
              // if (stripos($vtestOrder['ass'], $$vtestOrder['symbol']) === false) {
              // $Bin->show($testOrder[$keytestOrder]);
              //   // unset($testOrder[$keytestOrder]);
              // }
            if ($value['symbol'] != $testOrder[$keytestOrder]['symbol'] ) continue;
              if ($testOrder[$keytestOrder]['status'] == '') {
                  $testOrder[$keytestOrder]['statusTime'] = $value['closeTime'];
                  $testOrder[$keytestOrder]['lastPrice'] = $value['lastPrice'];
             }
             if (1== bccomp((string)$value['lastPrice'], (string)$testOrder[$keytestOrder]['M_Price'], 8) && $testOrder[$keytestOrder]['status'] == '') {
                  $testOrder[$keytestOrder]['status'] = 'MARGIN';
                  $testOrder[$keytestOrder]['statusTime'] = $value['closeTime'];

             }
             if (-1== bccomp((string)$value['lastPrice'], (string)$testOrder[$keytestOrder]['SL_Price'], 8) && $testOrder[$keytestOrder]['status'] == '') {
                  $testOrder[$keytestOrder]['status'] = 'SL';
                  $testOrder[$keytestOrder]['statusTime'] = $value['closeTime'];

             }
          }
        }

        //Получаем информацию о symbol и исключаем неактивные пары
        if (!$symbolInfo = array_values($Bin->multiSearch($exchangeInfo['symbols'], array('symbol' => $value['symbol'], 'status'=>'TRADING'))) ) continue;
            // $Bin->show($symbolInfo);
        //Получаем курс BTC USD
        $kurs = $Bin->kurs($symbolInfo['0']['quoteAsset'], $ticker24hr);
           // $Bin->show($kurs);


        //исключаем все кроме разрешоного
        if (!in_array($value['symbol'], $symbolBUY))  continue;

         // Исключаем если база не USDT
        // if ($symbolInfo[0]['quoteAsset']!='USDT') continue;

        // Исключаем пары с неутвержденой базой
        if (!array_key_exists($symbolInfo['0']['quoteAsset'], $base)) continue;


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
        if (-1== bccomp((string)$value['askPrice'], (string)number_format($base[$symbolInfo['0']['quoteAsset']]['minPriceBuy'], 8, '.', ''), 8)) continue;

        //Исключаем если моминтальная потеря цены при покупке больше //потеря маржи % при Stop Loss    $lossМargin_p
                    //находим моментальную потерю цены при покупке
                    $takerask =  bcmul($value['askPrice'], $tradeFeeKom[$value['symbol']]['taker'], 8);
                    $price_loss = bcsub($value['askPrice'], bcsub($value['bidPrice'], $takerask, 8), 8);
                    $price_loss_p = bcmul(bcdiv($price_loss, $value['bidPrice'], 8),100, 8);
        // if (1== bccomp((string)$price_loss_p, (string)abs($lossМargin_p), 8))  continue;

    // $Bin->show($symbolInfo);
    //****************************

      if (time() - ($symbolConfig[$value['symbol']]['Time']/1000) > 1000 && $sConf == 0) {
                $sConf++;

                if (!$klines = array_reverse($Bin->klines(array('symbol'=>$value['symbol'], 'interval' => '2h'))))
                  echo "klines NOU<br/>";
                  // $Bin->showArrayTable($klines);
                  $top = array();
                  $bottom = array();
                  $L = ceil(count($klines)*3/100);
                  $B = array('Time' => '', 'count' => 0, 'Op_Cl_p' => 0);
                  $Bcount=array();


                  foreach ($klines as $keyW => $valueW) {
                      $Op_Cl = bcsub($valueW[4], $valueW[1], 8);
                      $Op_Cl_p = bcmul(bcdiv($Op_Cl, $valueW[1], 8), 100, 2);

                      $Op_Low = bcsub($valueW[3], $valueW[1], 8);
                      $Op_Low_p = bcmul(bcdiv($Op_Low, $valueW[1], 8), 100, 2);

                      $Op_High = bcsub($valueW[2], $valueW[1], 8);
                      $Op_High_p = bcmul(bcdiv($Op_High, $valueW[1], 8), 100, 2);

                      $Low_High = bcsub($valueW[2], $valueW[3], 8);
                      $Low_High_p = bcmul(bcdiv($Low_High, $valueW[3], 8), 100, 2);

                      //если top
                      if (1== bccomp((string)$valueW[4], (string)$valueW[1], 8)) {
                          $top[$keyW]['Time'] = $valueW[0];
                          $top[$keyW]['Op_Cl_p'] = $Op_Cl_p;
                          $top[$keyW]['Op_Low_p'] = $Op_Low_p;
                          $top[$keyW]['Op_High_p'] = $Op_High_p;
                          $top[$keyW]['Low_High_p'] = $Low_High_p;

                          $Bcount[] =  $B;
                          $B['Op_Cl_p'] = $B['count'] = 0;

                      }
                      //если bottom
                      if (1== bccomp((string)$valueW[1], (string)$valueW[4], 8)) {
                          $bottom[$keyW]['Time'] = $valueW[0];
                          $bottom[$keyW]['Op_Cl_p'] = $Op_Cl_p;
                          $bottom[$keyW]['Op_High_p'] = $Op_High_p;
                          $bottom[$keyW]['Op_Low_p'] = $Op_Low_p;
                          $bottom[$keyW]['Low_High_p'] = $Low_High_p;

                          $B['Time'] = $valueW[0];
                          $B['Op_Cl_p'] +=  abs($Op_Cl_p);
                          $B['count'] ++;

                      }
                  }



                  usort($top, function($a, $b) {
                      return abs($b['Op_High_p']*100) - abs($a['Op_High_p']*100);
                  });
                  $top = array_slice($top, 0, $L);
                  echo $value['symbol'], " top ", "<br/>";
                  $Bin->showArrayTable($top);

                  usort($bottom, function($a, $b) {
                      return abs($b['Op_Low_p']*100) - abs($a['Op_Low_p']*100);
                  });
                  $bottom = array_slice($bottom, 0, $L);
                  echo $value['symbol'], " bottom ", $bottom[2]['Op_Low_p'], "<br/>";
                  $Bin->showArrayTable($bottom);

                  // usort($Bcount, function($a, $b) {
                  //     return abs($b['Op_Cl_p']*100) - abs($a['Op_Cl_p']*100);
                  // });
                  // $Bcount = array_slice($Bcount, 0, $L);
                  // echo $value['symbol'],  " Bcount", "<br/>";
                  // $Bin->showArrayTable($Bcount);





                  $symbolConfig[$value['symbol']]['symbol'] = $value['symbol'];
                  $symbolConfig[$value['symbol']]['Time'] = time()*1000;
                  $symbolConfig[$value['symbol']]['trend'] = '';

                  $symbolConfig[$value['symbol']]['Op_Low_p'] = end($bottom)['Op_Low_p'];


                  $symbolConfig[$value['symbol']]['top_Price'] = bcadd(1, abs(end($bottom)['Op_Low_p'])/300, 3);
                  $symbolConfig[$value['symbol']]['top_S_Price'] = bcsub(1, abs(end($bottom)['Op_Low_p'])/200, 3);
                  $symbolConfig[$value['symbol']]['top_SL_Price'] = bcsub($symbolConfig[$value['symbol']]['top_S_Price'], 0.001, 3);

                  // $symbolConfig[$value['symbol']]['Op_Low_p'] = end($bottom)['Op_Low_p'];
                  // $symbolConfig[$value['symbol']]['bottom_Price'] = bcadd(1, abs(end($bottom)['Op_Low_p'])/400, 3);
                  // $symbolConfig[$value['symbol']]['bottom_S_Price'] = bcsub(1, abs(end($bottom)['Op_Low_p'])/200, 3);
                  // $symbolConfig[$value['symbol']]['bottom_SL_Price'] = bcsub($symbolConfig[$value['symbol']]['bottom_S_Price'], 0.001, 3);
                  echo $value['symbol'], " обновлено в symbolConfig<br/>";
      }



        $symbol = array();
        $symbol['symbol'] = $value['symbol'];
        $symbol['asset'] = $symbolInfo['0']['baseAsset'];
        $symbol['*']=' ';
        $symbol['time'] = $value['closeTime'];
        $symbol['priceCP'] = $value['priceChangePercent'];
        $symbol['lastPrice'] = $value['lastPrice'];
        $ask_last = bcsub($value['askPrice'], $value['lastPrice'], 8);
        $symbol['ask_last_p'] = bcmul(bcdiv($ask_last, $value['lastPrice'], 8), 100, 8);


        $symbol['askPrice'] =$value['askPrice'];
        $symbol['USDaskPrice'] = bcmul($value['askPrice'], $kurs['kursUSD'], 8);
        $symbol['bidPrice'] = $value['bidPrice'];

        $symbol['askQty'] =$value['askQty'];
        $symbol['bidQty'] = $value['bidQty'];



        $symbol['Control'] = '';
        $symbol['status']= '';
        $symbol['statusTREND']='';

        $symbol['~']=' ';



        $spred = bcsub($value['askPrice'], $value['bidPrice'], 8);
        $spred_p = bcmul(bcdiv($spred, $value['bidPrice'], 8), 100, 8);

        // $symbol['average'] = $average = bcdiv(bcadd($value['askPrice'], $value['bidPrice'], 8), 2, 8);


        $symbol['Control_time'] = '';
        $symbol['Control_Price'] = '';
        $symbol['Control_p'] = '';

        $symbol['Start_time'] = '';
        $symbol['Start_Price'] = '';
        $symbol['Start_p'] = '';

        $symbol['##']=' ';







        $symbol['**']=' ';
        $symbol['max_date'] = $value['closeTime'];
        $symbol['max'] = $value['askPrice'];
        $symbol['S_max_p'] = '';
        $symbol['***']=' ';
        $symbol['min_date'] = $value['closeTime'];
        $symbol['min'] = $value['askPrice'];
        $symbol['S_min_p'] = '';

        $symbol['****']=' ';
        $symbol['min_max_p'] = '';
        $symbol['trend_max_p']='';
        $symbol['trend_min_p']='';

        $symbol['****']=' ';

        // $symbol['stop'] = '-';
        // $symbol['stop_key']='';


        $symbol['SUM_askQty'] = '';
        $symbol['SUM_bidQty'] = '';
        $symbol['SUM_lastQty'] = '';







// $Bin->show($value);

$tt = array();
$max24 = $value['highPrice'];
$min24 = $value['lowPrice'];

$i=0;

foreach ($historyTicker24hr_ALL as $keyHT24hr => $historyTicker24hr) {
      if ($value['symbol'] != $historyTicker24hr[$key]['symbol'] ) continue;


        //
      if ($historyTicker24hr['type']=='hours') {

      }

        //
      if ($historyTicker24hr['type']=='minutes') {
          //находим минимут
          if (1 == bccomp((string)$symbol['min'], (string)$historyTicker24hr[$key]['askPrice'], 8)) {
                  $symbol['min'] = $historyTicker24hr[$key]['askPrice'];
                  $symbol['min_date'] = $historyTicker24hr[$key]['closeTime'];
          }

          //находим максимум
          if (-1 == bccomp((string)$symbol['max'], (string)$historyTicker24hr[$key]['askPrice'], 8)) {
              $symbol['max'] = $historyTicker24hr[$key]['askPrice'];
              $symbol['max_date'] = $historyTicker24hr[$key]['closeTime'];
          }

          if ($symbol['Control_Price'] == '') {
            $symbol['Control_time'] = $historyTicker24hr[$key]['closeTime'];
            $symbol['Control_Price'] = $historyTicker24hr[$key]['askPrice'];
          }
          // continue;
      }



        //
      if ($symbol['Start_Price'] == '' && $historyTicker24hr['type']=='seconds') {
        $symbol['Start_time'] = $historyTicker24hr[$key]['closeTime'];
        $symbol['Start_Price'] = $historyTicker24hr[$key]['askPrice'];
      }




      $n = $i == 0?0:$i-1;

      $arrayPrice[$i]['time'] = $historyTicker24hr[$key]['closeTime'];
      $today = getdate($historyTicker24hr[$key]['closeTime']/1000);
      // $Bin->show($today);

      $arrayPrice[$i]['time_m'] = $today['minutes'];
      $arrayPrice[$i]['age'] = date("H:i:s", mktime(0, 0, $time - $historyTicker24hr[$key]['closeTime']/1000));
      $arrayPrice[$i]['-'] = '-';

      $arrayPrice[$i]['bidPrice'] = $historyTicker24hr[$key]['bidPrice'];

      $arrayPrice[$i]['askPrice'] = $historyTicker24hr[$key]['askPrice'];

      $askPrice_sub = bcsub($historyTicker24hr[$key]['askPrice'], $arrayPrice[$n]['askPrice'], 8);
      $arrayPrice[$i]['askPrice_sub_p'] = bcmul(bcdiv($askPrice_sub00, $arrayPrice[$n]['askPrice'], 8), 100, 8);

      $askPrice_sub0 = bcsub($historyTicker24hr[$key]['askPrice'],$arrayPrice[0]['askPrice'], 8);
      $arrayPrice[$i]['askPrice_sub_p_0'] = bcmul(bcdiv($askPrice_sub0, $arrayPrice[0]['askPrice'], 8), 100, 8);




      $arrayPrice[$i]['*'] = '***';
      $arrayPrice[$i]['lastPrice'] = $historyTicker24hr[$key]['lastPrice'];

      $lastPrice_sub = bcsub($historyTicker24hr[$key]['lastPrice'], $arrayPrice[$n]['lastPrice'], 8);
      $arrayPrice[$i]['askPrice_sub_p'] = bcmul(bcdiv($lastPrice_sub, $arrayPrice[$n]['lastPrice'], 8), 100, 8);

      $lastPrice_sub0 = bcsub($historyTicker24hr[$key]['lastPrice'],$arrayPrice[0]['lastPrice'], 8);
      $arrayPrice[$i]['lastPrice_sub_p_0'] = bcmul(bcdiv($lastPrice_sub0, $arrayPrice[0]['lastPrice'], 8), 100, 8);







      $arrayPrice[$i]['**'] = '***';


      $arrayPrice[$i]['volume'] = $historyTicker24hr[$key]['volume'];
      $arrayPrice[$i]['volume_n'] = $arrayPrice[$n]['volume'];

      $arrayPrice[$i]['volume_sub'] = bcsub($arrayPrice[$i]['volume_n'],$arrayPrice[$i]['volume'], 2);
      $arrayPrice[$i]['***'] = '***';

      $arrayPrice[$i]['askQty'] = $historyTicker24hr[$key]['askQty'];
      $arrayPrice[$i]['bidQty'] = $historyTicker24hr[$key]['bidQty'];
      $arrayPrice[$i]['lastQty'] = $historyTicker24hr[$key]['lastQty'];

      $arrayPrice[$i]['spred'] = $spred = bcsub($arrayPrice[$i]['askPrice'], $arrayPrice[$i]['bidPrice'], 8);
      $arrayPrice[$i]['spred_p'] = bcmul(bcdiv($spred, $arrayPrice[$i]['bidPrice'], 8), 100, 8);

      $i++;







      $tt[] = $symbol ;
}
    $symbol['SUM_askQty'] = array_sum(array_column($arrayPrice, 'askQty'));
    $symbol['SUM_bidQty'] = array_sum(array_column($arrayPrice, 'bidQty'));
    $symbol['SUM_lastQty'] = array_sum(array_column($arrayPrice, 'lastQty'));

// echo $value['symbol'],"<br/>";
// $Bin->showArrayTable($arrayPrice);
// $Bin->showArrayTable($tt);

$Control_p = bcsub($symbol['Start_Price'], $symbol['Control_Price'], 8);
$symbol['Control_p'] = bcmul(bcdiv($Control_p, $symbol['Control_Price'], 8), 100, 8);

$Start_p = bcsub($symbol['askPrice'], $symbol['Start_Price'], 8);
$symbol['Start_p'] = bcmul(bcdiv($Start_p, $symbol['Start_Price'], 8), 100, 8);



$S_max_p = bcsub($symbol['Start_Price'], $symbol['max'],  8);
$symbol['S_max_p'] = bcmul(bcdiv($S_max_p, $symbol['max'], 8), 100, 8);

$S_min_p = bcsub($symbol['min'], $symbol['Start_Price'], 8);
$symbol['S_min_p'] = bcmul(bcdiv($S_min_p, $symbol['min'], 8), 100, 8);


$min_max = bcsub($symbol['max'], $symbol['min'], 8);
$symbol['min_max_p'] = bcmul(bcdiv($min_max, $symbol['min'], 8), 100, 8);

$trend_min = bcsub($value['askPrice'], $symbol['min'], 8);
$symbol['trend_min_p'] = $trend_min_p = bcmul(bcdiv($trend_min, $symbol['min'], 8), 100, 8);

$trend_max = bcsub($value['askPrice'], $symbol['max'], 8);
$symbol['trend_max_p'] = $trend_max_p = bcmul(bcdiv($trend_max, $symbol['max'], 8), 100, 8);

$symbol['time'] = $value['closeTime'];


  // ПОКУПКА
  //####################################################################################################################################################
  //###########################        #############################


   //####################################################################################################################################################
    $buy=0;
    //

    if (-1==bccomp($symbol['Control_p'], -0.3, 8) && 1==bccomp($symbolConfig[$value['symbol']]['top_Price'], 1.002, 8)) {
        $symbol['Control'] .= '+';
        $buy++;
    }else{
      $symbol['Control'] .= '-';
    }
    //
    if (-1==bccomp($symbol['S_max_p'], $symbolConfig[$value['symbol']]['Op_Low_p'], 8) && -1==bccomp($symbol['S_max_p'], -0.5, 8)) {
        $symbol['Control'] .= '+';
        $buy++;
    }else{
      $symbol['Control'] .= '-';
    }
    //
    // if (1==bccomp($symbol['Start_p'], 0.001, 8))  {
    //     $symbol['Control'] .= '+';
    //     $buy++;
    // }else{
    //   $symbol['Control'] .= '-';
    // }
    //
    if (0==bccomp($symbol['lastPrice'], $symbol['bidPrice'], 8))  {
        $symbol['Control'] .= '+';
        $buy++;
    }else{
      $symbol['Control'] .= '-';
    }
    //
    if (!in_array($symbol['asset'], $open))   {
        $symbol['Control'] .= '+';
        $buy++;
    }else{
        $symbol['Control'] .= '-';
    }
    //
    if (count($open)<50)   {
        $symbol['Control'] .= '+';
        $buy++;
    }else{
      $symbol['Control'] .= '-';
    }
    // if (-1== bccomp((string)$price_loss_p, (string)abs($lossМargin_p), 8))  {
    //     $symbol['stop_loss_p_st']= '+';
    //     $buy++;
    // }
    //


$symbol['status']= $buy;


    if ($buy>=5) {
        $symbol['statusTREND']= 'TEST BUY';
                  //
                  // if (-1==bccomp($symbol['Start_p'], -1, 8)) {
                  //   $priceBUY = $Bin->round_min(bcmul($value['bidPrice'], 0.999, 8), $symbolInfo['0']['filters'][0]['minPrice']);
                  // }else{

                  // }
            $priceBUY = $value['askPrice'];

                      //параметры ПОКУПКА LIMIT GTC
                      // $symbol['statusTREND']= 'BUY_LIMIT';
                      // $Params_BUY = array('symbol'=>$value['symbol'],
                      //                 'side' => 'BUY',
                      //                 'type' => 'LIMIT',
                      //                 'quantity' => $Bin->round_min(bcdiv($trade_limit, bcmul($value['askPrice'], $kurs['kursUSD'], 8), 8), $symbolInfo['0']['filters'][2]['minQty']),
                      //                 'timeInForce' => 'GTC',
                      //                 'price' => $priceBUY);

                      //параметры ПОКУПКА LIMIT IOC
                      $Params_BUY = array('symbol'=>$value['symbol'],
                                      'side' => 'BUY',
                                      'type' => 'LIMIT',
                                      'quantity' => $Bin->round_min(bcdiv($trade_limit, bcmul($value['askPrice'], $kurs['kursUSD'], 8), 8), $symbolInfo['0']['filters'][2]['minQty']),
                                      'timeInForce' => 'IOC',
                                      'recvWindow' => '30000',
                                      'price' => $priceBUY);

                      //параметры ПОКУПКА OCO
                      // $Params_BUY = array('symbol'=>$value['symbol'],
                      //                 'side' => 'BUY',
                      //                 'quantity' => $Bin->round_min(bcdiv($trade_limit, bcmul($value['askPrice'], $kurs['kursUSD'], 8), 8), $symbolInfo['0']['filters'][2]['minQty']),
                      //                 'price' => $Bin->round_min(bcmul($value['bidPrice'], 0.995, 8), $symbolInfo['0']['filters'][0]['minPrice']),
                      //                 'stopPrice' => $value['askPrice'],
                      //                 'timeInForce' => 'GTC');

                      //масив параметров BUY_MARKET
                      // $Params_BUY = array('symbol'=>$value['symbol'],
                      //                   'side' => 'BUY',
                      //                   'type' => 'MARKET',
                      //                   'quantity' => $Bin->round_min(bcdiv($trade_limit, bcmul($value['askPrice'], $kurs['kursUSD'], 8), 8),$symbolInfo['0']['filters'][2]['minQty']),
                      //                   'timeInForce' => 'IOC',
                      //                   'price' => $priceBUY);

                      //параметры ПОКУПКА OCO
                      $Params_SELL_OCO = array('symbol'=>$value['symbol'],
                                        'side' => 'SELL',
                                        'quantity' => $Bin->round_min($order['executedQty'], $symbolInfo['0']['filters'][2]['minQty']),
                                        'price' => $Bin->round_min(bcmul($priceBUY, $symbolConfig[$value['symbol']]['top_Price'], 8), $symbolInfo['0']['filters'][0]['minPrice']),
                                        'stopPrice' => $Bin->round_min(bcmul($priceBUY, $symbolConfig[$value['symbol']]['top_S_Price'], 8), $symbolInfo['0']['filters'][0]['minPrice']),
                                        'stopLimitPrice' => $Bin->round_min(bcmul($priceBUY, $symbolConfig[$value['symbol']]['top_SL_Price'], 8), $symbolInfo['0']['filters'][0]['minPrice']),
                                        'stopLimitTimeInForce' => 'GTC');

                      // $Bin->show($Params_BUY);

                          $testOrder[$value['closeTime']]['Time'] =$value['closeTime'];
                          // $testOrder[$value['closeTime']]['symbol'] =$value['symbol'];
                          $testOrder[$value['closeTime']]['asset'] = $symbolInfo['0']['baseAsset'];
                          $testOrder[$value['closeTime']]['USDaskPrice'] = $symbol['USDaskPrice'] ;
                          $testOrder[$value['closeTime']]['**'] = '***';
                          $testOrder[$value['closeTime']] += $Params_BUY;
                          $testOrder[$value['closeTime']]['***'] = '***';
                          // $testOrder[$value['closeTime']] += $Params_SELL_OCO;
                          $testOrder[$value['closeTime']]['M_Price'] = $Params_SELL_OCO['price'];
                          $testOrder[$value['closeTime']]['SL_Price'] = $Params_SELL_OCO['stopLimitPrice'];

                          $testOrder[$value['closeTime']]['status'] = '';
                          $testOrder[$value['closeTime']]['statusTime'] = '';

                            //
                            if ($orderBUY == '1' && count($orderOpen)==0) {

                                //если создаем лемитные ордера то проверяем и удаляем устаревшие
                                $orderOpen = array();
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

                                //отправляем BUY ордер
                                if ($order = $Bin->orderNEW($Params_BUY)) {
                                    $symbol['statusTREND'] = 'BUY_LIMIT NO';

                                    if (0 != bccomp((string)$order['executedQty'], (string)0, 8)) {
                                      $symbol['statusTREND'] = 'BUY_LIMIT';
                                      $Params_SELL_OCO['quantity'] = $order['executedQty'];

                                        //при успехе BUY выставляем ОСО ордера
                                        if ($orderSELL_OCO == '1') {
                                            if ($orderOCO = $Bin->OCO($Params_SELL_OCO)) {
                                              $symbol['statusTREND'] = 'BUY_LIMIT + SELL_OCO';
                                            }
                                        }

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

}//конец цикла 1

//Сохраняем тестовых закупок
$Bin->saveFile($testOrder, $filetestOrder);
$Bin->saveFile($symbolConfig, $filesymbolConfig);


//Сохраняем историю.
// $Bin->saveFile($historyTicker24hr, $filehistoryTicker24hr);

//Сохраняем историю покупок
// $Bin->saveFile($historyBUY, $filehistoryBUY);

//Смотрим настройки
// $Bin->show($settings);

//ОТОБРАЛ

// //Сортируем и смотрим

// if (!$select = array_values($Bin->multiSearch($select, array('status' => 3, 'status' => 2)))) {
//   // if (count($select) == 0) {
//   //   $select = array_values($Bin->multiSearch($select, array('status' => 3)));
//   // }
// }
usort($select, function($a, $b) {
    return abs($b['status']) - abs($a['status']);
});

$symbol = array();
$test = array('symbol' => '', 'countALL' => 0, 'count___' => 0, 'countProfit' => 0, 'sumProfit' => 0, 'countSL' => 0, 'sumSL' => 0, 'balans' => 0);



$countALL = 0;
$count___ = 0;

$countMARGIN = 0;
$sumMARGIN = 0;

$countSL = 0;
$sumSL = 0;

$sumMaker = 0;
$sumTaker = 0;



foreach ($testOrder as $key => $value) {

    if (!array_key_exists($value['symbol'], $symbol)) {
      $symbol[$value['symbol']] = $test;
      $symbol[$value['symbol']]['symbol'] = $value['symbol'];
    }
    $symbol[$value['symbol']]['countALL'] ++;
    $countALL ++;

    $BUY = bcmul($value['quantity'], $value['price'], 8);
    $sumTaker += $taker =  bcmul($BUY, $tradeFeeKom[$value['symbol']]['taker'], 8);
    if ($value['status']=='') {
        $testOrder[$key]['result'] =  '';
        $count___ ++;
        $symbol[$value['symbol']]['count___'] ++;

    }
    if ($value['status']=='MARGIN') {
        $MARGIN = bcmul($value['quantity'], $value['M_Price'], 8);
        $sumMaker += $maker =  bcmul($MARGIN, $tradeFeeKom[$value['symbol']]['maker'], 8);
        $sumMARGIN += $testOrder[$key]['result'] = bcsub($MARGIN, $BUY, 8);
        $countMARGIN++;
        $symbol[$value['symbol']]['countProfit'] ++;
        $symbol[$value['symbol']]['sumProfit'] += $testOrder[$key]['result'];
    }
    if ($value['status']=='SL') {
        $SL = bcmul($value['quantity'], $value['SL_Price'], 8);
        $sumMaker += $maker =  bcmul($SL, $tradeFeeKom[$value['symbol']]['maker'], 8);
        $sumSL += $testOrder[$key]['result'] =  bcsub($SL, $BUY,  8);
        $countSL++;
        $symbol[$value['symbol']]['countSL'] ++;
        $symbol[$value['symbol']]['sumSL'] += $testOrder[$key]['result'];
    }
    $symbol[$value['symbol']]['balans'] = $symbol[$value['symbol']]['sumProfit'] + $symbol[$value['symbol']]['sumSL'];

}
foreach ($symbol as $key => $value) {
  $symbolConfig[$value['symbol']] += $symbol[$value['symbol']];
}

// $WWW =  array('symbol' => 'ИТОГО', 'countALL' => $countALL, 'count___' => $count___, 'countProfit' => $countMARGIN, 'sumProfit' => $sumMARGIN, 'countSL' => $countSL, 'sumSL' => $sumSL, 'balans' => $sumMARGIN+$sumSL);


echo "<br/>ВСЕГО ", count($testOrder), " открыто ", $count___, '<br/>';
echo  "комисия всего ", round($sumTaker+$sumTaker, 2), " Taker ", round($sumTaker, 2), " Maker ", round($sumMaker, 2), '<br/>';
echo "Profit -> ", $countMARGIN, ' -> ', $sumMARGIN, '<br/>';
echo "SL -> ", $countSL, ' -> ',   $sumSL, '<br/>';
echo "БАЛАНС ", $sumMARGIN+$sumSL, '<br/>';
// $Bin->show($open);

// $Bin->showArrayTable($WWW);

// $Bin->showArrayTable($symbol);

usort($symbolConfig, function($a, $b) {
    return $b['balans']*1000 - $a['balans']*1000;
});
$Bin->showArrayTable($symbolConfig);

$Bin->showArrayTable(array_reverse($testOrder));

echo "<br/>ОТОБРАЛ ", count($select), '<br/>';
$Bin->showArrayTable($select);
// echo "<br/>Открыто ", count($order), '<br/>';
// $show = array();
// foreach ($testOrder as $key => $value) {
//   if (in_array($value['symbol'], $show)) continue;
//  $array_symbol = array_values($Bin->multiSearch($testOrder, array('symbol' => $value['symbol'])));
//  $show[]=$value['symbol'];
//  $Bin->showArrayTable($array_symbol);
// }




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
echo 'Обем памяти: ', (memory_get_usage() - $mem_start)/1000000, ' мегабайта.<br/>';


exit();
?>