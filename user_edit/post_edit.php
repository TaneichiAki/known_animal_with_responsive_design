<?php
	require_once(__DIR__."/../classes/Dao.php");
	require_once(__DIR__.'/../classes/constants.php');
	/**
	*グローバル変数定義
	*/
	$msg = "";
	$user = "";
	/**
	*ユーザー情報(役職名）更新処理
	*/
	function update(){
		$update_sql = 'update users set post = ? where user_id = ?';
		Dao::db()->mod_exec($update_sql,array($_REQUEST['post'],$_SESSION['login']));
	}
	/**
	*POST時処理
	*/
	function post(){
		if($_REQUEST['post'] == ""){
			return “役職名を入力してください。“;
		}
		update();
		//下記ページに遷移する。
		header ('Location:'.Constants::USER_EDIT_URL);
		exit;
	}
	/*
	*メイン処理
	*/
	function main(){
		session_start();
		//セッションIDがセットされていなかったらログインページに戻る
		if(! isset($_SESSION['login'])){
			header("Location:".Constants::LOGIN_URL);
			exit();
		}
		try{
			$select_sql = 'select * from users where user_id = ?';
			$GLOBALS['user'] = Dao::db()->show_one_row($select_sql,array($_SESSION['login']));
			if($_SERVER["REQUEST_METHOD"] == "POST"){
				$GLOBALS['msg'] = post();
			}
		}catch (PDOException $e){
			//phpではない外部のアプリと連携するときはtry catchでエラーが起きた時の動きを定義した方が良い
			print('Error:'.$e->getMessage());
			die();
		}
	}
	/**
	*メイン処理実行
	*/
	main();
?>
<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
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
			<div>新しい役職名</div>
			<div>
			<input type="text" name="post" value="<?php echo $user['data']['post']?>">
			</div>
			<br>
			<input type="submit" value="更新">
			<br>
			<button type="button" onclick="location.href='<?php echo Constants::USER_EDIT_URL?>'">ユーザー情報へ戻る</button>
		</center>
		</form>
	</body>
</html>
