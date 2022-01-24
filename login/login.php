<?php
	require_once(__DIR__."/../classes/Logger.php");
	require_once(__DIR__."/../classes/Dao.php");
	require_once(__DIR__."/../classes/constants.php");

	$logger = new Logger(Constants::LOGPATH);

	$GLOBALS['logger']->loggerInfo(__FILE__.":".__LINE__);

	//セッション処理開始
	session_start();
	/*
	*メッセージ
	*/
	$msg = "";
	/*
	*メッセージ出力ファンクション
	*/
	function getMsg($name,$password){
		if($name == ""){
			return "ユーザーIDを入力してください。";
		}elseif($password == ""){
			return "パスワードを入力してください。";
		}else{
			return "";
		}
	}

	//リクエストメソッドが「POST」（ログインボタン押下後）ならば
	if($_SERVER["REQUEST_METHOD"] == "POST"){

		$msg = getMsg($_REQUEST['username'],$_REQUEST['password']);
		//メッセージが空ならば
		if($msg == ""){
			try{
				//データベースに接続し、ユーザーIDが一致するものを抽出
				$sql = "select * from users where user_id = ?";
				$user = Dao::db()->show_one_row($sql,array($_REQUEST['username']));

				//$userに値が入っていて、かつ　パスワードがデータベース内で指定したユーザー名のものと一致したら
				if($user['result'] && password_verify($_REQUEST['password'], $user['data']['password']) == true) {
					//セッションIDを再生成し
					session_regenerate_id(TRUE);
					$_SESSION['login'] = $_REQUEST['username'];
					//TOPページに遷移
					header ('Location:'.Constants::TOP_URL);
					exit;
				//IDとパスワードの照合がとれなければ
				}else{
					$msg = "IDまたはパスワードが違います。";
				}

			//例外バグ検出時に下記を実行（外部のアプリと連携するときによく使う）
			}catch (PDOException $e) {
				print('Error:'.$e->getMessage());
				die();
			}
		}
	}

?>
<link rel="stylesheet" href="login.css" type="text/css">
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
