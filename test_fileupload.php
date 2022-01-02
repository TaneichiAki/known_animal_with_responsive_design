<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){
//var_dump($_FILES['fname']['tmp_name']);
$tempfile = $_FILES['fname']['tmp_name'];
$filename = '/home/testaki/animal_photo/'. $_FILES['fname']['name'];

if (is_uploaded_file($tempfile)) {
    if ( move_uploaded_file($tempfile , $filename )) {
	echo $filename . "をアップロードしました。";
    } else {
        echo "ファイルをアップロードできません。";
    }
} else {
    echo "ファイルが選択されていません。";
} 

//move_uploaded_file($_FILES['fname']['tmp_name'] , "/home/testaki/animal_photo/test.png" );
exit;
}
?>
<?php
 echo__dir__;
?>
<form  method="post" enctype="multipart/form-data">
  <input type="file" name="fname">
  <input type="submit" value="アップロード">
</form>