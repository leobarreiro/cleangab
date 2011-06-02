<?php
/**
 * Clean-Gab Framework
 * Entity.php
 * Date: 	2011-01-21
 * Author: 	Leopoldo Barreiro
 */

include_once ("IDBEntity.php");
include_once ("sql.env.php");
include_once ("Connection.php");

class Entity implements IDBEntity {
	
	private $tableName;
	private $dataBase;
	private $fields;
	private $orderBy;
	private $offset;
	private $limit;
	private $pk;
	private $fk;
	private $uk;
	public 	$connection;
	public  $args;
	private $defaultArgument;
	private $stringTypes;
	private $numericTypes;
	
	public function __construct($tableName, $dataBase=CLEANGAB_DB_DB, $connection=null)
	{
		if ($connection != null) {
			$this->setConnection($connection);
		} else {
			$this->connection = new Connection();
		}
		$this->defaultArgument = "TRUE";
		$this->stringTypes  = unserialize(CLEANGAB_SQL_STRING_TYPES);
		$this->numericTypes = unserialize(CLEANGAB_SQL_NUMERIC_TYPES);
		
		$this->setDataBase($dataBase);
		$this->setTableName($tableName);
		$this->args   = array();
		$this->offset = 0;
		$this->limit  = CLEANGAB_DEFAULT_SQL_LIMIT;
	}
	
	public function setConnection($conn) {
		if (is_object($conn) && get_class($conn) == "Connection") {
			$this->connection = $conn;
		}
	}
	
	public function addArgument($key, $search, $operation=null) {
		if (in_array($key, $this->fields)) {
			$this->args[] = array('key'=>$key, 'search'=>$search, 'operation'=>$operation);
		}
	}

	public function setOffset($intOffset) {
		$this->offset = $intOffset;
	}
	
	public function setLimit($intLimit) {
		$this->limit = $intLimit;
	}
	
	public function getConnection() {
		return $this->connection;
	}
	
	public function init()
	{
		$fields 	 = array();
		$primaryKeys = array();
		$foreignKeys = array();
		$uniqueKeys  = array();
		try {
			$qr = $this->connection->resource->query($this->prepare(CLEANGAB_SQL_RETRIEVE_TABLE_FIELDS));
			if (!$qr) {
				throw new Exception("Error when retrieving table fields", "3");
			}
			while ($record = $qr->fetch_object())
			{
				if (strpos($record->Type, "("))
				{
					$type = substr($record->Type, 0, strpos($record->Type, "("));
					$size = substr($record->Type, strpos($record->Type, "("));
					$size = preg_replace(array("/[a-z]/", "/\(/", "/\)/"), array("", "", ""), $record->Type);
				}
				else
				{
					$type = $record->Type;
					$size = '';
				}
				$fields[$record->Field] = array('type'=>$type, 
												'size'=>$size, 
												'null'=>$record->Null, 
												'key'=>$record->Key, 
												'extra'=>$record->Extra, 
												'default'=>$record->Default, 
												'value'=>'');
				if ($record->Key == 'PRI') {
					$primaryKeys[] = $record->Field;
				}
				if ($record->Key == 'MUL') {
					$foreignKeys[] = $record->Field;
				}
				if ($record->Key == 'UNI') {
					$uniqueKeys[] = $record->Field;
				}
			}
			$qr->close();
		} catch (Exception $e) {
			CleanGab::stackTraceDebug($e);
		}
		$this->fields  = $fields;
		$this->pk      = $primaryKeys;
		$this->fk      = $foreignKeys;
		$this->uk      = $uniqueKeys;
		$this->orderBy = implode(", ", $this->pk);
	}
	
	public function setPk($args)
	{
		if (is_array($args))
		{
			$this->pk = $args;
		}
		else
		{
			$this->pk = array($args);
		}
		return true;
	}
	
	public function setFk($args)
	{
		if (is_array($args))
		{
			$this->fk = $args;
		}
		else
		{
			$this->fk = array($args);
		}
		return true;
	}
	
	public function getFields()
	{
		return $this->fields;
	}
	
	public function save()
	{
		if ((isset($this->fields[$this->pk])) && ($this->fields[$this->pk]['value'] != ''))
		{
			$sql = $this->prepare(CLEANGAB_SQL_INSERT);
		}
		else
		{
			$sql = $this->prepare(CLEANGAB_SQL_UPDATE);
		}
		mysql_query($sql);
		return mysql_affected_rows();
	}
	
	public function setValue($fieldName, $value)
	{
		if (isset($this->fields[$fieldName]))
		{
			$this->fields[$fieldName]['value'] = $value;
			return true;			
		}
		else
		{
			return false;
		}
	}
	
	public function getField($nameField)
	{
		if (isset($this->fields[$nameField]))
		{
			return $this->fields[$nameField];
		}
		else
		{
			return false;
		}
	}
	
	public function setDataBase($dataBase)
	{
		$this->dataBase = $dataBase;
	}
	
	public function setTableName($tableName)
	{
		$this->tableName = $tableName;
	}
	
	public function getTableName() {
		return $this->tableName;
	}
	
	public function retrieve($sql=CLEANGAB_SQL_RETRIEVE_ALL) {
		$qr = $this->connection->resource->query($this->prepare($sql));
		$this->recordset = new Recordset($qr);
		return $this->recordset;
	}
	
	private function prepare($sql) {
		$old = array("[database]", "[table]");
		$new = array($this->dataBase, $this->tableName);
		if ($this->fields != null) {
			$old[] = "[listable_fields]";
			$new[] = implode(", ", array_keys($this->fields));
		}
		
		$sqlArguments = array();
		foreach ($this->args as $arg) {
			$sqlArguments[] = $this->parseArgumentToSql($arg);
		}

		$old[] = "[args]";
		$new[] = (count($sqlArguments) > 0) ? implode (" AND ", $sqlArguments) : $this->defaultArgument;
		
		$old[] = "[order]";
		$new[] = $this->orderBy;
		
		$old[] = "[offset]";
		$new[] = $this->offset;
		
		$old[] = "[limit]";
		$new[] = $this->limit;
				
		$sql = str_replace($old, $new, $sql);
		return $sql;
	}
	
	private function parseArgumentToSql($arArgument) {
		$sqlPart  = $arArgument['key'];
		$sqlPart .= " ";
		$sqlPart .= $arArgument['operation'];
		if (in_array($this->fields[$arArgument['key']]['type'], $this->numericTypes)) {
			$sqlPart .= $arArgument['search'];
		} else {
			$sqlPart .= "'" . $arArgument['search'] . "'";
		}
		return $sqlPart;
	}
	
}
?>