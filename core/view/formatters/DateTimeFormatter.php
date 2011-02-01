<?php
/**
 * Clean-Gab Framework
 * DateTimeFormatter.php
 * Date: 	2011-01-XX
 * Author: 	Leopoldo Barreiro
 */

include ("DateFormatter.php");

class DateTimeFormatter extends DateFormatter {
	
	public function __construct() {	
		$this->dataBasePattern 	= "/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/";
		$this->screenPattern 	= "/^[0-9]{2}\/[0-9]{2}\/[0-9]{4} [0-9]{2}:[0-9]{2}:[0-9]{2}$/";
	}
	
	protected function translateToDataBase() {
		$parts = explode(" ", $this->screenContent);
		$date = $parts[0];
		$time = $parts[1];
		$dateParts = explode("/", $date);
		$translated = $dateParts[2] . "-" . $dateParts[1] . "-" . $dateParts[0] . " " . $time;
		$this->dataBaseContent = $translated;
	}
	
	protected function translateToScreen() {
		$parts = explode(" ", $this->dataBaseContent);
		$date = $parts[0];
		$dateParts = explode("-", $date);
		$time = $parts[1];
		$timeParts = explode(":", $time);
		$translated = $dateParts[2] . "/" . $dateParts[1] . "/" . $dateParts[0] . " " . $timeParts[0] . ":" . $timeParts[1];
		$this->screenContent = $translated; 
	}
	
}
?>