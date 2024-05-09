<?php
ini_set('error_reporting', E_ALL);
require "../model/functions_c.php";
require '../model/binance_c.php';
require_once '../model/indicators_c.php';
$configs = include('../../config/config.php');

$Indicators = new Indicators();
$Bin = new Binance($configs['DIR']);
$Bin->initialization($configs['test_user']['KEY'], $configs['test_user']['SEC']);
if ($symbolInfo = Functions::multiSearch($Bin->exchangeInfo['symbols'], array('symbol' => 'ETHUSDT', 'status' => 'TRADING'))[0])  //не торгуется
Functions::show($symbolInfo);

//общий масив индикаторов
// $file = "../../bot/file/all_indicator.txt";
// if (!$arr =  Functions::readFile($file)) die("Файл не прочитан");
// Functions::showArrayTable_key($arr);


// $file = "../../bot/file/analyticsSymbol/All_analytics.txt";
// if (!$arr =  Functions::readFile($file)) die("Файл не прочитан");
// Functions::showArrayTable_key($arr['ETHDOWNUSDT'], 'ETHDOWNUSDT');

//'1m', '3m', '5m', '15m', '30m', '1h', '2h', '4h', '6h', '8h', '12h', '1d', '3d', '1w', '1M'
// $indicators = [];
// $all_indicator = [];
// foreach (['30m', '1h', '2h', '4h', '6h', '8h', '12h', '1d', '3d', '1w', '1M'] as $keyInt => $Interval) {
// 	$strateg['symbol'] = 'ETHUSDT';
// 	$strateg['interval'] = $Interval;

// 	//получаем funded_klines
// 	// if($funded_klines = $Bin->funded_klines($strateg))
// 	// echo $Interval, '  ->  ', count($funded_klines),  "старт",  date("Y-m-d H:i:s", $funded_klines[0][0] / 1000), " - ", date("Y-m-d H:i:s", end($funded_klines)[6] / 1000), "<br/><br/>";
// 	// $analytics_indicators =  Functions::analytics_indicators($strateg, $funded_klines);

// 	//получаем klines
// 	$klines = $Bin->klines(array(
// 									'symbol' => $strateg['symbol'],
// 									'interval' => $strateg['interval']
// 								));
// 	Functions::show($klines[0], $Interval);

// 	$diff = date_diff($klines[0][0]*1000, $klines[0][6]*1000);
// 	echo $diff->format('%a дней');
// 	die();
// 	// $all_indicator += $Indicators->all_indicator($strateg['symbol'], $strateg['interval'], $klines);


// 	// sleep(1);
// }
// krsort($all_indicator);
// Functions::show($all_indicator, $Interval);



