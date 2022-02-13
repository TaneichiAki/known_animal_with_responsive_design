<?php
require_once(__DIR__."/../classes/Dao.php");
require_once(__DIR__."/../classes/constants.php");

abstract class KnownAnimalBase
{
  /**
  *グローバル変数定義
  */
	private $msg = "";
	private $select_no = "";
	private $select_animal = "";
	private $select_family = "";
	private $select_features = "";
	private $select_date = "";
  //初回処理
  abstract protected function prologue();
	//POST時処理
	abstract protected function post();
  //GET時処理
  abstract protected function get();
  //更新処理
  abstract protected function update();
  //終了前処理
  abstract protected function epilogue();
	/**
	*メイン処理
	*/
	public function main(){
		session_start();
		//セッションIDがセットされていなかったらログインページに戻る
		if(! isset($_SESSION['login'])){
			header("Location:".Constants::LOGIN_URL);
			exit();
		}
		try{
      $this->prologue();
			if($_SERVER["REQUEST_METHOD"] == "POST"){
				$GLOVALS['msg'] = $this->post();
			}else{
				$this->get();
			}
		//例外バグ検出時に下記を実行（外部のアプリと連携するときによく使う）
		}catch(PDOException $e){
			print('Error:'.$e->getMessage());
			die();
		}
	}
}
?>
