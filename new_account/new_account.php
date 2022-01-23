<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
</head>
<body>
	<?php
			require_once(__DIR__.'/../classes/constants.php');
			require_once(__DIR__.'/../classes/Dao.php');
			/*
			$dsn = 'mysql:dbname='.Constants::DB_NAME.
			' host='.Constants::DB_HOST.
			' port='.Constants::DB_PORT;
			$user = Constants::DB_USER;
			$password = Constants::DB_PASS;
			$dbh = new PDO($dsn,$user,$password);

			$select_id='select count(user_id) from users where user_id=?';
			$stmt_userid = $dbh->prepare($select_id);
			$stmt_userid->bindParam(1,$_REQUEST['user_id']);
			$stmt_userid->execute();
			$user_cnt = $stmt_userid->fetchAll(PDO::FETCH_ASSOC);
			*/
			//var_dump($users_id[0]);
			//exit;
			$sql = "select * from users where user_id = ?";
			$user_cnt = Dao::db()->count_row($sql,array($_REQUEST['user_id']));


			if($_SERVER["REQUEST_METHOD"] == "POST"){
				$msg = "";


			function valid($request){
				$pattern = '/\A(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)[a-zA-Z\d]{8,100}+\z/';
				preg_match($pattern,$_REQUEST['pass'],$matches);

				if($request['last_name'] ==  ""){
					return "姓を入力してください。";
				}
				if($request['first_name'] == ""){
					return  "名を入力してください。";
				}
				if($request['user_id'] == ""){
					return  "ユーザーIDを入力してください。";
				}
				if($user_cnt >= 1){
					return "このユーザーIDは既に使われています。";
				}
				if($request['department'] == ""){
					return  "部署名を入力してください。";
				}
				if($request['post'] == ""){
					return  "役職名を入力してください。";
				}
				if($request['birth'] == ""){
					return  "生年月日を入力してください。";
				}
				if($request['gender'] == ""){
					return  "性別を選択してください。";
				}
				if($request['mail'] == ""){
					return  "メールアドレスを入力してください。";
				}
				if($request['pass'] == ""){
					return  "パスワードを入力してください。";
				}
				if($request['re-pass'] == ""){
					return  "パスワードを再入力してください。";
				}
				if($request['pass'] != $request['re-pass']){
					return "初回と再入力欄でパスワードが一致しません。";
				}
				if($matches == false){
					return "半角英小文字大文字数字をそれぞれ1種類以上含む8文字以上のパスワードにしてください。";
				}
			}

			$msg = valid($_POST);

			if($msg==""){
				try{
				//PDO($dsn,$user,$password)はPHPがあらかじめ用意しているコンストラクタでデータベースへの接続の確立
				$new_users_sql = 'insert into users (user_id, last_name, first_name, department, post, birth, gender, mail, password) VALUES (?,?,?,?,?,?,?,?,?)';
				$hash = password_hash($_REQUEST['pass'], PASSWORD_DEFAULT);
				$result = Dao::db()->add_one_row(
					$new_users_sql,
					array(
						$_REQUEST['user_id'],
						$_REQUEST['last_name'],
						$_REQUEST['first_name'],
						$_REQUEST['department'],
						$_REQUEST['post'],
						$_REQUEST['birth'],
						$_REQUEST['gender'],
						$_REQUEST['mail'],
						$hash
					)
				);
				//sql文の組み立て

				//$stmt = $dbh->prepare($new_users_sql);
				//PDOのファンクションprepare()で準備をする


				//$stmt->bindParam(1,$_REQUEST['user_id']);
				//$_REQUESTは$_POSTをGETでもPOSTでも見れるようにしたもの。
				//上記のsql文の？を埋める
				//$stmt->bindParam(2,$_REQUEST['last_name']);
				//$stmt->bindParam(3,$_REQUEST['first_name']);
				//$stmt->bindParam(4,$_REQUEST['department']);
				//$stmt->bindParam(5,$_REQUEST['post']);
				//$stmt->bindParam(6,$_REQUEST['birth']);
				//$stmt->bindParam(7,$_REQUEST['gender']);
				//$stmt->bindParam(8,$_REQUEST['mail']);
				//$stmt->bindParam(9,$hash);

				//$stmt->execute();
				header ('Location:'.Constants::NEW_ACCOUNT_DONE_URL);
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
<center>
	<form method="post" enctype="multipart/form-data">
		<h1>新規アカウント作成画面</h1>
		<?php
				if ($msg != ""){
					echo "<center><div>".$msg."</div></center>";
				}
				?>
				<!-$msgの値が空でなければ、$msgを出力する。->


			<div>
				<input type="text" name="last_name" placeholder="姓">
			</div>
			<div>
				<input type="text" name="first_name" placeholder="名">
			</div>
			<div>
				<input type="text" name="user_id" placeholder="ユーザーID">
				<!-条件：他の人が使っているユーザー名でないこと->
			</div>
			<div>
				<input type="text" name="department" placeholder="部署名">
			</div>
			<div>
				<input type="text" name="post" placeholder="役職名">
			</div>
			<div>
				<input type="date" name="birth" placeholder="生年月日">
			</div>
			<div>
				<input type="radio" name="gender" value="male">男性
				<input type="radio" name="gender" value="female">女性
			</div>
			<div>
				<input type="email" name="mail" placeholder="メールアドレス">
			</div>
			<div>
				<input type="password" name="pass" placeholder="パスワード">
			</div>
			<div>
				<input type="password" name="re-pass" placeholder="パスワード再入力">
			</div>
			<div style="color:#696969">※パスワードは半角英小文字大文字数字をそれぞれ1種類以上含む8文字以上にしてください。</div>
			<br>
			<input type="submit" value="アカウントを作成する">
			<br>
			<br>
			<div style="padding: 10px 3px; margin: 5px 500px; border: 1px dashed #333333; border-radius: 10px;">
				<p>アカウントをお持ちの方はこちら</p>
				<button type="button" onclick="location.href='<?php echo Constants::LOGIN_URL?>'"> 今すぐログイン</button>
			</div>
	</form>
</center>
</body>
