<?php
/**
 * Clean-Gab Framework
 * CleanGabEngineView.php
 * Date: 	2011-01-28
 * Author: 	Leopoldo Barreiro
 */

//header("Content-Type: text/html; charset=ISO-8859-1");

include_once ("Validate.php");

class CleanGabEngineView {
	
	protected $nameController;
	protected $operationController;
	protected $objects;
	protected $template;
	protected $xhtmlFile;
	protected $xhtmlContent;
	protected $translated;
	
	public function __construct($nameController, $operationController, $objects=null) {
		
		$this->nameController 		= $nameController;
		$this->operationController 	= $operationController;
		$this->template 			= CLEANGAB_XHTML_TEMPLATE;
		$this->objects 				= array();
		if (is_array($objects)) {
			foreach ($objects as $obj) {
				$this->addObject($obj);
			}
		} else {
			$this->addObject($objects);
		}
		
		$this->xhtmlFile = "view" . SEPARATOR . strtolower($this->nameController) . SEPARATOR . strtolower($this->operationController) . ".xhtml";
		
		try {
			$this->xhtmlContent = file_get_contents($this->xhtmlFile);
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}
	
	public function getNameController() {
		return $this->nameController;
	}
	
	public function setTemplate($template) {
		$this->template = $template;
	}
	
	public function getTemplate() {
		return $this->template;
	}
	
	final function inject() {
		$newContent = $this->xhtmlContent;
		foreach ($this->objects as $objView) {
			$elTag = "#{" . $objView->getIdName() . "}";
			$newContent = str_replace($elTag, $objView->toXhtml(), $newContent);
		}
		$this->translated = $newContent;
	}
	
	public function addObject($object) {
		
		if (is_object($object) && method_exists($object, "getIdName") && 
			method_exists($object, "ensamble") && method_exists($object, "toXhtml")) {
			$this->objects[] = $object;
		}
	}
	
	public function renderize() {
		$this->inject();
		$templatePath = "lib" . SEPARATOR . "xhtml" . SEPARATOR . $this->template;
		$renderized = file_get_contents($templatePath);
		$renderized = str_replace("#{content}", $this->translated, $renderized);
		echo $renderized;
	}

}
?>