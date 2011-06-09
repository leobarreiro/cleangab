<?php

interface IDBEntity {

	public function __construct($tableName, $dataBase=CLEANGAB_DB_DB, $connection=null);

	/**
	 * @return void
	*/
	public function setTableName($tableName);

	/**
	 * @return String
	*/
	public function getTableName();
	
	/**
	 * @return void
	*/	
	public function setDataBase($dataBase);

	/**
	 * @return Array Structure Information of Entity
	*/
	public function init();

	/**
	 * @return Boolean
	*/
	public function setConnection($connection);

	/**
	 * @return Boolean
	*/
	public function setPk($args);
	
	/**
	 * @return Boolean
	*/
	public function setFk($args);
	
	/**
	 * @return Data Resource
	*/
	public function retrieve($sql=CLEANGAB_SQL_RETRIEVE_ALL);
	
	
	/**
	 * @return Integer affected rows
	*/
	public function save();
	
	/**
	 * @return Boolean
	*/
	public function setValue($fieldName, $value);

	/**
	 * @return Array all the fields and related information
	*/
	public function getFields();
	
	/**
	 * @return Array Field array data
	*/
	public function getField($fieldName);
	
}
?>