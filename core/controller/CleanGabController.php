<?php
/**
 * CleanGab Framework
 * CleanGabController.php
 * Date: 2011-01-29
 * Author: Leopoldo Barreiro
 */

include ("CleanGabEngineView.php");

class CleanGabController {

	private $action;
	private $args;
	public static $readOnlyJqueryScript;

	public function __construct($action=null, $args=null) {
		$this->action = ($action != null) ? $action : "index";
		$this->args = (is_array($args)) ? $args : array($args);
		$this->readOnlyJqueryScript = "jQuery(\"div.field > input\").add(\"div.field > select\").add(\"div.field > textarea\").attr('disabled', 'disable').addClass('readonly');";
	}

	public function getUserInput($strKey, $inputMethod="post") 
	{
		$method = (strtolower($inputMethod) == "post") ? INPUT_POST : INPUT_GET;
		$input = filter_input($method, $strKey, FILTER_SANITIZE_STRING);
		if ($input != null) 
		{
			return $input;
		}
		return false;
	}
	
	public function getInputCLI($strKey) 
	{
		$input = filter_input(INPUT_ENV, $strKey, FILTER_SANITIZE_STRING);
		if ($input != null) 
		{
			return $input;
		}
		return false;
	}
	
	public function injectBlockOArgumentsData($model) 
	{
		$expectedArguments = $model->getExpectedArguments();
		foreach ($expectedArguments as $argument) {
			$model->addArgumentData($argument, $this->getUserInput($argument));
		}
	}
	
	public function add() {}
	
	public function edit() {}

	public function index() {}
	
	public function view() {}
	
	public function save() {}
	
}
?>