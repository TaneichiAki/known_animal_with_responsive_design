<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>削除</title>
</head>
<body>
	<?php
	require_once(__DIR__."/../classes/Dao.php");
	require_once(__DIR__."/../classes/constants.php");
		session_start();

		if(! isset($_SESSION['login'])){
				header("Location: login.php");
				exit();
			}
			//セッションIDがセットされていなかったらログインページに戻る

		//$dsn = 'pgsql:dbname='.Constants::DB_NAME.
		//' host='.Constants::DB_HOST.
		//' port='.Constants::DB_PORT;
		//$user = Constants::DB_USER;
		//$password = Constants::DB_PASS;
		//$dbh = new PDO($dsn,$user,$password);
		//PDO($dsn,$user,$password)はPHPがあらかじめ用意しているコンストラクタでデータベースへの接続の確立

		$sql = 'select * from animal inner join users on users.id = animal.memberid where no = ?';
		//sql文の組み立て
		$users2 = Dao::db()->show_one_row($sql,array($_REQUEST['update_animal']));
		//$stmt_animal = $dbh->prepare($sql);
		//PDOのファンクションprepare()で準備をする

		//$stmt_animal->bindParam(1,$_REQUEST['update_animal']);

		//$stmt_animal->execute();

		//$users2 = $stmt_animal->fetchAll(PDO::FETCH_ASSOC);

		$memberid = $users2['data']['id'];
		$select_animal= $users2['data']['name'];
		$select_family=$users2['data']['family'];
		$select_features=$users2['data']['features'];
		$select_date=$users2['data']['date'];
		$select_no = $users2['data']['no'];

		//var_dump($_SERVER["REQUEST_METHOD"]);


		if($_SERVER["REQUEST_METHOD"] == "POST"){

			try{
				$delete_sql = 'delete from animal where no = ?';
				$users = Dao::db()->mod_exec($delete_sql,array($_REQUEST['delete_animal']));
				//$delete_stmt = $dbh->prepare($delete_sql);
				//$delete_stmt->bindParam(1,$_REQUEST['delete_animal']);
				//$delete_stmt->execute();

				//var_dump(3);

				//$users = $delete_stmt->fetchAll(PDO::FETCH_ASSOC);
				header ('Location:'.Constants:: DELETE2_URL);
				//上記ページに遷移する。
				exit;
			}catch (PDOException $e) {
				//phpではない外部のアプリと連携するときはtry catchでエラーが起きた時の動きを定義した方が良い
				print('Error:'.$e->getMessage());
				die();
				}
			}

	?>
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
						<input type = "hidden" name = "delete_animal"  value = "<?= $select_no ?>">
					</form>
			</center>
	</div>
</body>
