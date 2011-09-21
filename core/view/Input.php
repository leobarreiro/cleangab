<?php
/**
 * CleanGab Framework
 * Input.php
 * Date: 	2011-08-03
 * Author: 	Leopoldo Barreiro
 */

class Input implements XHTMLComponent {
	
	protected $name;
	protected $type;
	protected $supportedTypes;
	protected $value;
	protected $id;
	protected $attribs;
	protected $xhtml;
	protected $cssClass;
	protected $triggersJs;
	protected $supportedTriggers;
	
	public function __construct($name, $value, $type="text") 
	{
		$this->name = $name;
		$this->id = $name;
		$this->value = $value;
		$this->supportedTypes = array("text", "hidden", "password", "radio", "checkbox", "select");
		$this->cssClass = "";
		$this->setType($type);
		$this->attribs = array();
		$this->triggersJs = array();
		$this->supportedTriggers = array("onselect", "onchange", "onblur", "onclick", "onmouseover", "onmousedown", "onkeydown", "onkeyover");
	}
	
	public function getCssClass() 
	{
		return $this->cssClass;
	}
	
	public function setCssClass($strCss) {
		$this->cssClass = $strCss;
	}
	
	public function getIdName() 
	{
		return $this->id;
	}
	
	public function setIdName($id)
	{
		$this->id = $id;
	}
	
	public function addTriggerJs($event, $todo) 
	{
		$this->triggersJs[strtolower($event)] = $todo;
	}
	
	public function addAttrib($attribName, $attribValue) 
	{
		$this->attribs[strtolower($attribName)] = $attribValue;	
	}
	
	public function inject($mixedContent) 
	{
		$this->value = (string) $mixedContent;
	}
	
	public function assemble() 
	{
		$xhtml = array();
		$xhtml[] = "<input type=\"" . $this->type . "\"";
		$xhtml[] = " name=\"" . $this->name . "\"";
		$xhtml[] = " id=\"" . $this->id . "\"";
		$xhtml[] = " value=\"" . $this->value . "\"";
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
			if (in_array($event, $this->supportedTriggers))
			{
				$xhtml[] = " " . $event . "=\"" . $todo ."\"";
			}
		}
		$xhtml[] = "/>";
		$this->xhtml = implode("", $xhtml);
	}
	
	public function toXhtml() 
	{
		$this->assemble();
		return $this->xhtml;
	}
	
	public function setType($tp) 
	{
		$this->type = (in_array(strtolower($tp), $this->supportedTypes)) ? strtolower($tp) : "text";
	}
	
	public function getType() 
	{
		return $this->type;
	}
	
}
?>
