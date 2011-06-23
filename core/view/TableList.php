<?php
/**
 * Clean-Gab Framework
 * TableList.php
 * Date: 	2011-01-XX
 * Author: 	Leopoldo Barreiro
 */

include ("XHTMLComponent.php");

class TableList implements XHTMLComponent {

	protected $idName;
	protected $content;
	protected $pages;
	protected $page;
	protected $header;
	protected $masks;
	protected $formFields;
	protected $nameFields;
	protected $nameController;
	protected $operations;
	protected $linkableFields;
	protected $xhtml;

	public function __construct($idName, $nameController, $objectModel)
	{
		Validate::notNull($idName, "ID can not be null");
		$this->idName 		  = $idName;
		$this->header 		  = ($objectModel->getHintFields() != null) ? $objectModel->getHintFields() : array();
		$this->masks  		  = ($objectModel->getMasks() != null) ? $objectModel->getMasks() : array();
		
		$this->inject($objectModel->getRecordset());
		
		$totalRecords 		  = $objectModel->getEntity()->getTotal();
		$actualRecord 		  = ($objectModel->getEntity()->getOffset() > $totalRecords) ? $totalRecords : $objectModel->getEntity()->getOffset();
		$recordsPerPage 	  = $objectModel->getEntity()->getLimit();
		
		$this->pages 		  = floor($totalRecords / $recordsPerPage);
		$this->page  		  = $this->getActualPage($totalRecords, $recordsPerPage, $actualRecord);
		$this->formFields 	  = $objectModel->getListableFields();
		$this->nameFields 	  = $objectModel->getHintFields();
		$this->nameController = $nameController;
		$this->operations     = array("show", "edit");
		$this->linkableFields = array();
	}
	
	public function setOperations($arOperations)
	{
		$this->operations = $arOperations;
	}
	
	public function addLinkableFields($mixedFields)
	{
		if (is_array($mixedFields))
		{
			foreach ($mixedFields as $field)
			{
				if (!in_array($field, $this->linkableFields))
				{
					$this->linkableFields[] = $field;
				}
			}
		}
		else 
		{
			if (is_string($mixedFields))
			{
				$this->linkableFields[] = $mixedFields;
			}
		}
	}

	public function inject($mixedContent)
	{
		$this->content = $mixedContent;
	}

	public function ensamble()
	{
		$content = $this->content->getRecords();
		$xhtml = "";
		if (count($this->content->getRecords()) == 0)
		{
			$xhtml = "No records found.";
		}
		else 
		{
			$xhtml .= $this->formNavigatorToXhtml();
			$xhtml .= $this->paginationToXhtml();
			$xhtml .= "\n\t<table id=\"" . $this->idName . "\" class=\"" . strtolower(get_class($this)) . "\">\n";
			$xhtml .= "\t\t<tr>\n";
			foreach ($this->header as $headCell)
			{
				$xhtml .= "\t\t\t<th>" . $headCell . "</th>\n";
			}
			if (is_array($this->operations) && count($this->operations) > 0)
			{
				$xhtml .= "\t\t\t<th class=\"" . strtolower(get_class($this)) . "Operations\">Operations</th>\n";
			}
			$xhtml .= "\t\t</tr>\n";
			$headers = array_keys($this->header);
			foreach ($content as $record)
			{
				$xhtml .= "\t\t<tr>\n";
				foreach ($headers as $headCell)
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
					$xhtml .= "\t\t\t<td class=\"" . $headCell . "\">" . $cellContent . "</td>\n";
				}
				if (is_array($this->operations) && count($this->operations) > 0)
				{
					$xhtml .= "\t\t\t<td class=\"" . strtolower(get_class($this)) . "Operations\">";
					foreach ($this->operations as $operation)
					{
						$xhtml .= "<a href=\"" . $this->prepareActionLink($operation, $record->id) . "\" class=\"" . $operation . "\">" . $operation . "</a>&nbsp;";
					}
					$xhtml .= "</td>\n";
				}
				
				$xhtml .= "\t\t</tr>\n";
			}
			$xhtml .= "\t</table>\n";
			$xhtml .= $this->paginationToXhtml();
		}
		$this->xhtml = $xhtml;
	}

	public function toXhtml()
	{
		if (strlen($this->xhtml) == 0)
		{
			$this->ensamble();
		}
		return $this->xhtml;
	}

	public function getIdName()
	{
		return $this->idName;
	}
	
	private function getActualPage($totalRecords, $recordsPerPage, $actualRecord) 
	{
		$actualPage = ($totalRecords > 0) ? ($actualRecord / $recordsPerPage) + 1 : 1;
		return floor($actualPage);
	}
	
	private function paginationToXhtml() 
	{
		$xhtml   = array();
		$xhtml[] = "<div class=\"" . strtolower(get_class($this)) . "Paginator\">";
		if ($this->pages > 1)
		{
			if ($this->page != 1)
			{
				$xhtml[] = "<a href=\"javascript:" . strtolower(get_class($this)) . $this->idName . "FormPg(1);\">&laquo;&laquo;</a>";
			} 
			else 
			{
				$xhtml[] = "<span>&laquo;&laquo;</span>";
			}
			for ($i=1; $i<=$this->pages; ++$i)
			{
				$xhtml[] = ($i == $this->page) ? "<b>" . $i . "</b>" : "<a href=\"javascript:" . strtolower(get_class($this)) . $this->idName . "FormPg(" . $i . ");\">" . $i . "</a>";
			}
			if ($this->page != $this->pages)
			{
				$xhtml[] = "<a href=\"javascript:" . strtolower(get_class($this)) . $this->idName . "FormPg(" . $this->pages . ");\">&raquo;&raquo;</a>";
			}
			else 
			{
				$xhtml[] = "<span>&raquo;&raquo;</span>";
			}
		}
		$xhtml[] = "</div>";
		return implode("&nbsp;", $xhtml);
	}
	
	private function formNavigatorToXhtml()
	{
		$formName    = $this->idName . "Form";
		$formXhtml 	 = array();
		$formXhtml[] = "\n<div class=\"" . strtolower(get_class($this)) . "Form\"><form name=\"" . $formName . "\" id=\"" . $formName . "\" method=\"post\">";
		foreach ($this->formFields as $field)
		{
			$formXhtml[] = $this->ensambleSearchFormField($field);
		}
		$formXhtml[] = $this->ensambleSearchFormSortField();
		$formXhtml[] = "<input type=\"hidden\" name=\"pg\" value=\"" . $this->page . "\">";
		$formXhtml[] = "<input type=\"button\" value=\"ok\" onclick=\"this.form.pg.value=1;this.form.submit();\">";
		$formXhtml[] = "</form></div>\n";
		$formXhtml[] = "<script type=\"text/javascript\">function " . strtolower(get_class($this)) . $this->idName . "FormPg(id){document." . $formName . ".pg.value = id; document." . $formName . ".submit();}</script>";
		return implode("", $formXhtml);
	}
	
	private function ensambleSearchFormField($fieldName)
	{
		$varField = (filter_input(INPUT_POST, $fieldName) != null) ? filter_input(INPUT_POST, $fieldName) : "";
		$xhtml    = "<div class=\"" . $fieldName . "\">";
		$xhtml   .= "<span>" .$this->nameFields[$fieldName] . "</span>";
		$xhtml   .= "<input type=\"text\" name=\"" . $fieldName . "\" value=\"" . $varField . "\">";
		$xhtml   .= "</div>";
		return $xhtml;
	}
	
	private function ensambleSearchFormSortField()
	{
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
	
	private function prepareActionLink($action, $idKey)
	{
		$uriController = strtolower(str_replace("Controller", "", $this->nameController));
		return CLEANGAB_URL_BASE_APP . "/" . $uriController . "/" . strtolower($action) . "/" . $idKey;
	}

}
?>