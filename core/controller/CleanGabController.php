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
		$newAction = $this->action . '()';
		try {
			$this->{$newAction};
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	public function index() {}
	
	public function view() {}
	
	public function save() {}
	
}
?>