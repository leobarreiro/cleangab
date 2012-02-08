<?php
/**
 * CleanGab Framework
 * CleanGabModel.php
 * Date: 	2011-01-11
 * Author: 	Leopoldo Barreiro
 */

include_once ("Entity.php");
include_once ("Recordset.php");

class CleanGabModel {
	
	// Entity
	protected $entity;

	// lista de todos os campos
	// formato array: nome_campo1, nome_campo2...
	protected $fields;
	
	// Descricoes dos campos que irao em cabecalhos de table list, por exemplo
	// formato array: nome_campo=>descricao
	protected $hintFields;
	
	// campos que nao devem ser exibidos em uma tablelist
	// formato array: nomecampo1, nomecampo2, nomecampo3...
	protected $noListableFields;
	
	/**
	 * Campos que devem ser considerados em um filtro de pesquisa
	 * 
	 */
	protected $searchableFields;
	
	
	// mascaras que devem ser aplicadas por campo
	// formato array: nome_campo=>nome_classe_formatter
	protected $masks;
	
	// dados obtidos do banco de dados
	// formato: object recordset
	protected $recordset;
	
	// argumentos usados para pesquisar e/ou manipular registros no banco de dados
	// formato array: nome_campo=>valor_do_input
	public $argumentData;
	
	// Table name referenced, according to database
	protected $referencedTableName;
	
	public function __construct() 
	{
		$this->fields 			= array();
		$this->hintFields 		= array();
		$this->noListableFields = array();
		$this->masks 			= array();
		$this->entity 			= null;
		$this->recordset 		= null;
		$this->argumentData 	= array();
	}

	public function getHintFields() 
	{
		return $this->hintFields;
	}
	
	public function getFields() 
	{
		return $this->fields;
	}
	
	public function setRecordset($recordset) 
	{
		$this->recordset = $recordset;
	}
	
	public function getRecordset() 
	{
		return $this->recordset;
	}
	
	public function setEntity($objEntity) 
	{
		if (get_class($objEntity) == "Entity") 
		{
			$this->entity = $objEntity;
		}
	}
	
	public function getEntity()
	{
		return $this->entity;
	}
	
	public function getMasks() 
	{
		return $this->masks;
	}
	
	public function getMaskByKey($key) {
		Validation::notEmpty($key, "getMaskById: the key can not be empty");
		if (isset($this->masks[$key])) {
			return $this->masks[$key];
		} else {
			return false;
		}
	}
	
	public function setNoListableFields($arNoListableFields)
	{
		$this->noListableFields = $arNoListableFields;
	}
	
	public function getNoListableFields()
	{
		return $this->noListableFields;
	}
	
	public function addArgumentData($key, $value) 
	{
		if (isset($key) && is_string($key) && isset($value)) 
		{
			$this->argumentData[$key] = $value;
			return true;
		}
		return false;
	}
	
	public function getArgumentData($key) 
	{
		if (isset($this->argumentData[$key])) 
		{
			return $this->argumentData[$key];
		}
		else 
		{
			return false;
		}
	}
	
	public function getReferencedTableName() 
	{
		return $this->referencedTableName;
	}
	
	public function setReferencedTableName($tbName) 
	{
		$this->referencedTableName = $tbName;
	}
	
	public function clearArgumentData()
	{
		$this->argumentData = array();
	}
	
	public function get($key)
	{
		$tableName = str_replace("model", "", strtolower(get_class($this)));
		$this->entity = new Entity($tableName);
		$this->entity->init();
		$this->entity->addArgument($this->entity->getPk(), $key, "=");
		$this->entity->setLimit(1);
		$this->recordset = $this->entity->retrieve();
		if ($this->recordset->hasRecords())
		{
			return $this->recordset->getRecord();
		}
		else 
		{
			return false;
		}
	}
	
	// TODO: override
	/** 
	 * Prepare arguments to retrieve records
	 */
	public function prepareList() {}
	
	// TODO: override
	public function prepareSave() {}
	
	// TODO: override
	public function save() {}
	
	public function createEmptyObject($table=null) 
	{
		$tableName = ($table != null) ? strtolower($table) : str_replace("model", "", strtolower(get_class($this)));
		$this->setReferencedTableName($tableName);
		$this->entity = new Entity($tableName);
		$fields = $this->entity->getFields();
		if (is_array($fields) && count($fields) > 0)
		{
			$obj = new stdClass();
			foreach (array_keys($fields) as $field)
			{
				$obj->{$field} = null;
			}
			return $obj;
		}
		else 
		{
			return false;
		}
	}
	
	public function getExpectedArguments($table=null) 
	{
		$tableName = ($table != null) ? strtolower($table) : str_replace("model", "", strtolower(get_class($this)));
		$this->setReferencedTableName($tableName);
		$emptyObject = $this->createEmptyObject($this->referencedTableName);
		$arObject = (array) $emptyObject;
		return array_keys($arObject);
	}
	
	public function configure($table=null) 
	{
		$tableName 	  = ($table != null) ? strtolower($table) : str_replace("model", "", strtolower(get_class($this)));
		$this->setReferencedTableName($tableName);
		$this->entity = new Entity($tableName);
		$this->fields = array_keys($this->entity->getFields());
		$hintFields   = array();
		foreach ($this->fields as $field)
		{
			$propertyKey = "field." . $field;
			$hintFields[$field] = Properties::get($propertyKey);
		}
		$this->hintFields = $hintFields;
	}
	
	public function setBlockOfArguments($arArgumentsBlock) {
		foreach ($arArgumentsBlock as $key=>$value) {
			$this->addArgumentData($key, $value);
		}
	}

	public function createObjectToPersist($table=null) 
	{
		$tableName = ($table != null) ? strtolower($table) : str_replace("model", "", strtolower(get_class($this)));
		$this->setReferencedTableName($tableName);
		$objectToPersist = $this->createEmptyObject($tableName);
		$arFields = (array) $objectToPersist;
		$arKeys = array_keys($arFields);
		foreach ($arKeys as $key) {
			if ($this->getArgumentData($key)) {
				$objectToPersist->{$key} = $this->getArgumentData($key);
			}
		}
		return $objectToPersist;
	}

}
?>