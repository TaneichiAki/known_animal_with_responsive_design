<?php
	require_once(__DIR__."/../classes/Dao.php");
	require_once(__DIR__.'/../classes/constants.php');
	/**
	*グローバル変数定義
	*/
	$msg = "";
	$user = "";
	/*
	*メール送信処理
	*/
	function send_mail(){
		mb_language("Japanese");
		mb_internal_encoding("UTF-8");
		$to = $_REQUEST['mail'];
		$title = 'メールアドレス登録完了のお知らせ';
		$message = 'known_animalシステムからのお知らせです。'.PHP_EOL
		.'メールアドレスの登録が完了いたしました。'.PHP_EOL
		.'■メールアドレスの登録をリクエストされていない場合は本メールを削除してください。'.PHP_EOL
		.'他の方がメールアドレスを間違って入力したため本メールが送信された可能性があります。';

		$headers = "From: known_animal@test.com";

		if(mb_send_mail($to, $title, $message, $headers))
		{
			echo '登録したメールアドレスに登録完了のメールを送りました。<br />';
			echo 'メールの受信をご確認ください。';
		}
		else
		{
			echo "メール送信失敗です";
		}
		?>
		<script>
		setTimeout(function(){
			window.location.href='<?php echo Constants::USER_EDIT_URL?>';
		}, 5*1000);
	 </script>
	 <?php
	 exit;
	}
	/**
	*ユーザー情報(メールアドレス）更新処理
	*/
	function update(){
		$update_sql = 'update users set mail = ? where user_id = ?';
		Dao::db()->mod_exec($update_sql,array($_REQUEST['mail'],$_SESSION['login']));
		send_mail();
	}
	/**
	*POST時処理
	*/
	function post(){
		if($_REQUEST['mail'] == ""){
			return “メールアドレスを入力してください。“;
		}
		update();
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
			<div>新しいメールアドレス</div>
			<div>
			<input type="email" name="mail" value="<?php echo $user['data']['mail']?>">
			</div>
			<br>
			<input type="submit" value="更新">
			<br>
			<button type="button" onclick="location.href='<?php echo Constants::USER_EDIT_URL?>'">ユーザー情報へ戻る</button>
		</center>
		</form>
	</body>
</html>
