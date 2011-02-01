<?php
/**
 * Clean-Gab Framework
 * TableList.php
 * Date: 	2011-01-XX
 * Author: 	Leopoldo Barreiro
 */

include ("XHTMLComponentInterface.php");

class TableList implements XHTMLComponent {
	
	protected $content;
	protected $header;
	protected $masks;
	protected $xhtml;
	
	public function inject($mixedContent) {
		$this->content = $mixedContent;	
	}
	
	public function ensamble() {
		$content = $this->content->getRecords();
		
		$xhtml  = "<table border='1' cellpadding='2'>";

		if (is_array($this->header) && count($this->header) > 0) {
			$xhtml .= "<tr>";
			foreach ($this->header as $headCell) {
				$xhtml .= "<th>" . $headCell . "</th>";
			}
			$xhtml .= "</tr>";
			$headers = array_keys($this->header);
			foreach ($content as $record) {
				$xhtml .= "<tr>";
				foreach ($headers as $headCell) {
					if (is_array($this->masks) && isset($this->masks[$headCell])) {
						$mask = $this->masks[$headCell];
						$formatter = new $mask();
						$xhtml .= "<td>" . $formatter->toScreen($record[$headCell]) . "</td>";
					} else {
						$xhtml .= "<td>" . $record[$headCell] . "</td>";
					}
				}
				$xhtml .= "</tr>";
			}
		} 
		else {
			foreach ($content as $record) {
				$xhtml .= "<tr>";
				foreach ($record as $cell) {
					$xhtml .= "<td>" . $cell . "</td>";
				}
				$xhtml .= "</tr>";
			}			
		}

		$xhtml .= "</table>";
		$this->xhtml = $xhtml;
	}
	
	public function renderize() {
		if (strlen($this->xhtml) == 0) {
			$this->ensamble();
		}
		echo $this->xhtml;
	}
	
	public function __construct($objectModel) {
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
	
}
?>