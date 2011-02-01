<?php
/**
 * Clean-Gab Framework
 * Formatter.php
 * Date: 	2011-01-XX
 * Author: 	Leopoldo Barreiro
 */

class Formatter {
	
	protected $dataBaseContent;
	protected $dataBasePattern;
	protected $screenContent;
	protected $screenPattern;
	
	public function __construct() {}
	
	protected function matchDataBasePattern() {
		if (preg_match($this->dataBasePattern, $this->dataBaseContent) == 1) {
			return true;
		} else {
			return false;
		}
	}

	protected function matchScreenPattern() {
		if (preg_match($this->screenPattern, $this->screenContent) == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	public function toScreen($mixedDataBaseContent) {
		$this->dataBaseContent = $mixedDataBaseContent;
		if ($this->matchDataBasePattern()) {
			$this->translateToScreen();
			return $this->screenContent;
		} else {
			return false;
		}
	}
	
	public function toDataBase($mixedScreenContent) {
		$this->screenContent = $mixedScreenContent;
		if ($this->matchScreenPattern()) {
			$this->translateToDataBase();
			return $this->dataBaseContent;
		} else {
			return false;
		}
	}
	
	// TODO Escrever a lógica de cada Formatter
	protected function translateToDataBase() { }
	
	// TODO Escrever a lógica de cada Formatter
	protected function translateToScreen() { }
	
	
	public function getScreenContent() {
		return $this->screenContent;
	}
	
	public function getDataBaseContent() {
		return $this->dataBaseContent;
	}
	
}
?>