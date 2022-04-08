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
		<div class="row bg-light">
			<div class="col-4">
				<a class="navbar-brand" href="#">
					<img src="../image/KnownAnimalLogo.png" width="180" height="auto" class="d-inline-block align-top" alt="">
				</a>
			</div>
			<div class="offset-7 col-1">
				<ul class="navbar-nav">
	        <li class="nav-item dropdown">
	         <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	           MENU
	         </a>
	         <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
						 <a class="dropdown-item" href="<?php echo Constants::ENTRY_URL?>">新規登録</a>
						 <a class="dropdown-item" href='<?php echo Constants::USER_EDIT_URL?>'>ユーザー情報の編集</a>
						 <form method="get" action="">
		    				<input type="submit" class="dropdown-item" name="btn_logout" value="ログアウト">
							</form>
	         </div>
	        </li>
	      </ul>
			</div>
		</div>
		<main>
			<div class="container">
			</div>
		</main>
		<!--
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<div class="container-fluid">
				<a class="navbar-brand" href="#">
					<img src="../image/KnownAnimalLogo.png" width="180" height="auto" class="d-inline-block align-top" alt="">
				</a>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavLightDropdown" aria-controls="navbarNavLightDropdown" aria-expanded="false" aria-label="Toggle navigation">
  				<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse justify-content-end" id="navbarNavLightDropdown">
					<ul class="navbar-nav mr-auto">
				  	<li class="nav-item dropdown">
				    	<a class="nav-link dropdown-toggle" href="#" id="navbarNavLightDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">MENU</a>
							<ul class="list-right dropdown-menu dropdown-menu-right" aria-labelledby="navbarLightDropdownMenuLink">
								<li>
				    			<a class="dropdown-item" href="<?php echo Constants::ENTRY_URL?>">新規登録</a>
				  			</li>
				  			<li>
				    			<a class="dropdown-item" href='<?php echo Constants::USER_EDIT_URL?>'>ユーザー情報の編集</a>
				  			</li>
				  			<li>
									<form method="get" action="">
				    				<input type="submit" class="dropdown-item" name="btn_logout" value="ログアウト">
									</form>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</nav>
	-->
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
 		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
 		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

		<p class="account">ようこそ！ <?php echo $users['data']['first_name']?>さん</p>
		<br>
		<div>
			<?php echo $_REQUEST["update_message"] ?>
		</div>
		<div class="container">
			<div class="card-group mb-3">
				<div class="row">
					<?php for($i = 0;$i < $count; $i++): ?>
  				<div class="col-sm-3">
						<div class="card">
							<?php
							$filename = Constants::ANIMAL_PHOTO_SERVER.$animals['data'][$i]['no'].'_animal.jpg';
							?>
							<?php if(file_exists($filename)): ?>
							<img src='/~testaki/animal_photo/<?php echo $animals['data'][$i]['no'] ?>_animal.jpg?<?php echo date("YmdHis");?>' class="card-img-top" height=220 width=auto>
							<?php else: ?>
							<img src='/~testaki/animal_photo/no_image.jpeg?<?php echo date("YmdHis");?>' class="card-img-top" height=220 width=auto>
							<?php endif; ?>
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
