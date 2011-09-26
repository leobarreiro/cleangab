<?php
require_once ("XHTMLComponent.php");
class ToolbarButton implements XHTMLComponent {
	
	protected $idName;
	protected $uri;
	protected $cssStyle;
	protected $key;
	protected $content;
	protected $controller;
	protected $operation;
	
	public function __construct($id, $uri, $key, $css) 
	{
		$this->idName 	= $id;
		$this->uri 		= $uri;
		$this->key 		= $key;
		$this->cssStyle = $css;
	}
	
	public function setControllerAction($controller, $operation)
	{
		$this->controller 	= $controller;
		$this->operation	= $operation;
	}
	
	public function assemble() 
	{
		$this->content = "<a class=\"" . $this->cssStyle . "\" href=\"" . $this->uri . "\">" . $this->key . "</a>";
	}
	
	public function getIdName() 
	{
		return $this->idName;
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