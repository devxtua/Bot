<?php

//  $configs = include('config.php');
	if ($_SERVER['SystemRoot'] == "C:\WINDOWS") {
		ini_set('display_errors', '1');				// локально показывать ошибки
		return  array(
			'DIR' => 'D:\binance\\',
			'directory_user'=> 'D:\binance\strategies\\',
			'test_user' => array(
								'KEY' => "7WDYbxAytNjWRo5jqz4ZFZp2v2J5UahQirEcOFlacaE7ykUQxQcGwQZcBwcFUUvH",
								'SEC' => "0Ql01kwiBjx8jWzm7iA5CMfj4ODhBuUq8VutXdGTkNe8OsHTBoUWckQBr2LITseg"
								)
		);
	} else {
		ini_set('display_errors', '0');				// на хостинге не показывать
		return  	array(
				'DIR' => '/home/pas/bot/file/'
			);
	}
