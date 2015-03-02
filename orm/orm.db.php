<?php

class DB
{
	private $_dbHost = "localhost";
	private $_dbUser = "root";
	private $_dbPass = "";			
	private $_dbName = "";
	private $_dbType = "mysql";		//	mysql, sqlite. Use GetPDODrivers() to see availables Drivers


	private $_isTransaction = false;
	private $_pdo = null;

	public static function GetPDODrivers()
	{
		var_dump(PDO::getAvailableDrivers());
	}

	public function __construct($host, $name, $user, $pass, $type = "mysql" )
	{
		$this->_dbHost = $host;
		$this->_dbName = $name;
		$this->_dbUser = $user;
		$this->_dbPass = $pass;
		$this->_dbType = $type;
	}

	public function __destruct()
	{
		$this->_pdo = null;
	}

	public function Exec($query, $class = null, $returnValue = true)
	{
		$result = new DBResult();
		try
		{
			if($this->_pdo === null){
				$this->_pdo = $this->Connect();
			}

			$statement = $this->_pdo->prepare($query);
			$statement->execute();

			if($returnValue)
			{
				if($statement)
				{
					if($class != null){
						$result->data = $statement->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, $class);	
					}else{
						$result->data = $statement->fetchAll();
					}	
				}	
			}
			
			if(!$result->data){
				$result->data = [];
			}
			if(!$this->_isTransaction){
				$this->Destroy();
			}
		}
		catch (PDOException  $e)
		{
			$result->error( $e->getMessage() . " - query: " . $query );
		}
		return $result;
	}

	public function BeginTransaction()
	{
		if($this->_pdo === null){
			$this->_pdo = $this->Connect();
		}
		$this->_isTransaction = $this->_pdo->beginTransaction();
	}
	public function Commit()
	{
		if($this->_isTransaction){
			$this->_isTransaction = false;
			$this->_pdo->commit();
			$this->Destroy();
		}
	}
	public function Rollback()
	{
		if($this->_isTransaction){
			$this->_isTransaction = false;
			$this->_pdo->rollback();
			$this->Destroy();
		}
	}

	public function lastId()
	{
		if($this->_pdo !== null){
			return $this->_pdo->lastInsertId();
		}
		return null;
	}

	private function Connect()
	{
		$options = array(
		    PDO::ATTR_PERSISTENT => true, 
		    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		);
		$pdo = new PDO($this->_dbType .
						":host=" . $this->_dbHost .
						";dbname=" . $this->_dbName, 
						$this->_dbUser, $this->_dbPass, $options);

		$pdo->exec("set names utf8");
		return $pdo;
	}

	private function Destroy()
	{
		//$this->_pdo = null;
	}
}

/*
class DB
{
	private static $_dbHost = "localhost";
	private static $_dbUser = "root";
	private static $_dbPass = "";			
	private static $_dbName = "";
	private static $_dbType = "mysql";		//	mysql, sqlite. Use GetPDODrivers() to see availables Drivers


	private static $_isTransaction = false;
	private static $_pdo = null;

	public static function GetPDODrivers()
	{
		var_dump(PDO::getAvailableDrivers());
	}

	public static function SetConn($host, $name, $user, $pass, $type = "mysql" )
	{
		DB::$_dbHost = $host;
		DB::$_dbName = $name;
		DB::$_dbUser = $user;
		DB::$_dbPass = $pass;
		DB::$_dbType = $type;
	}

	public static function Exec($query, $class = null, $returnValue = true)
	{
		$result = new DBResult();
		try
		{
			if(self::$_pdo === null){
				self::$_pdo = DB::Connect();
			}

			$statement = self::$_pdo->prepare($query);
			$statement->execute();

			if($returnValue)
			{
				if($statement)
				{
					if($class != null){
						$result->data = $statement->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, $class);	
					}else{
						$result->data = $statement->fetchAll();
					}	
				}	
			}
			
			if(!$result->data){
				$result->data = [];
			}
			if(!self::$_isTransaction){
				DB::Destroy();
			}
		}
		catch (PDOException  $e)
		{
			$result->error( $e->getMessage() . " - query: " . $query );
		}
		return $result;
	}

	public static function BeginTransaction()
	{
		if(self::$_pdo === null){
			self::$_pdo = DB::Connect();
		}
		self::$_isTransaction = self::$_pdo->beginTransaction();
	}
	public static function Commit()
	{
		if(self::$_isTransaction){
			self::$_isTransaction = false;
			self::$_pdo->commit();
			DB::Destroy();
		}
	}
	public static function Rollback()
	{
		if(self::$_isTransaction){
			self::$_isTransaction = false;
			self::$_pdo->rollback();
			DB::Destroy();
		}
	}

	public static function lastId()
	{
		if(self::$_pdo !== null){
			return self::$_pdo->lastInsertId();
		}
		return null;
	}

	private static function Connect()
	{
		$options = array(
		    PDO::ATTR_PERSISTENT => true, 
		    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		);
		$pdo = new PDO(DB::$_dbType.":host=".DB::$_dbHost.";dbname=".DB::$_dbName, DB::$_dbUser, DB::$_dbPass, $options);
		$pdo->exec("set names utf8");
		return $pdo;
	}

	private static function Destroy()
	{
		self::$_pdo = null;
	}
}
*/
?>