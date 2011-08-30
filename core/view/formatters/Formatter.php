<?php
/**
 * CleanGab Framework
 * Formatter.php
 * Date: 	2011-01-02
 * Author: 	Leopoldo Barreiro
 */

class Formatter {
	
	protected $dataBaseContent;
	protected $dataBasePattern;
	protected $screenContent;
	protected $screenPattern;
	
	public function __construct() {}
	
	protected function matchDataBasePattern() 
	{
		if (preg_match($this->dataBasePattern, $this->dataBaseContent) == 1) 
		{
			return true;
		} 
		else 
		{
			return false;
		}
	}

	protected function matchScreenPattern() 
	{
		if (preg_match($this->screenPattern, $this->screenContent) == 1) 
		{
			return true;
		} 
		else 
		{
			return false;
		}
	}
	
	public function toScreen($mixedDataBaseContent) 
	{
		$this->dataBaseContent = $mixedDataBaseContent;
		if ($this->matchDataBasePattern()) 
		{
			$this->translateToScreen();
			return $this->screenContent;
		} 
		else 
		{
			return false;
		}
	}
	
	public function toDataBase($mixedScreenContent) 
	{
		$this->screenContent = $mixedScreenContent;
		if ($this->matchScreenPattern()) 
		{
			$this->translateToDataBase();
			return $this->dataBaseContent;
		} 
		else 
		{
			return false;
		}
	}
	
	public function toFormField($nameField, $idField=null) 
	{
		Validate::notNull($nameField, "NameField can not be null in a Formatter class, toFormField operation");
		$xhtml  = "<input type='text' name='" . $nameField . "'";
		if ($idField != null) 
		{
			$xhtml .= " id='" . $idField . "' ";
		}
		$xhtml .= " value='" . $this->screenContent . "' ";
		$xhtml .= " />";
		return $xhtml;
	}
	
	public function toListField($nameField=null, $idField=null) 
	{
		return $this->screenContent;
	}
	
	// TODO Escrever a logica de cada Formatter
	protected function translateToDataBase() { }
	
	// TODO Escrever a logica de cada Formatter
	protected function translateToScreen() { }
	
	
	public function getScreenContent() {
		return $this->screenContent;
	}
	
	public function getDataBaseContent() {
		return $this->dataBaseContent;
	}
	
}
?>
