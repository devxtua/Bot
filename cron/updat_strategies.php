<?php
	$start = $_SERVER['REQUEST_TIME'];
	$mem_start = memory_get_usage();
	ini_set('max_execution_time', 500000);
// header('refresh: 2');
	require "d:/domains/BUCKS/~core/model/binance_c.php";
	require "d:/domains/BUCKS/~core/model/functions_c.php";
	require "d:/domains/BUCKS/~core/model/indicators_c.php";
	require "d:/domains/BUCKS/~core/model/user_c.php";


$Users = new Users();
$Bin = new Binance();
$exchange = 'binance';
$audio = false;


foreach ($Users->user_arrey as $key => $user) {
    $Bin->initialization($user[$exchange]['config']['KEY'], $user[$exchange]['config']['SEC']);






// 	$fileOrder = 'D:\binance\strategies\\'.$user['login'].'_orders.txt';
// 	$symbols = $Bin->exchangeInfo['symbols'];
// 	//Сортируем symbols
// 	$quoteAsset  = array_column($symbols, 'quoteAsset');
// 	$baseAsset = array_column($symbols, 'baseAsset');
// 	array_multisort($quoteAsset,  SORT_ASC, $baseAsset, SORT_ASC,  $symbols);

// 	$fundedAllOrders = [];
// 	foreach ($symbols as $key => $value) {
// 		$symbolAllOrders = [];
// 		$endTime = time()*1000;
// 		$startTime= strtotime("now". " - 1 year")*1000;
// 		do{
// 			if ($all = $allOrders = $Bin->allOrders(array('symbol'=>$value['symbol'], 'startTime'=>$startTime, 'limit'=>1000))){
// 				echo count($allOrders), "string";
// 				foreach ($allOrders as $key => $value) {
// 					if ($value['status'] == 'EXPIRED'|| $value['status'] == 'CANCELED'){
// 						unset($allOrders[$key]);
// 						continue;
// 					}
// 					if (count($symbolAllOrders)>0) {
// 						$orderId = array_column($fundedAllOrders, 'orderId');
// 						if (!in_array($value['orderId'], $orderId)) {
// 						$symbolAllOrders[] = $value;
// 						}
// 					}else{
// 						$symbolAllOrders = $allOrders;
// 					}
// 				}
// 				$startTime = max($allOrders)['time'];

// 			}
// 			echo $startTime, ' -> ', count($allOrders), "<br/>";
// 		}while (count($all)>999);
// 		$fundedAllOrders = array_merge($fundedAllOrders, $symbolAllOrders);

// 		Functions::saveFile($fundedAllOrders, $fileOrder);
// 		echo $value['symbol'], '', count($fundedAllOrders), '<br/>';
// 	}

// echo 'готово';
// 	// Functions::showArrayTable_key($fundedAllOrders, 'fundedAllOrders');
// // die();

// 	$fundedAllOrders = Functions::readFile($fileOrder);
// 	$symbols = array_unique(array_column($fundedAllOrders, 'symbol'));
// 	echo count($symbols), "<br/>";
// 	$symb = [];
// 	foreach ($symbols as $ke => $symbol) {
// 		if ($symbolAllOrders = Functions::multiSearch($fundedAllOrders, array('symbol' => $symbol))){
// 			$symb[$symbol]['symbol'] = $symbol;
// 			$symb[$symbol]['count'] = count($symbolAllOrders);
// 			$symb[$symbol]['open'] = count(Functions::multiSearch($symbolAllOrders, array('status' => 'NEW')));

// 					$sum = 0;
// 				foreach ($symbolAllOrders as $key => $value) {

// 					if ($value['side'] == 'BUY') {
// 						$sum -= $value['cummulativeQuoteQty'];
// 					}else{
// 						$sum += $value['cummulativeQuoteQty'];
// 					}

// 					$symbolAllOrders[$key]['balans'] = round($sum, 2);
// 				}
// 			$symb[$symbol]['balans'] = $symbolAllOrders[$key]['balans'];


// Functions::showArrayTable($symbolAllOrders, 'symbolAllOrders');
// 		}
// 	}

// 	Functions::showArrayTable($symb, '$symb');
die();
}








// Functions::showArrayTable($symbols, 'end_Klin');

// $IntervalControl = array('1m','3m','5m','15m','30m','1h','2h','4h','6h','8h','12h','1d','3d','1w','1M');
// foreach ($Bin->ticker24hr as $key => $ticker) {
// 	        //Получаем информацию о symbol
//         if (!$symbolInfo = Functions::multiSearch($Bin->exchangeInfo['symbols'], array('symbol' => $ticker['symbol'], 'status'=>'TRADING')))  continue;
//                 // Исключаем если база не USDT
//         if ($symbolInfo[0]['quoteAsset']!='USDT') continue;

//         if ($ticker['symbol'] != 'ETHUSDT')  continue;

//         $klines_All = $end_Klin = array();
//           foreach (array_reverse($IntervalControl) as $key => $interval) {

//                 if ($klines = array_reverse($Bin->klines(array('symbol'=>$ticker['symbol'], 'interval' => $interval)))) {
// 					$klines_All[$interval] = $klines;
// 					$end['interval'] = $interval;
// 					$end['symbol'] = $ticker['symbol'];
// 					$end['dateOpen'] = date("Y-m-d H:i:s", $klines[0][0]/1000);
// 					$end['open'] =  $klines[0][1];
// 					$end['high'] = $klines[0][2];
// 					$end['low'] = $klines[0][3];
// 					$end['close'] = $klines[0][4];
// 					$end['volume'] = $klines[0][5];
// 					$end['dateClose'] = date("Y-m-d H:i:s", $klines[0][6]/1000);
// 					$end['***'] = '';

// 					//находим изминения последней свечи
// 					$end['shadow_top_p'] = '';
// 					$end['open_close_p'] = bcmul(bcdiv(bcsub($klines[0][4], $klines[0][1], 8), $klines[0][1], 8), 100, 8);
// 					$end['shadow_bottom_p'] = '';

// 					if (1 == bccomp($end['open_close_p'], 0, 8)) {
// 						$end['shadow_top_p'] = bcmul(bcdiv(bcsub($klines[0][2], $klines[0][4], 8), $klines[0][1], 8), 100, 8);
// 						$end['shadow_bottom_p'] = bcmul(bcdiv(bcsub($klines[0][3], $klines[0][1], 8), $klines[0][1], 8), 100, 8);
// 					}elseif(-1 == bccomp($end['open_close_p'], 0, 8)) {
// 						$end['shadow_top_p'] = bcmul(bcdiv(bcsub($klines[0][2], $klines[0][1], 8), $klines[0][1], 8), 100, 8);
// 						$end['shadow_bottom_p'] = bcmul(bcdiv(bcsub($klines[0][3], $klines[0][4], 8), $klines[0][1], 8), 100, 8);
// 					}else{
// 						$end['shadow_top_p'] = 0;
// 						$end['shadow_bottom_p'] = 0;
// 					}

// 					//находим процент увеличения цены от минимальной
// 					// $end['High_Close_p_⇓'] =  bcmul(bcdiv(bcsub($klines[0][4], $klines[0][2], 8), $klines[0][2], 8),100, 8);
// 					// $end['Low_Close_p_⇑'] = bcmul(bcdiv(bcsub($klines[0][4], $klines[0][3], 8), $klines[0][3], 8),100, 8);
// 					$end_Klin[$interval] = $end;
//           		}

//           }

// 		$end_klin_file = 'D:\binance\end_klin\\'.$ticker['symbol'].'.txt';
// 		Functions::saveFile($end_klin, $end_klin_file);
// }
        // Functions::showArrayTable($end_Klin, 'end_Klin');











	//********************************************************************
	$duration = round(microtime(true) - $start, 4);
	$mem = (memory_get_usage() - $mem_start)/1000000;
	$user = $_GET['action'] == 'show'?'user':'cron openserver';

    $file_log = 'D:\binance\log_CRON.txt';
	// $log = Functions::readFile($file_log);
    $log[] = ['user' => $user, 'time_start' => date("Y-m-d H:i:s", $start),'time_end' => date("Y-m-d H:i:s"), 'duration(sec)'=> $duration, 'mem(Mbyte)'=> $mem];
	Functions::saveFile($log, $file_log);

    if ($_GET['action'] == 'show'){
        //log_CRON
        Functions::showArrayTable_key($log, 'log file');
    }

    // php "%sitedir%\BUCKS\cron\updat_strategies.php"
?>
