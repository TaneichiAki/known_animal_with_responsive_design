<?php
	require_once(__DIR__."/../classes/constants.php");
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
		<center>
			<br>
			<br>
			<p>パスワードを変更しました！</p>
			<button type="button" onclick="location.href='<?php echo Constants::TOP_URL?>'">トップページへ戻る</button>
		</center>
	</body>
</html>
