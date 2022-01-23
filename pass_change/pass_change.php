<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
</head>
<body>
	<?php
	require_once(__DIR__."/../classes/Dao.php");
	require_once(__DIR__."/../classes/constants.php");
	session_start();

	if(! isset($_SESSION['login'])){
			header("Location:".Constants::LOGIN_URL);
			exit();
		}
			//セッションIDがセットされていなかったらログインページに戻る
		$id = $_SESSION['login'];

		//$dsn = 'pgsql:dbname='.Constants::DB_NAME.
		//' host='.Constants::DB_HOST.
		//' port='.Constants::DB_PORT;
		//$user = Constants::DB_USER;
		//$password = Constants::DB_PASS;
		//$dbh = new PDO($dsn,$user,$password);
		//PDO($dsn,$user,$password)はPHPがあらかじめ用意しているコンストラクタでデータベースへの接続の確立
		$sql = "select * from users where user_id = ?";
		$users = Dao::db()->show_one_row($sql,array($id));
		//sql文の組み立て
		//var_dump($sql);
		//exit;
		//var_dump($_SERVER["REQUEST_METHOD"]);
		//var_dump($_REQUEST['update_animal']);
		//$stmt_users= $dbh->prepare($sql);
		//PDOのファンクションprepare()で準備をする

		//$stmt_users->execute();

		//$users = $stmt_users->fetchAll(PDO::FETCH_ASSOC);
		//var_dump($users);
		//exit;
		$pass = $users['data']['password'];
		$pattern = '/\A(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)[a-zA-Z\d]{8,100}+\z/';
		preg_match($pattern,$_REQUEST['new_pass'],$matches);
		//var_dump($pass);
		//exit;

			if($_SERVER["REQUEST_METHOD"] != "POST"){
			$msg = "";
		}else{
			if($_REQUEST['current_pass'] == ""){
				$msg = "現在のパスワードを入力してください。";
			}elseif($_REQUEST['new_pass'] == ""){
				$msg = "新しいパスワードを入力してください。";
			}elseif($_REQUEST['re_new_pass'] == ""){
				$msg = "新しいパスワードを再度入力してください。";
			}elseif(password_verify($_REQUEST['current_pass'], $pass) == false){
				$msg = "現在のパスワードが正しくありません。";
			}elseif($_REQUEST['new_pass'] != $_REQUEST['re_new_pass'] ){
				$msg = "１回目と２回目で新しいパスワードが一致しません。";
			}elseif($matches == false){
				$msg = "半角英小文字大文字数字をそれぞれ1種類以上含む8文字以上のパスワードにしてください。";
			}else{
				try{
					$sql2 = "update users set password = ? where user_id = '".$id."'";
					//var_dump($sql2);
					//exit;
					$hash = password_hash($_REQUEST['new_pass'], PASSWORD_DEFAULT);
					Dao::db()->mod_exec($sql2,array($hash));
					//$stmt = $dbh->prepare($sql2);
					//PDOのファンクションprepare()で準備をする

					//$stmt->bindParam(1,$hash);
					//上記のsql文の？を埋める
					//$stmt->execute();
					//sqlを実行する。値は$stmtインスタンスの中に保管されている

					//$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
					//fetchAll(PDO::FETCH_ASSOC)でsqlの結果の取り出し
					header ('Location:'.Constants::PASS_CHANGE_DONE_URL);
					//上記ページに遷移する。
					exit;
				}catch (PDOException $e) {
					//phpではない外部のアプリと連携するときはtry catchでエラーが起きた時の動きを定義した方が良い
					print('Error:'.$e->getMessage());
					die();
				}
			}
		}
	?>
	<form method="post" enctype="multipart/form-data">
		<center>
			<h1>パスワード変更</h1>
				<?php
				if ($msg != ""){
					echo "<center><div>".$msg."</div></center>";
				}
				?>
				<!-$msgの値が空でなければ、$msgを出力する。->

			<div>
				<p>現在のパスワード: <input type="password" name="current_pass"></p>
			</div>
			<div>
				<p>新しいパスワード: <input type="password" name="new_pass"></p>
			</div>
			<div>
				<p>新しいパスワード: <input type="password" name="re_new_pass" placeholder="もう一度入力してください"></p>
				<p>※パスワードは半角英小文字大文字数字をそれぞれ1種類以上含む8文字以上にしてください。</p>
			</div>
			<input type="submit" value="変更">
			<br>
			<button type="button" onclick="location.href='<?php echo Constants::USER_EDIT_URL?>'">ユーザー情報へ戻る</button>
		</center>
	</form>
</body>
