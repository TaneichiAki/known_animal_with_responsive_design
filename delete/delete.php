<?php
	require_once(__DIR__."/../classes/Dao.php");
	require_once(__DIR__."/../classes/constants.php");
	/**
	*グローバル変数定義
	*/
	$select_animal = "";
	$select_family = "";
	$select_features = "";
	$select_date = "";
	$select_no = "";
	/*
	*削除処理
	*/
	function delete(){
		//対象の動物情報をデータベースから削除
		$delete_sql = 'delete from animal where no = ?';
		$delete_animal = Dao::db()->mod_exec($delete_sql,array($_REQUEST['delete_exec_animal']));
		//下記ページに遷移する
		header ('Location:'.Constants:: DELETE_DONE_URL);
		exit;
	}
	/*
	*メイン処理
	*/
	function main(){
		session_start();
		//セッションIDがセットされていなかったらログインページに戻る
		if(! isset($_SESSION['login'])){
			header("Location: login.php");
			exit();
		}
		try{
			//TOPページから受け取ったNoを使って削除対象の動物を抽出
			$select_sql = 'select * from animal inner join users on users.id = animal.memberid where no = ?';
			$animal = Dao::db()->show_one_row($select_sql,array($_REQUEST['delete_animal']));

			$GLOBALS['select_animal']=$animal['data']['name'];
			$GLOBALS['select_family']=$animal['data']['family'];
			$GLOBALS['select_features']=$animal['data']['features'];
			$GLOBALS['select_date']=$animal['data']['date'];
			$GLOBALS['select_no']=$animal['data']['no'];
			if($_SERVER["REQUEST_METHOD"] == "POST"){
				delete();
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

		<title>削除</title>
	</head>
	<body>
			<div class="offset-md-3 col-md-6 text-center mt-3 mb-3">
				<h6>このデータを削除しますか？</h6>
				<br>
				<table class="table table-bordered border-secondary">
					<tr><th class="table-primary border-secondary">名称</th><td><?php echo $select_animal ?></td></tr>
					<tr><th class="table-primary border-secondary">科</th><td><?php echo $select_family ?></td></tr>
					<tr><th class="table-primary border-secondary">特徴</th><td><?php echo $select_features ?></td></tr>
					<tr><th class="table-primary border-secondary">知った日</th><td><?php echo $select_date ?></td></tr>
				</table>
				<br>
				<form method="post">
					<button class="col-md-2 btn btn-primary btn-sm mt-2 mb-3" type="submit">削除</button>
					<input type = "hidden" name = "delete_exec_animal"  value = "<?= $select_no ?>">
				</form>
			</div>
	</body>
</html>
