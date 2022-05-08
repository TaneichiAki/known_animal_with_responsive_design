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
		protected function entry(){
			$update_sql = 'update animal set family = ?,features = ?,date = ? where no = ?';
			$users = Dao::db()->mod_exec($update_sql,array($_REQUEST['family'],$_REQUEST['features'],$_REQUEST['date'],$_REQUEST['update_animal']));
			move_uploaded_file($_FILES['image']['tmp_name'] ,Constants::ANIMAL_PHOTO_SERVER.$_REQUEST['update_animal'].'_animal.jpg' );
		}
		/**
		*POST時処理
		*/
		protected function post(){
			if($_REQUEST['family'] == ""){
				return “何科か入力してください。”;
			}
			if($_REQUEST['features'] == ""){
				return “特徴を入力してください。”;
			}
			if($_REQUEST['date'] == ""){
				return “知った日を入力してください。”;
			}
			$this->entry();

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
		<!-- Required meta tags -->
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Bootstrap CSS -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

		<title>更新</title>
	</head>
	<body>
		<div class="container-fluid">
			<div class="row">
				<form class="border offset-1 col-10 offset-md-3 col-md-6 rounded bg-light mt-5" method="post" enctype="multipart/form-data">
					<div class="offset-3 col-6 text-center mt-3">
					<h4>動物データの更新</h4>
					<h6>
						<?php
							if ($obj->existMsg()){
								echo "<center><div>".$obj->getMsg()."</div></center>";
							}
						?>
					</h6>
					<br>
					<h5><?php echo "対象の動物：".$select_animal ?></h5>
					</div>
					<div class="offset-2 col-8">
						<label for="family" class="control-label">何科</label>
						<input type="family" class="form-control" id="family" name="family" placeholder="何科" value="<?php echo $select_family ?>" >
					</div>
					<div class="offset-2 col-8">
						<label for="features" class="control-label">特徴</label>
						<input type="features" class="form-control" name="features" placeholder="特徴" value="<?php echo $select_features ?>" >
					</div>
					<div class="offset-2 col-8">
						<label for="date" class="control-label">知った日</label>
						<input type="date" class="form-control" name="date" placeholder="知った日" value="<?php echo $select_date ?>" >
					</div>
					<div class="offset-2 col-8">
						<label for="file" class="control-label">イメージ画像</label>
						<input type="file" class="form-control" name="image">
						<br>
					</div>
					<button class="offset-4 col-4 btn btn-primary btn-sm mt-2 mb-3" type="submit">更新</button>
					<input type = "hidden" name = "update_animal"  value = "<?= $select_no ?>">
					<br>
					<button class="offset-4 col-4 btn btn-primary btn-sm mt-2 mb-3" type="button" onclick="location.href='<?php echo Constants::TOP_URL?>'">トップページへ戻る</button>
				</form>
			</div>
		</div>
	</body>
</html>
