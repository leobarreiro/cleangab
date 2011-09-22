<?php
/**
 * CleanGab Framework
 * Input.php
 * Date: 	2011-08-16
 * Author: 	Leopoldo Barreiro
 */

require_once ("Input.php");

class AutoCompleteInput extends Input implements XHTMLComponent {
	
	protected $uri;
	protected $pluginEvents; // according to jquery autocomplete plugin: http://jqueryui.com/demos/autocomplete/#event-select
		
	public function __construct($name, $value, $uri) 
	{
		parent::__construct($name, $value, "text");
		$this->pluginEvents = array("create", "search", "open", "focus", "select", "close", "change");
		$this->uri = $uri;
	}
	
	public function assemble() 
	{
		parent::assemble();
		$xhtml = $this->xhtml;
		$complement = array();
		$complement[] = "<script type=\"text/javascript\">";
		$complement[] = "jQuery(\"#" . $this->getIdName() . "\").autocomplete('" . $this->uri . "', {";
		$complement[] = "width: 260, ";
		$complement[] = "multiple: false, ";
		$complement[] = "matchContains: true, ";
		foreach ($this->triggersJs as $event=>$action)
		{
			if (in_array($event, $this->pluginEvents))
			$complement[] = $event . ": function(event, ui){" . $action . "}, ";
		}
		//$complement[] = "formatItem: autoCompleteFormatItem, ";
		$complement[] = "formatResult: autoCompleteFormatResult";
		$complement[] = "});";
		$complement[] = "</script>";
		$this->xhtml = $xhtml . implode("", $complement);
	}
}
?>
