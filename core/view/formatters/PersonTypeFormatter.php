<?php
/**
 * CleanGab Framework
 * PersonTypeFormatter.php
 * Date: 	2012-02-01
 * Author: 	Leopoldo Barreiro
 */

require_once ("EnumFormatter.php");
require_once ("Properties.php");

class PersonTypeFormatter extends EnumFormatter {
	
	private $properties;
	private $scrJuridica;
	private $scrFisica;
	
	public function __construct() {
		parent::__construct();
		$this->properties 		= new Properties();
		$this->scrJuridica 		= Properties::get("persontype.juridica");
		$this->scrFisica 		= Properties::get("persontype.fisica");
		$this->dataBasePattern 	= "/^[FJ]{1}$/";
		$this->screenPattern 	= array($this->scrFisica, $this->scrJuridica);
		$this->setOptions(array("J"=>$this->scrJuridica, "F"=>$this->scrFisica));
	}
	
	
	protected function translateToDataBase() {
		$this->dataBaseContent = ($this->screenContent == $this->scrJuridica) ? "J" : "F";
	}
	
	protected function translateToScreen() {
		$this->screenContent = ($this->dataBaseContent == "J") ? $this->scrJuridica : $this->scrFisica;
	}
	
}

?>