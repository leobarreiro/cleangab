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
	protected $xhtmlFile;
	protected $xhtmlContent;
	protected $xhtmlObject;
	protected $translated;
	
	public function __construct($nameController, $operationController, $objects=null) {
		$this->nameController 		= $nameController;
		$this->operationController 	= $operationController;
		if ($objects != null) {
			if (is_array($objects)) {
				$this->objects = $objects;
			} else {
				$this->objects[] = $objects;
			}
		} else {
			$this->objects = array();
		}
		$this->xhtmlFile = "view" . SEPARATOR . strtolower($this->nameController) . SEPARATOR . strtolower($this->operationController) . ".xhtml";
		
		try {
			$this->xhtmlContent = file_get_contents($this->xhtmlFile);
			$this->xhtmlObject = new SimpleXMLElement($this->xhtmlContent);
		} catch (Exception $e) {
			echo $e->getMessage();
		}
		$this->inject();
	}
	
	public function getNameController() {
		return $this->nameController;
	}
	
	public function getTemplateXhtml() {
		return $this->templateXhtml;
	}
	
	final function inject() {
		Validate::notNull($this->objects, "Objects can not be null");
		$xhtml = $this->xhtmlObject;
		$childrens = $xhtml->body->children();
		$xhtmlNovo = "";
		for ($x=0; $x<count($childrens); $x++) {
			$arr = $childrens[$x]->attributes();
			if (isset($arr['type']) && strtolower((string)$arr['type']) == 'cleangab') {
				foreach ($this->objects as $objectView) {
					if ($objectView->getIdName() == (string)$arr['var']) {
						$xhtmlNovo .= $objectView->toXhtml();
					}
				}
			} else {
				$xhtmlNovo .= $childrens[$x]->asXML();
			}
		}
		$head = $xhtml->head->asXML();
		$newContent = "<html>" . $head . "<body>" . $xhtmlNovo . "</body></html>";
		$this->translated = $newContent;
	}
	
	public function addObject($object) {
		if (is_object($object)) {
			$this->objects[] = $object;
		}
	}
	
	public function renderize() {
		Validate::notNull($this->translated, "To renderize a view the content can not be null");
		echo $this->translated;
	}
	
}
?>