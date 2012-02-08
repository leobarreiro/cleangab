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
		$this->dataBasePattern 	= "/^[a-zA-Z0-9]{+}$/";
		$this->screenPattern 	= "/^[a-zA-Z0-9]{+}$/";
		$this->setOptions(array("N"=>"No", "Y"=>"Yes"));
	}
	
	protected function matchDataBasePattern()
	{
		$dataBaseOptions = array_keys($this->options);
		if (in_array($this->dataBaseContent, $dataBaseOptions)) 
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
		$screenOptions = array_values($this->options);
		if (in_array($this->screenContent, $screenOptions)) 
		{
			return true;
		}
		else 
		{
			return false;
		}
	}
	
	public function toFormField($nameField, $idField, $mixedValue)
	{
		$this->dataBaseContent = $mixedValue;
		Validation::notNull($nameField, "NameField can not be null in a Formatter class, toFormField operation");
		$xhtml = array();
		$xhtml[] = "<select name=\"" . $nameField . "\" class=\"" . strtolower(get_class($this)) . "\" id=\"" . $idField . "\" >";
		foreach ($this->options as $key=>$value)
		{
			$xhtml[] = "<option value=\"" . $key . "\" ";
			if ($mixedValue == $key && $this->matchDataBasePattern())
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
		return implode("", $xhtml);
	}
	
}
?>