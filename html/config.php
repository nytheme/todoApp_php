<?php
	//エラーをブラウザに表示する
	ini_set('display_errors', 1);

	define('USER', 'root');
	define('PW', 'secret');
	define('DBNAME', 'todo_app');
	define('HOST', 'db');
	define('DSN', 'mysql:host='.HOST.';dbname='.DBNAME.';charset=utf8');
	// $user = 'root';
	// $pw = 'secret';
	// $dbname = 'todo_app';
	// $host = 'db';
	// $dsn = 'mysql:host='.$host.';dbname='.$dbname.';charset=utf8';

	// try {
	// 	$pdo = new PDO(DSN, USER, PW);
	// 	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	// 	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	// 	echo "接続完了</br>";
	// 	$pdo = NULL;
	// } catch (Exception $e) {
	// 	echo "エラー</br>";
	// 	echo $e->getMessage();
	// 	exit();
	// }