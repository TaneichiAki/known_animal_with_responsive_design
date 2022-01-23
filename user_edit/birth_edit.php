<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
</head>
<body>

	<?php
			require_once(__DIR__."/../classes/Dao.php");
			require_once(__DIR__.'/../classes/constants.php');
			session_start();

			if( !empty($_GET['btn_logout']) ) {
				unset($_SESSION['login']);
			}


			if(! isset($_SESSION['login'])){
				header("Location:".Constants::LOGIN_URL);
				exit();
			}
			//セッションIDがセットされていなかったらログインページに戻る

			//$dsn = 'pgsql:dbname='.Constants::DB_NAME.
			//' host='.Constants::DB_HOST.
			//' port='.Constants::DB_PORT;
			//$user = Constants::DB_USER;
			//$password = Constants::DB_PASS;

			//$dbh = new PDO($dsn,$user,$password);
			//PDO($dsn,$user,$password)はPHPがあらかじめ用意しているコンストラクタでデータベースへの接続の確立
			$users_sql = 'select * from users where user_id = ?';
			//sql文の組み立て
			$users = Dao::db()->show_one_row($users_sql,array($_SESSION['login']));
			//$stmt = $dbh->prepare($users_sql);
			//PDOのファンクションprepare()で準備をする
			//$stmt->bindParam(1,$_SESSION['login']);
			//$_REQUESTは$_POSTをGETでもPOSTでも見れるようにしたもの。
			//上記のsql文の？を埋める

			//$stmt->execute();
			//sqlを実行する。値は$stmtインスタンスの中に保管されている

			//$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//fetchAll(PDO::FETCH_ASSOC)でsqlの結果の取り出し

				if($_SERVER["REQUEST_METHOD"] != "POST"){
			$msg = "";
		}else{
			if($_REQUEST['birth'] == ""){
				$msg = “生年月日を入力してください。“;
			}else{
				try{
					$sql2 = 'update users set birth = ? where user_id = ?';
					//sql文の組み立て
					Dao::db()->mod_exec($sql2,array($_REQUEST['birth'],$_SESSION['login']));
					//$stmt = $dbh->prepare($sql2);
					//PDOのファンクションprepare()で準備をする

					//$stmt->bindParam(1,$_REQUEST['birth']);
					//上記のsql文の？を埋める

					//$stmt->bindParam(2,$_SESSION['login']);

					//$stmt->execute();
					//sqlを実行する。値は$stmtインスタンスの中に保管されている

					header ('Location:'.Constants::USER_EDIT_URL);
					//上記ページに遷移する。
					exit;

					//$select_family  = "";
				}catch (PDOException $e) {
					//phpではない外部のアプリと連携するときはtry catchでエラーが起きた時の動きを定義した方が良い
					print('Error:'.$e->getMessage());
					die();
				}
			}
		}

?>
	<div>
		<form method="post" enctype="multipart/form-data">
	<center>
		<h1>ユーザー情報の編集</h1>
		<?php
					if ($msg != ""){
					echo "<center><div>".$msg."</div></center>";
				}
				?>
				<br>
		<div>生年月日</div>
		<div>
		<input type="date" name="birth" value="<?php echo $users['data']['birth']?>">
		</div>
		<br>
		<input type="submit" value="更新">
		<br>
			<button type="button" onclick="location.href='<?php echo Constants::USER_EDIT_URL?>'">ユーザー情報へ戻る</button>
	</center>
	</form>
</body>
