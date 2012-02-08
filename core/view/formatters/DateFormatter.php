<?php
/**
 * Clean-Gab Framework
 * DateFormatter.php
 * Date: 	2011-01-XX
 * Author: 	Leopoldo Barreiro
 */

require_once ("Formatter.php");

class DateFormatter extends Formatter {
	
	public function __construct() {	
		$this->dataBasePattern 	= "/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/";
		$this->screenPattern 	= "/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/";
	}
	
	protected function translateToDataBase() {
		$parts = explode("/", $this->screenContent);
		$translated = $parts[2] . "-" . $parts[1] . "-" . $parts[0];
		$this->dataBaseContent = $translated;
	}
	
	protected function translateToScreen() {
		$parts = explode("-", $this->dataBaseContent);
		$translated = $parts[2] . "/" . $parts[1] . "/" . $parts[0];
		$this->screenContent = $translated; 
	}
	
	public function toFormField($nameField, $idField)
	{
		Validation::notNull($nameField, "NameField can not be null in a Formatter class, toFormField operation");
		$xhtml  = "<input type=\"text\" name=\"" . $nameField . "\"";
		if ($idField != null) {
			$xhtml .= " id='" . $idField . "' ";
		}
		$xhtml .= " value=\"" . $this->screenContent . "\" ";
		$xhtml .= " class=\"" . strtolower(get_class($this)) . "\" />";
		return $xhtml;
	}
}
?>