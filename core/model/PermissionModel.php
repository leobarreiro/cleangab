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
		$entity->setLimit(1);
		$entity->addArgument("user_id", $idUser, "=");
		$entity->addArgument("permission", $permissionKey);
		$recordSet = $entity->retrieve($entity->getSql());
		return $recordSet->hasRecords();
	}
	
	public function loadPermissions($idUser=null)
	{
		$userToLoad = ($idUser == null) ? $_SESSION["CLEANGAB"]["user"]["id"] : $idUser;
		$entity = new Entity("permission");
		$entity->init();
		$entity->addArgument("user_id", $userToLoad, "=");
		$entity->setLimit(0);
		return $entity->retrieve();
	}
	
	public function grantToUser($userId, $permissions)
	{
		$entity = new Entity("permission");
		$entity->addArgument("user_id", $userId, "=");
		$entity->execute(CLEANGAB_SQL_DELETE_ALL);
		$toGrant = new stdClass();
		$recordSaved = 0;
		foreach ($permissions as $prm)
		{
			$toGrant->id = null;
			$toGrant->permission = $prm;
			$toGrant->user_id = $userId;
			$entity->getTableInfo();
			$entity->setObjectToPersist($toGrant);
			if ($entity->save())
			{
				++$recordSaved;
			}
		}
		return ($recordSaved > 0);
	}
	
}
?>