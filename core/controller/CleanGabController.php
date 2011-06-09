<?php
/**
 * Clean-Gab Framework
 * CleanGabController.php
 * Date: 	2011-01-29
 * Author: 	Leopoldo Barreiro
 */

include ("CleanGabEngineView.php");

class CleanGabController {

	private $action;
	private $args;

	public function __construct($action=null, $args=null) {

		$this->action = ($action != null) ? $action : "index";
		$this->args = (is_array($args)) ? $args : array($args);
	}

	public function getUserInput($strKey) {
		$input = filter_input(INPUT_POST, $strKey, FILTER_SANITIZE_STRING);
		if ($input != null) {
			return $input;
		}
		return false;
	}

	public function index() {}
	
	public function view() {}
	
	public function save() {}
	
}
?>