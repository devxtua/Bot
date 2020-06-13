<?php
	$start = $_SERVER['REQUEST_TIME'];
	$mem_start = memory_get_usage();

	require "d:/domains/BUCKS/~core/model/binance_c.php";
	require "d:/domains/BUCKS/~core/model/functions_c.php";
	require "d:/domains/BUCKS/~core/model/indicators_c.php";
	require "d:/domains/BUCKS/~core/model/user_c.php";









	//********************************************************************
	$duration = round(microtime(true) - $start, 4);
	$mem = (memory_get_usage() - $mem_start)/1000000;
	$user = $_GET['action'] == 'show'?'user':'cron openserver';

    $file_log = 'D:\binance\log_CRON.txt';
	$log = Functions::readFile($file_log);
    $log[] = ['user' => $user, 'time_start' => date("Y-m-d H:i:s", $start),'time_end' => date("Y-m-d H:i:s"), 'duration(minutes)'=> $duration, 'mem(Mbyte)'=> $mem];
	Functions::saveFile($log, $file_log);

    if ($_GET['action'] == 'show'){
        //Время выполнения скрипта:
        echo 'Время выполнения скрипта: ', $duration, ' сек.<br/>';
        echo 'Обем памяти: ', $mem , ' мегабайта.<br/>';
        //log_CRON
        Functions::showArrayTable_key($log, 'log file');
    }
    // '%progdir%\modules\php\%phpdriver%\php-win.exe' -c '%progdir%\userdata\config\%phpdriver%_php.ini' -q -f '%sitedir%\BUCKS\cron\updat_strategies.php'
    // "%progdir%\modules\php\%phpdriver%\php-win.exe" -c "%progdir%\modules\php\%phpdriver%\php-cli.ini" -q -f "%sitedir%\BUCKS\cron\updat_strategies.php"
    // php "%sitedir%\BUCKS\cron\updat_strategies.php"


?>
