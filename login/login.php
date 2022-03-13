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
		<!-- Required meta tags -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
		<title>ログイン</title>
	</head>
	<body>
		<div>
			<form class="border offset-4 col-4 rounded bg-light mt-5" method="post">
				<img class="offset-md-4 col-md-4 text-center mt-5" src="../image/KnownAnimalLogo.png">
				<?php
					if ($msg != ""){
						echo "<center><div>".$msg."</div></center>";
					}
				?>
				<div class="container-fluid">
					<div class="row">
						<div class="offset-md-2 col-md-8 text-center mt-4">
									<input type="id" class="form-control" name="username" placeholder="ユーザーID">
						</div>
						<div class="offset-md-2 col-md-8 text-center">
									<input type="password" class="form-control" name="password" placeholder="Password">
						</div>
						<div class="offset-md-2 col-md-8 text-center">
							<button class="btn btn-primary btn-sm mt-2 mb-3" type="submit">ログイン</button>
						</div>
						<div class="offset-md-2 col-md-8 text-center mb-3">
							<a href='<?php echo Constants::PASS_FORGET_URL?>' >パスワードを忘れた方はこちら</a>
						</div>
						<div class="offset-md-2 col-md-8 text-center">
							<div>初めてご利用の方はこちら</div>
						</div>
						<div class="offset-md-2 col-md-8 text-center mb-5">
							<button class="btn btn-primary btn-sm" type="button" onclick="location.href='<?php echo Constants::NEW_ACCOUNT_URL?>'">新規アカウントを作成</button>
						</div>
				</div>
				</div>
			</form>
		</div>
	</body>
</html>
