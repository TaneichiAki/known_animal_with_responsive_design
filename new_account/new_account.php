<?php
	require_once(__DIR__.'/../classes/constants.php');
	require_once(__DIR__.'/../classes/Dao.php');
	/**
	*グローバル変数定義
	*/
	$msg = "";
	/*
	*不備チェック処理
	*/
	function valid($request){
		if($request['last_name'] ==  ""){
			return "姓を入力してください。";
		}
		if($request['first_name'] == ""){
			return  "名を入力してください。";
		}
		if($request['user_id'] == ""){
			return  "ユーザーIDを入力してください。";
		}
		$select_sql = "select * from users where user_id = ?";
		$user_cnt = Dao::db()->count_row($select_sql,array($_REQUEST['user_id']));
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
		$pattern = '/\A(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)[a-zA-Z\d]{8,100}+\z/';
		preg_match($pattern,$_REQUEST['pass'],$matches);
		if($matches == false){
			return "半角英小文字大文字数字をそれぞれ1種類以上含む8文字以上のパスワードにしてください。";
		}
	}
	/*
	*新アカウント作成処理
	*/
	function new_account(){
		$new_user_sql = 'insert into users (user_id, last_name, first_name, department, post, birth, gender, mail, password) VALUES (?,?,?,?,?,?,?,?,?)';
		$hash = password_hash($_REQUEST['pass'], PASSWORD_DEFAULT);
		$result = Dao::db()->add_one_row(
			$new_user_sql,
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
		//下記ページに遷移する。
		header ('Location:'.Constants::NEW_ACCOUNT_DONE_URL);
		exit;
	}
	/*
	*メイン処理
	*/
	function main(){
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			try{
				$GLOBALS['msg'] = valid($_POST);
				if($GLOBALS['msg']==""){
					//新アカウント作成実行
					new_account();
				}
			}catch (PDOException $e){
				//phpではない外部のアプリと連携するときはtry catchでエラーが起きた時の動きを定義した方が良い
				print('Error:'.$e->getMessage());
				die();
			}
		}
	}
	/*
	*メイン処理実行
	*/
	main();
?>
<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<center>
			<form method="post" enctype="multipart/form-data">
				<h1>新規アカウント作成画面</h1>
				<?php
					if ($msg != ""){
						echo "<center><div>".$msg."</div></center>";
					}
				?>
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
</html>
