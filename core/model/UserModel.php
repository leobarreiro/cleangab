<?php
class UserModel extends CleanGabModel {

	public function __construct() {
		parent::__construct();
		$this->listableFields = array("id", "user", "name", "email", "created");
		$this->masks = array("created"=>"DateTimeFormatter");
		$this->hintFields = array("id"=>"Id", "user"=>"User", "name"=>"Nome", "email"=>"e-mail", "created"=>"Data Cadastro");		
	}
	
	public function prepareList() 
	{
		$recoveredEntity = Session::getHostedObject("user-entity");
		if ($recoveredEntity)
		{
			$this->entity = $recoveredEntity;
		}
		else 
		{
			$this->entity = new Entity("user");
			$this->entity->init();
		}

		// Arguments
		
		if ($this->getArgumentData("user")) {
			$this->entity->addArgument("user", $this->getArgumentData("user"), "LIKE");
		}
		if ($this->getArgumentData("name")) {
			$this->entity->addArgument("name", $this->getArgumentData("name"));
		}
		// pagination
		if ($this->getArgumentData("pg"))
		{
			$this->entity->setOffset(($this->getArgumentData("pg")*$this->entity->getLimit())+1);
		}
		// order
		if ($this->getArgumentData("order")) {
			$this->entity->setOrderBy($this->getArgumentData("order"));
		}
		$this->setRecordset($this->entity->retrieve());
		Session::hostObject("user-entity", $this->entity);
	}
	
}
?>