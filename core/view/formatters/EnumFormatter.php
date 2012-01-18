<?php
/**
 * CleanGab Framework
 * EnumFormatter.php
 * Date: 	2012-01-17
 * Author: 	Leopoldo Barreiro
 */

require_once ("TinyIntFormatter.php");

class EnumFormatter extends TinyIntFormatter {
	
	public function __construct() 
	{	
		$this->dataBasePattern 	= "/^[a-zA-Z0-9]$/";
		$this->screenPattern 	= "/^[a-zA-Z0-9]$/";
		$this->setOptions(array("N"=>"No", "Y"=>"Yes"));
	}
	
	public function toFormField($nameField, $idField, $mixedValue)
	{
		$this->dataBaseContent = $mixedValue;
		Validate::notNull($nameField, "NameField can not be null in a Formatter class, toFormField operation");
		$xhtml = array();
		if ($this->matchDataBasePattern()) 
		{
			$xhtml[] = "<select name=\"" . $nameField . "\" class=\"" . strtolower(get_class($this)) . "\" id=\"" . $idField . "\" >";
			foreach ($this->options as $key=>$value)
			{
				$xhtml[] = "<option value=\"" . $key . "\" ";
				if ($mixedValue == $key)
				{
					$xhtml[] = " selected ";
				}
				if ($this->disabled)
				{
					$xhtml[] = " disabled=\"disabled\" ";
				}
				$xhtml[] = "/>";
				$xhtml[] = $value;
				$xhtml[] = "</option>";
			}
			$xhtml[] = "</select>";
		}
		return implode("", $xhtml);
	}
	
}
?>