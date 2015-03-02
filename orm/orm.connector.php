<?php

class DBConnector
{
	private static $_instancia;
	private function __construct()
	{
		
	}
	public static function Inst()
	{
		if (  !self::$_instancia instanceof self)
			self::$_instancia = new self;
		return self::$_instancia;
	}

	private $db = null;

	//	DBConnector::Inst()->setConn();
	public function setConn($host, $name, $user, $pass, $type = "mysql" )
	{
		$this->db = new DB($host, $name, $user, $pass, $type);
	}

	//	DBConnector::Inst()->exect();
	public function exect($query, $className = null, $returnValue = true)
	{
		$result = new DBResult();
		try
		{
			if(isset($query))
			{
				$tmpResult = $this->db->Exec($query, $className, $returnValue);
		    	$result = $tmpResult;
			}
		}catch (PDOException $e){
		    $result->error( $e->getMessage() );
		}

		return $result;
	}

	//	DBConnector::Inst()->beginTransaction();
	public function beginTransaction()
	{
		$this->db->BeginTransaction();
	}
	//	DBConnector::Inst()->commit();
	public function commit()
	{
		$this->db->Commit();
	}
	//	DBConnector::Inst()->rollback();
	public function rollback()
	{
		$this->db->Rollback();
	}
	//	DBConnector::Inst()->lastId();
	public function lastId()
	{
		return $this->db->lastId();
	}
}

?>