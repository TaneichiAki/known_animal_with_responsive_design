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
		<!-- Required meta tags -->
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Bootstrap CSS -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	</head>
	<body>
		<div class="container-fluid">
			<div class="row">
				<form class="border offset-1 col-10 offset-md-3 col-md-6 rounded bg-light mt-4 mb-5" method="post" enctype="multipart/form-data">
					<div class="row">
						<div class="offset-2 col-8 text-center mt-3">
							<h5>新規アカウント作成</h5>
						</div>
					</div>
					<div class="row">
						<div class="offset-2 col-8 text-center mt-3">
						<?php
							if ($msg != ""){
								echo "<center><div>".$msg."</div></center>";
							}
						?>
						</div>
					</div>
					<div class="row">
						<div class="offset-2 col-8 mt-3">
							<label for="last_name" class="control-label">姓</label>
							<input type="text" class="form-control" name="last_name" placeholder="姓">
						</div>
					</div>
					<div class="row">
						<div class="offset-2 col-8 mt-3">
							<label for="first_name" class="control-label">名</label>
							<input type="text" class="form-control" name="first_name" placeholder="名">
						</div>
					</div>
					<div class="row">
						<div class="offset-2 col-8 mt-3">
							<label for="user_id" class="control-label">ユーザーID</label>
							<input type="text" class="form-control" name="user_id" placeholder="ユーザーID">
							<!-条件：他の人が使っているユーザー名でないこと->
						</div>
					</div>
					<div class="row">
						<div class="offset-2 col-8 mt-3">
							<label for="department" class="control-label">部署名</label>
							<input type="text" class="form-control" name="department" placeholder="部署名">
						</div>
					</div>
					<div class="row">
						<div class="offset-2 col-8 mt-3">
							<label for="post" class="control-label">役職名</label>
							<input type="text" class="form-control" name="post" placeholder="役職名">
						</div>
					</div>
					<div class="row">
						<div class="offset-2 col-8 mt-3">
							<label for="birth" class="control-label">生年月日</label>
							<input type="date" class="form-control" name="birth" placeholder="生年月日">
						</div>
					</div>
					<div class="row">
						<div class="offset-2 col-8 text-center mt-3">
							<input type="radio" name="gender" value="male">男性
							<input type="radio" name="gender" value="female">女性
						</div>
					</div>
					<div class="row">
						<div class="offset-2 col-8 mt-3">
							<label for="mail" class="control-label">メールアドレス</label>
							<input type="email" class="form-control" name="mail" placeholder="メールアドレス">
						</div>
					</div>
					<div class="row">
						<div class="offset-2 col-8 mt-3">
							<label for="pass" class="control-label">パスワード</label>
							<input type="password" class="form-control" name="pass" placeholder="パスワード">
						</div>
					</div>
					<div class="row">
						<div class="offset-2 col-8 mt-3">
							<label for="re-pass" class="control-label">パスワード再入力</label>
							<input type="password" class="form-control" name="re-pass" placeholder="パスワード再入力">
						</div>
					</div>
					<div class="row">
						<p class="text-secondary mt-3 mb-4">※パスワードは半角英小文字大文字数字をそれぞれ1種類以上含む8文字以上にしてください。</p>
					</div>
					<div class="row">
						<input type="submit" class="offset-2 col-8 offset-md-4 col-md-4 btn btn-primary btn-sm mb-2" value="アカウントを作成する">
					</div>
					<div class="row">
						<div class="text-center mt-4">
							<p>アカウントをお持ちの方はこちら</p>
						</div>
					</div>
					<div class="row">
						<button type="button" class="offset-2 col-8 offset-md-4 col-md-4 btn btn-primary btn-sm mt-1 mb-3" onclick="location.href='<?php echo Constants::LOGIN_URL?>'"> 今すぐログイン</button>
					</div>
				</form>
			</div>
		</div>
	</body>
</html>
