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
		<div class="container-fluid">
			<div class="row">
				<div class="offset-1 col-10 offset-md-3 col-md-6 text-center mt-3">
					<h4>ユーザー情報</h4>
				</div>
			</div>
				<div class="table-responsive col-12 offset-md-3 col-md-6 text-center mt-3 mb-3">
				<table class="table table-sm table-striped table-bordered">
					<thead>
					<tr class="bg-secondary text-white">
						<th style="width: 20%">項目</th>
						<th style="width: 60%">登録データ</th>
						<th style="width: 20%"></th>
					</tr>
				</thead>
					<tr>
						<th>会員番号</th>
						<td>
							<?php
					 		echo $user['data']['id'];
					 		?>
					 	</td>
					 	<td>
					 	</td>
					</tr>
					<tr>
						<th>ユーザーID</th>
					 	<td>
					 		<?php
					 			echo $user['data']['user_id'];
					 		?>
					 	</td>
						<td>
					 	</td>
					 </tr>
					 <tr>
					 	<th>姓</th>
					  <td>
					 		<?php
					 			echo $user['data']['last_name'];
					 		?>
					 	</td>
						<td>
					  	<button class="btn btn-primary btn-sm"　type="submit" onclick="location.href='<?php echo Constants::LAST_NAME_EDIT_URL?>'">変更</button>
					 	</td>
					  </tr>
						<tr>
					  	<th>名</th>
					  	<td>
					 			<?php
					 				echo $user['data']['first_name'];
					 			?>
					 		</td>
							<td>
					  		<button class="btn btn-primary btn-sm"　type="submit" onclick="location.href='<?php echo Constants::FIRST_NAME_EDIT_URL?>'">変更</button>
					 		</td>
					 	</tr>
						<tr>
					  	<th>部署</th>
					  	<td>
					 			<?php
					 				echo $user['data']['department'];
					 			?>
					 		</td>
							<td>
					  		<button class="btn btn-primary btn-sm"　type="submit" onclick="location.href='<?php echo Constants::DEPARTMENT_EDIT_URL?>'">変更</button>
					 		</td>
					 	</tr>
						<tr>
					 		<th>役職</th>
					  	<td>
					 			<?php
					 				echo $user['data']['post'];
					 			?>
					 		</td>
							<td>
					 			<button class="btn btn-primary btn-sm"　type="submit" onclick="location.href='<?php echo Constants::POST_EDIT_URL?>'">変更</button>
					 		</td>
					 	</tr>
						<tr>
					 		<th>生年月日</th>
					  	<td>
					 			<?php
					 				echo $user['data']['birth'];
					 			?>
					 		</td>
							<td>
					  		<button class="btn btn-primary btn-sm"　type="submit" onclick="location.href='<?php echo Constants::BIRTH_EDIT_URL?>'">変更</button>
					 		</td>
					 	</tr>
						<tr>
					 		<th>メールアドレス</th>
					  	<td>
					 			<?php
					 				echo $user['data']['mail'];
					 			?>
					 		</td>
							<td>
					  		<button class="btn btn-primary btn-sm"　type="submit" onclick="location.href='<?php echo Constants::MAIL_EDIT_URL?>'">変更</button>
					 		</td>
					 	</tr>
						<tr>
					 		<th>パスワード</th>
					  	<td>
					 			<?php
					 				echo '********';
					 			?>
					 		</td>
							<td>
					  		<button  class="btn btn-primary btn-sm"　type="submit" onclick="location.href='<?php echo Constants::PASS_CHANGE_URL?>'">変更</button>
					 		</td>
					 	</tr>
				</table>
		</div>
		<div class="row">
				<button  class="offset-3 col-6 offset-md-5 col-md-2 btn btn-primary btn-sm mt-2 mb-3"　type="button" onclick="location.href='<?php echo Constants::TOP_URL?>'">トップページへ戻る</button>
		</div>
</div>
	</body>
</html>
