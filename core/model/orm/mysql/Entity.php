<?php
/**
 * CleanGab Framework
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
	private $sql;
	private $countThis;
	private $sqlCount;
	private $pk;
	private $fk;
	private $uk;
	public 	$connection;
	public  $args;
	private $stringTypes;
	private $numericTypes;
	private $dateTypes;
	public  $objectToPersist;
	
	public function __construct($tableName, $dataBase=CLEANGAB_DB_DB)
	{
		$this->connection 		= null;
		$this->stringTypes  	= unserialize(CLEANGAB_SQL_STRING_TYPES);
		$this->numericTypes 	= unserialize(CLEANGAB_SQL_NUMERIC_TYPES);
		$this->dateTypes 		= unserialize(CLEANGAB_SQL_DATE_TYPES);
		$this->setDataBase($dataBase);
		$this->setTableName($tableName);
		$this->fields 			= array();
		$this->pk 				= null;
		$this->fk 				= array();
		$this->uk 				= array();
		$this->total 			= 0;
		$this->args 			= array();
		$this->offset 			= 0;
		$this->limit  			= CLEANGAB_DEFAULT_SQL_LIMIT;
		$this->sql 				= null;
		$this->countThis 		= true;
		$this->sqlCount 		= null;
		$this->objectToPersist 	= null;
	}
	
	private function connectIfNull() 
	{
		if ($this->connection == null)
		{
			$this->connection = new Connection();
		}
	}
	
	public function setObjectToPersist($mixed)
	{
		$this->objectToPersist = $this->createObjectToPersist($mixed);
	}
	
	public function createObjectToPersist($mixed=null)
	{
		$dateTypes = unserialize(CLEANGAB_SQL_DATE_TYPES);
		$mixed = (array) $mixed;
		foreach (array_keys($this->fields) as $field)
		{
			if (!isset($mixed[$field]))
			{
				if (in_array(strtoupper($this->fields[$field]['type']), $this->dateTypes) 
					 && strtoupper($this->fields[$field]['null']) == "NO")
				{
					$mixed[$field] = date("Y-m-d H:i:s");
				}
				else 
				{
					$mixed[$field] = null;
				}
			}
		}
		return (object) $mixed;
	}
	
	public function setConnection($conn) 
	{
		if (is_object($conn) && get_class($conn) == "Connection") {
			$this->connection = $conn;
		}
	}
	
	public function addArgument($key, $search, $operation='LIKE') 
	{
		$this->args[] = array('key'=>$key, 'search'=>$search, 'operation'=>$operation);
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
					$this->pk = $record->Field;
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
			CleanGab::debug($e);
		}
		$this->fields  = $fields;
		$this->fk      = $foreignKeys;
		$this->uk      = $uniqueKeys;
		$this->orderBy = $this->pk;
	}
	
	public function setSql($sql)
	{
		$this->sql = $sql;
	}
	
	public function setSqlCount($sqlCount)
	{
		$this->sqlCount = $sqlCount;
	}
	
	public function getSql()
	{
		return $this->sql;
	}
	
	public function getSqlCount()
	{
		return $this->sqlCount;
	}
	
	public function setPk($arg)
	{
		$this->pk = $arg;
		return true;
	}
	
	public function getPk()
	{
		return $this->pk;
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
	
	public function execute($sql)
	{
		if ($this->isUpdate($sql) || $this->isInsert($sql) || $this->isDelete($sql))
		{
			$this->connectIfNull();
			if ($this->connection->resource->query($this->prepare($sql)))
			{
				return $this->connection->resource->affected_rows;
			}
			else
			{
				return false;
			}
		}
		else 
		{
			return false;
		}
	}
	
	public function save()
	{
		$update = false;
		if ($this->objectToPersist->{$this->pk} != 0)
		{
			$sql = CLEANGAB_SQL_UPDATE;
			$update = true;
		}
		else 
		{
			$sql = CLEANGAB_SQL_INSERT;
		}
		$this->connectIfNull();
		$this->connection->resource->query($this->prepare($sql));
		if ($this->connection->resource->affected_rows > 0)
		{
			if ($update)
			{
				return $this->connection->resource->affected_rows;
			}
			else 
			{
				return $this->connection->resource->insert_id;
			}
		}
		else 
		{
			return false;
		}
	}
	
	private function isUpdate($sql)
	{
		return (strpos(strtoupper($sql), "UPDATE") !== false);
	}
	
	private function isInsert($sql)
	{
		return (strpos(strtoupper($sql), "INSERT") !== false);
	}
	
	private function isDelete($sql)
	{
		return (strpos(strtoupper($sql), "DELETE") !== false);
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
	
	public function getTableName() 
	{
		return $this->tableName;
	}
	
	public function retrieve($sql=CLEANGAB_SQL_RETRIEVE_ALL) 
	{
		$this->connectIfNull();
		$qr = $this->connection->resource->query($this->prepare($sql));
		$recordset = new Recordset($qr);
		if ($this->countThis)
		{
			$this->countRecords();
		}
		return $recordset;
	}
	
	private function countRecords()
	{
		$sql = ($this->getSqlCount() == null) ? CLEANGAB_SQL_COUNT_ALL : $this->getSqlCount();
		$qr = $this->connection->resource->query($this->prepare($sql));
		$rset = new Recordset($qr);
		$record = $rset->get();
		$this->total = $record->total;
	}
	
	private function prepare($sql) 
	{
		$this->setSql($sql);
		$old = array("[database]", "[table]");
		$new = array($this->dataBase, $this->tableName);
		
		$old[] = "[pk]";
		$new[] = $this->pk;
		
		if ($this->fields != null) 
		{
			$old[] = "[listable_fields]";
			$new[] = implode(", ", array_keys($this->fields));
			if (is_object($this->objectToPersist))
			{
				if ($this->objectToPersist->{$this->pk} != 0)
				{
					$statement = array();
					$update = array();
					foreach (array_keys($this->fields) as $field)
					{
						if ($this->objectToPersist->{$field})
						{
							$update[$field] = ($this->objectToPersist->{$field} == null) ? " NULL " : " '" . $this->objectToPersist->{$field} . "' ";
						}
					}
					foreach ($update as $field=>$value)
					{
						$statement[] = " " . $field . " = " . $value;
					}
					
					$old[] = "[update_values]";
					if (count($statement) > 0)
					{
						$new[] = " " . implode(", ", $statement) . " ";
					}
					else 
					{
						$new[] = "";
					}
					
					$old[] = "[update_conditions]";
					$new[] = " " . $this->pk . " = " . $this->objectToPersist->{$this->pk};
				}
				else 
				{
					$old[] = "[insert_fields]";
					$new[] = implode(", ", array_keys($this->fields));
					$insert_values = "";
					$insert = array();
					foreach (array_keys($this->fields) as $field)
					{
						$insert[] = ($this->objectToPersist->{$field} == null) ? " NULL " : " '" . $this->objectToPersist->{$field} . "' ";
					}
					$insert_values .= implode(", ", $insert);
					$old[] = "[insert_values]";
					$new[] = $insert_values;
				}
			}
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
		$sql = str_replace(array("\n", "\r", "\t"), array("", "", ""), $sql);
		return $sql;
	}
	
	private function parseArgumentToSql($arg) 
	{
		$sqlPart  = $arg['key'];
		if (strtoupper($arg['operation']) == "LIKE") 
		{
			$sqlPart .= " LIKE '%" . $arg['search'] . "%' ";
		} 
		else if (strtoupper($arg['operation']) == "MD5")
		{
			$sqlPart .= " = MD5('" . $arg['search'] . "')";
		}
		else 
		{
			if ($arg['search'] == null)
			{
				$sqlPart .= " " . $arg['operation'] . " NULL ";
			}
			else if ((isset($this->fields[$arg['key']])) && (in_array($this->fields[$arg['key']]['type'], $this->numericTypes))) 
			{
				$sqlPart .= " " . strtolower($arg['operation']) . " " . $arg['search'];
			}
			else 
			{
				$sqlPart .= " " . strtolower($arg['operation']) . " '" . $arg['search'] . "' ";
			}
		} 
		return $sqlPart;
	}
	
	public function setCountThis($boolean)
	{
		$this->countThis = $boolean;
	}
	
}
?>