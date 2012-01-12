<?php

require_once ("CleanGabModel.php");

class ParameterModel extends CleanGabModel {
	
	public function __construct() 
	{
		parent::__construct();
		$this->configure("parameter");
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