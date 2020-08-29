<?php 
//ページの表示は index.php が起点、Todo のチェックは ajax.php 
//そのため index.php と _ajax.php の両方に session_start() が必要
session_start();

require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/Todo.php');

$todoApp = new \MyApp\Todo();

//POSTされたときだけ処理する
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	try {
		//配列を結果で返すようにする
		$res = $todoApp->post();
		//ヘッダーに「json形式のデータ」と指定
		header('Content-Type: application/json');
		echo json_encode($res);
		exit;
	} catch (Exception $e) {
		//SERVER_PROTOCOL で HTTP/1.0 や HTTP/1.1 を返す
		header($_SERVER['SERVER_PROTOCOL'] . '500 Internal Server Error' , true, 500);
		echo $e->getMessage();
		exit;
	}
}