<?php
		require_once(__DIR__."/../classes/Dao.php");
		require_once(__DIR__."/../classes/constants.php");

		session_start();
		//ログアウトボタン押下後、セッションIDを削除する
		if( !empty($_GET['btn_logout']) ) {
			unset($_SESSION['login']);
		}

		//セッションIDがセットされていなかったらログインページに戻る
		if(! isset($_SESSION['login'])){
			header("Location:".Constants::LOGIN_URL);
			exit();
		}
		try{
			//データベースに接続し、テーブルに登録されているユーザーの知ってる動物データを抽出
			$animal_sql = 'select * from users inner join animal on users.id = animal.memberid  where user_id = ?';
			$animals = Dao::db()->show_any_rows($animal_sql,array($_SESSION['login']));
			//登録されている動物件数
			$count = Dao::db()->count_row($animal_sql,array($_SESSION['login']));
			//ログインユーザー情報
			$users_sql = 'select * from users where user_id = ?' ;
			$users = Dao::db()->show_one_row($users_sql,array($_SESSION['login']));
		}catch(PDOException $e){
			print('Error:'.$e->getMessage());
			die();
		}
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
		<link rel="stylesheet" type="text/css" href="./top.css">
		<title>TOP</title>
	</head>
	<body>
		<div class="container-fluid">
		<div class="row mb-2">
		<!-- ナビゲーションバー -->
		<nav class="navbar navbar-expand-md navbar-light bg-light fixed-top">
		  <!-- タイトル -->
		  <a class="navbar-brand" href="#">
				<img src="../image/KnownAnimalLogo.png" width="180" height="auto" class="d-inline-block align-top" alt="">
			</a>
		  <!-- ハンバーガーメニュー -->
		  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
		    <span class="navbar-toggler-icon"></span>
		  </button>
		  <!-- ナビゲーションメニュー -->
		  <div class="collapse navbar-collapse" id="navbarNav">
		    <ul class="navbar-nav">
		      <li class="nav-item active">
		        <a class="nav-link" href="<?php echo Constants::ENTRY_URL?>">新規登録</a>
		      </li>
		      <li class="nav-item">
		        <a class="nav-link" href='<?php echo Constants::USER_EDIT_URL?>'>ユーザー情報の編集</a>
		      </li>
		      <li class="nav-item">
						<form name="logout" method="get" action="">
							<a class="nav-link" id="logout" href='#'>ログアウト</a>
							<input type="hidden" id="hidden" name="btn_logout" value="ログアウト">
						 </form>
						 <script>
						  var a_link = document.getElementById("logout");

						  a_link.addEventListener('click', function() {
						    //submit()でフォームの内容を送信
						    document.logout.submit();
						  })
							console.dir(document.getElementById("hidden"));
						</script>

		      </li>
		    </ul>
		  </div>
		</nav>
		</div>
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
 		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
 		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
		<div class="row mt-5 mb-2">
			<br>
			<p class="account">ようこそ！ <?php echo $users['data']['first_name']?>さん</p>
		</div>
		<div class="row">
			<?php echo $_REQUEST["update_message"] ?>
		</div>
			<div class="row">
				<div class="card-group">
					<?php for($i = 0;$i < $count; $i++): ?>
  				<div class="col-sm-3">
						<div class="card">
							<img src="gazou.php?gazou_animal=<?php echo $animals['data'][$i]['no'] ?>" class="card-img-top" height=220 width=auto>
							<div class="card-body">
								<h5 class="card-title">
									<?= $animals['data'][$i]['name']; ?>
								</h5>
								<p>科：
									<?php
										echo $animals['data'][$i]['family'];
									?>
								</p>
								<p>特徴：
									<?php
										echo $animals['data'][$i]['features'];
									?>
								</p>
								<p>知った日：
									<?php
										echo $animals['data'][$i]['date'];
									?>
								</p>
								<button class="btn btn-primary" type="submit" onclick="location.href='<?php echo Constants::EDIT_URL?>?update_animal=<?php echo $animals['data'][$i]['no'] ?>'">更新</button>
								<button class="btn btn-primary" type="submit" onclick="window.open('<?php echo Constants::DELETE_URL?>?delete_animal=<?php echo $animals['data'][$i]['no'] ?>','Delete','width=800,height=600')">削除</button>
							</div>
						</div>
					</div>
					<?php endfor; ?>
				</div>
			</div>
		</div>
	</body>
</html>
