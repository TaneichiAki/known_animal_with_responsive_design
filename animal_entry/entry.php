<?php
	require_once(__DIR__."/../classes/Dao.php");
	require_once(__DIR__."/../classes/constants.php");
	require_once(__DIR__."/../classes/knownAnimalBase.php");

	class Entry extends KnownAnimalBase{
		
	}

	/**
	*グローバル変数定義
	*/
	$msg = "";
	/**
	*ENTRY処理
	*/
	function entry(){
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
	/**
	*POST時処理
	*/
	function post(){
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
		entry();
		//下記ページに遷移する。
		header ('Location:'.Constants::TOP_URL);
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

		<title>新規登録</title>
	</head>
	<body>
		<div>
			<form class="border offset-3 col-6 rounded bg-light mt-5" method="post" enctype="multipart/form-data">
					<div class="offset-md-3 col-md-6 text-center mt-3 mb-3">
					<h4>新規登録</h4>
					<?php
						if($msg != ""){
							echo "<center><div>".$msg."</div></center>";
						}
					?>
					</div>
					<div class="container-fluid">
						<div class="offset-md-3 col-md-6">
							<label for="name" class="control-label">動物の名称</label>
							<input type="name" class="form-control col-md-2" name="animalname" placeholder="動物の名称">
						</div>
						<div class="offset-md-3 col-md-6">
							<label for="family" class="control-label">何科</label>
							<input type="family" class="form-control col-md-2" name="family" placeholder="何科">
						</div>
						<div class="offset-md-3 col-md-6">
							<label for="features" class="control-label">特徴</label>
							<input type="features" class="form-control col-md-2" name="features" placeholder="特徴">
						</div>
						<div class="offset-md-3 col-md-6">
							<label for="date" class="control-label">知った日</label>
							<input type="date" class="form-control col-md-2" name="date" placeholder="知った日">
						</div>
						<div class="offset-md-3 col-md-6">
							<label for="file" class="control-label">イメージ画像</label>
							<input type="file" class="form-control" name="image">
							<br>
						</div>
							<br>
							<button class="offset-md-5 col-md-2 btn btn-primary btn-sm mt-2 mb-3" type="submit">登録</button>
					</div>
					<button class="offset-md-5 col-md-2 btn btn-primary btn-sm mt-2 mb-3" type="button" onclick="location.href='<?php echo Constants::TOP_URL?>'">トップページへ戻る</button>
				</div>
			</form>
		</div>
	</body>
</html>
