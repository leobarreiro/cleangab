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
	protected $xhtml;

	public function __construct($idName, $objectModel)
	{
		Validate::notNull($idName, "ID can not be null");
		$this->idName = $idName;
		if ($objectModel->getHintFields() != null)
		{
			$this->header = $objectModel->getHintFields();
		}
		if ($objectModel->getMasks() != null)
		{
			$this->masks = $objectModel->getMasks();
		}
		if ($objectModel->getRecordset())
		{
			$this->inject($objectModel->getRecordset());
		}
		
		$totalRecords   = $objectModel->getEntity()->getTotal();
		$actualRecord   = $objectModel->getEntity()->getOffset();
		if ($actualRecord > $totalRecords) 
		{
			$actualRecord = $totalRecords;
		}
		$recordsPerPage = $objectModel->getEntity()->getLimit();
		
		$this->pages = floor($totalRecords / $recordsPerPage);
		$this->page  = $this->getActualPage($totalRecords, $recordsPerPage, $actualRecord);
		$this->formFields = $objectModel->getListableFields();
		$this->nameFields = $objectModel->getHintFields();
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
			$xhtml .= "No records found.";
		}
		else 
		{
			$xhtml .= $this->formNavigatorToXhtml();
			$xhtml .= $this->paginationToXhtml();
			$xhtml .= "\n\t<table id=\"" . $this->idName . "\" class=\"" . strtolower(get_class($this)) . "\">\n";
			if (is_array($this->header) && count($this->header) > 0)
			{
				$xhtml .= "\t\t<tr>\n";
				foreach ($this->header as $headCell)
				{
					$xhtml .= "\t\t\t<th>" . $headCell . "</th>\n";
				}
				$xhtml .= "\t\t</tr>\n";
				$headers = array_keys($this->header);
				foreach ($content as $record)
				{
					$xhtml .= "\t\t<tr>\n";
					foreach ($headers as $headCell)
					{
						if (is_array($this->masks) && isset($this->masks[$headCell]))
						{
							$mask = $this->masks[$headCell];
							$formatter = new $mask();
							$formatter->toScreen($record[$headCell]);
							$xhtml .= "\t\t\t<td>" . $formatter->toListField() . "</td>\n";
	
						}
						else
						{
							$xhtml .= "\t\t\t<td>" . $record[$headCell] . "</td>\n";
						}
					}
					$xhtml .= "\t\t</tr>\n";
				}
			}
			else
			{
				foreach ($content as $record)
				{
					$xhtml .= "\t\t<tr>\n";
					foreach ($record as $cell)
					{
						$xhtml .= "\t\t\t<td>" . $cell . "</td>\n";
					}
					$xhtml .= "\t\t</tr>\n";
				}
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
		$xhtml  = array();
		$xhtml[] = "<div class=\"" . strtolower(get_class($this)) . "Paginator\">";
		$xhtml[] = "<a href=\"javascript:" . strtolower(get_class($this)) . $this->idName . "FormPg(1);\">&laquo;&laquo;</a>";
		for ($i=1; $i<=$this->pages; ++$i)
		{
			$xhtml[] = ($i == $this->page) ? "<b>" . $i . "</b>" : "<a href=\"javascript:" . strtolower(get_class($this)) . $this->idName . "FormPg(" . $i . ");\">" . $i . "</a>";
		}
		$xhtml[] = "<a href=\"javascript:" . strtolower(get_class($this)) . $this->idName . "FormPg(" . $this->pages . ");\">&raquo;&raquo;</a>";
		$xhtml[] = "</div>";
		return implode("&nbsp;", $xhtml);
	}
	
	private function formNavigatorToXhtml()
	{
		$formName = $this->idName . "Form";
		$formXhtml  = "\n<div class=\"" . strtolower(get_class($this)) . "Form\"><form name=\"" . $formName . "\" id=\"" . $formName . "\" method=\"post\">";
		foreach ($this->formFields as $field)
		{
			$varField = (filter_input(INPUT_POST, $field) != null) ? filter_input(INPUT_POST, $field) : "";
			$formXhtml .= "<input type=\"text\" class=\"\" name=\"" . $field ."\" value=\"" . $varField . "\">";
		}
		$formXhtml .= "<select name=\"sort\">";
		foreach ($this->formFields as $field)
		{
			$selected = (filter_input(INPUT_POST, "sort") == $field) ? "selected" : "";
			$formXhtml .= "<option value=\"" . $field . "\" " . $selected . ">" . $this->nameFields[$field] . "</option>";
		}
		$formXhtml .= "</select>";
		$formXhtml .= "<input type=\"hidden\" name=\"pg\" value=\"" . $this->page . "\">";
		$formXhtml .= "<input type=\"submit\" value=\"ok\">";
		$formXhtml .= "</form></div>\n";
		$formXhtml .= "<script type=\"text/javascript\">function " . strtolower(get_class($this)) . $this->idName . "FormPg(id){document." . $formName . ".pg.value = id; document." . $formName . ".submit();}</script>";
		return $formXhtml;
	}

}
?>