<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// phpinfo();
ini_set('max_execution_time', 500000);    //  одно и тоже что set_time_limit(6000);

require "../model/functions_c.php";
require "../../libraries/binance_api/vendor/autoload.php";

$KEY = "7WDYbxAytNjWRo5jqz4ZFZp2v2J5UahQirEcOFlacaE7ykUQxQcGwQZcBwcFUUvH";
$SEC = "0Ql01kwiBjx8jWzm7iA5CMfj4ODhBuUq8VutXdGTkNe8OsHTBoUWckQBr2LITseg";



$api = new Binance\API($KEY, $SEC);
// $api = new Binance\RateLimiter($api);


$api->kline(["BTCUSDT", "EOSBTC"], "5m", function ($api, $symbol, $chart) {
	Functions::show($chart, date("H:i:s", time()));
	// var_dump($chart);
	//echo "{$symbol} ({$interval}) candlestick update\n";
	// $interval = $chart->i;
	// $tick = $chart->t;
	// $open = $chart->o;
	// $high = $chart->h;
	// $low = $chart->l;
	// $close = $chart->c;
	// $volume = $chart->q; // +trades buyVolume assetVolume makerVolume
	// echo "{$symbol} price: {$close}\t volume: {$volume}\n";

	$endpoint = strtolower($symbol) . '@kline_' . "5m";
	$api->terminate($endpoint);
});





// Trade Updates via WebSocket

// $symbolBUY = $symbol = "BNBBTC";
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
//     $limit = 5; // Show only the closest asks/bids
//     $sorted = $api->sortDepth($symbol, $limit);
//     $bid = $api->first($sorted['bids']);
//     $ask = $api->first($sorted['asks']);
// 	 echo $api->displayDepth($sorted);
//     echo "ask: {$ask}<br/>";
// 	 echo "bid: {$bid}\n";
//     $endpoint = strtolower( $symbol ) . '@depthCache';
//     $api->terminate( $endpoint );
// });


// echo "Подключеные файлы";
// Functions::show(get_included_files());

// echo "Подключеные классы";
// Functions::show(get_declared_classes());

//Functions::show($GLOBALS, "_GLOBAL");
// Functions::show($_SERVER, "_SERVER");
// Functions::show($_GET, "_GET");
// Functions::show($_POST, "_POST");
// Functions::show($_FILES, "_FILES");
// Functions::show($_COOKIE, "_COOKIE");
// Functions::show($_SESSION, "_SESSION");
// Functions::show($_ENV, "_ENV");

die();