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
		while ($record = $this->result->fetch_object()) {
			$item = array();
			foreach ($record as $property => $value) {
				$item[$property] = $value;
			}
			$this->records[] = $item;
		}
		$this->i = (count($this->records)-1);
	}
	
	public function getRecords() {
		return $this->records;
	}
}
?>