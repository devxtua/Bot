<?php
ini_set('error_reporting', E_ALL);


//биржа
$exchange = 'binance';


//зацикливаем процес
while (true):
    $bookTickerfile = __DIR__ . '/file/historyBookTicker/'.time().'.txt';
    $lastTickerfile = __DIR__ . '/file/bookTicker.txt';
    $dir_bookTicker = __DIR__ . '/file/historyBookTicker/';
    //
    require_once __DIR__ . '/model/binance_c.php';
    require_once __DIR__ . '/model/functions_c.php';
    require_once __DIR__ . '/model/user_c.php';

    $Users = new Users('/home/pas/bot/strategies/');
    $Bin = new Binance('/home/pas/bot/file/');

    foreach ($Users->user_arrey as $key => $user) {
        $Bin->initialization($user[$exchange]['config']['KEY'], $user[$exchange]['config']['SEC']);

        if (!$bookTicker = $Bin->bookTicker(array())) continue;
        Functions::saveFile($bookTicker, $bookTickerfile);//сохраняем файлы с именем времени
        // unlink($lastTickerfile);
        Functions::saveFile($bookTicker, $lastTickerfile);//сохраняем файлы с именем времени
    }


    //Читаем нужные файлы  и удаляем старые***********
    $files = array_reverse(scandir($dir_bookTicker, 1)); //сканируем директорию с файлами
    // Functions::show($files);

    //*****ОТБИРАЕМ НУЖНЫЕ ФАЙЛЫ и смотрим возраст контрольных файлов
    $time = time();
    $today = getdate();
    foreach ($files as $key => $name) {
        $file = $dir_bookTicker . $name;
        if (!is_file($file)) continue; //проверяем существование файла

        $filemtime = filemtime($file);
        if ($time - $filemtime > 60 *24) unlink($file);// удаляем ненужные более 1 дня
    }
    sleep(1);

endwhile; //
?>