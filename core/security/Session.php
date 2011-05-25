<?php
/**
 * Clean-Gab Framework
 * Session.php
 * Date: 	2011-05-18
 * Author: 	Leopoldo Barreiro
 * 
 */

include_once("Entity.php");

class Session {

	private $user;
	private $name;
	private $email;
	private $initDate;
	private $permissions;
	
	public function __construct() {
		
		if (!isset($_SESSION)) {
			session_start();
		}
		$_SESSION['cleangab'] = array();
		$_SESSION['cleangab']['user'] = "";
		$this->user 		= "";
		$this->name 		= "";
		$this->email 		= "";
		$this->initDate 	= new DateTime();
		$this->permissions 	= array();
	}

	public static function login() {
		$username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
		$passwd   = filter_input(INPUT_POST, "passwd", FILTER_SANITIZE_STRING);
		if ($username == null || $passwd == null) {
			throw new Exception("username or password invalid", "1");
		}
		$entity = new Entity("usuario");
		$entity->addArgs(array("username"=>$username, "password"=>$passwd));
		$rs = $entity->retrieve(CLEANGAB_SQL_VERIFY_LOGIN);
		
		CleanGab::debug($rs);
		
		//throw new Exception("login fail", "1");
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