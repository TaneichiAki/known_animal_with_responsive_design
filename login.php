<link rel="stylesheet" href="login.css" type="text/css">
<?php
	require_once(__DIR__."/classes/Dao.php");
	require_once(__DIR__."/constants.php");
	session_start();
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$msg = "";
		if($_REQUEST['username'] == ""){
			$msg = "ユーザーIDを入力してください。";
		}elseif($_REQUEST['password'] == ""){
			$msg = "パスワードを入力してください。";
		}else{
			//$dsn = 'mysql:dbname='.Constants::DB_NAME.
			//' host='.Constants::DB_HOST.
			//' port='.Constants::DB_PORT;
			//$user = Constants::DB_USER;
			//$password = Constants::DB_PASS;

		
		try{
			//$dbh = new PDO($dsn,$user,$password);
			//PDO($dsn,$user,$password)はPHPがあらかじめ用意しているコンストラクタでデータベースへの接続の確立
			$sql = "select * from users where user_id = ?";
			$user = Dao::db()->show_one_row($sql,array($_REQUEST['username']));
			//sql文の組み立て
			//$stmt = $dbh->prepare($sql);
			//PDOのファンクションprepare()で準備をする
			//$stmt->bindParam(1,$_REQUEST['username']);
			//$_REQUESTは$_POSTをGETでもPOSTでも見れるようにしたもの。
			//上記のsql文の？を埋める
			
			//$stmt->execute();
			//sqlを実行する。値は$stmtインスタンスの中に保管されている
			
			//$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//fetchAll(PDO::FETCH_ASSOC)でsqlの結果の取り出し
			
			//var_dump($users);
			//検索結果がある場合は配列で取得できる
			//ない場合はarray(0)が取得できる
			/*if(empty($users)) {
				echo "ユーザーIDまたはパスワードが違います";
				exit;
			}
			*/
			//if((!  empty($users)) && password_verify($_REQUEST['password'], $users[0]['password']) == true) {

			if( $user['result'] && password_verify($_REQUEST['password'], $user['data']['password']) == true) {
				//$usersに値が入っていて、かつ　パスワードがデータベース内で指定したユーザー名のものと一致したら、
				
				session_regenerate_id(TRUE);
				$_SESSION['login'] = $_REQUEST['username'];
				
				header ('Location:'.Constants::TOP_URL);
				//上記ページに遷移する。
				exit;
			}else{
				$msg = "IDまたはパスワードが違います。";
			}
			
			/*foreach($dbh->query($sql) as $row) {
				print($row['user_id']);
				print($row['password'].'<br>');
			}
			*/
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
	<meta charset="UTF-8">
	<title>ログイン</title>
</head>
<body>
	<div>
		<form method="post">
			<center>
				<h1>ログイン</h1>
			
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
						<input type="id" name="username" placeholder="ユーザーID">
					</div>
					<br>
					<div>
						<input type="password" name="password" placeholder="Password">
					</div>
					
					
				</center>
			</div>
			<center>
				<button type="submit">ログイン</button>
				<br>
				 <a href='<?php echo Constants::PASS_FORGET_URL?>' >パスワードを忘れた方はこちら</a>
				<br>
				<br>
				<br>
				<div>初めてご利用の方はこちら</div>
					 <button type="button" onclick="location.href='<?php echo Constants::NEW_ACCOUNT_URL?>'">新規アカウントを作成</button>
			</center>
		</form>
	</div>
</body>
</html>
