<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
</head>
<body>

	<?php
			require_once(__DIR__."/../classes/Dao.php");
			require_once(__DIR__.'/../classes/constants.php');
			session_start();

			if( !empty($_GET['btn_logout']) ) {
				unset($_SESSION['login']);
			}


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
			$users_sql = 'select * from users where user_id = ?';
			//sql文の組み立て
			$users = Dao::db()->show_one_row($users_sql,array($_SESSION['login']));
			//$stmt = $dbh->prepare($users_sql);
			//PDOのファンクションprepare()で準備をする
			//$stmt->bindParam(1,$_SESSION['login']);
			//$_REQUESTは$_POSTをGETでもPOSTでも見れるようにしたもの。
			//上記のsql文の？を埋める

			//$stmt->execute();
			//sqlを実行する。値は$stmtインスタンスの中に保管されている

			//$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//fetchAll(PDO::FETCH_ASSOC)でsqlの結果の取り出し

				if($_SERVER["REQUEST_METHOD"] != "POST"){
			$msg = "";
		}else{
			if($_REQUEST['mail'] == ""){
				$msg = “メールアドレスを入力してください。“;
			}else{
				try{
					$sql2 = 'update users set mail = ? where user_id = ?';
					//sql文の組み立て
					Dao::db()->mod_exec($sql2,array($_REQUEST['mail'],$_SESSION['login']));
					//$stmt = $dbh->prepare($sql2);
					//PDOのファンクションprepare()で準備をする

					//$stmt->bindParam(1,$_REQUEST['mail']);
					//上記のsql文の？を埋める

					//$stmt->bindParam(2,$_SESSION['login']);

					//$stmt->execute();
					//sqlを実行する。値は$stmtインスタンスの中に保管されている

					mb_language("Japanese");
					mb_internal_encoding("UTF-8");

					  $to = $_REQUEST['mail'];
					  $title = 'メールアドレス登録完了のお知らせ';
					  $message = 'known_animalシステムからのお知らせです。'.PHP_EOL
					  .'メールアドレスの登録が完了いたしました。'.PHP_EOL
					  .'■メールアドレスの登録をリクエストされていない場合は本メールを削除してください。'.PHP_EOL
					  .'他の方がメールアドレスを間違って入力したため本メールが送信された可能性があります。';

					  $headers = "From: known_animal@test.com";

					  if(mb_send_mail($to, $title, $message, $headers))
					  {
					    echo '登録したメールアドレスに登録完了のメールを送りました。<br />';
					    echo 'メールの受信をご確認ください。';
					  }
					  else
					  {
					    echo "メール送信失敗です";
					  }
					  ?>
					  <script>
						setTimeout(function(){
  						window.location.href='<?php echo Constants::USER_EDIT_URL?>';
						}, 5*1000);

					 </script>

					<?php
					exit;

					//header ('Location:'.Constants::USER_EDIT_URL);
					//上記ページに遷移する。
					//exit;

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
		<h1>ユーザー情報の編集</h1>
		<?php
					if ($msg != ""){
					echo "<center><div>".$msg."</div></center>";
				}
				?>
				<br>
		<div>新しいメールアドレス</div>
		<div>
		<input type="email" name="mail" value="<?php echo $users['data']['mail']?>">
		</div>
		<br>
		<input type="submit" value="更新">
		<br>
			<button type="button" onclick="location.href='<?php echo Constants::USER_EDIT_URL?>'">ユーザー情報へ戻る</button>
	</center>
	</form>
</body>
