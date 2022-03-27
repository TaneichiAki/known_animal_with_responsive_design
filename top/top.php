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
		<header>



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



		</header>
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
		</div>
		<div>
			<?php echo $_REQUEST["update_message"] ?>
		</div>
		<center>
			<h2>
				<img src= "../image/inu.png"  class=""animal" alt = "犬" title = "犬" width = 60 height = 60 >知ってる動物一覧
				<img src= "../image/neko.png"  class="animal" alt = "猫" title = "猫" width = 60 height = 60 >
			</h2>
				<table border = "1" style = "border-collapse: collapse">
					<tr bgcolor="#dcdcdc"><th>No</th><th>イメージ</th><th>名称</th><th>科</th><th>特徴</th><th>知った日</th><th>編集</th></tr>
					<?php for($i = 0;$i < $count; $i++): ?>
					<tr height=80px><td>
					<?=$i+1 ;?>
					</td><td>
					<div style="text-align:center;">
					<?php
					$filename = Constants::ANIMAL_PHOTO_SERVER.$animals['data'][$i]['no'].'_animal.jpg';
					?>
					<?php if(file_exists($filename)): ?>
					<img src='/~testaki/animal_photo/<?php echo $animals['data'][$i]['no'] ?>_animal.jpg?<?php echo date("YmdHis");?>' width=auto height=80px />
					<?php else: ?>
						<img src='/~testaki/animal_photo/no_image.jpeg?<?php echo date("YmdHis");?>' width=auto height=80px>
					<?php endif; ?>
					</div>
					</td><td>
					<?= $animals['data'][$i]['name']; ?>
					</td><td>
					<?php
					echo $animals['data'][$i]['family'];
					?>
					</td><td>
					<?php
					echo $animals['data'][$i]['features'];
					?>
					</td><td>
					<?php
					echo $animals['data'][$i]['date'];
					?>
					</td><td>
					<button type="submit" onclick="location.href='<?php echo Constants::EDIT_URL?>?update_animal=<?php echo $animals['data'][$i]['no'] ?>'">更新</button>

					<button type="submit" onclick="window.open('<?php echo Constants::DELETE_URL?>?delete_animal=<?php echo $animals['data'][$i]['no'] ?>','Delete','width=800,height=600')">削除</button>
					</td></tr>
					<?php endfor; ?>
				</table>
				<br>
		</center>
	</body>
</html>
