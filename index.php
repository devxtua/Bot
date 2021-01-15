<?php
session_start();

// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// phpinfo();
$start = $_SERVER['REQUEST_TIME'];
$mem_start = memory_get_usage();

//Устанавливаем настройки памяти
  ini_set('memory_limit', '3072M');
  // echo "memory_limit ", ini_get('memory_limit'), "<br />";

//Устанавливаем настройки времени
  ini_set('max_execution_time', 500000);    //  одно и тоже что set_time_limit(6000);
  // echo "max_execution_time ", ini_get('max_execution_time'), "<br />";

//Устанавливаем настройки буфера вывода
  // ob_implicit_flush(1);

  ob_start();
	// 	ob_flush();
	// flush();

	// ob_get_contents();
	// ob_get_clean();
	// ob_end_flush();
//$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
require "./~core/model/binance_c.php";
require "./~core/model/functions_c.php";
require "./~core/model/indicators_c.php";
require "./~core/model/strategies_c.php";
require "./~core/model/user_c.php";


require "./libraries/binance_api/vendor/autoload.php";



// require "./~core/model/apiviber_c.php";
// $Viber = new Viber();
// $Viber->send_message('380505953494','Привет Это бот!');

// $Viber->message_post(
//     '01234567890A=',
//     [
//         'name' => 'Admin', // Имя отправителя. Максимум символов 28.
//         'avatar' => 'http://avatar.example.com' // Ссылка на аватарку. Максимальный размер 100кб.
//     ],
//     'Test'
// );
//


//*********************************
// $analytics_indicators_file = 'E:/bot/file/analytics_indicators.txt';
// if (is_file($analytics_indicators_file)){
// 	$analytics_indicators = Functions::readFile($analytics_indicators_file);
// 	foreach ($analytics_indicators as $key => $value) {
// 		echo "**********************************************<br/>";
// 		foreach ($value as $key2 => $value2) {
// 			Functions::showArrayTable($value2, $key.$key2);
// 		}
// 	}
// }else{
// 	echo "файла нет";
// }






header( 'Content-type: text/html; charset=utf-8' );
echo "<!DOCTYPE html>";

echo '<link rel="stylesheet" href="./html/css/style.css">';
echo '<script type="text/javascript" src="./libraries/jquery.js"></script>';
echo '<script type="text/javascript" src="./html/script/script.js"></script>';

// echo '<h1 class="area">&#36&#36&#36</h1>';
echo '<h1 class="area">BUCKS</h1>';








$Bin = new Binance();

$Users = new Users();
$GLOBALS['Users'] = &$Users;

//Получаем информацию о symbol
// $symbolInfo = Functions::multiSearch($Bin->exchangeInfo['symbols'], array('symbol' => 'ETHDOWNUSDT', 'status'=>'TRADING'))[0] ;
// Functions::show($symbolInfo);


$Indicators = new Indicators();
$GLOBALS['Indicators'] = &$Indicators;

// $indicator_klin_count_down = $Indicators->indicator_klin_last_down('1m',  $klines);
// $all_indicator = $Indicators->all_indicator('BTCUSDT', '1m');
// die();

// Functions::show(array_values(Functions::multiSearch($GLOBALS['ticker24hr'], array('symbol' => 'BTCUSDT'))), 'BTCUSDT ');

// $api = new Binance\API($KEY, $SEC);
// $api = new Binance\RateLimiter($api);
//

//
// $symbolBUY =  array( 'BTCUSDT', 'ETHUSDT', 'BNBUSDT');
// $api->kline($symbolBUY, "1m", function($api, $symbol, $chart) {
// 	// echo "{$symbol} kline".PHP_EOL;
//  //    print_r('<pre>');
//  //    print_r($chart);
//  //    print_r('</pre>');
//     $endpoint = strtolower( $symbol ) . '@ticker';
//     $api->terminate( $endpoint );
// });

// // // Trade Updates via WebSocket
// $api->kline(["BTCUSDT", "EOSBTC"], "5m", function($api, $symbol, $chart) {
//     // var_dump( $chart );
//     echo "{$symbol} ({$interval}) candlestick update\n";
//     $interval = $chart->i;
//     $tick = $chart->t;
//     $open = $chart->o;
//     $high = $chart->h;
//     $low = $chart->l;
//     $close = $chart->c;
//     $volume = $chart->q; // +trades buyVolume assetVolume makerVolume
//     echo "{$symbol} price: {$close}\t volume: {$volume}\n";

//     $endpoint = strtolower( $symbol ) . '@kline_' . "5m";
//     $api->terminate( $endpoint );
// });
// echo "<br/>";
// Functions::show($api);


// $api->trades($symbolBUY, function($api, $symbol, $trades) {
//     echo "{$symbol} trades update".PHP_EOL;
//     print_r('<pre>');
//     print_r($trades);
//     print_r('</pre>');
//     $endpoint = strtolower( $symbol ) . '@trades';
//     $api->terminate( $endpoint );
// });

// die();

//Определяем базове валют
$base['USDT']= array('minBalans'=>100, 'minPriceBuy'=>0.00000100);
$base['BNB']= array('minBalans'=>3, 'minPriceBuy'=>0.00000100);

//***********************************************
// //Получить изминения за 24 часса



//сохранение истории '1m' и 'quoteAsset'=='USDT'
// $interval_control = array('1m','3m','5m','15m','30m','1h','2h','4h','6h','8h','12h','1d','3d','1W');
// $Bin->funded_USDT($interval_control, $ticker24hr);
// echo 'Время выполнения скрипта: ', round($time, 4), ' сек.<br/>';
// die('Все варианты обновлены');
//***********************************************

// Functions::show($_ENV, "_ENV");
// Functions::show($_GET, "_GET");
// Functions::show($_POST, "_POST");


// $Strategies = new Strategies();

$exchange = 'binance';


if ($_GET['START'] == 'START') {
	unset($_SESSION['login']);
}elseif(!empty($_SESSION['login'])) {
	echo '<form action="index.php?action=&login='.$_SESSION['login'].'" method="post"><input type="submit" value="ГЛАВНАЯ '.$_SESSION['login'].'"></form>';
}elseif(!empty($_GET['login']) && $_GET['login']!= 'ALL') {
	$_SESSION['login']= $_GET['login'];
	echo '<form action="index.php?action=&login='.$_GET['login'].'" method="post"><input type="submit" value="ГЛАВНАЯ '.$_GET['login'].'"></form>';
}





$result = '';
if ($_GET['action'] == '') {
	header('refresh: 20');
	echo '<p style="text-align: right; "><a href="index.php?START=START" >USERS</a>&nbsp;&nbsp;&nbsp;&nbsp';
	echo '<a href="./cron/updating_strategies.php?action=show" target="_blank">updat_strategies</a>&nbsp;&nbsp;&nbsp;&nbsp;';
	echo '<a href="./cron/bot.php?action=show" target="_blank">bot</a>&nbsp;&nbsp;&nbsp;&nbsp;';
	echo '<a href="index.php?action=analytics_indicators" target="_blank">analytics_indicators</a>&nbsp;&nbsp;&nbsp;&nbsp;';
	echo '<a href="index.php?action=testBUY" target="_blank">testBUY</a>&nbsp;&nbsp;&nbsp;&nbsp;';
	echo '<a href="index.php?action=testSymbols" target="_blank">testSymbols</a>&nbsp;&nbsp;&nbsp;&nbsp;</p>';

	//проверяем стратегии user
	if (count($Users->user_arrey)>0) {

		if (empty($_GET['login'])) goto start;
		if ($_GET['login']!= 'ALL'){
			foreach ($Users->user_arrey as $key => $user) {
				if ($_GET['login']!= $user['login']) unset($Users->user_arrey[$key]);
			}
		}

		foreach ($Users->user_arrey as $key => $user) {
			// Functions::show($user, '');


			echo '<div style="text-align: right; background: #fc0;">', '  <font size="20" color=blue face="Arial">', date("H:i:s", time()), '</font></div>';
			$Bin->initialization($user[$exchange]['config']['KEY'], $user[$exchange]['config']['SEC']);

			//Получить свободный баланс АК
			if ($accountBalance = $Bin->accountBalance($base)){
				echo 'АКТИВЫ ' , $user['login'], ' баланс: <font size="6" color="green" face="Arial">', round(array_sum(array_column($accountBalance, 'total_USD')),2),  '</font> $<br/>';
				Functions::showArrayTable($accountBalance, '');
			}
			//открытые ордера
			if ($orderOPEN = $Bin->orderOPEN(array())){
				Functions::showArrayTable($orderOPEN);
			}


			if (count($user[$exchange]['strategies'])>0) {
				foreach ($user[$exchange]['strategies'] as $keyS => $strateg) {
					unset($roi);
					//обновляем историю ордеров
					$filehistory = 'D:\binance\history_user\\'.$user['login'].'\\'.$strateg['symbol'].'.txt';
					if ($_GET['history'] == '1') {
 						$allOrders = $Bin->history_orders_symbol($strateg, $filehistory);
					}else{
						$allOrders = Functions::readFile($filehistory);
					}
					$sum = 0;
					if (count($allOrders)>0) {

						foreach ($allOrders as $key => $value) {

							// if (stristr($value['clientOrderId'], $strateg['key'])===false){
							// 	unset($allOrders[$key]);
							// 	continue;
							// }
							if ($value['status'] == 'EXPIRED'|| $value['status'] == 'CANCELED'){
								unset($allOrders[$key]);
								continue;
							}

							if ($value['side'] == 'BUY') {
								$sum -= $value['cummulativeQuoteQty'];
							}else{
								$sum += $value['cummulativeQuoteQty'];
							}
							$allOrders[$key]['commission']=bcmul($value['cummulativeQuoteQty'], 0.00075, 8);
							$sum -= $allOrders[$key]['commission'];


							$allOrders[$key]['balans'] = round($sum, 2);
						}
						if (count($allOrders)>0) {
							$invest = abs(min(array_column($allOrders, 'balans')));
							$roi = bcdiv($sum, $invest, 4)*100;
						}
					}


					$user[$exchange]['strategies'][$keyS]['order_all'] = count($allOrders);
					$count = 0;
					foreach ($orderOPEN as $key => $value) {
	                    if (stristr($value['clientOrderId'], $strateg['key']) && $value['type'] == 'STOP_LOSS_LIMIT' && $value['symbol'] == $strateg['symbol']) {
							$count++;
	                    }
	                }
	                $user[$exchange]['strategies'][$keyS]['order_open'] = $count>0?$count:'';



					$user[$exchange]['strategies'][$keyS]['balans'] = round($sum, 2);
					$user[$exchange]['strategies'][$keyS]['roi'] = !empty($roi)?$roi:'';


				}
				$title ="СТРАТЕГИИ ".$exchange. ' ';
				$title .= '<form action="index.php?action=&login='.$user['login'].'&history=1" method="post"><input type="submit" value="ОБНОВИТЬ ИСТОРИЮ"></form>';
				Functions::showStrategies($user, $title);
			}




			echo '<p><form action="index.php?action=strateg_add" method="post">
				<input type="hidden" name="login" value="'.$user['login'].'">
				<input type="hidden" name="exchange" value="'.$exchange.'">
				<input type="submit" value="Добавить стратегию для '.$exchange.'">
				</form><br/></p>';

			//*************************************************************************************************************************

			// if ($allOrderList = $Bin->allOrderList(array())){
			// 	Functions::showArrayTable($allOrderList);
			// }


			// if ($allOCO = $Bin->allOCO(array())){
			// 	Functions::showArrayTable($allOCO);
			// }

			//*************************************************************************************************************************

		}
		die();
		start:

		foreach ($Users->user_arrey as $key => $user) {
				echo '<p><form action="index.php?action=&login='.$user['login'].'" method="post">
					<input type="submit" value="'.$user['login'].'">
					</form></p>';
		}
		echo '<p><form action="index.php?action=&login=ALL" method="post"><input type="submit" value="ALL USERS"></form></p>';
		echo '<form action="index.php?action=user_add" method="post" ><input type="submit" value="Добавить пользователя"></form>';
	}

}elseif($_GET['action'] == 'user_add') {

			if ($_GET['step'] == 'save') {
				$Users->add_user($_POST);
				echo "Пользователь добавлен<br/>";
				die();
			}

			$result .= '<form action="index.php?action=add_user&step=save" method="post">';
			$result .= '<p>Ваш номер телефона: <input size="50" type="tel" name="login" required placeholder="380XXXXXXXXX" pattern="38[0-9]{10}"/></p>';
			$result .= '<p>Доступ Binance API </p>';
			$result .= '<p>KEY: <input size="100" type="text" required name="binance[config][KEY]" /></p>';
			$result .= '<p>SEC: <input size="100" type="text" required name="binance[config][SEC]" /></p>';
			$result .= '<p>При наличии заполните Proxy:</p>';
			$result .= '<p>Proxy ip: <input size="50" type="text" name="binance[config][Proxy][ip]" /></p>';
			$result .= '<p>Proxy login: <input size="50" type="text" name="binance[config][Proxy][login]" /></p>';
			$result .= '<p>Proxy password: <input size="50" type="text" name="binance[config][Proxy][password]" /></p>';
			$result .= '<input type="submit" value="Сохранить"></form>';
}elseif($_GET['action'] == 'strateg_add') {
	if ($_GET['step'] == 'seve') {
		// Functions::show($_POST);
		$Strategies = new Strategies($_POST['login']);
		$Strategies->strateg_add($_POST);
	}

	if($_GET['step'] == ''){
		$result .= '<form action="index.php?action=strateg_add&step=1" method="post">';
		$result .= '<p>Новая стратегия</p>';
		$result .= '<input type="hidden" name="login" value="'.$_POST['login'].'">';
		$result .= '<input type="hidden" name="exchange" value="'.$_POST['exchange'].'">';


		$result .= '<p>Название: <input size="100" type="text" name="title" required placeholder="Укажите название"/>&nbsp;&nbsp;';
		$result .= 'key: <input type="text" readonly style="background:#e6e6e6;" name="key" value="'.uniqid('').'" /></p>';

		$result .= '<p>Символ:<select size="0"  name="symbol" title="Выберите торговую пару" required ><option></option>' ;
	        foreach ($Bin->ticker24hr as $key => $value) {
	                if (!$symbolInfo = Functions::multiSearch($Bin->exchangeInfo['symbols'], array('symbol' => $value['symbol'], 'status'=>'TRADING'))) continue;
	                 // Исключаем если база не USDT
	                if ($symbolInfo[0]['quoteAsset']!='USDT') continue;
	                $result .= '<option value="'.$value['symbol'].'">'.$value['symbol'].'</option>';
	        }
		$result .='</select>&nbsp;&nbsp;';
		$result .= 'Таймфрейм:<select size="0"  name="interval" title="Выберите таймфрейм" required><option></option>' ;
			foreach (['1m','3m','5m','15m','30m','1h','2h','4h','6h','8h','12h','1d','3d','1W','1M'] as $key => $value) {
			    $result .= '<option value="'.$value.'">'.$value.'</option>';
			}
		$result .='</select>&nbsp;&nbsp;';
		$result .= 'Торговый лимит: <input size="0" type="number"  name="trading_limit"  value="12" min="12" /></p>';

		$result .= '<p> По умолчанию установлен период 1000 последних свичей.  :</p>';
		$result .= '<p>start <input type="datetime-local"  name="startTime" value="'.date('Y-m-d', $startTime).'T'.date('H:i', $startTime).'"/>&nbsp;
	               	 end<input type="datetime-local"  name="endTime" value="'.date('Y-m-d').'T'.date('H:i').'"/>&nbsp;';
		$result .= '<input type="submit" name="button" value="ANALYTICS_indicators"> </p>';

	}elseif ($_GET['step'] == 1){
		$all_indicators = $Indicators->all_indicator($_POST['symbol'], $_POST['interval']);

		if ($_POST['interval']!='off') {
			// $analytics_arrey = $Indicators->analytics_klines($_POST['interval']);
			$indicator_arrey = $Indicators->indicator_arrey;
			$funded_klines = $Bin->funded_klines($_POST, strtotime($_POST['startTime'])*1000, strtotime($_POST['endTime'])*1000);
			$analytics_indicators =  Functions::analytics_indicators($_POST, $funded_klines);
			// Functions::show($analytics_indicators, $funded_klines);
		}

		$result .= '<form action="index.php?action=strateg_add&step=seve" method="post">';
		$result .= '<p>Новая стратегия</p>';
		$result .= '<input type="hidden" name="login" value="'.$_POST['login'].'">';
		$result .= '<input type="hidden" name="exchange" value="'.$_POST['exchange'].'">';
		$result .= '<p>Название: <input size="100" type="text" name="title" value="'.$_POST['title'].'"/>&nbsp;&nbsp;';
		$result .= 'key: <input type="text" readonly style="background:#e6e6e6;" name="key" value="'.$_POST['key'].'" /></p>';
		$result .= '<p>Символ:<select size="0"  name="symbol" readonly style="background:#e6e6e6;"><option selected value="'.$_POST['symbol'].'">'.$_POST['symbol'].'</option>' ;
		$result .='</select>&nbsp;&nbsp;';
		$result .= 'Таймфрейм:<select size="0"  name="interval" readonly style="background:#e6e6e6;"><option selected value="'.$_POST['interval'].'">'.$_POST['interval'].'</option>' ;
		$result .='</select>&nbsp;&nbsp;';
		$result .= 'Торговый лимит: <input size="0" type="number"  name="trading_limit"  value="'.$_POST['trading_limit'].'" min="12" />&nbsp;&nbsp;';
	    $result .= '<p>Настройки</p>';
		$result .="BUY_OCO<table border='1'>";
		$result .="<tr><th>Reinstall</th><th>Distance</th><th>Price</th><th>S_Price</th><th>SL_Price</th></tr>";
		//BUY_OCO
 		foreach ([0=>'start'] as $key => $value) {
 			if ($key == 0) {
        		$param = array('Distance' => 0.1, 'Price' => 0.9, 'S_Price' => 1.001, 'SL_Price' => 1.005);
        	}
 			$result .= '<tr>';
            $result .= '<td>'.$value.'</td>';
            $result .= '<td><input size="5" type="number" name="BUY_OCO['.$key.'][Distance]"  value="'.$param['Distance'].'" min="0" max="100" step="0.00000001"></td>';
            $result .= '<td><input size="5" type="number" name="BUY_OCO['.$key.'][Price]" value="'.$param['Price'].'" min="-5" max="1" step="0.00000001"></td>';
            $result .= '<td><input size="5" type="number" name="BUY_OCO['.$key.'][S_Price]" value="'.$param['S_Price'].'" min="1" max="5" step="0.00000001"></td>';
            $result .= '<td><input size="5" type="number" name="BUY_OCO['.$key.'][SL_Price]" value="'.$param['SL_Price'].'" min="1" max="5" step="0.00000001"></td>';
            $result .= '</tr>';
        }
	    $result .="</table><br/>";
        //SELL_OCO
        $result .="SELL_OCO<table border='1'>";
		$result .="<tr><th>&#10003;</th><th>Reinstall</th><th>Distance</th><th>Price</th><th>S_Price</th><th>SL_Price</th></tr>";
        foreach ([0=>'start',1=>'1',2=>'2'] as $key =>  $value) {
        	if ($key == 0) {
        		$param = array('Distance' => 0, 'Price' => 1.006, 'S_Price' => 0.98, 'SL_Price' => 0.9);
        	}elseif ($key == 1) {
        		$param = array('Distance' => -2.55, 'Price' => 1.006, 'S_Price' => 0.999, 'SL_Price' => 0.9);
        	}elseif ($key == 2) {
        		$param = array('Distance' => -0.55, 'Price' => 1.006, 'S_Price' => 0.996, 'SL_Price' => 0.9);
        	}

 			$result .= '<tr>';
 			$result .= '<tr><td><input type="checkbox" name="indicator_arrey['.$i.'][indicator]" value="'.$key.'"></td>';
            $result .= '<td>'.$value.'</td>';
            $result .= '<td><input size="5" type="number" name="SELL_OCO['.$key.'][Distance]" value="'.$param['Distance'].'" min="-100" max="0" step="0.01"></td>';
            $result .= '<td><input size="5" type="number" name="SELL_OCO['.$key.'][Price]" value="'.$param['Price'].'" min="1" max="5" step="0.00000001"></td>';
            $result .= '<td><input size="5" type="number" name="SELL_OCO['.$key.'][S_Price]" value="'.$param['S_Price'].'" min="-5" max="1" step="0.00000001"></td>';
            $result .= '<td><input size="5" type="number" name="SELL_OCO['.$key.'][SL_Price]" value="'.$param['SL_Price'].'" min="-5" max="1" step="0.00000001"></td>';
            $result .= '</tr>';
        }
	    $result .="</table><br/>";

		$result .='<p>Индикаторы:</p>';
		$result .="<table border='1'>";
		$result .="<tr><th>&#10003;</th><th>Indicator</th><th>key</th><th>Now</th><th>Operator</th><th>Index</th><th>updat avto</th><th>Analytics param</th><th>Operator</th><th>Index</th>";

		//аналитика шапка
		// if ($_POST['interval'] != 'off' && $_POST['trend_end_klin'] != 'NO')
			foreach (reset($analytics_indicators) as $key => $value) {
				if ($key == 'indicator') continue;
				$analytics_key[] = $key;
				$result .='<th>'.$key.'</th>';
			}
		$result .="</tr>";
		$i=0;

 		foreach ($all_indicators as $key => $value) {
            $result .= '<tr><td><input type="checkbox" name="indicator_arrey['.$i.'][indicator]" value="'.$key.'"></td>';
            $result .='<td title="'.$indicator_arrey[$key]['description'].'">'.$indicator_arrey[$key]['title'].'</td>';
            $result .='<td title="'.$key.'">***</td>';
            $result .='<td>'.$all_indicators[$key].'</td>';
			$result .= '<td><select size="0"  name="indicator_arrey['.$i.'][operator]" required placeholder="Выберите operator"><option selected disabled>Выбрать</option>';
				foreach (['<', '=', '>'] as $operator) {
		            $result .= '<option value="'.$operator.'">'.$operator.'</option>';
		        }
			$result .= '</select></td>';
            $result .= '<td><input size="5" type="number" name="indicator_arrey['.$i.'][value]" step="0.00000001"></td>';

            //авто обновление
            $result .= '<td><input type="checkbox" name="indicator_arrey['.$i.'][updat]" value="true"></td>';
			$result .= '<td><select size="0"  name="indicator_arrey['.$i.'][updat_analytics_key]" title="Выберите analytics_ind" ><option></option>' ;
		        foreach ($analytics_key as $key1 => $value) {
		                $result .= '<option value="'.$value.'">'.$value.'</option>';
		        }
			$result .='</select></td>';
			$result .= '<td><select size="0"  name="indicator_arrey['.$i.'][updat_operator]" title="Выберите оператор" ><option></option>' ;
		        foreach (['bcmul'=>'* уножить','bcdiv' => '/ разделить', 'bcadd'=>'+ плюс', 'bcsub'=>'- минус'] as $key2 => $value2) {
		                $result .= '<option value="'.$key2.'">'.$value2.'</option>';
		        }
			$result .='</select></td>';
			$result .= '<td><input size="5" type="number" name="indicator_arrey['.$i.'][updat_value]" title="значение" value="" ></td>';

            //аналитика значения
            foreach ($analytics_indicators[$key] as $k => $value) {
				if ($k == 'indicator') continue;
				$result .='<td>'.$value.'</td>';
			}

			$i++;
        }
	    $result .="</table><br/>";

		$result .= '<input type="hidden" readonly name="setting" value="" /></p>';
		$result .= '<input type="hidden" readonly name="creat_time" value="'.(time()*1000).'" /></p>';

		$result .= '<p><input type="radio" checked name="status" value="OFF"/>OFF<input type="radio" name="status" value="ON"/>ON<input type="submit" value="Сохранить"/></p></form>';

		$result .= '<form action="action="index.php?action=strateg_add" method="post">
						<input type="submit" value="Назад">
					</form>';
	}
}elseif($_GET['action'] == 'optimization') {
	$strateg = $Users->user_arrey[$_POST['login']][$_POST['exchange']]['strategies'][$_POST['key']];
// Functions::show($strateg);
	if ($_GET['step'] == 'strateg_change') {
		$Strategies = new Strategies($_POST['login']);
		$strateg = $Strategies->strateg_change($_POST);
		// echo '<div style="background: #fc0;">', 'Сохраненино</div>';
	}elseif($_GET['step'] == 'strateg_config_save') {
		$Strategies = new Strategies($_POST['login']);
		$strateg = $Strategies->strateg_config_save($_POST);
		// echo '<div style="background: #fc0;">', 'Сохраненино</div>';
	}

		$all_indicators = $Indicators->all_indicator($strateg['symbol'], $strateg['interval']);
		if ($strateg['interval']!='off') {
			$analytics_arrey = $Indicators->analytics_klines($strateg['interval']);

			$startTime = $_POST['startTime']??time() - $Bin->interval[$strateg['interval']]*1000;
			$endTime = $_POST['endTime']??time();
echo date("Y-m-d H:i:s", $startTime), '<br>';
echo date("Y-m-d H:i:s", $endTime), '<br>';
			$indicator_arrey = $Indicators->indicator_arrey;
			$funded_klines = $Bin->funded_klines($_POST, strtotime($startTime)*1000, strtotime($endTime)*1000);
			$analytics_indicators =  Functions::analytics_indicators($_POST, $funded_klines);
		}

		$result .= '<form action="index.php?action=optimization&step=strateg_change" method="post">';
		$result .= '<p>Оптимизация</p>';
		$result .= '<input type="hidden" name="login" value="'.$_POST['login'].'">';
		$result .= '<input type="hidden" name="exchange" value="'.$_POST['exchange'].'">';
		$result .= '<p>Название: <input size="100" type="text" name="title" value="'.$strateg['title'].'"/>&nbsp;&nbsp;';
		$result .= 'Уникальный ключ: <input type="text" readonly style="background:#e6e6e6;" name="key" value="'.$_POST['key'].'" /></p>';
		$result .= '<p>Символ:<select size="0"  name="symbol" readonly style="background:#e6e6e6;"><option selected value="'.$strateg['symbol'].'">'.$strateg['symbol'].'</option>' ;
		$result .='</select>&nbsp;&nbsp;';
		$result .= 'Таймфрейм:<select size="0"  name="interval" readonly style="background:#e6e6e6;"><option selected value="'.$strateg['interval'].'">'.$strateg['interval'].'</option>' ;
		$result .='</select>&nbsp;&nbsp;';
		$result .= 'Торговый лимит: <input size="0" type="number"  name="trading_limit"  value="'.$strateg['trading_limit'].'" min="12" />&nbsp;&nbsp;';

			    $result .= '<p>Настройки </p>';
		$result .="BUY_OCO<table border='1'>";
		$result .="<tr><th>Reinstall</th><th>Distance</th><th>Price</th><th>S_Price</th><th>SL_Price</th></tr>";
		//BUY_OCO
 		foreach ($strateg['BUY_OCO'] as $key => $value) {
 			if ($key == 0) {
        		$param = $strateg['BUY_OCO'][0];
        	}
 			$result .= '<tr>';
            $result .= '<td>'.$value.'</td>';
            $result .= '<td><input size="5" type="number" name="BUY_OCO['.$key.'][Distance]"  value="'.$param['Distance'].'" min="0" max="100" step="0.00000001"></td>';
            $result .= '<td><input size="5" type="number" name="BUY_OCO['.$key.'][Price]" value="'.$param['Price'].'" min="-5" max="1" step="0.00000001"></td>';
            $result .= '<td><input size="5" type="number" name="BUY_OCO['.$key.'][S_Price]" value="'.$param['S_Price'].'" min="1" max="5" step="0.00000001"></td>';
            $result .= '<td><input size="5" type="number" name="BUY_OCO['.$key.'][SL_Price]" value="'.$param['SL_Price'].'" min="1" max="5" step="0.00000001"></td>';
            $result .= '</tr>';
        }
	    $result .="</table><br/>";
        //SELL_OCO
        $result .="SELL_OCO<table border='1'>";
		$result .="<tr><th>Reinstall</th><th>Distance</th><th>Price</th><th>S_Price</th><th>SL_Price</th></tr>";
        foreach ($strateg['SELL_OCO'] as $key =>  $value) {
        	if ($key == 0) {
        		$param = $strateg['SELL_OCO'][0];
        	}elseif ($key == 1) {
        		$param = $strateg['SELL_OCO'][1];
        	}elseif ($key == 2) {
        		$param = $strateg['SELL_OCO'][2];
        	}

 			$result .= '<tr>';
            $result .= '<td>'.$value.'</td>';
            $result .= '<td><input size="5" type="number" name="SELL_OCO['.$key.'][Distance]" value="'.$param['Distance'].'" min="-100" max="0" step="0.0000001"></td>';
            $result .= '<td><input size="5" type="number" name="SELL_OCO['.$key.'][Price]" value="'.$param['Price'].'" min="1" max="5" step="0.00000001"></td>';
            $result .= '<td><input size="5" type="number" name="SELL_OCO['.$key.'][S_Price]" value="'.$param['S_Price'].'" min="-5" max="1" step="0.00000001"></td>';
            $result .= '<td><input size="5" type="number" name="SELL_OCO['.$key.'][SL_Price]" value="'.$param['SL_Price'].'" min="-5" max="1" step="0.00000001"></td>';

            $result .= '</tr>';
        }
	    $result .="</table><br/>";
	    //Индикаторы
		$result .='<p>Индикаторы:</p>';

	   	$startTime = time() - $Bin->interval[$strateg['interval']]*1000;
		$result .= '<p>УСТАНОВЛЕН период 1000 последних свичей. МОЖНО ИЗМЕНИТЬ : start <input type="datetime-local"  name="startTime" value="'.date('Y-m-d', $startTime).'T'.date('H:i', $startTime).'"/>&nbsp;
	               	 end<input type="datetime-local"  name="endTime" value="'.date('Y-m-d').'T'.date('H:i').'"/>&nbsp';
		$result .= '<input type="submit" name="button" value="ANALYTICS_indicators"></p>';

		$result .="<table border='1'>";
		$result .="<tr><th>Title</th><th>&#10003;</th><th>Indicator</th><th>Operator</th><th>Index</th><th>updat avto</th><th>Index</th><th>Index</th><th>Index</th></tr>";
		$result .= '<tr>';
		$i = 0;
 		foreach ($strateg['indicator_arrey'] as $key => $value) {
 			$result .= '<tr><td>'.$Indicators->indicator_arrey[$value['indicator']]['title'].'</td>';
			$result .= '<td><input type="checkbox"  checked disabled></td>';
			$result .= '<td>'.$value['indicator'].'</td>';
			$result .= '<input type="hidden"  name="indicator_arrey['.$i.'][indicator]" value="'.$value['indicator'].'">';
			$result .= '<td><input type="text"  name="indicator_arrey['.$i.'][operator]" value="'.$value['operator'].'" readonly  style="background:#e6e6e6;"></td>';
			$result .= '<td><input size="5" type="number" name="indicator_arrey['.$i.'][value]" step="0.00000001" value="'.$value['value'].'"></td>';


            $result .= '<td><input type="checkbox" name="indicator_arrey['.$i.'][indicator]" value="'.$key.'"></td>';
			$result .= '<td><select size="0"  name="" title="Выберите analytics_indicators" ><option></option>' ;
		        foreach ([1,2,3] as $key => $value) {
		                $result .= '<option value="'.$value.'">'.$value.'</option>';
		        }
			$result .='</select></td>';
			$result .= '<td><select size="0"  name="" title="Выберите оператор" ><option></option>' ;
		        foreach (['*','2',3] as $key => $value) {
		                $result .= '<option value="'.$value.'">'.$value.'</option>';
		        }
			$result .='</select></td>';



            $result .= '<td><input size="5" type="number" name="" title="значение" value="" ></td>';

			$result .= '</tr>';
            $i++;
        }


	    $result .="</table><br/>";




		$result .= '<p> Установить histori_time <input type="datetime-local" name="histori_time" value="'.date('Y-m-d', $strateg['histori_time']/1000).'T'.date('H:i', $strateg['histori_time']/1000).'" /></p>';
		$result .= '<p><input type="radio" checked name="status" value="OFF"/>OFF' ;
		$result .= '<input type="radio" name="status" value="ON"/>ON</p>';
		$result .= '<input type="hidden" readonly name="change_time" value="'.(time()*1000).'" />';

		$result .='<p><input type="submit" value="Сохранить"/></p></form>';

		$result .='<br/>Условия расчета вариантов каждой НАСТРОЙКИ';
		$result .='<form action="index.php?action=optimization&step=strateg_config_save" method="post">';

		$result .='<input type="hidden" name="login" value="'.$_POST['login'].'">';
        $result .='<input type="hidden" name="exchange" value="'.$_POST['exchange'].'">';
        $result .='<input type="hidden" name="key" value="'.$_POST['key'].'">';

		$result .='<table border="1">';

		if (strcasecmp($_POST['button'], 'options') == 0 || strcasecmp($_POST['button'], 'COMBINATIONS') == 0) {
			$options_indicator = Functions::options_indicator($_POST['config']);
			$header = Functions::header_table($options_indicator);
			$result .="<tr><th>setting</th><th>MIN</th><th>MAX</th><th>STEP</th><th>count</th>";
			foreach ($header as $key => $value) {
				$result .="<th>".$key."</th>";
			}
			$result .="</tr>";

		}else{
			$result .="<tr><th>setting</th><th>MIN</th><th>MAX</th><th>STEP</th></tr>";
		}



		if (!empty($strateg['config'])) {
			// unset($strateg['config']);
			foreach ($strateg['config'] as $key_config => $value) {
				$result .= '<tr>';
				$result .= '<td>'.$key_config.'</td>';
				$result .= '<td><input size="5" type="number" name="config['.$key_config.'][min]" step="0.00000001" value="'.$value['min'].'"></td>';
				$result .= '<td><input size="5" type="number" name="config['.$key_config.'][max]" step="0.00000001" value="'.$value['max'].'"></td>';
				$result .= '<td><input size="5" type="number" name="config['.$key_config.'][step]" step="0.00000001" value="'.$value['step'].'"></td>';

					if (strcasecmp($_POST['button'], 'options') == 0 || strcasecmp($_POST['button'], 'COMBINATIONS') == 0) {
						$result .= '<td>'.count($options_indicator[$key_config]).'</td>';
						foreach ($options_indicator[$key_config] as $key => $value) {
							$result .="<td>".$value."</td>";
						}
					}

				$result .= '</tr>';
			}
		}else{
				$result .= '<tr>';
				$result .= '<td>SELL_OCO_start_Price:</td>';
				$result .= '<td><input size="5" type="number" name="config[SELL_OCO_start_Price][min]" step="0.00000001" value="1.01"></td>';
				$result .= '<td><input size="5" type="number" name="config[SELL_OCO_start_Price][max]" step="0.00000001" value="1.01"></td>';
				$result .= '<td><input size="5" type="number" name="config[SELL_OCO_start_Price][step]" step="0.00000001" value="0"></td>';

					if (strcasecmp($_POST['button'], 'options') == 0 || strcasecmp($_POST['button'], 'COMBINATIONS') == 0||strcasecmp($_POST['button'], 'ANALYTICS_indicators') == 0) {
						$result .= '<input size="5" type="hidden" name="config[SELL_OCO_start_Price][count]"value="'.count($options_indicator['SELL_OCO_start_Price']).'">';
						foreach ($options_indicator['SELL_OCO_start_Price'] as $key => $value) {
							$result .="<td>".$value."</td>";
						}
					}
				$result .= '</tr>';


				$result .= '<tr>';
				$result .= '<td>SELL_OCO_start_S_Price:</td>';
				$result .= '<td><input size="5" type="number" name="config[SELL_OCO_start_S_Price][min]" step="0.00000001" value="0.999"></td>';
				$result .= '<td><input size="5" type="number" name="config[SELL_OCO_start_S_Price][max]" step="0.00000001" value="0.999"></td>';
				$result .= '<td><input size="5" type="number" name="config[SELL_OCO_start_S_Price][step]" step="0.00000001" value="0"></td>';

					if (strcasecmp($_POST['button'], 'options') == 0 || strcasecmp($_POST['button'], 'COMBINATIONS') == 0||strcasecmp($_POST['button'], 'ANALYTICS_indicators') == 0) {
						$result .= '<input size="5" type="hidden" name="config[SELL_OCO_start_S_Price][count]" step="0.00000001" value="'.count($options_indicator['SELL_OCO_start_S_Price']).'">';
						foreach ($options_indicator['SELL_OCO_start_S_Price'] as $key => $value) {
							$result .="<td>".$value."</td>";
						}
					}
				$result .= '</tr>';


				foreach ($strateg['indicator_arrey'] as $key => $value) {
					//значения по умолчанию
					if ($value['operator'] == '<') {
						$min = $analytics_arrey['all'][$value['indicator']]['min'];
						$max = $analytics_arrey['all'][$value['indicator']]['avg'];
						$sub = bcsub($max, $min, 8);
						$step = bcdiv($sub, 10, 8);
					}else if ($value['operator'] == '>') {
						$min = $analytics_arrey['all'][$value['indicator']]['avg'];
						$max = $analytics_arrey['all'][$value['indicator']]['max'];
						$sub = bcsub($max, $min, 8);
						$step = bcdiv($sub, 10, 8);
					}else if ($value['operator'] == '='){
						$min = $value['value'];
						$max = $value['value'];
						$step = 0;
					}

		 			$result .= '<tr>';
					$result .= '<td>'.$value['indicator'].'</td>';
					$result .= '<td><input size="5" type="number" name="config['.$value['indicator'].'][min]" step="0.00000001" value="'.$min.'"></td>';
					$result .= '<td><input size="5" type="number" name="config['.$value['indicator'].'][max]" step="0.00000001" value="'.$max.'"></td>';
					$result .= '<td><input size="5" type="number" name="config['.$value['indicator'].'][step]" step="0.00000001" value="'.$step.'"></td>';
					$result .= '<input size="5" type="hidden" name="config['.$value['indicator'].'][count]" step="0.00000001" value="">';
						if (strcasecmp($_POST['button'], 'options') == 0 || strcasecmp($_POST['button'], 'COMBINATIONS') == 0||strcasecmp($_POST['button'], 'ANALYTICS_indicators') == 0) {
							foreach ($options_indicator[$value['indicator']] as $key => $value) {
								$result .="<td>".$value."</td>";
							}
						}
					$result .= '</tr>';
				}
		}

		$result .= '</table><br/>';
		$result .= '<input type="submit" name="button" value="OPTIONS">&nbsp;';
		$result .= '<input type="submit" name="button" value="COMBINATIONS">&nbsp;&nbsp;';

		$startTime = time() - $Bin->interval[$strateg['interval']]*1000;
		$result .= '<p>УСТАНОВЛЕН период 1000 последних свичей. МОЖНО ИЗМЕНИТЬ : start <input type="datetime-local"  name="startTime" value="'.date('Y-m-d', $startTime).'T'.date('H:i', $startTime).'"/>&nbsp;
	               	 end<input type="datetime-local"  name="endTime" value="'.date('Y-m-d').'T'.date('H:i').'"/></p>';
		$result .= '<input type="submit" name="button" value="ANALYTICS_indicators"> &nbsp;Подбор настроек статегии &nbsp;';


		$result .= '<input type="submit" name="button" value="TEST COMBINATIONS">';
		$result .= '</form><br/>';
		echo $result ;

		if (strcasecmp($_POST['button'], 'COMBINATIONS') == 0) {
			$combinations = Functions::combinations_options($options_indicator, array_keys($options_indicator));
			Functions::showArrayTable($combinations, 'COMBINATIONS: <strong>'. number_format(count($combinations), 0, ',', ' ')."</strong> вариантов настроек<br/>");

		}elseif (strcasecmp($_POST['button'], 'ANALYTICS_indicators') == 0) {
			$funded_klines = $Bin->funded_klines($strateg, strtotime($_POST['startTime'])*1000, strtotime($_POST['endTime'])*1000);


			$analytics_indicators =  Functions::analytics_indicators($strateg, $funded_klines);
			Functions::showAnalytics_indicators($analytics_indicators, 'analytics_indicators');


			$settings_strategy = Functions::settings_strategy($strateg, $analytics_indicators, $funded_klines);
			// Functions::show($settings_strategy, 'settings_strategy');

			// Functions::best_indicators($strateg, $funded_klines);
			// Functions::show($analytics_arrey, 'analytics_arrey');

			// $analytics_arrey = $Indicators->analytics_klines($strateg['interval'], $funded_klines);
			// Functions::show($analytics_arrey, 'analytics_arrey');


		}elseif (strcasecmp($_POST['button'], 'TEST COMBINATIONS') == 0) {
				// Functions::show($_POST);

				$funded_klines = $Bin->funded_klines($strateg, strtotime($_POST['startTime'])*1000, strtotime($_POST['endTime'])*1000);
				// Functions::showArrayTable($funded_klines, 'Всего '.count($funded_klines));

			// // for ($i=0; $i < 300; $i++) {
				$options_indicator = Functions::options_indicator($_POST['config']);
				$example = Functions::array_map_keys(function($a,$b){return $a+$b;}, $_POST['config'], $options_indicator);
				// Functions::showArrayTable($example, 'Настройки ');

				$combinations = Functions::combinations_options($options_indicator, array_keys($options_indicator));
				$all = count($combinations);

				//тестируем по сылке
				Functions::test_combination($combinations, $strateg, $funded_klines);
				Functions::showArrayTable_key($combinations, 'Всего комбинаций  '. $all. ' ЭФЕКТИВНЫЕ комбинации '.count($combinations));

				//сохраняем первую
				// $settings_statistics = Functions::settings_statistics($combinations);
				// Functions::showArrayTable($settings_statistics, 'settings_statistics');

				echo 'Время выполнения скрипта: ', round(microtime(true) - $start, 4), ' сек.<br/>';
				echo 'Обем памяти: ', (memory_get_usage() - $mem_start)/1000000, ' мегабайта.<br/><br/><br/><br/>';

				// ob_flush();
				// flush();

			// }

		}
	die();
}elseif($_GET['action'] == 'remove') {
	$Strategies = new Strategies($_POST['login']);
	$Strategies->remove_strateg($_POST);
	Functions::show($_POST, '');
	die('Стратегия удалина');
}elseif($_GET['action'] == 'history') {

	$Bin->initialization($Users->user_arrey[$_POST['login']]['binance']['config']['KEY'], $Users->user_arrey[$_POST['login']]['binance']['config']['SEC']);
	$strateg = $Users->user_arrey[$_POST['login']][$_POST['exchange']]['strategies'][$_POST['key']];
	$filehistory = 'D:\binance\history_user\\'.$_POST['login'].'\\'.$strateg['symbol'].'.txt';
 	$allOrders = $Bin->history_orders_symbol($strateg, $filehistory);

        $time = array_column($allOrders, 'updateTime');
        array_multisort($time, SORT_ASC, $allOrders);


  //       $status = array_unique(array_column($allOrders, 'status'));
		// Functions::show($status, $status);
		$sum = $balansQty = 0;
		foreach ($allOrders as $key => $value) {

			// if (stristr($value['clientOrderId'], $_POST['key'])===false){
			// 	unset($allOrders[$key]);
			// 	continue;
			// }


			if ($value['status'] == 'EXPIRED'|| $value['status'] == 'CANCELED'){
				unset($allOrders[$key]);
				continue;
			}
			if ($value['status'] != 'NEW') {
				$allOrders[$key]['price'] = bcdiv($value['cummulativeQuoteQty'], $value['executedQty'], 8);
			}


			if ($value['side'] == 'BUY') {
				$sum -= $value['cummulativeQuoteQty'];
				$balansQty += $value['executedQty'];

			}else{
				$sum += $value['cummulativeQuoteQty'];
				$balansQty -= $value['executedQty'];

			}

			$allOrders[$key]['commission']=bcmul($value['cummulativeQuoteQty'], 0.00075, 8);
			$sum -= $allOrders[$key]['commission'];


			$allOrders[$key]['balansQty'] = round($balansQty,2);
			$allOrders[$key]['balansUSDT'] = round($sum, 2);
		}

			$invest = abs(min(array_column($allOrders, 'balansUSDT')));
			$roi = bcdiv($sum, $invest, 4)*100;
			$title = 'Cтратегия: '.$_POST['key'].'<br/>';
			$title .= 'Количество операций: '.count($allOrders).'<br/>';
			$title .= 'Максимальная инвистиция: '.$invest.'<br/>';
			$title .= 'Баланс: <font size="10" color="green" face="Arial">'.round($sum,2).'</font>$<br/>';
			$title .= 'ROI: <font size="10" color="green" face="Arial">'.$roi.'</font>%<br/>';

		Functions::showHistory(array_reverse($allOrders), $title);

	// if ($myTrades = $Bin->myTrades(array('symbol'=>$strateg['symbol']))){
	// 	Functions::showArrayTable(array_reverse($myTrades));
	// }
	die('История');
}elseif($_GET['action'] == 'analytics_indicators') {
	$dir_analyticsSymbol = 'E:/bot/file/analyticsSymbol/';
    $files = array_reverse(scandir($dir_analyticsSymbol, 1));//сканируем директорию с файлами
    foreach ($files as $key => $name) {

	        $file = $dir_analyticsSymbol.$name;

	        if (!is_file($file)) continue;//проверяем существование файла

			$analytics_indicators = Functions::readFile($file);
			// Functions::show_Analytics_symbol($analytics_indicators, $name);
			echo $analytics_indicators['BTCUSDT']['indicator_klin_Coefficient_1m']['up_avg'];
			Functions::show($analytics_indicators, $name);
			// foreach ($analytics_indicators as $key => $value) {
			// 	Functions::show($value, $key);
			// }

	}
}elseif($_GET['action'] == 'testSymbols') {
	$testSymbols_file = 'E:/bot/file/380505953494/testSymbols.txt';
	if (is_file($testSymbols_file)){
		$testSymbols = Functions::readFile($testSymbols_file);
		Functions::showArrayTable($testSymbols, 'testSymbols');
	}else{
		echo "НЕТ файла: ".$testBUY_file;
	}
}elseif($_GET['action'] == 'testBUY') {
	$testBUY_file = 'E:/bot/file/380505953494/testBUY.txt';
	if (is_file($testBUY_file)){
		$testBUY = Functions::readFile($testBUY_file);
		$sum_symbols = [];
		$sum = 0;
		foreach ($testBUY as $key => &$v) {
				if (!array_key_exists($v['symbol'], $sum_symbols)) {
					$sum_symbols[$v['symbol']] = ['SL' => 0, 'TP' => 0, '' => 0, 'sum'=> 0];
				}

				$sum_symbols[$v['symbol']][$v['status']]++;
				$sum_symbols[$v['symbol']]['sum'] += (float)$v['profit'];

				$sum += (float)$v['profit'];
				$v['balans'] = $sum;
		}

        $stolb = array_column($sum_symbols, 'sum');
        array_multisort($stolb, SORT_DESC, $sum_symbols);

		Functions::showArrayTable_key($sum_symbols);
		foreach ($sum_symbols as $key => $vlue) {
			$testBUYsymbol = Functions::multiSearch($testBUY, array('symbol' => $key));
			Functions::showArrayTable_key($testBUYsymbol, $vlue['sum']);
		}
		// Functions::showArrayTable_key($testBUY, $sum);
	}else{
		echo "НЕТ файла: ".$testBUY_file;
	}
}
echo $result;

// die();

//******************************************************************
//отправка SMS
	// require "./model/apisms_c.php";
	// $Gateway = new APISMS('843e5bec02b4c36e0202d4a0cf227eaa', 'd9c9f37cc5b86d4368da4cd7b3781a27', 'http://atompark.com/api/sms/', false);
	// 	$res = $Gateway->execCommad(
	// 		'sendSMS',
	// 		array(
	// 			'sender' => 'xt.ua',
	// 			'text' => 'привет',
	// 			'phone' => '380505953494',
	// 			'datetime' => null,
	// 			'sms_lifetime' => 0
	// 		)
	// 	);
	// Functions::show($res);
//******************************************************************

// Trade Updates via WebSocket
// $api->kline($symbolBUY, "1m", function($api, $symbol, $chart) {
// 	Functions::showArrayTable($chart, "{$symbol}\n");
//     $endpoint = strtolower( $symbol ) . '@ticker';
//     $api->terminate( $endpoint );
// });
//
// $api->trades(["BNBBTC"], function($api, $symbol, $trades) {
//     echo "{$symbol} trades update".PHP_EOL;
//     print_r($trades);
//     $endpoint = strtolower( $symbol ) . '@trades';
//     $api->terminate( $endpoint );
// });

// $api->depthCache(["BNBBTC"], function($api, $symbol, $depth) {
//     echo "{$symbol} depth cache update\n";
//     $limit = 11; // Show only the closest asks/bids
//     $sorted = $api->sortDepth($symbol, $limit);
//     $bid = $api->first($sorted['bids']);
//     $ask = $api->first($sorted['asks']);
//     echo $api->displayDepth($sorted);
//     echo "ask: {$ask}\n";
//     echo "bid: {$bid}\n";
//     $endpoint = strtolower( $symbol ) . '@depthCache';
//     $api->terminate( $endpoint );
// });


// echo "Подключеные файлы";
// $show->show(get_included_files());

// echo "Подключеные классы";
// $show->show(get_declared_classes());

// echo 'Время выполнения скрипта: ', round(microtime(true) - $start, 4), ' сек.<br/>';
// echo 'Обем памяти: ', (memory_get_usage() - $mem_start)/1000000, ' мегабайта.<br/><br/><br/><br/>';
die();

