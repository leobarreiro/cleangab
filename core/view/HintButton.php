<?php

require_once ("XHTMLComponent.php");

class HintButton implements XHTMLComponent {
	
	protected $idName;
	protected $uri;
	protected $key;
	protected $content;
	
	public function __construct($id, $content) 
	{
		$this->idName 	= $id;
		$this->content	= $content;
	}
	
	public function assemble() 
	{
		$content 	   = array();
		$content[] 	   = "<a id=\"" . $this->generate_uuid() . "\" class=\"" . strtolower(get_class($this)) . "\" title=\"" . $this->content . "\">hint</a>";
		$content[]     = "<script type=\"text/javascript\">jQuery('#" . $this->generate_uuid() . "').removeAttr('href');</script>";
		$this->content = implode("", $content);
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
	
	private function generate_uuid()
	{
		$uuid = $this->getIdName() . "_";
		$i = 0;
		while ($i < 6)
		{
			$uuid .= chr(rand(97, 122));
			$i++;
		}
		return $uuid;
	}
	
}
?>