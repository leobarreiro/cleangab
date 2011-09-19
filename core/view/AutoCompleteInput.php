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
		
	public function __construct($name, $value, $uri) 
	{
		parent::__construct($name, $value, "text");
		$this->uri = $uri;
	}
	
	public function assemble() 
	{
		parent::assemble();
		$xhtml = $this->xhtml;
		$complement = array();
		$complement[] = "<script type=\"text/javascript\">";
		$complement[] = "jQuery(\"#" . $this->getIdName() . "\").autocomplete('" . $this->uri . "', {";
		$complement[] = "width: 300,";
		$complement[] = "multiple: false,";
		$complement[] = "matchContains: true,";
		//$complement[] = "formatItem: autoCompleteFormatItem,";
		$complement[] = "formatResult: autoCompleteFormatResult";
		$complement[] = "});";
		$complement[] = "</script>";
		$this->xhtml = $xhtml . implode("", $complement);
	}
}
?>
