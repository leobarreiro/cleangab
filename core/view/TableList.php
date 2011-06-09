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
	
	public function __construct($idName, $objectModel) {
		Validate::notNull($idName, "ID can not be null");
		$this->idName = $idName;
		if ($objectModel->getHintFields() != null) {
			$this->header = $objectModel->getHintFields();
		}
		if ($objectModel->getMasks() != null) {
			$this->masks = $objectModel->getMasks();
		}
		if ($objectModel->getRecordset()) {
			$this->inject($objectModel->getRecordset());
		}
	}

	public function inject($mixedContent) {
		$this->content = $mixedContent;	
	}
	
	public function ensamble() 
	{
		$content = $this->content->getRecords();
		$xhtml  = "\n\t<table id='" . $this->idName . "' class='" . strtolower(get_class($this)) . "'>\n";
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
		$xhtml .= "\t</table>\n\n";
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
	
}
?>