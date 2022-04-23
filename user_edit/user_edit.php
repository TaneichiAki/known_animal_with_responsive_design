<?php
	require_once(__DIR__."/../classes/Dao.php");
	require_once(__DIR__.'/../classes/constants.php');
	/**
	*グローバル変数定義
	*/
	$user = "";
	/*
	*メイン処理
	*/
	function main(){
		session_start();
		//セッションIDがセットされていなかったらログインページに戻る
		if(! isset($_SESSION['login'])){
			header("Location:".Constants::LOGIN_URL);
			exit();
		}
		$select_sql = 'select * from users where user_id = ?';
		$GLOBALS['user'] = Dao::db()->show_one_row($select_sql,array($_SESSION['login']));
	}
	/**
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

		<title>ユーザー情報編集</title>
	</head>
	<body>
		<center>
			<h1>ユーザー情報</h1>
				<table border = "1" style = "border-collapse: collapse">
					<tr>
						<td bgcolor= '#dcdcdc'>会員番号</td>
						<td>
							<?php
					 		echo $user['data']['id'];
					 		?>
					 	</td>
					 	<td>
					 	</td>
					</tr>
					<tr>
						<td bgcolor= '#dcdcdc'>ユーザーID</td>
					 	<td>
					 		<?php
					 			echo $user['data']['user_id'];
					 		?>
					 	</td>
						<td>
					 	</td>
					 </tr>
					 <tr>
					 	<td bgcolor= '#dcdcdc'>姓</td>
					  <td>
					 		<?php
					 			echo $user['data']['last_name'];
					 		?>
					 	</td>
						<td>
					  	<button type="submit" onclick="location.href='<?php echo Constants::LAST_NAME_EDIT_URL?>'">変更</button>
					 	</td>
					  </tr>
						<tr>
					  	<td bgcolor= '#dcdcdc'>名</td>
					  	<td>
					 			<?php
					 				echo $user['data']['first_name'];
					 			?>
					 		</td>
							<td>
					  		<button type="submit" onclick="location.href='<?php echo Constants::FIRST_NAME_EDIT_URL?>'">変更</button>
					 		</td>
					 	</tr>
						<tr>
					  	<td bgcolor= '#dcdcdc'>部署</td>
					  	<td>
					 			<?php
					 				echo $user['data']['department'];
					 			?>
					 		</td>
							<td>
					  		<button type="submit" onclick="location.href='<?php echo Constants::DEPARTMENT_EDIT_URL?>'">変更</button>
					 		</td>
					 	</tr>
						<tr>
					 		<td bgcolor= '#dcdcdc'>役職</td>
					  	<td>
					 			<?php
					 				echo $user['data']['post'];
					 			?>
					 		</td>
							<td>
					 			<button type="submit" onclick="location.href='<?php echo Constants::POST_EDIT_URL?>'">変更</button>
					 		</td>
					 	</tr>
						<tr>
					 		<td bgcolor= '#dcdcdc'>生年月日</td>
					  	<td>
					 			<?php
					 				echo $user['data']['birth'];
					 			?>
					 		</td>
							<td>
					  		<button type="submit" onclick="location.href='<?php echo Constants::BIRTH_EDIT_URL?>'">変更</button>
					 		</td>
					 	</tr>
						<tr>
					 		<td bgcolor= '#dcdcdc'>メールアドレス</td>
					  	<td>
					 			<?php
					 				echo $user['data']['mail'];
					 			?>
					 		</td>
							<td>
					  		<button type="submit" onclick="location.href='<?php echo Constants::MAIL_EDIT_URL?>'">変更</button>
					 		</td>
					 	</tr>
						<tr>
					 		<td bgcolor= '#dcdcdc'>パスワード</td>
					  	<td>
					 			<?php
					 				echo '********';
					 			?>
					 		</td>
							<td>
					  		<button type="submit" onclick="location.href='<?php echo Constants::PASS_CHANGE_URL?>'">変更</button>
					 		</td>
					 	</tr>
				</table>
				<br>
			<button type="button" onclick="location.href='<?php echo Constants::TOP_URL?>'">トップページへ戻る</button>
		</center>
	</body>
</html>
