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
	
}
?>