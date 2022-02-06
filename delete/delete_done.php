<?php
	require_once(__DIR__."/../classes/constants.php");
	session_start();
	//セッションIDがセットされていなかったらログインページに戻る
	if(! isset($_SESSION['login'])){
		header('Location:'.Constants::LOGIN_URL);
		exit();
	}
?>
<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<title>削除完了</title>
	</head>
	<body>
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
</html>
