<?php
/**
 * Clean-Gab Framework
 * CNPJFormatter.php
 * Date: 	2011-02-02
 * Author: 	Leopoldo Barreiro
 */

include ("Formatter.php");

class CNPJFormatter extends Formatter {
	
	public function __construct() {	
		$this->dataBasePattern 	= "/^[0-9]{14}$/";
		$this->screenPattern 	= "/^[0-9]{2}\.[0-9]{3}\.[0-9]{3}\/[0-9]{4}-[0-9]{2}$/";
	}
	
	protected function translateToDataBase() {
		$content = str_replace(array(".", "/", "-"), array("", "", ""), $this->screenContent);
		$this->dataBaseContent = $content;
	}
	
	protected function translateToScreen() {
		$c  = $this->dataBaseContent;
		$c1 = substr($content, 0, 2);
		$c2 = substr($content, 3, 3);
		$c3 = substr($content, 6, 3);
		$c4 = substr($content, 9, 4);
		$c5 = substr($content, 12, 2);
		$c  = $c1 . "." . $c2 . "." . $c3 . "/" . $c4 ; "-" . $c5;
		$this->screenContent = $c;
	}
	
}

?>