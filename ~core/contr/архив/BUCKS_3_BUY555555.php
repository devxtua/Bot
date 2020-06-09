<?php



sleep(3);
$start = microtime(true);
$mem_start = memory_get_usage();
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
echo "BUCKS_3_BUY<br/>";

//ВКЛЮЧИТЬ создания ордеров покупку
$orderBUY = 0; //0-нет, 1 да
$settings['trade_limit'] = $trade_limit = 11;//стартовый лимит закупки
$BUY_OCO = array('Price' => 1.01, 'S_Price' => 0.986, 'SL_Price' => 0.985); //стартовые условия

//Стартовые условия продажи
$orderSELL_OCO = 0; //0-нет, 1 да
$SELL_OCO = array('Price' => 1.01, 'S_Price' => 0.986, 'SL_Price' => 0.985);  //стартовые условия


//КОНТРОЛЬНЫЙ интервал 1m/3m/5m/15m/30m/1h/2h/4h/6h/8h/12h/1d/3d/1w/1M
$settings['IntervalControl'] = $IntervalControl = array('1m','3m','5m','15m','30m','1h','2h','4h','6h','8h','12h','1d','3d','1w','1M');



//словия для покупки последние свечи
$arra_g_trend = array('+000','-000', '0000', '00-0', '0-00', '-0-0', '-+-+', '000-','000+','0-0-','0-0+','-00-');

//условия OCO  при B



//условия OCO




//только торговые пары
// $symbolBUY =  array( 'BTCUSDT', 'ETHUSDT', 'EOSUSDT', 'XRPUSDT');
// $symbolBUY =  array('ETHUSDT');

$symbolBUY =  array('WINUSDT','NPXSUSDT','DENTUSDT','BTTUSDT','COCOSUSDT','HOTUSDT','MFTUSDT','ERDUSDT','KEYUSDT','MBLUSDT','ANKRUSDT','TFUELUSDT','DREPUSDT','FUNUSDT','CELRUSDT','IOTXUSDT','TROYUSDT','ONEUSDT','IOSTUSDT','VETUSDT','ZILUSDT','MITHUSDT','DOCKUSDT','FTMUSDT','GTOUSDT','TCTUSDT','COSUSDT','ARPAUSDT','CHZUSDT','TRXUSDT','VITEUSDT','NKNUSDT','BTSUSDT','MATICUSDT','RVNUSDT','FETUSDT','PERLUSDT','DUSKUSDT','ADAUSDT','XLMUSDT','CTXCUSDT','ENJUSDT','ONGUSDT','THETAUSDT','STXUSDT','AIONUSDT','IOTAUSDT','XRPUSDT','BATUSDT','WANUSDT','ZRXUSDT','NULSUSDT','BNTUSDT','MTLUSDT','ALGOUSDT','ICXUSDT','STRATUSDT','TOMOUSDT','BEAMUSDT','RLCUSDT','ONTUSDT','NANOUSDT','OMGUSDT','KAVAUSDT','HCUSDT','QTUMUSDT','XTZUSDT','EOSUSDT','ATOMUSDT','LINKUSDT','MCOUSDT','ETCUSDT','NEOUSDT','BNBUSDT','ZECUSDT','LTCUSDT','XMRUSDT','DASHUSDT','ETHUSDT','BCHUSDT','BTCUSDT');

//минимальное количество сделок за 24 час
$settings['countTrends'] = $countTrends= 1000;
//минимальное обем продаж за 24 час
$settings['quoteVolume'] = $quoteVolume = 500000;
// максимальный % изминения цены за 24 час
$settings['priceChangePercent'] = $priceChangePercent= array(0, 50);


//НАСТРОЙКИ БЕРЕМ С ПРОДАЖИ
//Увеличить моментальную потерю маржи0
$settings['addPrice_loss_p'] = $addPrice_loss_p = 0;
//потеря маржи % при Stop Loss
$settings['lossМargin_p'] = $lossМargin_p = -0.2;


//минимальный процент  Волонтильность за последние countKlines свечей
$settings['BUYcontrolChangePrice_p'] = $BUYcontrolChangePrice_p = 1;
//процент контроля роста цены от минимальной для покупки
$settings['controltopPrice_p'] = $controltopPrice_p = 0.3;

//Определяем базове валют
$base['USDT']= array('minBalans'=>100, 'minPriceBuy'=>0.00000100);
// $base['BTC']= array('minBalans'=>0.5, 'minPriceBuy'=>0.00000100);
$base['BNB']= array('minBalans'=>3, 'minPriceBuy'=>0.00000100);
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
$time = time();
$today = getdate();
// $Bin->show($today);
echo 'ВРЕМЯ : ', '  <font size="20" color=blue face="Arial">', date("H:i:s", time()), '</font> <br/>';

//Получить изминения за 24 часса
$ticker24hr = $Bin->ticker24hr();
// $Bin->show($ticker24hr[0]);

//Получить свободный баланс АКТИВОВ
if (!$accountBalance = $Bin->accountBalance($ticker24hr, $base)){
    die('БАЛАНС НЕ ПОЛУЧЕН');
}
$Bin->showArrayTable($accountBalance);
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
      $Bin->showArrayTable($orderOPEN);
}



// die();

//сохраняем файлы с именем времени первого символа
$fileTicker24hr = 'E:\binance\ticker24hr\\'.$ticker24hr[0]['closeTime'].'.txt';
$Bin->saveFile($ticker24hr, $fileTicker24hr);

//Читаем нужные файлы  и удаляем старые***********
//сканируем директорию с файлами
$dir = 'E:\binance\ticker24hr\\';
$files = array_reverse(scandir($dir, 1));
echo 'Итого файлов: ', count($files),  "<br/><br/>";




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
        if (!$symbolInfo = array_values($Bin->multiSearch($exchangeInfo['symbols'], array('symbol' => $value['symbol'], 'status'=>'TRADING'))) ) continue;
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
                    //находим моментальную потерю цены при покупке
                    $takerask =  bcmul($value['askPrice'], $tradeFeeKom[$value['symbol']]['taker'], 8);
                    $price_loss = bcsub($value['askPrice'], bcsub($value['bidPrice'], $takerask, 8), 8);
                    $price_loss_p = bcmul(bcdiv($price_loss, $value['bidPrice'], 8),100, 8);
        // if (1== bccomp((string)$price_loss_p, (string)abs($lossМargin_p), 8))  continue;

//=================================================================================================
//=================================================================================================
//=================================================================================================


        //   $temp = $t =array();
        //   foreach (array_reverse($IntervalControl) as $key => $interval) {

        //         if ($klines = array_reverse($Bin->klines(array('symbol'=>$value['symbol'], 'interval' => $interval)))) {
        //           // $Bin->showArrayTable($klines);

        //            $temp0 = $tt = array();

        //           //устанавливаем сколько свечей анализируем если они есть
        //           $k_n = 5;
        //           $N = count($klines)<$k_n?count($klines):$k_n;
        //           $klines = array_slice($klines, 0, $k_n);
        //           $Bin->showArrayTable($klines);
        //           if ($trend = array_reverse($Bin->trend($klines))) {
        //             // echo $interval, "<br/>";
        //             // $Bin->show($trend);
        //           }



        //           for ($i=0; $i < $N; $i++) {
        //               $tt['symbol'] = $value['symbol'];
        //               $tt['asset']=$symbolInfo[0]['baseAsset'];
        //               $tt['base']=$symbolInfo[0]['quoteAsset'];
        //               $tt['interval'] = $interval;
        //               // $today = getdate($filemtime);
        //               $tt['dateOpen'] = date("Y-m-d H:i:s", $klines[$i][0]/1000);
        //               $tt['Open'] =  $klines[$i][1];
        //               $tt['High'] = $klines[$i][2];
        //               $tt['Low'] = $klines[$i][3];
        //               $tt['Close'] = $klines[$i][4];
        //               $tt['Volume'] = $klines[$i][5];
        //               $tt['dateClose'] = date("Y-m-d H:i:s", $klines[$i][6]/1000);

        //               $tt['*.*'] = '----';

        //               //отбераем последний период
        //               if ($i==0) {
        //                   //находим изминения последней свечи
        //                   $Open_Close = bcsub($klines[0][4], $klines[$i][1], 8);
        //                   $tt['Open_Close_p'] = bcmul(bcdiv($Open_Close, $klines[$i][1], 8), 100, 8);

        //                   //находим процент увеличения цены от минимальной
        //                   $High_Close = bcsub($klines[$i][4], $klines[$i][2], 8);
        //                   $tt['High_Close_p_⇓'] =  bcmul(bcdiv($High_Close, $klines[$i][2], 8),100, 8);


        //                   $Low_Close = bcsub($klines[$i][4], $klines[$i][3], 8);
        //                   $tt['Low_Close_p_⇑'] = bcmul(bcdiv($Low_Close, $klines[$i][3], 8),100, 8);

        //                   $tt += $trend;
        //                   $temp[]=$tt;
        //               }

        //               $tt['*..*'] = '----';

        //               // $MaxMin = bcsub($klines[$i][2], $klines[$i][3], 8);
        //               // $tt['MaxMin_p'] =  bcmul(bcdiv($MaxMin, $klines[$i][3], 8),100, 8);

        //               // //находим процент увеличения цены от минимальной
        //               // $top = bcsub($klines[0][4], $klines[$i][3], 8);
        //               // $tt['min_p_⇑'] = bcmul(bcdiv($top, $klines[$i][3], 8),100, 8);

        //               // $tt['*...*'] = '----';
        //               //находим процент увеличения цены от минимальной


        //               // $temp0[]=$tt;


        //           }
        //         // $Bin->showArrayTable($temp0);

        //         }
        //     // }else{
        //     //   // $klines_max_min  = $historyKlines[$value['symbol']]['klines_max_min'];
        //     // }
        //     // $temp[]=$t;
        //   }
        // echo "Последние свечи<br/>";
        // $Bin->showArrayTable($temp);

    //****************************
        $symbol = array();
        $symbol['symbol'] = $value['symbol'];
        $symbol['asset'] = $symbolInfo['0']['baseAsset'];
        $symbol['askPrice'] =$value['askPrice'];
        // $symbol['klinesPrice'] = '';




               $interval = '1m';

                if (!$klin = array_reverse($Bin->klines(array('symbol'=>$value['symbol'], 'interval' => $interval, 'limit' => 1000)))) {
                  $symbol['statusTREND'] = 'NOT klines';
                  continue;
                }

                $fileklines = 'E:\binance\symbols\\'.$value['symbol'].'_klines_'.$interval.'.txt';
                if ($klines = $Bin->readFile($fileklines)){

                   foreach ($klin as $k => $v) {
                      $time = array_column($klines, 0);
                      if (!in_array($v[0], $time)) {
                        array_unshift($klines, $v);
                      }
                   }
                }else{
                    $klines = $klin;

                }
                $Bin->saveFile($klines, $fileklines);
                $symbol['klines'] = count($klines);



                //находим настройки
                  $top = $bottom = array();
                  $topsumVolume = $bottomsumVolume = 0;
                   foreach ($klin as $keyW => $valueW) {
                      //если top
                      if (-1== bccomp((string)$valueW[1], (string)$valueW[4], 8)) {
                          $Op_High = bcsub($valueW[2], $valueW[1], 8);
                          $top[$keyW]['Op_High_p'] =  bcmul(bcdiv($Op_High, $valueW[1], 8), 100, 8);
                          $top[$keyW]['Op_High_k'] =  bcdiv($valueW[2], $valueW[1], 8);

                          $topsumVolume += $valueW[10];
                      }

                      //если bottom
                      if (1== bccomp((string)$valueW[1], (string)$valueW[4], 8)) {
                          $Op_Low = bcsub($valueW[3], $valueW[1], 8);
                          $bottom[$keyW]['Op_Low_p'] = bcmul(bcdiv($Op_Low, $valueW[1], 8), 100, 8);
                          $bottom[$keyW]['Op_Low_k'] = bcdiv($valueW[3], $valueW[1], 8);

                          $bottomsumVolume += $valueW[10];
                      }

                  }

                  usort($top, function($a, $b) {
                    // echo ($a['Op_High_k']*1000000000) - ($b['Op_High_k']*1000000000), "<br/>";
                      return abs($b['Op_High_k']*1000000000) - abs($a['Op_High_k']*1000000000);
                  });

                  // echo $value['symbol'], ' ', " top ", '<br/>';
                  // $top = array_slice($top, 0, 20);
                  // $Bin->showArrayTable($top);


                  usort($bottom, function($a, $b) {
                    // echo abs($b['Op_Low_k']*1000000000) - abs($a['Op_Low_k']*1000000000), "<br/>";
                      return abs($b['Op_Low_k']*1000000000) - abs($a['Op_Low_k']*1000000000);
                  });
                  // echo $value['symbol'], ' ', " bottom ", '<br/>';
                  // $bottom = array_slice($bottom, 0, 20);
                  // $Bin->showArrayTable($bottom);

                  $symbol['n'] = 1;
                  // $symbol['persent'] = $bottom[$symbol['n']]['Op_Low_p'];
                  // $symbol['coefficient'] = $bottom[$symbol['n']]['Op_Low_k'];
                  // $symbol['f'] = bcsub(1, $symbol['coefficient'], 8);
                  // $symbol['coef_profit'] = bcadd(1.005, $symbol['f'], 8);
                  // $symbol['coef_sl'] = bcsub($symbol['coefficient'], $symbol['f'], 8);

                  $symbol['coef_profit'] = 1.01;
                  $symbol['coef_sl'] = 0.9;


                  $symbol['topAVGVolume'] = bcmul(bcdiv($topsumVolume, count($top), 0), 2, 0);


                  $symbol['bottomAVGVolume'] = bcmul(bcdiv($bottomsumVolume, count($bottom), 0), 2, 0);

                  $symbol['thresholdBuy'] = 0.9985;
                  $symbol['limit'] = 11;
                    $taker = bcmul($symbol['limit'], 0.00075, 8);

                    $sumProfit = bcmul($symbol['limit'], $symbol['coef_profit'], 8);
                    $makerPr = bcmul($sumProfit, 0.00075, 8);
                    $comisPr = bcadd($taker, $makerPr, 8);
                    $margePr = bcsub($sumProfit, $symbol['limit'], 8);
                  $symbol['profit'] = bcsub($margePr, $comisPr, 8);

                    $sumSL = bcmul($symbol['limit'], $symbol['coef_sl'], 8);
                    $makerSL = bcmul($sumSL, 0.00075, 8);
                    $comisSL = bcadd($taker, $makerSL, 8);
                    $lossSL = bcsub($symbol['limit'], $sumSL,  8);
                  $symbol['loss'] = -1*bcadd($lossSL, $comisSL, 8);

                  $Bin->show($bot);





                  // $Bin->show($klines[0]);

                    $g_trend = '';
                    $array_klin_p = array();
                    $key_BUY = array();

                    $sum_klin_p = 0;
                    $b = $p = $sl = 0;
                    $klines = array_reverse($klines);

                foreach ($klines as $keyK => $value_klines) {
                      $getdate = getdate($value_klines[0]/1000);
                      $klines[$keyK]['mday'] = $getdate['mday'];
                      $klines[$keyK]['hours'] = $getdate['hours'];
                      $klines[$keyK]['minutes'] = $getdate['minutes'];

                      $klines[$keyK]['klin_c'] = $q = bcdiv($value_klines[4], $value_klines[1], 5);

                      $klines[$keyK]['trend'] = '';
                      $klines[$keyK]['b'] = '';
                      $klines[$keyK]['Price_BUY'] = '';
                      $klines[$keyK]['Price_PROFIT'] = '';
                      $klines[$keyK]['Price_SL'] = '';

                      $klines[$keyK]['profit+'] = '';
                      $klines[$keyK]['SL+'] = '';
                      $klines[$keyK]['key'] = '';
                      $klines[$keyK]['allow_price'] = '';

                      $lastBUY = $klines[$keyK][2];

                     //Проверка открытых ордеров
                      foreach ($key_BUY as $kk => $k) {
                        // echo $k, '<br/>';
                          //продажа profit
                          if (1 == bccomp($value_klines[2], $klines[$k]['Price_PROFIT'], 5) && $klines[$k]['key'] == '') {
                              $testOrderT[$k]['profit+'] = $p++;
                              $testOrderT[$k]['key'] = $keyK;
                              unset($key_BUY[$kk]);

                          }
                          //продажа sl
                          if (-1 == bccomp($value_klines[3], $klines[$k]['Price_SL'], 5) && $klines[$k]['key'] == '') {
                              $testOrderT[$k]['SL+'] = $sl++;
                              $testOrderT[$k]['key'] = $keyK;
                              unset($key_BUY[$kk]);
                          }

                          //выберае закупочную цену последнего окрытого ордера
                          if ($klines[$k]['key'] == '') {
                            // $klines[$keyK]['lastBUY'] = $lastBUY = $klines[$k]['Price_BUY'];
                            $klines[$keyK]['allow_priceBUY'] = $lastBUY = bcmul($klines[$k]['Price_BUY'], $symbol['thresholdBuy'], 8);
                          }
                      }


                      //##### ЗАКУПКА при TOP И выставление ОСО PROFIT SL
                      if (1 == bccomp($value_klines[10], $symbol['topAVGVolume'], 5)   &&  1 == bccomp($klines[$keyK]['klin_c'], 1, 5) && -1 == bccomp($klines[$keyK][4], $lastBUY, 5)) {
                        $klines[$keyK]['trend'] = 'top';
                        $klines[$keyK]['b'] = $b++;
                        $klines[$keyK]['Price_BUY'] = $klines[$keyK][4];
                        $klines[$keyK]['Price_PROFIT'] = bcmul($klines[$keyK][4], $symbol['coef_profit'], 3);
                        $klines[$keyK]['Price_SL'] = bcmul($klines[$keyK][4], $symbol['coef_sl'], 3);
                        $key_BUY[] = $keyK;

                      }


                      //##### ЗАКУПКА при BOTTOM выставление ОСО PROFIT SL
                      if ( 1 == bccomp($value_klines[10], $symbol['bottomAVGVolume'], 5) &&  -1 == bccomp($klines[$keyK]['klin_c'], 1, 5) && -1 == bccomp($klines[$keyK][4], $lastBUY, 5)) {
                        $klines[$keyK]['trend'] = 'bottom';
                        $klines[$keyK]['b'] = $b++;
                        $klines[$keyK]['Price_BUY'] = $klines[$keyK][4];
                        $klines[$keyK]['Price_PROFIT'] = bcmul($klines[$keyK][4], $symbol['coef_profit'], 3);
                        $klines[$keyK]['Price_SL'] = bcmul($klines[$keyK][4], $symbol['coef_sl'], 3);
                        $key_BUY[] = $keyK;
                      }

                     $testOrderT[] = $klines[$keyK];


                  }// онец цикла  klines

                    // array_multisort($array_klin_p);
                    // $Bin->show($array_klin_p);
                  $symbol['N_all']= $b;
                  $symbol['N_open']= $b-$p-$sl;
                  $symbol['N_profit']= $p;
                  $symbol['SUMprofit']= bcmul($p, $symbol['profit'], 2);
                  $symbol['N_loss']= $sl;
                  $symbol['SUMloss']= bcadd(bcmul($p, $symbol['profit'], 2), bcmul($sl, $symbol['loss'], 2), 2);
                  $symbol['BALANCE']= bcadd(bcmul($p, $symbol['profit'], 2), bcmul($sl, $symbol['loss'], 2), 2);




      // ПОКУПКА
      //####################################################################################################################################################

        // $open = array_column(array_values($Bin->multiSearch($testOrder, array('status' => ''))), 'asset');
       //####################################################################################################################################################

        $buy=0;

        if (in_array($g_trend, $arra_g_trend)) {
            // $symbol['g_trend_st'] = '+';
            $buy++;
        }


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
        if (count($open)<100)   {
            // $symbol['open_count_st']= '+';
            $buy++;
        }


    // $symbol['Yes']= $buy;
    $Params_BUY = $Params_SELL_OCO = array();

    if ($buy=='not') {
        $symbol['statusTREND']= 'TEST BUY';
        //определяем цену за которую хотим  купить
        if (-1==bccomp($symbol['Start_p'], -1, 8)) {
           $priceBUY = $Bin->round_min(bcmul($value['bidPrice'], 0.999, 8), $symbolInfo['0']['filters'][0]['minPrice']);
        }else{
           $priceBUY = $klines[$k_n-1][4];
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
            $orderOpen = array_values($Bin->multiSearch($orderOPEN, array('symbol' => $value['asset'].'USDT', 'type'=>'LIMIT', 'side'=>'BUY')));
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
        // $testOrder[$value['closeTime']]['Time'] =$value['closeTime'];
        // // $testOrder[$value['closeTime']]['symbol'] =$value['symbol'];
        // $testOrder[$value['closeTime']]['asset'] = $symbolInfo['0']['baseAsset'];
        // $testOrder[$value['closeTime']] += $symbol;
        // $testOrder[$value['closeTime']]['**'] = '***';
        // $testOrder[$value['closeTime']]['BUYsymbol'] = $Params_BUY['symbol'];
        // $testOrder[$value['closeTime']]['quantity'] = $Params_BUY['quantity'];
        // $testOrder[$value['closeTime']]['price'] = $Params_BUY['price'];

        // // $testOrder[$value['closeTime']] += $Params_BUY;
        // $testOrder[$value['closeTime']]['***'] = '***';
        // // $testOrder[$value['closeTime']] += $Params_SELL_OCO;
        // $testOrder[$value['closeTime']]['M_Price'] = $Params_SELL_OCO['price'];
        // $testOrder[$value['closeTime']]['SL_Price'] = $Params_SELL_OCO['stopLimitPrice'];

        // $testOrder[$value['closeTime']]['status'] = '';
        // $testOrder[$value['closeTime']]['statusTime'] = '';
        $select[] = $symbol;
}//конец цикла 1

//Сохраняем тестовых закупок
$Bin->saveFile($testOrder, $filetestOrder);



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
$Bin->showArrayTable($select);

echo 'N_all ', array_sum(array_column($select, 'N_all')), "<br/>";
echo 'N_open ', array_sum(array_column($select, 'N_open')), "<br/>";
echo 'N_profit ', array_sum(array_column($select, 'N_profit')), "<br/>";
echo 'N_loss ', array_sum(array_column($select, 'N_loss')), "<br/>";

echo 'BALANCE ', array_sum(array_column($select, 'BALANCE')), "<br/>";




$profit = $countMARGIN =0;
$losses= $countSL =0;
foreach ($testOrder as $key => $value) {
    $testOrder[$key]['interval'] = '';
    $testOrder[$key]['sumBUY'] = '';
    $testOrder[$key]['taker'] =  '';
    $testOrder[$key]['sumSELL'] = '';
    $testOrder[$key]['maker'] = '';
    $testOrder[$key]['result'] = '';

    $testOrder[$key]['sumBUY'] = $sumBUY = bcmul($value['quantity'], $value['price'], 8);
    $testOrder[$key]['taker'] =  bcmul($sumBUY, $tradeFeeKom[$value['symbol']]['taker'], 8);

    if ($value['status']=='MARGIN') {
      $testOrder[$key]['interval'] = round(($testOrder[$key]['statusTime']-$testOrder[$key]['Time'])/1000,0);
      $testOrder[$key]['sumSELL'] = $sumSELL = bcmul($value['quantity'], $value['M_Price'], 8);
      $testOrder[$key]['maker'] =  bcmul($sumSELL, $tradeFeeKom[$value['symbol']]['maker'], 8);
      $profit += $testOrder[$key]['result'] =  bcsub(bcsub(bcsub($sumSELL, $sumBUY, 8), $testOrder[$key]['taker'], 8), $testOrder[$key]['maker'], 8);
      $countMARGIN++;
    }
    if ($value['status']=='SL') {
      $testOrder[$key]['interval'] = round(($testOrder[$key]['statusTime']-$testOrder[$key]['Time'])/1000,0);
      $testOrder[$key]['sumSELL'] =  $sumSELL = bcmul($value['quantity'], $value['SL_Price'], 8);
      $testOrder[$key]['maker'] =  bcmul($sumSELL, $tradeFeeKom[$value['symbol']]['maker'], 8);
      $losses += $testOrder[$key]['result'] =  bcsub(bcsub(bcsub($sumSELL, $sumBUY, 8), $testOrder[$key]['taker'], 8), $testOrder[$key]['maker'], 8);
      $countSL++;
    }
}

echo "<br/>ТЕСТ ордера ", count($testOrder), '<br/>';
echo "MARGIN -> ", $countMARGIN, ' -> ', $profit, '<br/>';
echo "SL -> ", $countSL, ' -> ',   $losses, '<br/>';
echo "БАЛАНС  ", $profit+$losses, '<br/>';
echo "<br/>Открыто ", count($open), '<br/>';

$symbol = array_unique(array_column($testOrder, 'symbol'));
echo 'symbol ', count($symbol), '<br/>';
// $Bin->showArrayTable($symbol);

$g_trend = array_unique(array_column($testOrder, 'g_trend'));
echo 'Стратегий  g_trend ', count($g_trend), '<br/>';
// $Bin->showArrayTable($g_trend);

$analiz =array();
$i =0;
    foreach ($g_trend as $keyGT => $valueGT) {
        $i++;
        // $analiz[$i]['symbol'] = $value;
        $analiz[$i]['g_trend'] = $valueGT;

        $selectMARGIN = array_values($Bin->multiSearch($testOrder, array('g_trend' => $valueGT, 'status' => 'MARGIN')));
            $analiz[$i]['countM'] = count($selectMARGIN);
            $analiz[$i]['profit'] = array_sum(array_column($selectMARGIN, 'result'));

        $selectSL = array_values($Bin->multiSearch($testOrder, array('g_trend' => $valueGT, 'status' => 'SL')));
            $analiz[$i]['countSL'] = count($selectSL);
            $analiz[$i]['losses'] = array_sum(array_column($selectSL, 'result'));


        $analiz[$i]['balans'] = $analiz[$i]['profit'] +  $analiz[$i]['losses'];


        $select_ = array_values($Bin->multiSearch($testOrder, array('g_trend' => $valueGT, 'status' => '')));
            $analiz[$i]['count_'] = count($select_);



// break;
}
usort($analiz, function($a, $b) {
    return abs($b['balans']*100) - abs($a['balans']*100);
});
$Bin->showArrayTable($analiz);

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
echo 'Обем памяти: ', (memory_get_usage() - $mem_start)/1000000, ' мегабайта.<br/>';


exit();
?>