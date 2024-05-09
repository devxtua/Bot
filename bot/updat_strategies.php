<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);


//биржа
$exchange = 'binance';
$dir_bookTicker = __DIR__ . '/file/historyBookTicker/';


//зацикливаем процес
while (true):

    //ВРЕМЯ ПОЗВОЛЯЕТ ПЕРЕЧИТІВАЕМ  файлы
    require_once __DIR__ . '/model/binance_c.php';
    require_once __DIR__ . '/model/functions_c.php';
    require_once __DIR__ . '/model/indicators_c.php';
    require_once __DIR__ . '/model/user_c.php';
    // require "./libraries/binance_api/vendor/autoload.php";

    $Users = new Users('/home/pas/bot/strategies/');
    $Bin = new Binance('/home/pas/bot/file/');
    $Indicators = new Indicators();

    //читаем общий масив всех индикаторов
    $all_indicator_file = __DIR__. '/file/all_indicator.txt';
    if (!$all_indicator = Functions::readFile($all_indicator_file)) {
        $all_indicator = [];
    }

//------------------------------------------------------------------------
    // foreach (['30m', '1h', '2h', '4h', '6h', '8h', '12h', '1d', '3d', '1w'] as $keyInt => $Interval) {
    //     foreach ($Bin->ticker24hr as $key => $value) {

    //         $klines = $Bin->klines(['symbol' => $value['symbol'], 'interval' => $Interval]);
    //         //получаем индикаторы последней  свечи
    //         $indicators_symbol += $Indicators->all_indicator($value['symbol'], $Interval, $klines);

    //     }

    //     //получаем аналитику индикаторов
    //     $funded_klines = $Bin->funded_klines($strateg);
    //     $analytics_indicators =  Functions::analytics_indicators($strateg, $funded_klines);
    //     foreach ($analytics_indicators as $key => $val) {
    //         $indicators[$key] = $val;
    //     }


    //     sleep(1);
    // }




//------------------------------------------------------------------------
    foreach ($Bin->ticker24hr as $key => $value) {
        //Получаем информацию о symbol и исключаем неактивные пары
        if (!$symbolInfo = Functions::multiSearch($Bin->exchangeInfo['symbols'], array('symbol' => $value['symbol'], 'status'=>'TRADING'))) continue;

        //Исключаем если база не USDT
        if ($symbolInfo[0]['quoteAsset']!='USDT') continue;

        //получаем статистику каждого интервала
        //'1m','3m','5m','15m','30m','1h','2h','4h','6h','8h','12h','1d','3d','1w','1M'
        $indicators_symbol = $indicators = [];        
        foreach (['30m','1h','2h','4h','6h','8h','12h','1d', '3d', '1w'] as $keyInt => $Interval) {
        	$strateg['symbol'] = $value['symbol'];
        	$strateg['interval'] = $Interval;

            //получаем аналитику индикаторов
            $funded_klines = $Bin->funded_klines($strateg);
        	$analytics_indicators =  Functions::analytics_indicators($strateg, $funded_klines);
        	foreach ($analytics_indicators as $key => $val) {
        		$indicators[$key] = $val;
        	}

            //получаем индикаторы последней  свечи
            $indicators_symbol += $Indicators->all_indicator($strateg['symbol'], $strateg['interval'], $funded_klines);
			sleep(1);
        }

		//сохраняем файл аналитику в файл
        $analytics_file = __DIR__.'/file/analyticsSymbol/'.$value['symbol'].'.txt';
        Functions::saveFile($indicators, $analytics_file);
		chown($analytics_file, 'pas');

        //сохраняем масив всех индикаторов в общий файл
        $all_indicator[$value['symbol']] = $indicators_symbol;
        Functions::saveFile($all_indicator, $all_indicator_file);
		chown($all_indicator_file, 'pas');
    }


    //***** обновление настроек стратегий
    // foreach ($Users->user_arrey as $key => $user) {

    //     $Bin->initialization($user[$exchange]['config']['KEY'], $user[$exchange]['config']['SEC']);

    //     //***** проверяем наличие стратегий  (если нет пропускаем)
    //     if (count($user[$exchange]['strategies'])==0) goto end;

    //     //Сортируем стратегии по symbols intervals
    //     $strategies = &$user[$exchange]['strategies'];
    //     $symbols  = array_column($strategies, 'symbol');
    //     $intervals = array_column($strategies, 'interval');
    //     array_multisort($symbols, SORT_ASC, $intervals, SORT_DESC, $strategies);

    //     //***** проверяем стратегии на покупку
    //     foreach ($strategies as $key => &$strateg) {

    //     }
	// 	sleep(1);
    //     end: // стратегий нет пропустили пользователя
    // }
endwhile;
?>