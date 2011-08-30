<?php

require_once ("CleanGabModel.php");

class ParameterModel extends CleanGabModel {
	
	public function __construct() 
	{
		parent::__construct();
		$this->listableFields = array("id", "name", "value", "type");
		$this->masks = array();
		$this->hintFields = array("id"=>"Id", "name"=>"Name", "value"=>"Value", "type"=>"Type");
	}
	
	public function getParameter($parameterName)
	{
		$entity = new Entity("parameter");
		$entity->init();
		$entity->setCountThis(false);
		$entity->setLimit(1);
		$entity->addArgument("name", $parameterName, "=");
		$recordset = $entity->retrieve();
		if ($recordset->hasRecords())
		{
			return $recordset->getRecord();
		}
		else 
		{
			return false;
		}
	}
}
?>