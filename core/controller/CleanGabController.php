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
		if ($args != null) {
			if (is_array($args)) {
				$this->args = $args;
			}
			if (is_string($args)) {
				$this->args = array($args);
			}
		}
		if ($action != null) {
			$this->action = $action;
		} else {
			$this->action = "index";
		}
		$newAction = $this->action."()";
		$this->$newAction;
	}
	
	public function index() {
		echo "Entrou no index";
	}
}
?>