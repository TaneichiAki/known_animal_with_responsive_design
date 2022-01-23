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

	?>
	<center>
		<h1>ユーザー情報</h1>
		<table border = "1" style = "border-collapse: collapse">
						<tr><td bgcolor= '#dcdcdc'>会員番号</td>
						<td>
						<?php
						 echo $users['data']['id'];
						 ?>
						 </td>
						 <td>
						 </td>
						 </tr><tr>
						 <td bgcolor= '#dcdcdc'>ユーザーID</td>
						 <td>
						 <?php
						 echo $users['data']['user_id'];
						 ?>
						 </td><td>
						 </td>
						  </tr><tr>
						 <td bgcolor= '#dcdcdc'>姓</td>
						  <td>
						 <?php
						 echo $users['data']['last_name'];
						 ?>
						 </td><td>
						  <button type="submit" onclick="location.href='<?php echo Constants::LAST_NAME_EDIT_URL?>'">変更</button>
						 </td>
						  </tr><tr>
						  <td bgcolor= '#dcdcdc'>名</td>
						  <td>
						 <?php
						 echo $users['data']['first_name'];
						 ?>
						 </td><td>
						  <button type="submit" onclick="location.href='<?php echo Constants::FIRST_NAME_EDIT_URL?>'">変更</button>
						 </td>
						 </tr><tr>
						  <td bgcolor= '#dcdcdc'>部署</td>
						  <td>
						 <?php
						 echo $users['data']['department'];
						 ?>
						 </td><td>
						  <button type="submit" onclick="location.href='<?php echo Constants::DEPARTMENT_EDIT_URL?>'">変更</button>
						 </td>
						 </tr><tr>
						 <td bgcolor= '#dcdcdc'>役職</td>
						  <td>
						 <?php
						 echo $users['data']['post'];
						 ?>
						 </td><td>
						 <button type="submit" onclick="location.href='<?php echo Constants::POST_EDIT_URL?>'">変更</button>
						 </td>
						 </tr><tr>
						 <td bgcolor= '#dcdcdc'>生年月日</td>
						  <td>
						 <?php
						 echo $users['data']['birth'];
						 ?>
						 </td><td>
						  <button type="submit" onclick="location.href='<?php echo Constants::BIRTH_EDIT_URL?>'">変更</button>
						 </td>
						 </tr><tr>
						 <td bgcolor= '#dcdcdc'>メールアドレス</td>
						  <td>
						 <?php
						 echo $users['data']['mail'];
						 ?>
						 </td><td>
						  <button type="submit" onclick="location.href='<?php echo Constants::MAIL_EDIT_URL?>'">変更</button>
						 </td>
						 </tr><tr>
						 <td bgcolor= '#dcdcdc'>パスワード</td>
						  <td>
						 <?php
						 echo '********';
						 ?>
						 </td><td>
						  <button type="submit" onclick="location.href='<?php echo Constants::PASS_CHANGE_URL?>'">変更</button>
						 </td>
						 </tr>
						 </table>
						 <br>
						 <button type="button" onclick="location.href='<?php echo Constants::TOP_URL?>'">トップページへ戻る</button>

	</center>

</body>
