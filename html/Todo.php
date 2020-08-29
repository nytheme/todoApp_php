<?php
	namespace MyApp;

	class Todo {
		private $_db;

		public function __construct() {
			//トークン作成メソッドの実行
			$this->_createToken();

			try {
				//Todo.php は MyApp という名前空間。この場合、PDO に \ をつけないと
				//「MyApp という名前空間のなかの PDO クラス」という意味になってしまう。
				//「PHP 標準で用意している PDO クラスを使う」という場合は、
				//　PHP標準で用意しているクラスが存在する \ という名前空間をつけるルールになっている。
				$this->_db = new \PDO(DSN, USER, PW);
				$this->_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

			} catch (\PDOException $e) {
				echo $e->getMessage();
				exit;
			}
		}

		private function _createToken() {
			if(!isset($_SESSION['token'])) {
				$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));
			}
		}

		public function getAll() {
			$stmt = $this->_db->query("select * from todos order by id desc");
			//fetchAll(\PDO::FETCH_OBJ)でオブジェクト形式で返す
			return $stmt->fetchAll(\PDO::FETCH_OBJ);
		}

		public function post() {
			$this->_validateToken();
			//postにはmodeが渡されるようになっているはずなので確認
			if(!isset($_POST['mode'])) {
				throw new \Exception('mode not set!');
			}
			//modeが渡ってきたらそれに応じて処理を振り分ける
			switch ($_POST['mode']) {
				case 'update':
					return $this->_update();
				case 'create':
					return $this->_create();
				case 'delete':
					return $this->_delete();
			}
		}

		private function _validateToken() {
			if (
				!isset($_SESSION['token']) ||
				!isset($_POST['token']) ||
				$_SESSION['token'] !== $_POST['token']
			) {
				throw new \Exception('invalid token!');
			}
		}

		private function _update() {
			if(!isset($_POST['id'])) {
				throw new \Exception('[update] id not set!');
			}
			//同時にたくさんアクセスされた時に id がずれると困ってしまうので、
			//確実に更新された todo の $state が取得できるように、
			//一応トランザクションで囲む
			$this->_db->beginTransaction();

				//渡ってきたidを元にDBの更新をすればOK
				//stateが0のときは1に、1のときは0にしたい。%dは整数型
				//% が変数の埋め込みの % と勘違いされるので、% 自体をこの中で使いたい場合には %% とする
				$sql = sprintf("update todos set state = (state + 1) %% 2 where id = %d", $_POST['id']);
				$stmt = $this->_db->prepare($sql); //prepare ユーザーからの入力をSQLに利用
				//実行
				$stmt->execute();

				//更新されたstateを返す→またDBにアクセス
				$sql = sprintf("select state from todos where id = %d", $_POST['id']);
				// データを引っ張ってくるのでqueryでOK
				$stmt = $this->_db->query($sql); //query ユーザーからの入力をSQLに利用しない
				//fetchColumnでデータを$stateに入れる
				$state = $stmt->fetchColumn();

			//トランザクションここまで
			$this->_db->commit();

			return [
				'state' => $state
			];
		}

		private function _create() {
			
		}

		private function _delete() {
			if(!isset($_POST['id'])) {
				throw new \Exception('[delete] id not set!');
			}

			$sql = sprintf("delete from todos where id = %d", $_POST['id']);
			$stmt = $this->_db->prepare($sql); 
			$stmt->execute();

			return [];
		}
	}

	// $user = 'root';
	// $pw = 'secret';
	// $dbname = 'todo_app';
	// $host = 'db'; 
	// $dsn = 'mysql:host='.$host.';dbname='.$dbname.';charset=utf8';

	// try {
	// 	$pdo = new PDO($dsn, $user, $pw);
	// 	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	// 	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	// 	echo "接続完了</br>";
	// 	$pdo = NULL;
	// } catch (Exception $e) {
	// 	echo "エラー</br>";
	// 	echo $e->getMessage();
	// 	exit();
	// }