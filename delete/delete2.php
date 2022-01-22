<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>削除完了</title>
</head>
<body>
	<?php
	require_once(__DIR__."/constants.php");
		session_start();
				
		if(! isset($_SESSION['login'])){
				header('Location:'.Constants::LOGIN_URL);
				exit();
			}
			//セッションIDがセットされていなかったらログインページに戻る
	?>
	
<center>
	<h1>削除しました！</h1>
	<script type="text/javascript">
function refresh(){
    window.opener.location.reload();
    window.close();
}
</script>
	<button type="button" onclick="refresh()">閉じる</button>
</center>
</body>
