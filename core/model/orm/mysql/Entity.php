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
	private $total;
	private $sqlBeforeParser;
	private $sqlAfterParser;
	private $pk;
	private $fk;
	private $uk;
	public 	$connection;
	public  $args;
	private $stringTypes;
	private $numericTypes;
	
	public function __construct($tableName, $dataBase=CLEANGAB_DB_DB)
	{
		$this->connection = null;
		$this->stringTypes  = unserialize(CLEANGAB_SQL_STRING_TYPES);
		$this->numericTypes = unserialize(CLEANGAB_SQL_NUMERIC_TYPES);
		$this->setDataBase($dataBase);
		$this->setTableName($tableName);
		$this->fields 	= array();
		$this->pk 		= array();
		$this->fk 		= array();
		$this->uk 		= array();
		$this->total 	= 0;
		$this->args 	= array();
		$this->offset 	= 0;
		$this->limit  	= CLEANGAB_DEFAULT_SQL_LIMIT;
		$this->sqlBeforeParser = null;
		$this->sqlAfterParser = null;
	}
	
	private function connectIfNull() 
	{
		if ($this->connection == null)
		{
			$this->connection = new Connection();
		}
	}
	
	public function setConnection($conn) 
	{
		if (is_object($conn) && get_class($conn) == "Connection") {
			$this->connection = $conn;
		}
	}
	
	public function addArgument($key, $search, $operation='LIKE') 
	{
		if (array_key_exists($key, $this->fields)) 
		{
			$this->args[] = array('key'=>$key, 'search'=>$search, 'operation'=>$operation);
		}
	}
	
	public function setOrderBy($strOrder) 
	{
		$this->orderBy = $strOrder;
	}

	public function setOffset($intOffset) 
	{
		$this->offset = $intOffset;
	}
	
	public function setLimit($intLimit) 
	{
		$this->limit = $intLimit;
	}
	
	public function getConnection() 
	{
		return $this->connection;
	}
	
	public function getOffset() 
	{
		return $this->offset;
	}
	
	public function getLimit() 
	{
		return $this->limit;
	}
	
	public function getTotal() 
	{
		return $this->total;
	}
	
	public function init()
	{
		$fields 	 = array();
		$primaryKeys = array();
		$foreignKeys = array();
		$uniqueKeys  = array();
		$this->connectIfNull();
		try 
		{
			$qr = $this->connection->resource->query($this->prepare(CLEANGAB_SQL_RETRIEVE_TABLE_FIELDS));
			if (!$qr) 
			{
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
				if ($record->Key == 'PRI') 
				{
					$primaryKeys[] = $record->Field;
				}
				if ($record->Key == 'MUL') 
				{
					$foreignKeys[] = $record->Field;
				}
				if ($record->Key == 'UNI') 
				{
					$uniqueKeys[] = $record->Field;
				}
			}
			$qr->close();
		} 
		catch (Exception $e) 
		{
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
		$this->connectIfNull();
		$this->sqlBeforeParser = $sql;
		$qr = $this->connection->resource->query($this->prepare($this->sqlBeforeParser));
		$recordset = new Recordset($qr);
		$this->countRecords();
		return $recordset;
	}
	
	private function countRecords() {
		$qr = $this->connection->resource->query($this->prepare(CLEANGAB_SQL_COUNT_ALL));
		$rset = new Recordset($qr);
		$record = $rset->get();
		$this->total = $record['total'];
	}
	
	private function prepare($sql) 
	{
		$old = array("[database]", "[table]");
		$new = array($this->dataBase, $this->tableName);
		
		$old[] = "[pk]";
		$new[] = implode (", ", $this->pk);
		
		if ($this->fields != null) 
		{
			$old[] = "[listable_fields]";
			$new[] = implode(", ", array_keys($this->fields));
		}
		
		$sqlArguments = array();
		foreach ($this->args as $arg) 
		{
			$sqlArguments[] = $this->parseArgumentToSql($arg);
		}

		$old[] = "[args]";
		$new[] = (count($sqlArguments) >0) ? " WHERE " . implode (" AND ", $sqlArguments) : "";
		
		$old[] = "[order]";
		$new[] = " ORDER BY " . $this->orderBy;
		
		$old[] = "[limit]";
		$new[] = "LIMIT " . $this->offset . ", " . $this->limit;
		
		$sql = str_replace($old, $new, $sql);
		$this->sqlAfterParser = $sql;
		return $sql;
	}
	
	private function parseArgumentToSql($arArgument) 
	{
		$sqlPart  = $arArgument['key'];
		$sqlPart .= " " . $arArgument['operation'] . " ";
		if (in_array($this->fields[$arArgument['key']]['type'], $this->numericTypes)) 
		{
			$sqlPart .= $arArgument['search'];
		} 
		else 
		{
			if ($arArgument['operation'] == "LIKE") 
			{
				$sqlPart .= "'%" . $arArgument['search'] . "%'";
			} 
			else 
			{
				$sqlPart .= "'" . $arArgument['search'] . "'";
			}
		}
		return $sqlPart;
	}
	
}
?>