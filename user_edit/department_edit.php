<?php
			require_once(__DIR__."/../classes/Dao.php");
			require_once(__DIR__.'/../classes/constants.php');
			session_start();

			if(! isset($_SESSION['login'])){
				header("Location:".Constants::LOGIN_URL);
				exit();
			}
			$users_sql = 'select * from users where user_id = ?';
			//sql文の組み立て
			$users = Dao::db()->show_one_row($users_sql,array($_SESSION['login']));

				if($_SERVER["REQUEST_METHOD"] != "POST"){
			$msg = "";
		}else{
			if($_REQUEST['department'] == ""){
				$msg = “部署名を入力してください。“;
			}else{
				try{
					$sql2 = 'update users set department = ? where user_id = ?';
					//sql文の組み立て
					Dao::db()->mod_exec($sql2,array($_REQUEST['department'],$_SESSION['login']));
					//下記ページに遷移する。
					header ('Location:'.Constants::USER_EDIT_URL);
					exit;
				}catch (PDOException $e) {
					//phpではない外部のアプリと連携するときはtry catchでエラーが起きた時の動きを定義した方が良い
					print('Error:'.$e->getMessage());
					die();
				}
			}
		}

?>
<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div>
			<form method="post" enctype="multipart/form-data">
			<center>
				<h1>ユーザー情報の編集</h1>
				<?php
					if ($msg != ""){
					echo "<center><div>".$msg."</div></center>";
				}
				?>
				<br>
				<div>新しい部署名</div>
				<div>
				<input type="text" name="department" value="<?php echo $users['data']['department']?>">
				</div>
				<br>
				<input type="submit" value="更新">
				<br>
			<button type="button" onclick="location.href='<?php echo Constants::USER_EDIT_URL?>'">ユーザー情報へ戻る</button>
			</center>
			</form>
		</body>
</html>
