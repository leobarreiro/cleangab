<?php
/**
 * CleanGab Framework
 * TinyIntFormatter.php
 * Date: 	2011-08-14
 * Author: 	Leopoldo Barreiro
 */

require_once ("Formatter.php");

class TinyIntFormatter extends Formatter {
	
	private $options;

	public function __construct() 
	{	
		$this->dataBasePattern 	= "/^[0-9]$/";
		$this->screenPattern 	= "/^[a-zA-Z]$/";
		$this->setOptions(array("0"=>"I", "1"=>"A"));
	}
	
	public function setOptions($options)
	{
		$this->options = $options;
	}
	
	protected function translateToDataBase() 
	{
		foreach ($this->options as $opt=>$value)
		{
			
			if ($value == $this->screenContent)
			{
				$this->dataBaseContent = $opt;
			}
		}
	}
	
	protected function translateToScreen() 
	{
		foreach ($this->options as $opt=>$value)
		{
			if ($opt == $this->dataBaseContent)
			{
				$this->screenContent = $value;
			}
		}
	}
	
	public function toFormField($nameField, $idField, $mixedValue)
	{
		Validate::notNull($nameField, "NameField can not be null in a Formatter class, toFormField operation");
		$xhtml = array();
		foreach ($this->options as $key=>$value)
		{
			$xhtml[] = "<span class=\"" . strtolower(get_class($this)) . "\">";
			$xhtml[] = "<label>";
			$xhtml[] = "<input type=\"radio\" name=\"" . $nameField . "\"";
			$xhtml[] = " id=\"" . $idField . "\" ";
			$xhtml[] = " value=\"" . $key . "\" ";
			if ($mixedValue == $key)
			{
				$xhtml[] = " checked ";
			}
			if ($this->disabled)
			{
				$xhtml[] = " disabled=\"disabled\" ";
			}
			$xhtml[] = "/>";
			$xhtml[] = $value;
			$xhtml[] = "</label>";
			$xhtml[] = "</span>";
		}
		return implode("", $xhtml);
	}
	
}
?>