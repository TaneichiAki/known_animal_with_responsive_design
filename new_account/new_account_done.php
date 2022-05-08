<?php
	require_once(__DIR__.'/../classes/constants.php');
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
			<div class="row">
				<div class="text-center mt-5">
					<h5>アカウントの作成が完了しました。</h5>
				</div>
			</div>
			<div class="row">
				<button type="button" class="offset-4 col-4 offset-md-5 col-md-2 btn btn-primary btn-sm mt-5" onclick="location.href='<?php echo Constants::LOGIN_URL?>'"> ログイン画面へ</button>
			</div>
		</div>
	</body>
</html>
