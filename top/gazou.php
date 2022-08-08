<?php
session_start();

//セッションIDがセットされていなかったらログインページに戻る
if(! isset($_SESSION['login'])){
  exit();
}

$file = '/home/testaki/animal_photo/'.$_REQUEST["gazou_animal"].'_animal.jpg';

if(!file_exists($file)){
  $file = '/home/testaki/animal_photo/no_image.jpeg';
}
//MIMEタイプの取得
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime_type = $finfo->file($img_file);

header('Content-Type: '.$mime_type);
readfile($file);

?>
