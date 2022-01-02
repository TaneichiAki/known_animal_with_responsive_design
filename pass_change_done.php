<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
</head>
<body>
	<?php
	require_once(__DIR__."/constants.php");
	session_start();
				
	if(! isset($_SESSION['login'])){
			header("Location:".Constants::LOGIN_URL);
			exit();
		}
	?>
<center>
	<br>
	<br>
	<p>パスワードを変更しました！</p>
		<button type="button" onclick="location.href='<?php echo Constants::TOP_URL?>'">トップページへ戻る</button>

</center>
</body>