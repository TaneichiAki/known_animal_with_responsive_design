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
		<!-- Required meta tags -->
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Bootstrap CSS -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

	</head>
	<body>
		<div class="container-fluid">
			<form class="border offset-1 col-10 offset-md-4 col-md-4 rounded bg-light mt-4 mb-5" method="post" enctype="multipart/form-data">
				<div class="row">
					<div class="offset-2 col-8 text-center mt-3 mb-3">
						<h4>ユーザー情報の編集</h4>
						<?php
							if ($msg != ""){
								echo "<center><div>".$msg."</div></center>";
							}
						?>
					</div>
				</div>
				<div class="row">
					<div class="offset-2 col-8 text-center mt-3">
						<div>新しいメールアドレス</div>
					</div>
				</div>
				<div class="row">
					<div class="offset-2 col-8 mt-2">
						<input type="email" class="form-control" name="mail" value="<?php echo $user['data']['mail']?>">
					</div>
				</div>
				<div class="row">
					<button class="offset-2 col-8 offset-md-4 col-md-4 btn btn-primary btn-sm mt-4" type="submit">更新</button>
				</div>
				<div class="row">
					<button class="offset-2 col-8 offset-md-4 col-md-4 btn btn-primary btn-sm mt-3 mb-4" type="button" onclick="location.href='<?php echo Constants::USER_EDIT_URL?>'">ユーザー情報へ戻る</button>
				</div>
			</form>
		</div>
	</body>
</html>
