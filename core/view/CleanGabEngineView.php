<?php
/**
 * Clean-Gab Framework
 * CleanGabEngineView.php
 * Date: 	2011-01-28
 * Author: 	Leopoldo Barreiro
 */

include_once ("Validate.php");

class CleanGabEngineView {
	
	private $nameController;
	private $operationController;
	private $args = array();
	private $xhtmlContent;
	
	
	public function __construct($nameController, $operationController, $args=null) {
		$this->nameController 		= $nameController;
		$this->operationController 	= $operationController;
		if ($args != null) {
			if (is_array($args)) {
				$this->args = $args;
			} else {
				$this->args[] = $args;
			}
		}
		$this->xhtmlContent = "";
	}
	
	public function getNameController() {
		return $this->nameController;
	}
	
	public function getTemplateXhtml() {
		return $this->templateXhtml;
	}
	
	private function getXhtmlOriginal() {
		$pathFile = CLEANGAB_FWK_VIEW . $this->nameController . SEPARATOR . $this->operationController . ".xhtml";
		$this->xhtmlContent = file_get_contents($pathFile);
	}
	
	final function injectArgs() {
		Validate::notNull($this->args);
		foreach ($this->args as $arg) {
			
		}
	}
}
?>