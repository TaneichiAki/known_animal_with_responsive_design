<?php
	require_once(__DIR__."/../classes/Dao.php");
	require_once(__DIR__."/../classes/constants.php");
	require_once(__DIR__."/../classes/knownAnimalBase.php");

	class NewEntry extends KnownAnimalBase{
		//初回処理
		protected function prologue(){}
		//登録処理
		protected function entry(){
			//ログインしているユーザーのユーザー情報を取得
			$user_sql = 'select * from users  where user_id = ?';
			$user = Dao::db()->show_one_row($user_sql,array($_SESSION['login']));
			//ユーザーID
			$memberid = $user['data']['id'];
			//テキストボックスで入力した新規動物情報をデータベースに登録
			$insert_sql = 'insert into animal(name,family,features,date,memberid) values(?,?,?,?,?)';
			$insert_id = Dao::db()->add_one_row($insert_sql,array($_REQUEST['animalname'],$_REQUEST['family'],$_REQUEST['features'],$_REQUEST['date'],$memberid));
			//アップロードされたファイルを一時フォルダから指定のフォルダへファイル名「（insert_id）_animal.jpg」にして移動
			move_uploaded_file($_FILES['image']['tmp_name'] , Constants::ANIMAL_PHOTO_SERVER.$insert_id['id'].'_animal.jpg' );
		}
		//POST時処理
		protected function post(){
			if($_REQUEST['animalname'] ==  ""){
				return "動物の名称を入力してください。";
			}
			if($_REQUEST['family'] == ""){
				return "何科か入力してください。";
			}
			if($_REQUEST['features'] == ""){
				return "特徴を入力してください。";
			}
			if($_REQUEST['date'] == ""){
				return "知った日を入力してください。";
			}
			//ENTRY処理実行
			$this->entry();
			//下記ページに遷移する。
			header ('Location:'.Constants::TOP_URL);
			exit;
		}
		//GET時処理
		protected function get(){}
		//終了前処理
		protected function epilogue(){}
	}

		$obj = new NewEntry();
		/**
		*メイン処理実行
		*/
		$obj->main();

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

		<title>新規登録</title>
	</head>
	<body>
		<div class="container-fluid">
			<div class="row">
			<form class="border offset-1 col-10 offset-md-3 col-md-6 rounded bg-light mt-5" method="post" enctype="multipart/form-data">
				<div class="row">
					<div class="offset-3 col-6 text-center mt-3 mb-3">
					<h4>新規登録</h4>
					<?php
						if($obj->existMsg()){
							echo "<center><div>".$obj->getMsg()."</div></center>";
						}
					?>
					</div>
				</div>
				<div class="row">
					<div class="offset-3 col-6 mt-3">
							<label for="name" class="control-label">動物の名称</label>
							<input type="name" class="form-control" name="animalname" placeholder="動物の名称">
					</div>
				</div>
				<div class="row">
					<div class="offset-3 col-6 mt-3">
							<label for="family" class="control-label">何科</label>
							<input type="family" class="form-control" name="family" placeholder="何科">
					</div>
				</div>
				<div class="row">
					<div class="offset-3 col-6 mt-3">
							<label for="features" class="control-label">特徴</label>
							<input type="features" class="form-control" name="features" placeholder="特徴">
					</div>
				</div>
				<div class="row">
					<div class="offset-3 col-6 mt-3">
							<label for="date" class="control-label">知った日</label>
							<input type="date" class="form-control" name="date" placeholder="知った日">
					</div>
				</div>
				<div class="row">
					<div class="offset-3 col-6 mt-3">
							<label for="file" class="control-label">イメージ画像</label>
							<input type="file" class="form-control" name="image">
					</div>
				</div>
				<br>
				<button class="offset-4 col-4 btn btn-primary btn-sm mt-2 mb-3" type="submit">登録</button>
				<button class="offset-4 col-4 btn btn-primary btn-sm mt-2 mb-5" type="button" onclick="location.href='<?php echo Constants::TOP_URL?>'">トップページへ戻る</button>
			</form>
			</div>
		</div>
	</body>
</html>
