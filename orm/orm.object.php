<?php

define("STRING", "_String_");
define("INT", "_Integer_");
define("DATE", "_Date_");
define("DATETIME", "_DateTime_");
define("BOOLEAN", "_Bool_");
define("GUID", "_GUID_");		//	VARCHAR()
define("TEXT", "_Text_");
define("KEY", "_Key_");

/*
define("DEFAULT_STRING", "");
define("DEFAULT_INT", -1);
define("DEFAULT_DATE", '1900-01-01');
define("DEFAULT_BOOLEAN", false);
*/

abstract class DBObject
{
	protected $_tableName = "";

	/**
	------------------------------------------------------------------	STATIC
	*/
	private static $_props = [];
	private static function setProps()
	{
		$tableName = self::getClassName();

		if(!array_key_exists($tableName, self::$_props) ){

			$tmp = get_class_vars($tableName . "Mapp");
			if($tmp){

				self::$_props[$tableName] = $tmp;
			}

			/*
			//		With Reflection

			$reflect = new ReflectionClass($tableName . "Mapp");
			$tmp = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
			foreach ($tmp as $key => $value) {
				//if()	
			}

			echo("<br>-------------	$tableName-----------<br>");
			print_r($tmp);
			echo("<br>------------------------<br>");

			self::$_props[$tableName]
			*/
		}
	}


	private static function getProps()
	{
		$tableName = self::getClassName();
		if( array_key_exists($tableName, self::$_props) ){
			return self::$_props[$tableName];
		}
		return [];
	}

	/**
		Get class name, not this one
		* Example:		self::getClassName();
	*/
	private static function getClassName()
	{
		return strtolower(get_called_class());
	}

	private static function getTableName()
	{
		$tableName = self::getClassName();
		/*
		$tmpClass = new class($tableName)();
		$myprops = get_class_vars($tmpClass);
		*/

		foreach (get_class_vars($tableName) as $key => $value) {
			if($key === '_tableName'){
				return $value;
			}
		}
		return null;
	}

	/**
		We need to validate the Where and Order key, to know if there are valids
	*/
	public static function get($where=[], $order=[],$limit=0,$skip=0)
	{
		$className = self::getClassName();

		$table = self::getTableName();
		if(!empty($table)){
			$limitStr = $limit > 0 ? " LIMIT $limit" : "";
			$query = "SELECT * FROM `$table`";
			$whereParam = null;
			$orderParam = null;

			/*
			foreach ($where as $key => $value) {

				if($whereParam == null){
					$whereParam = " WHERE ";
				}else{
					$whereParam .= " AND ";
				}

				$whereParam .= "$key='" . htmlentities($value, ENT_QUOTES) . "' ";
			}
			*/
			$countWhere = 0;
			foreach ($where as $value) {

				if($whereParam == null){
					$whereParam = " WHERE $value";
				}else{
					//$whereParam .= "$key='" . htmlentities($value, ENT_QUOTES) . "' ";					

					$whereParam = str_replace("{".$countWhere."}",htmlentities($value, ENT_QUOTES),$whereParam);
					$countWhere++;
				}
			}


			foreach ($order as $key => $value) {
				if($orderParam == null){
					$orderParam = " ORDER BY ";
				}else{
					$orderParam .= ", ";
				}
				
				$orderParam .= "$key $value";
			}

			//echo("<br>Exec:<br>$query $whereParam $orderParam $limitStr<br><br>");
			$result = DBConnector::Inst()->exect($query . $whereParam . $orderParam . $limitStr, $className);
			return $result->data;
		}
	}

	public static function getOne($where=[], $order=[])
	{
		$result = self::get($where,$order,1);

		if($result){
			if(count($result) > 0){
				return $result[0];
			}
		}
		return null;
	}
	/**
	------------------------------------------------------------------
	*/



	/**
	------------------------------------------------------------------
	*/
	private $_sqlError = "";

	public function __construct()
	{
		self::setProps();
	}
	

	public function save()
	{
		$className = self::getClassName();
		if(is_a($this, $className))
		{
			$tableName = self::getTableName();
			if(!empty($tableName)){

				$query = "INSERT INTO `$tableName`";

				$columns = null;
				$values = null;

				$mapprops = self::getProps();
				$myprops = get_class_vars($className);

				if($mapprops && $myprops)
				{
					//	SET PROPERTIES
					$isKey = false;
					foreach ($mapprops as $key => $value) {
						$isKey = false;
						if(array_key_exists($key, $myprops))
						{
							if($key !== '_tableName')
							{
								if($this->$key != null)
								{
									if($columns == null){
										$columns = "";
									}else{
										$columns .= ", ";
									}
									$columns .= $key;

									if($values == null){
										$values = "";
									}else{
										$values .= ", ";
									}

									//	TODO: Check Type and then if string, add htmlentities
									$values .= "'" . $this->$key . "'";
									//	" . htmlentities($value, ENT_QUOTES) . "
								}
							}
						}
					}
					//	EXEC
					if(!empty($columns) && !empty($values)){
						$result = DBConnector::Inst()->exect($query . " (" . $columns . ") VALUES (" . $values . ")", $className, false);

						if($result->valid){
							return true;	
						}else{
							$this->_sqlError = $result->message;
							return false;
						}
					}
				}
				else
				{
					throw new Exception('Class or Map Class are not fine.');
				}
			}

		}
		return false;
	}
	

	public function update()
	{
		$className = self::getClassName();
		if(is_a($this, $className))
		{
			$tableName = self::getTableName();
			if(!empty($tableName)){

				$query = "UPDATE `$tableName`";

				$setParams = null;
				$whereParams = null;

				//	Get the Map properties and Class properties
				$mapprops = self::getProps();
				$myprops = get_class_vars($className);

				if($mapprops && $myprops)
				{
					//	SET PROPERTIES
					$isKey = false;
					foreach ($mapprops as $key => $value) {
						$isKey = false;
						if(array_key_exists($key, $myprops))
						{
							if($key !== '_tableName'){

								$searchKey = array_search(KEY, $mapprops[$key]);
								if( $searchKey !== false )
								{
									$isKey = true;
									if($whereParams == null){
										$whereParams = " WHERE ";
									}else{
										$whereParams .= " AND ";
									}
									if( empty($this->$key))
										throw new Exception('The key of the DB is required to update');

									$whereParams .= "$key='" . $this->$key . "'";
									//	" . htmlentities($value, ENT_QUOTES) . "

								}

								if(!$isKey)
								{
									$tmpVar = $this->$key;
									if( !empty($tmpVar)){
										if($setParams == null){
											$setParams = " SET ";
										}else{
											$setParams .= ", ";
										}
										$setParams .= "$key='" . $this->$key . "'";	
									}	
								}
							}
						}
					}


					//echo("<br><br>UPDATE: " . $query . $setParams . $whereParams . "<br><br>");
					//	EXEC
					if(!empty($setParams) && !empty($whereParams)){

						$result = DBConnector::Inst()->exect($query . $setParams . $whereParams, $className);

						if($result->valid){
							//return $result->data;	
							return true;
						}else{
							$this->_sqlError = $result->message;
							return false;
						}
						
					}
				}
			}

		}
		return false;
	}

	public function delete()
	{
		$className = self::getClassName();
		if(is_a($this, $className)){

			$tableName = self::getTableName();
			if(!empty($tableName)){

				$query = "DELETE FROM `$tableName`";

				$whereParams = null;

				//	Get the Map properties and Class properties
				$mapprops = self::getProps();
				$myprops = get_class_vars($className);


				if($mapprops && $myprops)
				{
					//	SET PROPERTIES
					$isKey = false;
					foreach ($mapprops as $key => $value) {
						$isKey = false;
						if($key !== '_tableName'){
							
							if(array_key_exists($key, $myprops))
							{
								$searchKey = array_search(KEY, $mapprops[$key]);
								if( $searchKey !== false )
								{
									if($whereParams == null){
										$whereParams = " WHERE ";
									}else{
										$whereParams .= " AND ";
									}
									$whereParams .= "$key='" . $this->$key . "'";
								}
							}
						}
					}

					//	EXEC
					if(!empty($whereParams)){
						$result = DBConnector::Inst()->exect($query . $whereParams, $className);
						if($result->valid){
							return $result->data;	
						}else{
							$this->_sqlError = $result->message;
							return false;
						}
					}
				}
			}
		}
	}
	public function getSqlError()
	{
		return $this->_sqlError;
	}
}

?>