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
	}

	public function inject($mixedContent)
	{
		$this->content = $mixedContent;
	}

	public function ensamble()
	{
		$content = $this->content->getRecords();
		$xhtml = "";
		
		if ($this->content->getI() < 1)
		{
			$xhtml .= "No records found.";
		}
		else 
		{
			$xhtml .= $this->paginationToXhtml();
			$xhtml  .= "\n\t<table id='" . $this->idName . "' class='" . strtolower(get_class($this)) . "'>\n";
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
		$pages = ($totalRecords > 0 && $actualRecord > 0) ? $totalRecords / $recordsPerPage : 0;
		$actualPage = ($pages > 0) ? ($pages * $actualRecord) / $totalRecords : 0; 
		return floor($actualPage);
	}
	
	private function paginationToXhtml() 
	{
		$pg = $this->page;
		$tt = $this->pages;
		$xhtml  = array();
		$xhtml[] = "<div class='paginator'>";
		for ($i=1; $i<=$tt; ++$i)
		{
			$xhtml[] = ($i == $pg) ? "<b>" . $i . "</b>" : "<a href='" . $i . "'>" . $i . "</a>";
		}
		$xhtml[] = "</div>";
		return implode("&nbsp;", $xhtml);
	}
	
}
?>