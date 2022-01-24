<!DOCTYPE html>
<html lang=“ja”>
<head>
<meta charset="utf-8">
<title>更新</title>
</head>
<body>
	<?php
		require_once(__DIR__."/../classes/Dao.php");
		require_once(__DIR__."/../classes/constants.php");
		session_start();

		//セッションIDがセットされていなかったらログインページに戻る
		if(! isset($_SESSION['login'])){
				header("Location:".Constants::LOGIN_URL);
				exit();
			}

		$sql = 'select * from animal inner join users on users.id = animal.memberid where no = ?';
		$users2 = Dao::db()->show_one_row($sql,array($_REQUEST['update_animal']));

		$memberid = $users2['data']['id'];
		$select_animal= $users2['data']['name'];
		$select_family=$users2['data']['family'];
		$select_features=$users2['data']['features'];
		$select_date=$users2['data']['date'];


		if($_SERVER["REQUEST_METHOD"] != "POST"){
			$msg = "";
		}else{
			if($_REQUEST['family'] == ""){
				$msg = “何科か入力してください。“;
			}elseif($_REQUEST['features'] == ""){
				$msg = “特徴を入力してください。“;
			}elseif($_REQUEST['date'] == ""){
				$msg = “知った日を入力してください。“;
			}else{
				try{
					$sql2 = 'update animal set family = ?,features = ?,date = ? where no = ?';
					$users = Dao::db()->mod_exec($sql2,array($_REQUEST['family'],$_REQUEST['features'],$_REQUEST['date'],$_REQUEST['update_animal']));

					move_uploaded_file($_FILES['image']['tmp_name'] ,Constants::ANIMAL_PHOTO_SERVER.$_REQUEST['update_animal'].'_animal.jpg' );
					//下記ページに遷移する。
					header ('Location:'.Constants::TOP_URL);
					exit;

				}catch (PDOException $e) {
					//phpではない外部のアプリと連携するときはtry catchでエラーが起きた時の動きを定義した方が良い
					print('Error:'.$e->getMessage());
					die();
				}
			}
		}

?>
	<div>
		<form method="post" enctype="multipart/form-data">
			<center>
				<h1>データの更新</h1>
			</center>
				<?php
				if ($msg != ""){
					echo "<center><div>".$msg."</div></center>";
				}
				?>
				<div>
					<center>
						<div>
							<?php echo "対象の動物：".$select_animal ?>
						</div>

						<div>
							<input type="family" name="family" placeholder="何科" value = "<?php echo $select_family ?>" >
						</div>

						<div>
							<input type="features" name="features" placeholder="特徴" value = "<?php echo $select_features ?>" >
						</div>

						<div>
							<input type="date" name="date" placeholder="知った日" value = "<?php echo $select_date ?>" >
						</div>
						<div>
						<input type="file" name="image">
						<br>
						<br>
						</div>
					</center>
				</div>
					<center>
						<input type="submit" value="更新">
						<input type = "hidden" name = "update_animal"  value = "<?= $users2['data']['no'] ?>">

						<br>
						<button type="button" onclick="location.href='<?php echo Constants::TOP_URL?>'">トップページへ戻る</button>
					</center>
		</form>
	</div>
</body>
