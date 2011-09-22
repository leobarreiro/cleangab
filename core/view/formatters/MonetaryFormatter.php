<?php
/**
 * CleanGab Framework
 * MonetaryFormatter.php
 * Date: 	2011-09-12
 * Author: 	Leopoldo Barreiro
 */

require_once ("Formatter.php");

class MonetaryFormatter extends Formatter {
	
	protected $locale;
	protected $options;
	protected $language;
	
	public function __construct($lang=CLEANGAB_APP_LANGUAGE) 
	{
		$this->language 		= $lang;
		$this->locale 			= localeconv();
		$this->dataBasePattern 	= "/^[0-9\. ]+$/";
		$this->screenPattern 	= "/^[0-9\.\,]+$/";
		$this->options 			= $this->mountOptions();
	}
	
	private function mountOptions()
	{
		$options = array();
		$options["pt_BR"] = array("thousands_separator"=>".", "decimal_point"=>",");
		$options["en_US"] = array("thousands_separator"=>",", "decimal_point"=>".");
		return $options;
	}
	
	protected function translateToDataBase() 
	{
		$translated = str_replace(".", "", $this->screenContent);
		$translated = str_replace(",", ".", $translated);
		$this->dataBaseContent = $translated;
	}
	
	protected function translateToScreen() 
	{
		$translated = number_format($this->dataBaseContent, 2, $this->options[$this->language]["decimal_point"], $this->options[$this->language]["thousands_separator"]);
		$this->screenContent = $translated; 
	}
	
	public function toFormField($nameField, $idField)
	{
		Validate::notNull($nameField, "NameField can not be null in a Formatter class, toFormField operation");
		$xhtml  = "<input type=\"text\" name=\"" . $nameField . "\"";
		if ($idField != null) 
		{
			$xhtml .= " id='" . $idField . "' ";
		}
		$xhtml .= " value=\"" . $this->screenContent . "\" ";
		$xhtml .= " class=\"" . strtolower(get_class($this)) . "\" />";
		return $xhtml;
	}
}
?>