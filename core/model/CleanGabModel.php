<?php
/**
 * Clean-Gab Framework
 * CleanGabModel.php
 * Date: 	2011-01-XX
 * Author: 	Leopoldo Barreiro
 */

include_once ("Entity.php");
include_once ("Recordset.php");

class CleanGabModel {
	
	// Entity
	protected $entity;
	
	// Descrições dos campos que irão em cabeçalhos de table list, por exemplo
	// formato array: nome_campo=>descrição
	protected $hintFields;
	
	// campos que devem ser exibidos em uma tablelist
	// formato array: nome_campo1, nome_campo2...
	protected $listableFields;
	
	// mascaras que devem ser aplicadas por campo
	// formato array: nome_campo=>nome_classe_formatter
	protected $masks;
	
	protected $sqlListAll;
	
	protected $recordset;
	
	public function __construct() {
		$this->hintFields = array();
		$this->listableFields = array();
		$this->masks = array();
		$this->entity = null;
		$this->recordset = null;
		$this->sqlListAll = null;
	}
	
	public function retrieveAll() {
		
		if ($this->sqlListAll != null) {
			$this->recordset = $this->entity->retrieve($this->sqlListAll);
		} else {
			$this->recordset = false;
		}
	}
	
	public function getHintFields() {
		return $this->hintFields;
	}
	
	public function getRecordset() {
		return $this->recordset;
	}
	
	public function getMasks() {
		return $this->masks;
	}
	
}
?>