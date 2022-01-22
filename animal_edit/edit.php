<!DOCTYPE html>
<html lang=“ja”>
<head>
<meta charset="utf-8">
<title>更新</title>
</head>
<body>
	<?php
		require_once(__DIR__."/classes/Dao.php");
		require_once(__DIR__."/constants.php");
		session_start();
				
				
		if(! isset($_SESSION['login'])){
				header("Location:".Constants::LOGIN_URL);
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
		$users2 = Dao::db()->show_one_row($sql,array($_REQUEST['update_animal']));
		//sql文の組み立て
		//var_dump($_SERVER["REQUEST_METHOD"]);
		//var_dump($_REQUEST['update_animal']);
		//$stmt_animal = $dbh->prepare($sql);
		//PDOのファンクションprepare()で準備をする
		//$stmt_animal->bindParam(1,$_REQUEST['update_animal']);
		//$_REQUESTは$_POSTをGETでもPOSTでも見れるようにしたもの。
		//上記のsql文の？を埋める
		//$stmt_animal->execute();
		
		//$users2 = $stmt_animal->fetchAll(PDO::FETCH_ASSOC);
		
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
					//sql文の組み立て
					$users = Dao::db()->mod_exec($sql2,array($_REQUEST['family'],$_REQUEST['features'],$_REQUEST['date'],$_REQUEST['update_animal']));
					//$stmt = $dbh->prepare($sql2);
					//PDOのファンクションprepare()で準備をする
					
					//$stmt->bindParam(1,$_REQUEST['family']);
					//上記のsql文の？を埋める
					
					//$stmt->bindParam(2,$_REQUEST['features']);
					
					//$stmt->bindParam(3,$_REQUEST['date']);
					
					//$stmt->bindParam(4,$memberid);
					
					//$stmt->bindParam(4,$_REQUEST['update_animal']);
					
					//$stmt->execute();
					//sqlを実行する。値は$stmtインスタンスの中に保管されている

					//$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
					//fetchAll(PDO::FETCH_ASSOC)でsqlの結果の取り出し
					
					move_uploaded_file($_FILES['image']['tmp_name'] ,Constants::ANIMAL_PHOTO_SERVER.$_REQUEST['update_animal'].'_animal.jpg' );
					
					//var_dump($test);
					//exit;
										
					header ('Location:'.Constants::TOP_URL);
					//上記ページに遷移する。
					exit;

					//$select_family  = "";
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
				<!-$msgの値が空でなければ、$msgを出力する。->
				
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