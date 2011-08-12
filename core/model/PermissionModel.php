<?php

require_once ("CleanGabModel.php");

class PermissionModel extends CleanGabModel {
	
	public function __construct() 
	{
		parent::__construct();
		$this->listableFields = array("id", "permission", "user_id");
		$this->masks = array();
		$this->hintFields = array("id"=>"Id", "permission"=>"Permission", "user_id"=>"User Id");
	}
	
	public function hasPermission($permissionKey, $idUser=null)
	{
		if ($idUser == null) 
		{
			try 
			{
				$idUser = $_SESSION["CLEANGAB"]["user"]["id"];
			} catch (Exception $ex) 
			{
				CleanGab::debug("Undefined User in the Session");
				return false;
			}
		}
		$entity = new Entity("permission");
		$entity->init();
		$entity->setCountThis(false);
		$entity->setSql("SELECT [fields] [table] [args] [limit]");
		$entity->setLimit(1);
		$entity->addArgument("user_id", $idUser, "=");
		$entity->addArgument("permission", $permissionKey);
		$recordSet = $entity->retrieve($entity->getSql());
		return $recordSet->hasRecords();
	}
	
}
?>