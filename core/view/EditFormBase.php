<?php
/**
 * CleanGab Framework
 * EditFormBase.php
 * Date: 	2011-06-24
 * Author: 	Leopoldo Barreiro
 */

require_once ("XHTMLComponent.php");

class EditFormBase implements XHTMLComponent {

	protected $idName;
	protected $content;
	protected $masks;
	protected $formFields;
	protected $nameFields;
	protected $nameController;
	protected $formOperation;
	protected $xhtml;

	public function __construct($idName, $nameController, $objectModel)
	{
		Validate::notNull($idName, "ID can not be null");
		$this->idName 		  = $idName;
		$this->masks  		  = ($objectModel->getMasks() != null) ? $objectModel->getMasks() : array();
		
		$this->inject($objectModel->getRecordset());
		
		$this->formFields 	  = $objectModel->getListableFields();
		$this->nameFields 	  = $objectModel->getHintFields();
		$this->nameController = $nameController;
		$this->formOperation  = array("save");
	}
	
	public function setFormOperation($operation)
	{
		$this->formOperation = $operation;
	}
	
	public function inject($mixedContent)
	{
		$this->content = $mixedContent;
	}

	public function assemble()
	{
		$content = $this->content->getRecords();
		$xhtml = array();
		if (count($this->content->getRecords()) == 0)
		{
			$xhtml[] = "No record found.";
		}
		else 
		{
			$xhtml[] = "<div id=\"" . $this->idName . "\" class=\"" . strtolower(get_class($this)) . "\">";
			foreach ($this->formFields as $field)
			{
				$xhtml[] = $this->assembleFormField($field);
			}
			$xhtml[] = "</div>";
		}
		$this->xhtml = implode("", $xhtml);
	}

	public function toXhtml()
	{
		if (strlen($this->xhtml) == 0)
		{
			$this->assemble();
		}
		return $this->xhtml;
	}

	public function getIdName()
	{
		return $this->idName;
	}
	
	private function assembleFormField()
	{
		
		$cellContent = "";
		if (is_array($this->masks) && isset($this->masks[$headCell]))
		{
			$mask = $this->masks[$headCell];
			$formatter = new $mask();
			$formatter->toScreen($record->{$headCell});
			$cellContent = $formatter->toListField();
		}
		else
		{
			$cellContent = $record->{$headCell};
		}
		$xhtml[] = "<td class=\"" . $headCell . "\">" . $cellContent . "</td>";
		
		$xhtml  = "<div class=\"sort\">";
		$xhtml .= "<span>Order by</span>";
		$xhtml .= "<select name=\"sort\">";
		foreach ($this->formFields as $field)
		{
			$selected    = (filter_input(INPUT_POST, "sort") == $field) ? "selected" : "";
			$xhtml .= "<option value=\"" . $field . "\" " . $selected . ">" . $this->nameFields[$field] . "</option>";
		}
		$xhtml .= "</select>";
		$xhtml .= "</div>";
		return $xhtml;
	}
	
}
?>
