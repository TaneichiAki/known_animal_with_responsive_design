<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="./entry.css">

<title>新規登録</title>
	<?php
			require_once(__DIR__."/classes/Dao.php");
			require_once(__DIR__."/constants.php");
			session_start();
			
			if(! isset($_SESSION['login'])){
				header("Location:".Constants::LOGIN_URL);
				exit();
			}
			//セッションIDがセットされていなかったらログインページに戻る
			
			
				if($_SERVER["REQUEST_METHOD"] == "POST"){
		$msg = "";
		if($_REQUEST['animalname'] ==  ""){
			$msg = "動物の名称を入力してください。";
		}elseif($_REQUEST['family'] == ""){
			$msg = "何科か入力してください。";
		}elseif($_REQUEST['features'] == ""){
			$msg = "特徴を入力してください。";
		}elseif($_REQUEST['date'] == ""){
			$msg = "知った日を入力してください。";
		}else{
			/*
			if (is_uploaded_file($tempfile)) {
				if ( move_uploaded_file($tempfile , $filename )) {
					echo $filename . "をアップロードしました。";
					} else {
						 echo "ファイルをアップロードできません。";
						 }
					} else {
						echo "ファイルが選択されていません。";
						}
						*/
			//$dsn = 'pgsql:dbname='.Constants::DB_NAME.
			//' host='.Constants::DB_HOST.
			//' port='.Constants::DB_PORT;
			//$user = Constants::DB_USER;
			//$password = Constants::DB_PASS;
		//
		
		try{
			//$dbh = new PDO($dsn,$user,$password);
			//PDO($dsn,$user,$password)はPHPがあらかじめ用意しているコンストラクタでデータベースへの接続の確立
			$user_sql = 'select * from users  where user_id = ?';
			//sql文の組み立て
			$users2 = Dao::db()->show_one_row($user_sql,array($_SESSION['login']));
			
			//$stmt_animal = $dbh->prepare($animal_sql);
			//PDOのファンクションprepare()で準備をする
			//$stmt_animal->bindParam(1,$_SESSION['login']);
			//$_REQUESTは$_POSTをGETでもPOSTでも見れるようにしたもの。
			//上記のsql文の？を埋める
			
			//$stmt_animal->execute();
			//$users2 = $stmt_animal->fetchAll(PDO::FETCH_ASSOC);
			
			$memberid = $users2['data']['id'];
			
			//rename("$img_data","./animal_photo/test.jpg");
			
			
			/*$fp = fopen($img_data, "r");
			$data = fread($fp,filesize($img_data));
			fclose($fp);
			$escaped = pg_escape_bytea($data);
			*/
			$sql = 'insert into animal(name,family,features,date,memberid) values(?,?,?,?,?)';
			//sql文の組み立て
			$insert_id = Dao::db()->add_one_row($sql,array($_REQUEST['animalname'],$_REQUEST['family'],$_REQUEST['features'],$_REQUEST['date'],$memberid));			//$stmt = $dbh->prepare($sql);
			//PDOのファンクションprepare()で準備をする
			//var_dump($insert_id['id']);
			//exit;
			//$stmt->bindParam(1,$_REQUEST['animalname']);
			//上記のsql文の？を埋める
			
			//$stmt->bindParam(2,$_REQUEST['family']);
			
			//$stmt->bindParam(3,$_REQUEST['features']);
			
			//$stmt->bindParam(4,$_REQUEST['date']);
			
			//$stmt->bindParam(5,$memberid);
			
			//$stmt->execute();
			//sqlを実行する。値は$stmtインスタンスの中に保管されている
			
			//$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//fetchAll(PDO::FETCH_ASSOC)でsqlの結果の取り出し
			//$insert_id = $dbh->lastInsertId();
			move_uploaded_file($_FILES['image']['tmp_name'] , Constants::ANIMAL_PHOTO_SERVER.$insert_id['id'].'_animal.jpg' );
			
			header ('Location:'.Constants::TOP_URL);
				//上記ページに遷移する。
				exit;
	}catch (PDOException $e) {
			//phpではない外部のアプリと連携するときはtry catchでエラーが起きた時の動きを定義した方が良い
			print('Error:'.$e->getMessage());
			die();
			}
		}
	}
?>
</head>
<body>
	<div>
		<form method="post" enctype="multipart/form-data">
			<center>
				<h1>新規登録</h1>
			</center>
				<?php
				if ($msg != ""){
					echo "<center><div>".$msg."</div></center>";
				}
				?>
				<!-$msgの値が空でなければ、$msgを出力する。->
				

			<div>
				<center>
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
				</center>
			</div>
			<center>
				<button type="button" onclick="location.href='<?php echo Constants::TOP_URL?>'">トップページへ戻る</button>
			</center>
		</form>
	</div>
</body>

