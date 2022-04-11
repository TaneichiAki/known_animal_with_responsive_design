<?php
	require_once(__DIR__."/../classes/Dao.php");
	require_once(__DIR__."/../classes/constants.php");
	require_once(__DIR__."/../classes/knownAnimalBase.php");
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
		<link rel="stylesheet" type="text/css" href="./entry.css">
		<title>新規登録</title>
	</head>
	<body>
		<div>
			<form method="post" enctype="multipart/form-data">
				<center>
					<h1>新規登録</h1>
					<?php
						if($msg != ""){
							echo "<center><div>".$msg."</div></center>";
						}
					?>
					<div>
						<div>
							<input type="name" name="animalname" placeholder="動物の名称">
						</div>
						<div>
							<input type="family" name="family" placeholder="何科">
						</div>
						<div>
							<input type="features" name="features" placeholder="特徴">
						</div>
						<div>
							<input type="date" name="date" placeholder="知った日">
						</div>
						<div>
							<input type="file" name="image">
							<br>
							<br>
							<input type="submit" value="登録">
						</div>
					</div>
					<button type="button" onclick="location.href='<?php echo Constants::TOP_URL?>'">トップページへ戻る</button>
				</center>
			</form>
		</div>
	</body>
</html>
