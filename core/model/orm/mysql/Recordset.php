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
		$this->i = 0;
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
	
	public function get($p=null) {
		if (count($this->records) == 0) {
			throw new OutOfRangeException("Out of range exception");
			return false;
		}
		if ($p != null) {
			if ($p < count($this->records)) {
				return $this->records[$p];
			}
			else {
				throw new OutOfRangeException("Out of range exception");
				return false;
			} 
		}
		return $this->records[$this->i];
	}
	
	function ensamble() {
		$this->records = array();
		$this->i = 0;
		if (!$this->result) {
			throw new Exception("recordset dont have a valid resultset", "4");
		}
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