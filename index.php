<?php

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

header( 'Content-type: text/html; charset=utf-8' );
echo "<!DOCTYPE html>";

echo '<link rel="stylesheet" href="./html/css/style.css">';
echo '<script type="text/javascript" src="./libraries/jquery.js"></script>';
echo '<script type="text/javascript" src="./html/script/script.js"></script>';

// echo '<h1 class="area">&#36&#36&#36</h1>';
echo '<h1 class="area">BUCKS</h1>';
echo '<form action="index.php" method="post"><input type="submit" value="НА ГЛАВНУЮ"></form>';


$Bin = new Binance();

$Users = new Users();
$GLOBALS['Users'] = &$Users;

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

$result = '';
if ($_GET['action'] == '') {
	echo '<p style="text-align: right; "><a href="./cron/bot.php?action=show" target="_blank">bot</a></p>';
	echo '<p style="text-align: right; "><a href="./cron/updating_strategies.php?action=show" target="_blank">updat_strategies</a></p>';


	//проверяем стратегии user
	if (count($Users->user_arrey)>0) {

		foreach ($Users->user_arrey as $key => $user) {
			$Bin->initialization($user[$exchange]['config']['KEY'], $user[$exchange]['config']['SEC']);
			// Functions::show($Bin, '');

			//Получить свободный баланс АК
			if ($accountBalance = $Bin->accountBalance($base)){
				Functions::showArrayTable($accountBalance, "АКТИВЫ ".$user['login']);
			}
			if (count($user[$exchange]['strategies'])>0) {
				Functions::showStrategies($user, "СТРАТЕГИИ ".$exchange);
			}
			echo '<p><form action="index.php?action=strateg_add" method="post">
				<input type="hidden" name="login" value="'.$user['login'].'">
				<input type="hidden" name="exchange" value="'.$exchange.'">
				<input type="submit" value="Добавить стратегию для '.$exchange.'">
				</form><br/></p>';
			//открытые ордера
			if ($orderOPEN = $Bin->orderOPEN(array())){
				Functions::showArrayTable($orderOPEN);
			}
			//*************************************************************************************************************************

			// if ($allOrderList = $Bin->allOrderList(array())){
			// 	Functions::showArrayTable($allOrderList);
			// }


			// if ($allOCO = $Bin->allOCO(array())){
			// 	Functions::showArrayTable($allOCO);
			// }

			//*************************************************************************************************************************

		}

	}
	echo '<form action="index.php?action=user_add" method="post" ><input type="submit" value="Добавить пользователя"></form>';
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
		$Strategies = new Strategies($_POST['login']);
		$Strategies->strateg_add($_POST);
	}

	// $exchange = 'binance';

	if($_GET['step'] == ''){
		$result .= '<form action="index.php?action=strateg_add&step=1" method="post">';
		$result .= '<p>Новая стратегия</p>';
		$result .= '<input type="hidden" name="login" value="'.$_POST['login'].'">';
		$result .= '<input type="hidden" name="exchange" value="'.$_POST['exchange'].'">';


		$result .= '<p>Название: <input size="100" type="text" name="title" required placeholder="Укажите название"/>&nbsp;&nbsp;';
		$result .= 'Уникальный ключ: <input type="text" readonly style="background:#e6e6e6;" name="key" value="'.uniqid('').'" /></p>';

		$result .= '<p>Символ:<select size="0"  name="symbol" required placeholder="Выберите торговую пару"><option selected disabled>Выбрать</option>' ;
		        foreach ($Bin->ticker24hr as $key => $value) {
		                if (!$symbolInfo = Functions::multiSearch($Bin->exchangeInfo['symbols'], array('symbol' => $value['symbol'], 'status'=>'TRADING'))) continue;
		                 // Исключаем если база не USDT
		                if ($symbolInfo[0]['quoteAsset']!='USDT') continue;
		                $result .= '<option value="'.$value['symbol'].'">'.$value['symbol'].'</option>';
		        }
		$result .='</select>&nbsp;&nbsp;';
		$result .= 'Таймфрейм:<select size="0"  name="interval" required placeholder="Выберите таймфрейм"><option selected >off</option>' ;
		         foreach (['1m','3m','5m','15m','30m','1h','2h','4h','6h','8h','12h','1d','3d','1W','1M'] as $key => $value) {
		                $result .= '<option value="'.$value.'">'.$value.'</option>';
		         }
		$result .='</select>&nbsp;&nbsp;';

		$result .= 'Торговый лимит: <input size="0" type="number"  name="trading_limit"  value="11" min="11" />&nbsp;&nbsp;';
		$result .= 'Кофициент PROFIT: <input size="0" type="number"  name="coefficient_profit" value="1.003" max="10" min="1.003" step="0.00000001"/>&nbsp;&nbsp;';
		$result .= 'Кофициент STOP LOSS: <input size="0" type="number"  name="coefficient_stop_loss" value="0.997" step="0.00000001"/></p>';

		$result .= 'Показать анализ последних 1000 свечей при условии тренда свечи:<input type="radio" checked name="trend_end_klin" value="NO"/>NO' ;
		         foreach (['all', 'up', 'down', 'equally'] as $key => $value) {
		             $result .= '<input type="radio" name="trend_end_klin" value="'.$value.'"/>'.$value;
		         }

		$result .='<p><input type="submit" value="ПОКАЗАТЬ"/></p></form>';


	}elseif ($_GET['step'] == 1){

		$all_indicators = $Indicators->all_indicator($_POST['symbol'], $_POST['interval']);
		if ($_POST['interval']!='off') {
			$analytics_arrey = $Indicators->analytics_klines($_POST['interval']);
		}

		// Functions::show($Indicators->analytics_arrey);
		$result .= '<form action="index.php?action=strateg_add&step=seve" method="post">';
		$result .= '<p>Новая стратегия</p>';
		$result .= '<input type="hidden" name="login" value="'.$_POST['login'].'">';
		$result .= '<input type="hidden" name="exchange" value="'.$_POST['exchange'].'">';
		$result .= '<p>Название: <input size="100" type="text" name="title" value="'.$_POST['title'].'"/>&nbsp;&nbsp;';
		$result .= 'Уникальный ключ: <input type="text" readonly style="background:#e6e6e6;" name="key" value="'.$_POST['key'].'" /></p>';
		$result .= '<p>Символ:<select size="0"  name="symbol" readonly style="background:#e6e6e6;"><option selected value="'.$_POST['symbol'].'">'.$_POST['symbol'].'</option>' ;
		$result .='</select>&nbsp;&nbsp;';
		$result .= 'Таймфрейм:<select size="0"  name="interval" readonly style="background:#e6e6e6;"><option selected value="'.$_POST['interval'].'">'.$_POST['interval'].'</option>' ;
		$result .='</select>&nbsp;&nbsp;';
		$result .= 'Торговый лимит: <input size="0" type="number"  name="trading_limit"  value="'.$_POST['trading_limit'].'" min="11" />&nbsp;&nbsp;';
		$result .= 'Кофициент PROFIT: <input size="0" type="number"  name="coefficient_profit" value="'.$_POST['coefficient_profit'].'" max="10" min="1.003" step="0.00000001"/>&nbsp;&nbsp;';
		$result .= 'Кофициент STOP LOSS: <input size="0" type="number"  name="coefficient_stop_loss" value="'.$_POST['coefficient_stop_loss'].'" step="0.00000001"/></p>';

		$result .='<p>Индикаторы:</p>';
		$result .="<table border='1'>";
		$result .="<tr><th>Title</th><th>&#10003;</th><th>Indicator</th><th>value now</th><th>Operator</th><th>Index</th>";
		if ($_POST['interval'] != 'off' && $_POST['trend_end_klin'] != 'NO') $result .="<th>AVG</th><th>MIN</th><th>MAX</th>";
		$result .="</tr>";
		$i=0;
 		foreach ($Indicators->indicator_arrey as $key => $value) {
                $result .= '<tr><td title="'.$value['description'].'">'.$value['title'].'</td><td><input type="checkbox" name="indicator_arrey['.$i.'][indicator]" value="'.$key.'"></td><td>'.$key.'</td><td>'.$all_indicators[$key].'</td>';
				$result .= '<td><select size="0"  name="indicator_arrey['.$i.'][operator]" required placeholder="Выберите operator"><option selected disabled>Выбрать</option>';
						foreach (['<'=>'<', '='=>'=', '>'=>'>'] as $keyO => $valueO) {
				            $result .= '<option value="'.$valueO.'">'.$valueO.'</option>';
				        }
				$result .= '</select></td>';
                $result .= '<td><input size="5" type="number" name="indicator_arrey['.$i.'][value]" step="0.00000001"></td>';
                if (isset($analytics_arrey[$_POST['trend_end_klin']][$key])) {
	                $result .= '<td>'.$analytics_arrey[$_POST['trend_end_klin']][$key]['avg'].'</td>';
	                $result .= '<td>'.$analytics_arrey[$_POST['trend_end_klin']][$key]['min'].'</td>';
	                $result .= '<td>'.$analytics_arrey[$_POST['trend_end_klin']][$key]['max'].'</td></tr>';
                }
                $i++;
        }
	    $result .="</table><br/>";

		if ($_POST['interval'] != 'off' && $_POST['trend_end_klin'] != 'NO') {
			$result .='<div class="block1"><p>СТАТИСТИКА индикаторов klin</p>';
			$result .='<p>'.$analytics_arrey['all']['time']['description'].': '.$analytics_arrey['all']['time']['value'].'</p>';
			$result .='<p>'.$analytics_arrey['all']['count']['description'].': '.$analytics_arrey['all']['count']['value'].'</p>';
			$result .='<p>'.$analytics_arrey['all']['priceChangeCoefficient']['description'].': '.$analytics_arrey['all']['priceChangeCoefficient']['value'].'</p>';
			$result .='<p>'.$analytics_arrey['all']['price_max']['description'].': '.$analytics_arrey['all']['price_max']['value'].'</p>';
			$result .='<p>'.$analytics_arrey['all']['price_min']['description'].': '.$analytics_arrey['all']['price_min']['value'].'</p>';
			$result .='<p>даные статистики (AVG, MIN, MAX) выбраны и  посчитаны по условию: символ: <strong>'.$_POST['symbol'].'</strong>, таймфрейм: <strong>'.$_POST['interval'].'</strong>, тренд: <strong>'.$_POST['trend_end_klin'].'</strong>, выборка: <strong>'.$analytics_arrey[$_POST['trend_end_klin']]['count'].'</strong></p>';
			$result .= '</div>';
		}

		$result .= '<input type="hidden" readonly name="setting" value="'.date("Y-m-d H:i:s", time()).'NEW user" /></p>';
		$result .= '<p><input type="radio" checked name="status" value="OFF"/>OFF' ;
		$result .= '<input type="radio" name="status" value="ON"/>ON</p>';

		$result .='<input type="submit" value="Сохранить"/></p></form>';

		$result .= '<form action="index.php?action=strateg_add&step=" method="post">
						<input type="submit" value="Назад">
					</form>';
	}
}elseif($_GET['action'] == 'strateg_change') {
	$strateg = $Users->user_arrey[$_POST['login']][$_POST['exchange']]['strategies'][$_POST['key']];

	if ($_GET['step'] == 'seve') {
		$Strategies = new Strategies($_POST['login']);
		$strateg = $Strategies->strateg_change($_POST);
	}

		$all_indicators = $Indicators->all_indicator($strateg['symbol'], $strateg['interval']);
		if ($strateg['interval']!='off') {
			$analytics_arrey = $Indicators->analytics_klines($strateg['interval']);
		}
		$result .='<div style="text-align: right;"><form action="index.php?action=remove" method="post">
            <input type="hidden" name="login" value="'.$_POST['login'].'">
            <input type="hidden" name="exchange" value="'.$_POST['exchange'].'">
            <input type="hidden" name="key" value="'.$_POST['key'].'">
            <input type="submit" value="Удалить стратегию">
            </form></div>';

		// Functions::show($analytics_arrey, "analytics_arrey");
		$result .= '<form action="index.php?action=strateg_change&step=seve" method="post">';
		$result .= '<p>Редактирование стратегии</p>';
		$result .= '<input type="hidden" name="login" value="'.$_POST['login'].'">';
		$result .= '<input type="hidden" name="exchange" value="'.$_POST['exchange'].'">';
		$result .= '<p>Название: <input size="100" type="text" name="title" value="'.$strateg['title'].'"/>&nbsp;&nbsp;';
		$result .= 'Уникальный ключ: <input type="text" readonly style="background:#e6e6e6;" name="key" value="'.$_POST['key'].'" /></p>';
		$result .= '<p>Символ:<select size="0"  name="symbol" readonly style="background:#e6e6e6;"><option selected value="'.$strateg['symbol'].'">'.$strateg['symbol'].'</option>' ;
		$result .='</select>&nbsp;&nbsp;';
		$result .= 'Таймфрейм:<select size="0"  name="interval" readonly style="background-color:#e6e6e6;"><option selected value="'.$strateg['interval'].'">'.$strateg['interval'].'</option>' ;
		$result .='</select>&nbsp;&nbsp;';
		$result .= 'Торговый лимит: <input size="0" type="number"  name="trading_limit"  value="'.$strateg['trading_limit'].'" min="11" />&nbsp;&nbsp;';
		$result .= 'Кофициент PROFIT: <input size="0" type="number"  name="coefficient_profit" value="'.$strateg['coefficient_profit'].'" max="10" min="1.003" step="0.00000001"/>&nbsp;&nbsp;';
		$result .= 'Кофициент STOP LOSS: <input size="0" type="number"  name="coefficient_stop_loss" value="'.$strateg['coefficient_stop_loss'].'" step="0.00000001"/></p>';

		$result .='Индикаторы:';
			$result .="<table border='1'>";
			$result .="<tr><th>Title</th><th>&#10003;</th><th>Indicator</th><th>Operator</th><th>Index</th><th>AVG</th><th>MIN</th><th>MAX</th></tr>";
			$result .= '<tr>';
			$i = 0;
	 		foreach ($strateg['indicator_arrey'] as $key => $value) {
	 			$result .= '<tr><td>'.$Indicators->indicator_arrey[$value['indicator']]['title'].'</td>';
 				$result .= '<td><input type="checkbox"  checked disabled></td>';
 				$result .= '<td>'.$value['indicator'].'</td>';
				$result .= '<input type="hidden"  name="indicator_arrey['.$i.'][indicator]" value="'.$value['indicator'].'">';
				$result .= '<td><input type="text"  name="indicator_arrey['.$i.'][operator]" value="'.$value['operator'].'" readonly style="background-color:#e6e6e6;"></td>';
 				$result .= '<td><input size="5" type="number" name="indicator_arrey['.$i.'][value]" step="0.00000001" value="'.$value['value'].'"></td>';

                $result .= '<td>'.$analytics_arrey['all'][$value['indicator']]['avg'].'</td>';
                $result .= '<td>'.$analytics_arrey['all'][$value['indicator']]['min'].'</td>';
                $result .= '<td>'.$analytics_arrey['all'][$value['indicator']]['max'].'</td></tr>';
                $i++;
		    }
		    $result .= '</table><br/>';

		$result .= '<input type="hidden" readonly name="config" value="" /></p>';
		$result .= '<input type="hidden" readonly name="setting" value="'.date("Y-m-d H:i:s", time()).' user" /></p>';
		$result .= '<p>' ;
		foreach (['OFF', 'ON'] as $key => $value) {
			$checked = $strateg['status'] == $value?'checked':'';
			$result .= '<input type="radio" '.$checked.' name="status" value="'.$value.'"/>'.$value ;
		}
		$result .= '</p>' ;
		$result .='<input type="submit" value="Сохранить"/>&nbsp;'.$strateg['setting'].'</form>';
}elseif($_GET['action'] == 'optimization') {
	$strateg = $Users->user_arrey[$_POST['login']][$_POST['exchange']]['strategies'][$_POST['key']];

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
		$result .= 'Торговый лимит: <input size="0" type="number"  name="trading_limit"  value="'.$strateg['trading_limit'].'" min="11" />&nbsp;&nbsp;';
		$result .= 'Кофициент PROFIT: <input size="0" type="number"  name="coefficient_profit" value="'.$strateg['coefficient_profit'].'" max="10" min="1.003" step="0.00000001"/>&nbsp;&nbsp;';
		$result .= 'Кофициент STOP LOSS: <input size="0" type="number"  name="coefficient_stop_loss" value="'.$strateg['coefficient_stop_loss'].'" step="0.00000001"/></p>';

		$result .='Индикаторы:';
			$result .="<table border='1'>";
			$result .="<tr><th>Title</th><th>&#10003;</th><th>Indicator</th><th>Operator</th><th>Index</th><th>AVG</th><th>MIN</th><th>MAX</th></tr>";
			$result .= '<tr>';
			$i = 0;
	 		foreach ($strateg['indicator_arrey'] as $key => $value) {
	 			$result .= '<tr><td>'.$Indicators->indicator_arrey[$value['indicator']]['title'].'</td>';
 				$result .= '<td><input type="checkbox"  checked disabled></td>';
 				$result .= '<td>'.$value['indicator'].'</td>';
				$result .= '<input type="hidden"  name="indicator_arrey['.$i.'][indicator]" value="'.$value['indicator'].'">';
				$result .= '<td><input type="text"  name="indicator_arrey['.$i.'][operator]" value="'.$value['operator'].'" readonly  style="background:#e6e6e6;"></td>';
 				$result .= '<td><input size="5" type="number" name="indicator_arrey['.$i.'][value]" step="0.00000001" value="'.$value['value'].'"></td>';

                $result .= '<td>'.$analytics_arrey['all'][$value['indicator']]['avg'].'</td>';
                $result .= '<td>'.$analytics_arrey['all'][$value['indicator']]['min'].'</td>';
                $result .= '<td>'.$analytics_arrey['all'][$value['indicator']]['max'].'</td>';

                $i++;
	        }

		    $result .= '</tr>';
		    $result .= '</table><br/>';
		$result .= '<input type="hidden" readonly name="config" value="" /></p>';
		$result .= '<input type="hidden" readonly name="setting" value="'.date("Y-m-d H:i:s", time()).' user" /></p>';

		$result .= '<p>' ;
		foreach (['OFF', 'ON'] as $key => $value) {
			$checked = $strateg['status'] == $value?'checked':'';
			$result .= '<input type="radio" '.$checked.' name="status" value="'.$value.'"/>'.$value ;
		}
		$result .= '</p>' ;
		$result .='<p><input type="submit" value="Сохранить"/>&nbsp;'.$strateg['setting'].'</p></form>';

		if ($_POST['trend_end_klin'] != 'NO') {
			$result .='<div class="block1"><p>СТАТИСТИКА индикаторов klin (Даные для анализа) </p>';
			$result .='<p>'.$analytics_arrey['all']['time']['description'].': '.$analytics_arrey['all']['time']['value'].'</p>';
			$result .='<p>'.$analytics_arrey['all']['count']['description'].': '.$analytics_arrey['all']['count']['value'].'</p>';
			$result .='<p>'.$analytics_arrey['all']['priceChangeCoefficient']['description'].': '.$analytics_arrey['all']['priceChangeCoefficient']['value'].'</p>';
			$result .='<p>'.$analytics_arrey['all']['price_max']['description'].': '.$analytics_arrey['all']['price_max']['value'].'</p>';
			$result .='<p>'.$analytics_arrey['all']['price_min']['description'].': '.$analytics_arrey['all']['price_min']['value'].'</p>';
			$result .='<p>даные статистики (AVG, MIN, MAX) выбраны и  посчитаны по условию: символ: <strong>'.$_POST['symbol'].'</strong>, таймфрейм: <strong>'.$_POST['interval'].'</strong>, тренд: <strong>'.$_POST['trend_end_klin'].'</strong>, выборка: <strong>'.$analytics_arrey[$_POST['trend_end_klin']]['count'].'</strong></p>';
			$result .= '</div>';
		}

		$result .='<br/>Условия расчета вариантов каждой НАСТРОЙКИ';
		$result .='<form action="index.php?action=optimization&step=strateg_config_save" method="post">';

		$result .='<input type="hidden" name="login" value="'.$_POST['login'].'">';
        $result .='<input type="hidden" name="exchange" value="'.$_POST['exchange'].'">';
        $result .='<input type="hidden" name="key" value="'.$_POST['key'].'">';

		$result .='<table border="1">';
		$result .="<tr><th>setting</th><th>MIN</th><th>MAX</th><th>STEP</th></tr>";

		if (count($strateg['config'])>1) {
			foreach ($strateg['config'] as $key_config => $value) {
				$result .= '<tr>';
				$result .= '<td>'.$key_config.'</td>';
				$result .= '<td><input size="5" type="number" name="config['.$key_config.'][min]" step="0.00000001" value="'.$value['min'].'"></td>';
				$result .= '<td><input size="5" type="number" name="config['.$key_config.'][max]" step="0.00000001" value="'.$value['max'].'"></td>';
				$result .= '<td><input size="5" type="number" name="config['.$key_config.'][step]" step="0.00000001" value="'.$value['step'].'"></td>';
				$result .= '<td><input size="5" type="hidden" name="config['.$key_config.'][count]" step="0.00000001" value="'.$value['count'].'"></td>';
				$result .= '</tr>';
			}
		}else{
				$result .= '<tr>';
				$result .= '<td>Кофициент PROFIT:</td>';
				$result .= '<td><input size="5" type="number" name="config[coefficient_profit][min]" step="0.00000001" value="1.01"></td>';
				$result .= '<td><input size="5" type="number" name="config[coefficient_profit][max]" step="0.00000001" value="1.01"></td>';
				$result .= '<td><input size="5" type="number" name="config[coefficient_profit][step]" step="0.00000001" value="0"></td>';
				$result .= '<input size="5" type="hidden" name="config[coefficient_profit][count]" step="0.00000001" value="0">';
				$result .= '</tr>';


				$result .= '<tr>';
				$result .= '<td>Кофициент STOP LOSS::</td>';
				$result .= '<td><input size="5" type="number" name="config[coefficient_stop_loss][min]" step="0.00000001" value="0.999"></td>';
				$result .= '<td><input size="5" type="number" name="config[coefficient_stop_loss][max]" step="0.00000001" value="0.999"></td>';
				$result .= '<td><input size="5" type="number" name="config[coefficient_stop_loss][step]" step="0.00000001" value="0"></td>';
				$result .= '<input size="5" type="hidden" name="config[coefficient_stop_loss][count]" step="0.00000001" value="0">';
				$result .= '</tr>';





				foreach ($strateg['indicator_arrey'] as $key => $value) {
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
					$result .= '<input size="5" type="hidden" name="config['.$value['indicator'].'][count]" step="0.00000001" value="0">';
					$result .= '</tr>';
				}
		}

		$result .= '</table><br/>';
		$startTime = time() - $Bin->interval[$strateg['interval']]*1000;
		$result .= '<p>УСТАНОВЛЕН период тестирования 1000 последних свичей. МОЖНО ИЗМЕНИТЬ : start <input type="datetime-local"  name="startTime" value="'.date('Y-m-d', $startTime).'T'.date('H:i', $startTime).'"/>&nbsp;
	               	 end<input type="datetime-local"  name="endTime" value="'.date('Y-m-d').'T'.date('H:i').'"/></p>';
		$result .= '<input type="submit" name="button" value="BEST_indicators">&nbsp; Также можно протестировать &nbsp;';
		$result .= '<input type="submit" name="button" value="OPTIONS">&nbsp;';
		$result .= '<input type="submit" name="button" value="COMBINATIONS">&nbsp;&nbsp;';

		$result .= '<input type="submit" name="button" value="TEST COMBINATIONS">';
		$result .= '</form>';
		echo $result ;

		if (strcasecmp($_POST['button'], 'options') == 0) {
			$options_indicator = Functions::options_indicator($_POST['config']);

			$example = Functions::array_map_keys(create_function('$a,$b','return $a+$b;'), $_POST['config'], $options_indicator);
			Functions::showArrayTable($example, 'Настройки ');

		}elseif (strcasecmp($_POST['button'], 'COMBINATIONS') == 0) {
			$options_indicator = Functions::options_indicator($_POST['config']);
			$example = Functions::array_map_keys(create_function('$a,$b','return $a+$b;'), $_POST['config'], $options_indicator);
			Functions::showArrayTable($example, 'Настройки ');


			$combinations = Functions::combinations_options($options_indicator, array_keys($options_indicator));
			echo 'COMBINATIONS: <strong>'. number_format(count($combinations), 0, ',', ' ')."</strong> вариантов настроек<br/>";
			Functions::showArrayTable($combinations, '');

		}elseif (strcasecmp($_POST['button'], 'BEST_indicators') == 0) {
			$funded_klines = $Bin->funded_klines($strateg, strtotime($_POST['startTime'])*1000, strtotime($_POST['endTime'])*1000);
			$best_indicators = Functions::best_indicators($strateg, $funded_klines);

			Functions::show($best_indicators, 'best_indicators');

		}elseif (strcasecmp($_POST['button'], 'TEST COMBINATIONS') == 0) {
				// Functions::show($_POST);

				$funded_klines = $Bin->funded_klines($strateg, strtotime($_POST['startTime'])*1000, strtotime($_POST['endTime'])*1000);
				// Functions::showArrayTable($funded_klines, 'Всего '.count($funded_klines));



			// // for ($i=0; $i < 300; $i++) {
				$options_indicator = Functions::options_indicator($_POST['config']);
				$example = Functions::array_map_keys(create_function('$a,$b','return $a+$b;'), $_POST['config'], $options_indicator);
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

	// Functions::show($Users->user_arrey[$_POST['login']]['binance']['config']['KEY']);
	$Bin = new binance($Users->user_arrey[$_POST['login']]['binance']['config']['KEY'], $Users->user_arrey[$_POST['login']]['binance']['config']['SEC']);
	$strateg = $Users->user_arrey[$_POST['login']][$_POST['exchange']]['strategies'][$_POST['key']];
	if ($allOrders = $Bin->allOrders(array('symbol'=>$strateg['symbol'], 'limit'=>1000))){
		$sum = 0;
		foreach ($allOrders as $key => $value) {

			if (stristr($value['clientOrderId'], $_POST['key'])===false){
				unset($allOrders[$key]);
				continue;
			}
			if ($value['status'] == 'EXPIRED'|| $value['status'] == 'CANCELED'){
				unset($allOrders[$key]);
				continue;
			}

			if ($value['side'] == 'BUY') {
				$sum -= $value['cummulativeQuoteQty'];
			}else{
				$sum += $value['cummulativeQuoteQty'];
			}
			$allOrders[$key]['price_fact'] ='';
			if ($value['status'] != 'NEW') {
				$allOrders[$key]['price_fact'] = bcdiv($value['cummulativeQuoteQty'], $value['executedQty'], 8);
			}
			$allOrders[$key]['balans'] = round($sum, 2);
		}

		$invest = abs(min(array_column($allOrders, 'balans')));
		$roi = bcdiv($sum, $invest, 4)*100;
		$title = 'Cтратегия: '.$_POST['key'].'<br/>';
		$title .= 'Количество операций: '.count($allOrders).'<br/>';
		$title .= 'Максимальная инвистиция: '.$invest.'<br/>';
		$title .= 'Баланс: <font size="10" color="green" face="Arial">'.round($sum,2).'</font>$<br/>';
		$title .= 'ROI: <font size="10" color="green" face="Arial">'.$roi.'</font>%<br/>';
		Functions::showHistory(array_reverse($allOrders), $title);
	}
	// if ($myTrades = $Bin->myTrades(array('symbol'=>$strateg['symbol']))){
	// 	Functions::showArrayTable(array_reverse($myTrades));
	// }
	die('История');
}
echo $result ;

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

echo 'Время выполнения скрипта: ', round(microtime(true) - $start, 4), ' сек.<br/>';
echo 'Обем памяти: ', (memory_get_usage() - $mem_start)/1000000, ' мегабайта.<br/><br/><br/><br/>';
die();

