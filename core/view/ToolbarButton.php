<?php
require_once ("XHTMLComponent.php");
require_once ("Properties.php");

class ToolbarButton implements XHTMLComponent {
	
	protected $idName;
	protected $uri;
	protected $cssStyle;
	protected $key;
	protected $content;
	protected $controller;
	protected $operation;
	private   $propertyPrefix;
	
	public function __construct($id, $uri, $key, $css) 
	{
		$this->idName 			= $id;
		$this->uri 				= $uri;
		$this->key 				= $key;
		$this->cssStyle 		= $css;
		$this->propertyPrefix 	= "toolbarbutton.";
	}
	
	public function setControllerAction($controller, $operation)
	{
		$this->controller 	= $controller;
		$this->operation	= $operation;
	}
	
	public function assemble() 
	{
		//$this->content = "<a title=\"" . $this->id . "\" class=\"" . $this->cssStyle . "\" href=\"" . $this->uri . "\">" . $this->key . "</a>";
		$actionButton 	= (strpos($this->uri, "http") === 0) ? "window.location='" . $this->uri . "'" : $this->uri;
		$propertyKey 	= $this->propertyPrefix . $this->idName;
		$this->content 	= "<input id=\"" . $this->idName . "\" type=\"button\" onclick=\"" . $actionButton . "\" value=\"" . Properties::get($propertyKey) . "\" class=\"" . $this->cssStyle . "\" />\n";
	}
	
	public function getIdName() 
	{
		return $this->idName;
	}
	
	public function setIdName($id) {
		$this->idName = $id;
	}
	
	public function inject($mixedContent) 
	{
		$this->content = $mixedContent;
	}

	public function toXhtml() 
	{
		$this->assemble();
		return $this->content;
	}
	
	public function getKey()
	{
		return $this->key;
	}
	
	public function getController()
	{
		return $this->controller;
	}
	
	public function getOperation()
	{
		return $this->operation;
	}
}
?>