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
	private $pk;
	private $fk;
	private $uk;
	public $connection;
	
	public function __construct($connection=null, $dataBase=CG_DB_NAME, $tableName)
	{
		if (!is_object($connection)) {
			$this->connection = new Connection();
		}
		$this->setConnection($connection);
		$this->setDataBase($dataBase);
		$this->setTableName($tableName);
		$this->init();
	}
	
	public function init()
	{
		if (is_resource($this->connection->resource))
		{
			$primaryKeys = array();
			$foreignKeys = array();
			$uniqueKeys  = array();
			
			$sql = "SHOW COLUMNS FROM `" . $this->dataBase . "`.`" . $this->tableName . "` ";
			$qr = mysql_query($sql, $this->connection->resource);
			
			while ($register = mysql_fetch_assoc($qr))
			{
				if (strpos($register['Type'], "("))
				{
					$type = substr($register['Type'], 0, strpos($register['Type'], "("));
					$size = substr($register['Type'], strpos($register['Type'], "("));
					$size = preg_replace(array("/[a-z]/", "/\(/", "/\)/"), array("", "", ""), $register['Type']);
				}
				else
				{
					$type = $register['Type'];
					$size = '';
				}
				
				$fields[$register['Field']] = array('type'=>$type, 
													'size'=>$size, 
													'null'=>$register['Null'], 
													'key'=>$register['Key'], 
													'extra'=>$register['Extra'], 
													'default'=>$register['Default'], 
													'value'=>'');
				if ($register['Key'] == 'PRI')
				{
					$primaryKeys[] = $register['Field'];
				}
				if ($register['Key'] == 'MUL')
				{
					$foreignKeys[] = $register['Field'];
				}
				if ($register['Key'] == 'UNI')
				{
					$uniqueKeys[] = $register['Field'];
				}
				
			}
			$this->fields = $fields;
			$this->pk     = $primaryKeys;
			$this->fk     = $foreignKeys;
			$this->uk     = $uniqueKeys;
		}
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
			$sql = "UPDATE `" . $this->dataBase . "`.`" . $this->tableName . "`  ";
		}
		else
		{
			$sql = "INSERT INTO `" . $this->dataBase . "`.`" . $this->tableName . "`  ";
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
	
	public function setConnection($connection)
	{
		$this->connection = $connection;
	}
	
	public function setTableName($tableName)
	{
		$this->tableName = $tableName;
	}
	
	public function getTableName() {
		return $this->tableName;
	}
	
	public function retrieve($sql) {
		$old = array("[database]", "[table]");
		$new = array($this->dataBase, $this->tableName);
		$sql = str_replace($old, $new, $sql);
		$result = mysql_query($sql, $this->connection->resource);
		$this->recordset = new Recordset($result);
		return $this->recordset;
	}
}
?>