<?php
/**
 * CleanGab Framework
 * TableList.php
 * Date: 	2011-01-XX
 * Author: 	Leopoldo Barreiro
 */

require_once ("XHTMLComponent.php");

class TableListBase implements XHTMLComponent {

	protected $idName;
	protected $content;
	protected $pages;
	protected $page;
	protected $header;
	protected $masks;
	protected $formFields;
	protected $renderForm;
	protected $nameFields;
	protected $nameController;
	protected $operations;
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
		
		$this->pages 		  = ceil($totalRecords / $recordsPerPage);
		$this->page  		  = $this->getActualPage($totalRecords, $recordsPerPage, $actualRecord);
		$this->formFields 	  = $objectModel->getListableFields();
		$this->renderForm 	  = true;
		$this->nameFields 	  = $objectModel->getHintFields();
		$this->nameController = $nameController;
		$this->operations     = array("show", "edit");
	}
	
	public function setOperations($arOperations)
	{
		$this->operations = $arOperations;
	}
	
	public function setFormFields($arFormFields)
	{
		$this->formFields = $arFormFields;
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
			$xhtml[] = "No records found.";
		}
		else 
		{
			$xhtml[] = $this->formActionToXhtml();
			$xhtml[] = $this->formNavigatorToXhtml();
			$xhtml[] = $this->paginationToXhtml();
			$xhtml[] = "<table id=\"" . $this->idName . "\" class=\"" . strtolower(get_class($this)) . "\">";
			$xhtml[] = "<tr>";
			foreach ($this->header as $headCell)
			{
				$xhtml[] = "<th>" . $headCell . "</th>";
			}
			$xhtml[] = "</tr>";
			$headers = array_keys($this->header);
			foreach ($content as $record)
			{
				$xhtml[] = "<tr class=\"records\" onclick=\"" . strtolower(get_class($this)) . $this->idName . "KeyActionForm(this,'" . $record->id . "')\">";
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
					$xhtml[] = "<td class=\"" . $headCell . "\">" . $cellContent . "</td>";
				}
				$xhtml[] = "</tr>";
			}
			$xhtml[] = "</table>";
			$xhtml[] = $this->paginationToXhtml();
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
	
	private function getActualPage($totalRecords, $recordsPerPage, $actualRecord) 
	{
		$actualPage = ($totalRecords > 0) ? ($actualRecord / $recordsPerPage) + 1 : 1;
		return floor($actualPage);
	}
	
	private function paginationToXhtml() 
	{
		$xhtml   = array();
		$xhtml[] = "<div class=\"" . strtolower(get_class($this)) . "Tools\">";
		$xhtml[] = "<div class=\"" . strtolower(get_class($this)) . "Actions\">";
		foreach ($this->operations as $operation)
		{
			$xhtml[] = "<a href=\"javascript:" . strtolower(get_class($this)) . $this->idName . "ActionForm('" . $operation . "')" . "\" class=\"" . $operation . "\">" . $operation . "</a>&nbsp;";
		}
		$xhtml[] = "</div>";
		
		$xhtml[] = "<div class=\"" . strtolower(get_class($this)) . "Paginator\">";
		
		if ($this->pages > 1)
		{
			$xhtml[] = "P&aacute;ginas: \n";
			if ($this->pages > 10)
			{
				$xhtml[] = "<select name=\"" . strtolower(get_class($this)) . "PageSelector\" onchange=\"" . strtolower(get_class($this)) . $this->idName . "FormPg(this.value)\">";
				for ($i=1; $i<=$this->pages;$i++)
				{
					$xhtml[] = ($i == $this->page) ? "<option value=\"" . $i . "\" selected>" . $i . "</option>" : "<option value=\"" . $i . "\">" . $i . "</option>";
				}
				$xhtml[] = "</select>";
			}
			else 
			{
				$xhtml[] = ($this->page != 1) ? "<a href=\"javascript:" . strtolower(get_class($this)) . $this->idName . "FormPg(1);\">&laquo;&laquo;</a>" : "<span>&laquo;&laquo;</span>";
				for ($i=1; $i<=$this->pages; ++$i)
				{
					$xhtml[] = ($i == $this->page) ? "<b>" . $i . "</b>" : "<a href=\"javascript:" . strtolower(get_class($this)) . $this->idName . "FormPg(" . $i . ");\">" . $i . "</a>";
				}
				$xhtml[] = ($this->page != $this->pages) ? "<a href=\"javascript:" . strtolower(get_class($this)) . $this->idName . "FormPg(" . $this->pages . ");\">&raquo;&raquo;</a>" : "<span>&raquo;&raquo;</span>";	
			}
		}
		$xhtml[] = "</div>";
		$xhtml[] = "</div>";
		return implode("&nbsp;", $xhtml);
	}
	
	private function formNavigatorToXhtml()
	{
		$formXhtml 	 = array();
		if ($this->renderForm)
		{
			$formName    = $this->idName . "Form";
			$formXhtml[] = "\n<div class=\"" . strtolower(get_class($this)) . "Form\"><form name=\"" . $formName . "\" id=\"" . $formName . "\" method=\"post\">";
			foreach ($this->formFields as $field)
			{
				$formXhtml[] = $this->assembleSearchFormField($field);
			}
			$formXhtml[] = $this->assembleSearchFormSortField();
			$formXhtml[] = "<input type=\"hidden\" name=\"pg\" value=\"" . $this->page . "\">";
			$formXhtml[] = "<input type=\"button\" value=\"Pesquisar\" onclick=\"this.form.pg.value=1;this.form.submit();\">";
			$formXhtml[] = "</form></div>\n";
			$formXhtml[] = "<script type=\"text/javascript\">function " . strtolower(get_class($this)) . $this->idName . "FormPg(id){document." . $formName . ".pg.value = id; document." . $formName . ".submit();}</script>";
		}
		return implode("", $formXhtml);
	}	
	
	private function assembleSearchFormField($fieldName)
	{
		$varField = (filter_input(INPUT_POST, $fieldName) != null) ? filter_input(INPUT_POST, $fieldName) : "";
		$xhtml    = "<div class=\"" . $fieldName . "\">";
		$xhtml   .= "<span>" .$this->nameFields[$fieldName] . "</span>";
		$xhtml   .= "<input type=\"text\" name=\"" . $fieldName . "\" value=\"" . $varField . "\">";
		$xhtml   .= "</div>";
		return $xhtml;
	}
	
	private function assembleSearchFormSortField()
	{
		$xhtml  = "<div class=\"sort\">";
		$xhtml .= "<span>Organizar por</span>";
		$xhtml .= "<select name=\"sort\">";
		$sortableFields = array_keys($this->nameFields);
		
		foreach ($sortableFields as $field)
		{
			$selected    = (filter_input(INPUT_POST, "sort") == $field) ? "selected" : "";
			$xhtml .= "<option value=\"" . $field . "\" " . $selected . ">" . $this->nameFields[$field] . "</option>";
		}
		$xhtml .= "</select>";
		$xhtml .= "</div>";
		return $xhtml;
	}
	
	private function formActionToXhtml()
	{
		$frm = array();
		$formName = $this->idName . "ActionForm";
		$frm[] = "<form name=\"" . $formName . "\" id=\"" . $formName . "\" method=\"post\" action=\"\">";
		$frm[] = "<input type=\"hidden\" name=\"key\" value=\"\">";
		$frm[] = "</form>";
		$frm[] = "<script type=\"text/javascript\">";
		$frm[] = "function " . strtolower(get_class($this)) . $this->idName . "ActionForm(action) {";
		$frm[] = "if (document." . $formName . ".key.value != '') {";
		$frm[] = "var url = '" . CLEANGAB_URL_BASE_APP . "/" . strtolower(str_replace("Controller", "", $this->nameController)) . "/'+action+'/'+document." . $formName . ".key.value;";
		$frm[] = "document." . $formName . ".action=url;";
		$frm[] = "document." . $formName . ".submit();";
		$frm[] = "}";
		$frm[] = "}";
		$frm[] = "function " . strtolower(get_class($this)) . $this->idName . "KeyActionForm(obj,key) {";
		$frm[] = "document." . $this->idName . "ActionForm" . ".key.value=key;";
		$frm[] = "$(\"tr\").removeClass('" . strtolower(get_class($this)) . "SelectedRecord');";
		$frm[] = "$(obj).addClass('" . strtolower(get_class($this)) . "SelectedRecord');";
		$frm[] = "$('." . strtolower(get_class($this)) . "Actions > a').addClass('active');";
		$frm[] = "}";
		$frm[] = "</script>";
		return implode("", $frm);
	}
	
	public function setRenderForm($render)
	{
		$this->renderForm = $render;
	}

}
?>