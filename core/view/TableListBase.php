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
	protected $controller;
	protected $operation;
	protected $operations;
	protected $xhtml;
	protected $view;

	public function __construct($idName, $view, $model)
	{
		Validation::notNull($idName, "ID can not be null");
		$this->idName 	  = $idName;

		$hintFieldsToList = array();
		$allHints 		  = $model->getHintFields();
		$hintsAvailable   = array_keys($allHints);
		$noListableFields = $model->getNoListableFields();
		
		foreach ($hintsAvailable as $field) 
		{
			if (!in_array($field, $noListableFields))
			{
				$hintFieldsToList[$field] = $allHints[$field];
			}
		}
		$this->header 	  = $hintFieldsToList;
		$this->masks  	  = ($model->getMasks() != null) ? $model->getMasks() : array();
		
		$this->inject($model->getRecordset());
		
		$totalRecords 	  = $model->getEntity()->getTotal();
		$actualRecord 	  = ($model->getEntity()->getOffset() > $totalRecords) ? $totalRecords : $model->getEntity()->getOffset();
		$recordsPerPage   = $model->getEntity()->getLimit();
		
		$this->pages 	  = ceil($totalRecords / $recordsPerPage);
		$this->page 	  = $this->getActualPage($totalRecords, $recordsPerPage, $actualRecord);
		$this->formFields = $model->getFields();
		$this->renderForm = true;
		$this->nameFields = $model->getHintFields();
		$this->view 	  = $view;
		$this->controller = $view->getController();
		$this->operation  = $view->getOperation();
		$this->operations = array("show", "edit");
		$view->addObject($this->getIdName(), $this);
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
						if (is_string($this->masks[$headCell]))
						{
							if (!class_exists($mask))
							{
								require_once ($mask . ".php");
							}
							$formatter = new $mask();
							$cellContent = $formatter->toScreen($record->{$headCell});
						}
						else if (is_object($this->masks[$headCell]) && ($this->masks[$headCell] instanceof Formatter))
						{
							$formatter = $this->masks[$headCell];
							$cellContent = $formatter->toScreen($record->{$headCell});
						}
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
		//$xhtml[] = "<div class=\"" . strtolower(get_class($this)) . "Actions\">";
		if (is_array($this->operations)) 
		{
			foreach ($this->operations as $operation)
			{
				//$id = strtolower(get_class($this)) . $this->idName . $operation;
				$id = $operation;
				$key = strtolower($operation . "_" . $this->controller);
				$uri = "javascript:" . strtolower(get_class($this)) . $this->idName . "ActionForm('" . $operation . "')";
				$tbBt = new ToolbarButton($id, $uri, $key, $operation);
				$tbBt->setControllerAction($this->controller, $this->operation);
				if (is_object($this->view->toolbar))
				{
					$this->view->toolbar->addButton($tbBt);
				}
				//$xhtml[] = "<a href=\"javascript:" . strtolower(get_class($this)) . $this->idName . "ActionForm('" . $operation . "')" . "\" class=\"" . $operation . "\">" . $operation . "</a>&nbsp;";
			}
		}
		//$xhtml[] = "</div>";
		$xhtml[] = "<div class=\"" . strtolower(get_class($this)) . "Paginator\">";
		if ($this->pages > 1)
		{
			$xhtml[] = "Go to Page: \n";
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
		$frm[] = "var url = '" . CLEANGAB_URL_BASE_APP . "/" . $this->controller . "/'+action+'/'+document." . $formName . ".key.value;";
		$frm[] = "document." . $formName . ".action=url;";
		$frm[] = "document." . $formName . ".submit();";
		$frm[] = "}";
		$frm[] = "}";
		$frm[] = "function " . strtolower(get_class($this)) . $this->idName . "KeyActionForm(obj,key) {";
		$frm[] = "document." . $this->idName . "ActionForm" . ".key.value=key;";
		$frm[] = "jQuery(\"tr\").removeClass('" . strtolower(get_class($this)) . "SelectedRecord');";
		$frm[] = "jQuery(obj).addClass('" . strtolower(get_class($this)) . "SelectedRecord');";
		//$frm[] = "$('." . strtolower(get_class($this)) . "Actions > a').addClass('active');";
		$frm[] = "jQuery('#toolbar-buttons > a').add('#toolbar-buttons > input').addClass('active');";
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
