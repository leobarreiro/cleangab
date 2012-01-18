<?php
/**
 * CleanGab Framework
 * CEPFormatter.php
 * Date: 	2012-01-09
 * Author: 	Leopoldo Barreiro
 */

require_once ("Formatter.php");

class CEPFormatter extends Formatter {
	
	public function __construct() {	
		$this->dataBasePattern 	= "/^[0-9]{8}$/";
		$this->screenPattern 	= "/^[0-9]{5}-[0-9]{3}$/";
	}
	
	protected function translateToDataBase() {
		$content = str_replace(array("-"), array(""), $this->screenContent);
		$this->dataBaseContent = $content;
	}
	
	protected function translateToScreen() {
		$c  = $this->dataBaseContent;
		$c1 = substr($content, 0, 5);
		$c2 = substr($content, 6, 3);
		$c  = $c1 . "-" . $c2;
		$this->screenContent = $c;
	}
	
}

?>