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
		<!-- Required meta tags -->
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Bootstrap CSS -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

		<title>削除完了</title>
	</head>
	<body>
		<div class="container-fluid">
			<div class="row">
				<div class="offset-3 col-6 text-center mt-4">
					<h6>削除しました！</h6>
				</div>
			</div>
			<div class="row">
				<div class="offset-3 col-6 text-center mt-4">
					<script type="text/javascript">
						function refresh(){
							window.opener.location.reload();
							window.close();
						}
					</script>
					<button class="col-5 btn btn-primary btn-sm mt-4" type="button" onclick="refresh()">閉じる</button>
				</div>
			</div>
		</div>
	</body>
</html>
