<?php
require_once ("CleanGabModel.php");

class UserModel extends CleanGabModel {

	public function __construct() 
	{
		parent::__construct();
		$this->listableFields = array("id", "user", "name", "email", "created");
		$this->masks = array("created"=>"DateTimeFormatter");
		$this->hintFields = array("id"=>"Id", "user"=>"User", "name"=>"Nome", "email"=>"e-mail", "created"=>"Data Cadastro");		
	}
	
	public function prepareList() 
	{
		$this->entity = new Entity("user");
		$this->entity->init();

		// Arguments
		
		if ($this->getArgumentData("user"))
		{
			$this->entity->addArgument("user", $this->getArgumentData("user"), "LIKE");
		}
		if ($this->getArgumentData("name")) 
		{
			$this->entity->addArgument("name", $this->getArgumentData("name"));
		}
		if ($this->getArgumentData("email")) 
		{
			$this->entity->addArgument("email", $this->getArgumentData("email"), "LIKE");
		}
		// pagination
		if ($this->getArgumentData("pg"))
		{
			$pg = $this->getArgumentData("pg") - 1;
			$this->entity->setOffset(($pg * $this->entity->getLimit()));
		}
		// order
		if ($this->getArgumentData("sort")) {
			$this->entity->setOrderBy($this->getArgumentData("sort"));
		}
		$this->setRecordset($this->entity->retrieve());
	}
	
	public function authenticate() 
	{
		$entity = new Entity("user");
		$entity->init();
		$entity->setCountThis(false);
		$entity->addArgument("user", $this->getArgumentData("user"), "=");
		$entity->addArgument("passwd", $this->getArgumentData("passwd"), "MD5");
		$entity->addArgument("active", "1", "=");
		$entity->setLimit(1);
		$entity->setSql(CLEANGAB_SQL_VERIFY_LOGIN);
		return Session::authenticate($entity, CLEANGAB_SQL_VERIFY_LOGIN);
	}
	
	public function getUserById($idUser) 
	{
		$entity = new Entity("user");
		$entity->init();
		$entity->addArgument("id", $idUser, "=");
		$entity->setLimit(1);
		$entity->setCountThis(false);
		$recordSet = $entity->retrieve();
		if ($recordSet->hasRecords())
		{
			return $recordSet->getRecord();
		}
		else 
		{
			return false;
		}
	}
	
	public function save()
	{
		$user 				= new stdClass();
		$user->id 			= $this->getArgumentData("iduser");
		$user->uuid 		= $this->getArgumentData("uuid");
		$user->name 		= $this->getArgumentData("name");
		$user->user 		= $this->getArgumentData("user");
		$user->email 		= $this->getArgumentData("email");
		$user->passwd 		= md5($this->getArgumentData("senha"));
		$user->senha 		= $this->getArgumentData("senha");
		$user->repitaSenha 	= $this->getArgumentData("repitaSenha");
		$user->active 		= $this->getArgumentData("active");
		$user->renew_passwd = $this->getArgumentData("renew");
		$formatter 			= new DateTimeFormatter();
		$user->created 		= ($this->getArgumentData("created")) ? $formatter->toDataBase($this->getArgumentData("created")) : date("Y-m-d H:i:s");
		$user->first_page 	= $this->getArgumentData("first_page");
		$permissions 		= $this->getArgumentData("permission");
		
		if (strlen($user->senha) == 0) 
		{
			$user->passwd = null;
		}
		if ($user->senha && $user->repitaSenha)
		{
			if ($user->senha != $user->repitaSenha)
			{
				Session::addUIMessage("The password and confirmation must be identical");
				Session::goBack();
			}
		}
		
		$entity = new Entity("user");
		$entity->init();
		$entity->setObjectToPersist($user);
		$saved = $entity->save();
		if ($saved)
		{
			$user->id = $saved;
		}
		$granted = false;
		if ($user->id > 0)
		{
			$prmModel = new PermissionModel();
			$prmModel->removePermissionsByUserId($user->id);
			$granted = $prmModel->grantToUser($user->id, $permissions);
		}
		
		return ($saved || $granted);
	}
	
	public function getUsersHaveAPermission($keyPermission) 
	{
		$sql = "SELECT u.id AS id, u.user AS user, u.name AS name, u.email AS email FROM `[database]`.`user` u INNER JOIN `[database]`.`permission` p ON p.user_id = u.id [args] [order] [limit]";
		$entity = new Entity("user");
		$entity->addArgument("u.active", "1", "=");
		$entity->addArgument("p.permission", $keyPermission, "=");
		$entity->setOrderBy("u.user, u.name");
		$entity->setCountThis(false);
		$rset = $entity->retrieve($sql);
		if ($rset->hasRecords())
		{
			return $rset;
		}
		else 
		{
			return false;
		}
	}
	
	public function saveOptions()
	{
		$user 				= Session::getUser();
		$user->email 		= $this->getArgumentData("email");
		$user->passwd 		= md5($this->getArgumentData("senha"));
		$user->senha 		= $this->getArgumentData("senha");
		$user->repitaSenha 	= $this->getArgumentData("repitaSenha");
		$user->first_page 	= $this->getArgumentData("first_page");
		
		if (strlen($user->senha) == 0) 
		{
			$user->passwd = null;
		}
		if ($user->senha && $user->repitaSenha)
		{
			if ($user->senha != $user->repitaSenha)
			{
				Session::addUIMessage("The password and confirmation must be identical");
				Session::goBack();
			}
		}
		$entity = new Entity("user");
		$entity->init();
		$entity->setObjectToPersist($user);
		return $entity->save();
	}

}
?>