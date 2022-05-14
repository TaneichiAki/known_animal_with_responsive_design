<?php
	require_once(__DIR__."/../classes/Dao.php");
	require_once(__DIR__.'/../classes/constants.php');
	/**
	*グローバル変数定義
	*/
	$msg = "";
	$user = "";
	/**
	*ユーザー情報(誕生日）更新処理
	*/
	function update(){
		$update_sql = 'update users set birth = ? where user_id = ?';
		Dao::db()->mod_exec($update_sql,array($_REQUEST['birth'],$_SESSION['login']));
	}
	/**
	*POST時処理
	*/
	function post(){
		if($_REQUEST['birth'] == ""){
			return “生年月日を入力してください。“;
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
			//ログインユーザーのユーザー情報を抽出
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
						<div>生年月日</div>
					</div>
				</div>
				<div class="row">
					<div class="offset-2 col-8 mt-2">
						<input type="date" class="form-control" name="birth" value="<?php echo $user['data']['birth']?>">
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
