<?php
	require_once(__DIR__."/../classes/Dao.php");
	require_once(__DIR__."/../classes/constants.php");
	session_start();
	//セッションIDがセットされていなかったらログインページに戻る
	if(! isset($_SESSION['login'])){
		header("Location:".Constants::LOGIN_URL);
		exit();
	}
	/*
	*ユーザーID
	*/
	$id = $_SESSION['login'];

	//ログインユーザーの情報を抽出
	$sql = "select * from users where user_id = ?";
	$users = Dao::db()->show_one_row($sql,array($id));
	/*
	*パスワード
	*/
	$pass = $users['data']['password'];
	//新しいパスワードが$patternの正規表現パターンにマッチしているか判定
	$pattern = '/\A(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)[a-zA-Z\d]{8,100}+\z/';
	preg_match($pattern,$_REQUEST['new_pass'],$matches);


	if($_SERVER["REQUEST_METHOD"] != "POST"){
		$msg = "";
	}else{
		if($_REQUEST['current_pass'] == ""){
			$msg = "現在のパスワードを入力してください。";
		}elseif($_REQUEST['new_pass'] == ""){
			$msg = "新しいパスワードを入力してください。";
		}elseif($_REQUEST['re_new_pass'] == ""){
			$msg = "新しいパスワードを再度入力してください。";
		}elseif(password_verify($_REQUEST['current_pass'], $pass) == false){
			$msg = "現在のパスワードが正しくありません。";
		}elseif($_REQUEST['new_pass'] != $_REQUEST['re_new_pass'] ){
			$msg = "１回目と２回目で新しいパスワードが一致しません。";
		}elseif($matches == false){
			$msg = "半角英小文字大文字数字をそれぞれ1種類以上含む8文字以上のパスワードにしてください。";
		}else{
			try{
				$sql2 = "update users set password = ? where user_id = '".$id."'";

				$hash = password_hash($_REQUEST['new_pass'], PASSWORD_DEFAULT);
				Dao::db()->mod_exec($sql2,array($hash));
				//下記ページに遷移する
				header ('Location:'.Constants::PASS_CHANGE_DONE_URL);
				exit;
			}catch (PDOException $e) {
				print('Error:'.$e->getMessage());
				die();
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
</head>
<body>
<form method="post" enctype="multipart/form-data">
	<center>
		<h1>パスワード変更</h1>
			<?php
			if ($msg != ""){
				echo "<center><div>".$msg."</div></center>";
			}
			?>
		<div>
			<p>現在のパスワード: <input type="password" name="current_pass"></p>
		</div>
		<div>
			<p>新しいパスワード: <input type="password" name="new_pass"></p>
		</div>
		<div>
			<p>新しいパスワード: <input type="password" name="re_new_pass" placeholder="もう一度入力してください"></p>
			<p>※パスワードは半角英小文字大文字数字をそれぞれ1種類以上含む8文字以上にしてください。</p>
		</div>
		<input type="submit" value="変更">
		<br>
		<button type="button" onclick="location.href='<?php echo Constants::USER_EDIT_URL?>'">ユーザー情報へ戻る</button>
	</center>
</form>
</body>
