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
		header ('Location:'.Constants:: DELETE2_URL);
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
		<title>削除</title>
	</head>
	<body>
		<div>
			<center>
				<h1>このデータを削除しますか？</h1>
				<br>
				<table border = "1" style = "border-collapse: collapse">
					<tr><th>名称</th><td><?php echo $select_animal ?></td></tr>
					<tr><th>科</th><td><?php echo $select_family ?></td></tr>
					<tr><th>特徴</th><td><?php echo $select_features ?></td></tr>
					<tr><th>知った日</th><td><?php echo $select_date ?></td></tr>
				</table>
				<br>
				<form method="post">
					<button type="submit">削除</button>
					<input type = "hidden" name = "delete_exec_animal"  value = "<?= $select_no ?>">
				</form>
			</center>
		</div>
	</body>
</html>
