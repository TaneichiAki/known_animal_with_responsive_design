<?php
	require_once(__DIR__."/../classes/Dao.php");
	require_once(__DIR__."/../classes/constants.php");
	require_once(__DIR__."/../classes/knownAnimalBase.php");

	class Edit extends KnownAnimalBase{
		/*
		*初回処理
		*/
		protected function prologue(){
			$select_sql = 'select * from animal inner join users on users.id = animal.memberid where no = ?';
			$info = Dao::db()->show_one_row($select_sql,array($_REQUEST['update_animal']));
			//動物の登録番号
			$GLOBALS['select_no']=$info['data']['no'];
			//動物の名称
			$GLOBALS['select_animal']=$info['data']['name'];
			//動物の科目
			$GLOBALS['select_family']=$info['data']['family'];
			//動物の特徴
			$GLOBALS['select_features']=$info['data']['features'];
			//知った日
			$GLOBALS['select_date']=$info['data']['date'];
		}
		/**
		*animalデータ更新処理
		*/
		protected function update(){
			$update_sql = 'update animal set family = ?,features = ?,date = ? where no = ?';
			$users = Dao::db()->mod_exec($update_sql,array($_REQUEST['family'],$_REQUEST['features'],$_REQUEST['date'],$_REQUEST['update_animal']));
			move_uploaded_file($_FILES['image']['tmp_name'] ,Constants::ANIMAL_PHOTO_SERVER.$_REQUEST['update_animal'].'_animal.jpg' );
		}
		/**
		*POST時処理
		*/
		protected function post(){
			if($_REQUEST['family'] == ""){
				return “何科か入力してください。“;
			}
			if($_REQUEST['features'] == ""){
				return “特徴を入力してください。“;
			}
			if($_REQUEST['date'] == ""){
				return “知った日を入力してください。“;
			}
			update();
			//下記TOPページに遷移する。
			header ('Location:'.Constants::TOP_URL);
			exit;
		}

		protected function get(){}

		protected function epilogue(){}
	}

	$obj = new Edit();
	/**
	*メイン処理実行
	*/
	$obj->main();
?>
<!DOCTYPE html>
<html lang=“ja”>
	<head>
		<meta charset="utf-8">
		<title>更新</title>
	</head>
	<body>
		<div>
			<form method="post" enctype="multipart/form-data">
				<center>
					<h1>データの更新</h1>
					<?php
					if ($msg != ""){
						echo "<center><div>".$msg."</div></center>";
					}
					?>
					<div>
						<div>
							<?php echo "対象の動物：".$select_animal ?>
						</div>
						<div>
							<input type="family" name="family" placeholder="何科" value="<?php echo $select_family ?>" >
						</div>
						<div>
							<input type="features" name="features" placeholder="特徴" value="<?php echo $select_features ?>" >
						</div>
						<div>
							<input type="date" name="date" placeholder="知った日" value="<?php echo $select_date ?>" >
						</div>
						<div>
							<input type="file" name="image">
							<br>
							<br>
						</div>
					</div>
					<input type="submit" value="更新">
					<input type = "hidden" name = "update_animal"  value = "<?= $select_no ?>">
					<br>
					<button type="button" onclick="location.href='<?php echo Constants::TOP_URL?>'">トップページへ戻る</button>
				</center>
			</form>
		</div>
	</body>
</html>
