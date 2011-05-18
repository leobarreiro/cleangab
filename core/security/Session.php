<?php
/**
 * Clean-Gab Framework
 * Session.php
 * Date: 	2011-05-18
 * Author: 	Leopoldo Barreiro
 * 
 */

class Session {

	private $user;
	private $name;
	private $email;
	private $initDate;
	private $permissions;
	
	public function __construct() {
		$this->user 		= "";
		$this->name 		= "";
		$this->email 		= "";
		$this->initDate 	= new DateTime();
		$this->permissions 	= array();
	}
	
	public function getUser() {
		return $this->user;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getPermissions() {
		return $this->permissions;
	}
	
	public function setUser($usr="") {
		$this->user = $usr;
	}
	
	public function setName($nam="") {
		$this->name = $nam;
	}
	
	public function setEmail($email="") {
		$this->email = $email;
	}
	
	public function setPermissions($perms) {
		$this->permissions = $perm;
	}
	
	public function setInitDate($dtIni) {
		$this->initDate = $dtIni;
	}
	
	public function hasPermission($key) {
		if (in_array(strtolower($key), $this->permissions)) {
			return true;
		}
		return false;
	}
	
}
?>