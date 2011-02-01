<?php
/**
 * Clean-Gab Framework
 * Recordset.php
 * Date: 	2011-01-XX
 * Author: 	Leopoldo Barreiro
 */

class Recordset {

	private $result;
	private $records;
	private $i;

	function __construct($result) {
		$this->records = array();
		$this->setResult($result);
		$this->ensamble();
	}
	
	function hasNext() {
		return ($i < (count($this->records)-1));
	}

	function goFirst() {
		$this->i = 0;
	}
	
	function goEnd() {
		$this->i = (count($this->records)-1);
	}
	
	function setResult($result) {
		$this->result = $result;
	}
	
	function ensamble() {
		if (is_resource($this->result)) {
			$x = 0;
			while ($record = mysql_fetch_assoc($this->result)) {
				$this->records[] = $record;
				$x++;
			}
			$this->i = (count($this->records)-1);
		}
	}
	
	public function getRecords() {
		return $this->records;
	}
	
}
?>