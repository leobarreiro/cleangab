<?php

class UserModel extends CleanGabModel {

	public function __construct() {
		parent::__construct();
		$this->listableFields = array("id", "user", "name", "email", "created");
		$this->masks = array("created"=>"DateTimeFormatter");
		$this->hintFields = array("id"=>"Id", "user"=>"User", "name"=>"Nome", "email"=>"e-mail", "created"=>"Data Cadastro");		
		$entity = new Entity("user");
		$entity->init();
		$this->recordset = $entity->retrieve();
		$this->recordset->goFirst();
	}
}
?>