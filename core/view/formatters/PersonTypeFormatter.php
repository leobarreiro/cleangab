<?php
/**
 * CleanGab Framework
 * CEPFormatter.php
 * Date: 	2012-01-12
 * Author: 	Leopoldo Barreiro
 */

require_once ("Formatter.php");
require_once ("Properties.php");

class PersonTypeFormatter extends Formatter {
	
	private $properties;
	private $scrJuridica;
	private $scrFisica;
	
	public function __construct() {

		$this->properties = new Properties();
		$this->scrJuridica = Properties::get("persontype.juridica");
		$this->scrFisica = Properties::get("persontype.fisica");
		$this->dataBasePattern 	= "/^[JF]{1}$/";
		$this->screenPattern 	= array($this->scrFisica, $this->scrJuridica);
	}
	
	protected function matchScreenPattern() 
	{
		if (in_array($this->screenContent, $this->screenPattern)) 
		{
			return true;
		}
		else 
		{
			return false;
		}
	}
	
	protected function translateToDataBase() {
		$this->dataBaseContent = ($this->screenContent == $this->scrJuridica) ? "J" : "F";
	}
	
	protected function translateToScreen() {
		$this->screenContent = ($this->dataBaseContent == "J") ? $this->scrJuridica : $this->scrFisica;
	}
	
}

?>