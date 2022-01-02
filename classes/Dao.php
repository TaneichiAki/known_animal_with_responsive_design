<?php
require_once __DIR__."/DbInfo.php";

class Dao {
	private static $dao;
	private static $pdo;

	/*
	 * pdo生成
	 */	
	private function __construct()
	{
		$dsn = sprintf(
			'%s:host=%s:%s;dbname=%s;charset=utf8mb4',
			DbInfo::DB_TYPE,
			DbInfo::DB_HOST,
			DbInfo::DB_PORT,
			DbInfo::DB_NAME
		);
		try
		{
			self::$pdo = new PDO($dsn, DbInfo::DB_USER, DbInfo::DB_PW);
		}
		catch(PDOException $e)
		{
			die('DataBase Error:' .$e->getMessage());
		}
	}
	/*
	 * シングルトンでインスタンス生成
	 */
	public static function db(): Dao
	{
		try
		{
			if (!isset(self::$dao))
			{
				self::$dao = new Dao();    
			}
			return self::$dao;
		}
		catch(PDOException $e)
		{
			die('DataBase Error:' .$e->getMessage());
		}
	}
	
	/*
	 * pdo.prepareを実行
	 */
	private function prepare(string $sql)
	{
		try
		{
			return self::$pdo->prepare($sql);
		}
		catch(PDOException $e)
		{
			die('DataBase Error:' .$e->getMessage());
		}
	}
	
	/*
	 * pdo.bindParamsを実行
	 */
	private function bind($stmt,?array $params)
	{
		if(isset($params))
		{
			for($i = 0; $i < count($params); $i++)
			{
				$stmt->bindParam($i+1,$params[$i]);
			}
		}
		return $stmt;
	}

	/*
	 * レコード件数取得メソッド
	 */
	public function count_row(string $sql,?array $params = null): int
	{
		try
		{
			$stmt = $this->prepare($sql);
			$stmt = $this->bind($stmt,$params);
			$stmt->execute();
			return $stmt->rowCount();
		}
		catch(PDOException $e)
		{
			die('DataBase Error:' .$e->getMessage());
		}
	}

	/*
	 * selectで1件引きの為のメソッド
	 * 2件以上取得できるときも1件になる
	 */
	public function show_one_row(string $sql,?array $params = null)
	{
		try
		{
			$stmt = $this->prepare($sql);
			$stmt = $this->bind($stmt,$params);
			$stmt->execute();
			if($stmt->rowCount() < 1)
			{
				return array("result" => false);
			}
			$result =  $stmt->fetchAll(PDO::FETCH_ASSOC);
			return array(
				"result" => true,
				"data" => $result[0]
			);
		}
		catch(PDOException $e)
		{
			die('DataBase Error:' .$e->getMessage());
		}
	}
	
	/*
	 * selectで複数件取得メソッド
	 */
	public function show_any_rows(string $sql,?array $params = null): array
	{
		try
		{
			$stmt = $this->prepare($sql);
			$stmt = $this->bind($stmt,$params);
			$stmt->execute();
			if($stmt->rowCount() < 1)
			{
				return array("result" => false);
			}
			$result =  $stmt->fetchAll(PDO::FETCH_ASSOC);
			return array(
				"result" => true,
				"data" => $result
			);
		}
		catch (PDOException $e)
		{
			die('DataBase Error:' .$e->getMessage());
		}
	}

	/*
	 * lastInsertIdをする為のインサート用メソッド
	 * Insert時にlastInsertIdを戻り値で返す
	 */
	public function add_one_row(string $sql,?array $params = null): array
	{
		try
		{
			$stmt = $this->prepare($sql);
			$stmt = $this->bind($stmt,$params);
			$bool = $stmt->execute();
			$result = array(
				"result" => $bool,
				"id" => null
			);
			if($result)
			{
				$result["id"] = self::$pdo->lastInsertId();
			}
			return $result;
		}
		catch (PDOException $e)
		{
			die('DataBase Error:' .$e->getMessage());
		}
	}
	
	/*
	 * Insert,Update,Deleteで使える
	 */
	public function mod_exec(string $sql,?array $params = null): array
	{
		try
		{
			$stmt = $this->prepare($sql);
			$stmt = $this->bind($stmt,$params);
			$bool = $stmt->execute();
			return array(
				"result" => $bool,
				"mod_row" => $stmt->rowCount()
			);
		}
		catch (PDOException $e)
		{
			die('DataBase Error:' .$e->getMessage());
		}
	}
}
