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
	
	// dados obtidos do banco de dados
	// formato: object recordset
	protected $recordset;
	
	// argumentos usados para pesquisar e/ou manipular registros no banco de dados
	// formato array: nome_campo=>valor_do_input
	public $argumentData;
	
	
	public function __construct() {
		$this->hintFields = array();
		$this->listableFields = array();
		$this->masks = array();
		$this->entity = null;
		$this->recordset = null;
	}
	
	public function getHintFields() {
		return $this->hintFields;
	}
	
	public function setRecordset($recordset) {
		$this->recordset = $recordset;
	}
	
	public function getRecordset() {
		return $this->recordset;
	}
	
	public function getMasks() {
		return $this->masks;
	}
	
	public function addArgumentData($key, $value) {
		if (isset($key) && is_string($key) && isset($value)) {
			$this->argumentData[$key] = $value;
			return true;
		}
		return false;
	}
	
	public function getArgumentData($key) {
		if (isset($this->argumentData[$key])) {
			return $this->argumentData[$key];
		} else {
			return false;
		}
	}
	
	// TODO: override
	public function prepareList() {}
	
	// TODO: override
	public function prepareSave() {}

}
?>