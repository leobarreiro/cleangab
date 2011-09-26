<?php
/**
 * CleanGab Framework
 * SelectInput.php
 * Date: 	2011-08-03
 * Author: 	Leopoldo Barreiro
 */

require_once ("Input.php");

class SelectInput extends Input {
	
	protected $options;
	
	public function __construct($name, $value, $options=array()) 
	{
		parent::__construct($name, $value, "select");
		$this->options = $options;
	}
	
	public function addOption($key, $value)
	{
		$this->options[$key] = $value;
	}
	
	public function setOptions($mixedOptions)
	{
		$opts = (!is_array($mixedOptions)) ? (array)$mixedOptions : $mixedOptions;
		$this->options = $opts;
	}
	
	public function assemble() 
	{
		$xhtml = array();
		$xhtml[] = "<select";
		$xhtml[] = " name=\"" . $this->name . "\"";
		$xhtml[] = " id=\"" . $this->id . "\"";
		if (strlen($this->cssClass) > 0) 
		{
			$xhtml[] = " class=\"" . $this->cssClass . "\"";
		}
		foreach ($this->attribs as $attrName=>$attrValue)
		{
			$xhtml[] = " " . $attrName . "=\"" . $attrValue ."\"";
		}
		foreach ($this->triggersJs as $event=>$todo)
		{
			$xhtml[] = " " . $event . "=\"" . $todo ."\"";
		}
		$xhtml[] = ">";
		
		$xhtml[] = "<option value=\"\">Select...</option>";
		
		if (is_array($this->options) && count($this->options) > 0)
		{
			foreach ($this->options as $k=>$v)
			{
				$option = array();	
				$option[] = "<option value=\"" . $k . "\"";
				if ($this->value == $k)
				{
					$option[] = " selected";
				}
				$option[] = ">";
				$option[] = $v . "</option>";
				$xhtml[] = implode("", $option);
			}
		}
		$xhtml[] = "</select>";
		$this->xhtml = implode("", $xhtml);
	}
	
}
?>
