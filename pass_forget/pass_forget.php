<?php
	require_once(__DIR__."/../classes/Dao.php");
	require_once(__DIR__.'/../classes/constants.php');
	/**
	*グローバル変数定義
	*/
	$msg = "";
	$number = "";
	/*
	*メール送信処理
	*/
	function send_mail(){
		mb_language("Japanese");
		mb_internal_encoding("UTF-8");
		$to = $_REQUEST['mail'];
		$title = '仮パスワード発行のお知らせ';
		$message = 'known_animalシステムからのお知らせです。'.PHP_EOL
		.'仮パスワードを発行しました。'.PHP_EOL
		.'仮パスワードは下記の通りです。'.PHP_EOL
		.'仮パスワード：'.$GLOBALS['number'];
		$headers = "From: known_animal@test.com";

		if(mb_send_mail($to, $title, $message, $headers))
		{
			echo '入力したメールアドレスに仮パスワード発行のメールを送りました。<br />';
			echo 'メールの受信をご確認ください。';
			echo $GLOBALS['number'];
		}
		else
		{
			echo "メール送信失敗です";
		}
		?>
		<script>
		 setTimeout(function(){
		 window.location.href='<?php echo Constants::LOGIN_URL?>';
		 }, 5*1000);
		</script>
	<?php
	}
	/*
	*仮パスワード発行処理
	*/
	function pass_issuance(){
		$ar01 = array(0,1,2,3,4,5,6,7,8,9);
		$ar02 = array(a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z);
		$ar03 = array(A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z);
		$ar04 = array(0,1,2);
		for($i = 0; $i <= 1; $i++){
			$key_[0] = $ar01[array_rand($ar01)];
			$key_[1] = $ar02[array_rand($ar02)];
			$key_[2] = $ar03[array_rand($ar03)];
			shuffle($ar04);
			$GLOBALS['number'] =$GLOBALS['number']. $key_[$ar04[0]] . $key_[$ar04[1]] . $key_[$ar04[2]].$key_[rand(0,2)];
		}
		//上記で発行したパスワードをデータベース上に更新登録
		$update_sql = "update users set password = ? where mail = ?";
		$hash = password_hash($GLOBALS['number'], PASSWORD_DEFAULT);
		Dao::db()->mod_exec($update_sql,array($hash,$_REQUEST['mail']));
		//メール送信実行
		send_mail();
	}
	/*
	*POST時処理
	*/
	function post(){
		$select_sql = 'select * from users where mail = ?';
		$used_mail = Dao::db()->show_one_row($select_sql,array($_REQUEST['mail']));
		if($_REQUEST['mail'] == ""){
			return "メールアドレスを入力してください。";
		}
		if($used_mail['result'] == false ){
			return "入力したアドレスは登録がありません。";
		}
		//仮パスワード発行実行
		pass_issuance();
	}
	/*
	*メイン処理
	*/
	function main(){
		if($_SERVER["REQUEST_METHOD"]== "POST"){
			try{
				$GLOBALS['msg'] = post();
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
		<title>パスワード再設定画面</title>
	</head>
	<body>
		<form method="post">
		<center>
			<h1>パスワード再設定</h1>
				<?php
					if ($msg != ""){
						echo "<center><div>".$msg."</div></center>";
					}
				?>
				<br>
			<p>known_animalシステムアカウントに関連づけられているEメールアドレスを入力してください。</p>
			<input type='email' name='mail'>
			<br>
			<br>
			<input type='submit' value='パスワードを再設定'>
			<br>
			<br>
			<div style="padding: 10px 3px; margin: 5px 500px; border: 1px dashed #333333; border-radius: 10px;">
				<p>通常通りログインする方はこちら</p>
				<button type="button" onclick="location.href='<?php echo Constants::LOGIN_URL?>'"> 今すぐログイン</button>
			</div>
		</center>
		</form>
	</body>
</html>
