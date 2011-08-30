<?php
/**
 * CleanGab Framework
 * UIMessageBase.php
 * Date: 	2011-06-25
 * Author: 	Leopoldo Barreiro
 */

require_once ("XHTMLComponent.php");

class UIMessageBase implements XHTMLComponent {

	protected $idName;
	protected $content;
	protected $xhtml;

	public function __construct($idName, $mixedContent)
	{
		$this->idName  = $idName;
		$this->inject($mixedContent);
	}
	
	public function inject($mixedContent)
	{
		$this->content = $mixedContent;
	}

	public function assemble()
	{
		$xhtml = array();
		
		if (is_string($this->content) && strlen($this->content) > 0)
		{
			$xhtml[] = "<div id=\"" . $this->idName . "\" class=\"" . strtolower(get_class($this)) . "\">";
			$xhtml[] = $this->content;
			$xhtml[] = "</div>\n";
		}
		else if (is_object($this->content))
		{
			$xhtml[] = "<div id=\"" . $this->idName . "\" class=\"" . strtolower(get_class($this)) . "\">";
			$xhtml[] = $this->content->msg;
			$xhtml[] = "</div>\n";
		}
		else 
		{
			$xhtml[] = "";
		}
		$this->xhtml = implode("", $xhtml);
	}

	public function toXhtml()
	{
		$this->assemble();
		return $this->xhtml;
	}

	public function getIdName()
	{
		return $this->idName;
	}
	
}
?>