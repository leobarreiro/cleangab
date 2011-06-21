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

	public static function login() 
	{
		$username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
		$passwd   = filter_input(INPUT_POST, "passwd", FILTER_SANITIZE_STRING);
		try 
		{
			if ($username == null || $passwd == null) 
			{
				throw new Exception("username or password invalid", "1");
			}
			$entity = new Entity("usuario");
			$entity->addArgs(array("username"=>$username, "password"=>$passwd));
			$rs = $entity->retrieve(CLEANGAB_SQL_VERIFY_LOGIN);
			if (count($rs->getRecords()) == 0) 
			{
				throw new Exception("login fail", "2");
			} 
			else 
			{
				if (!isset($_SESSION)) 
				{
					session_start();
				}
				if (!isset($_SESSION['CLEANGAB'])) 
				{
					$_SESSION["CLEANGAB"] = array();
				}
				foreach ($rs->get() as $key=>$value) 
				{
					$_SESSION['CLEANGAB'][$key] = $value;
				}
			}
		} 
		catch (Exception $e) 
		{
			CleanGab::stackTraceDebug($e);
		}
	}
	
	public static function verify() 
	{
		$isValidSession = (isset($_SESSION) && isset($_SESSION['CLEANGAB']) && isset($_SESSION['CLEANGAB']['user']) && strlen($_SESSION['CLEANGAB']['user'] > 0));
		if (!$isValidSession) 
		{
			header("Location: " . CLEANGAB_URL_BASE_APP . "/user/login");
		}
	}
	
	public function getUser() 
	{
		return $_SESSION['CLEANGAB']['user'];
	}
	
	public function getName() 
	{
		return $_SESSION['CLEANGAB']['name'];
	}
	
	public function getPermissions() 
	{
		return $this->permissions;
	}
	
	public function setUser($usr="") 
	{
		$this->user = $usr;
	}
	
	public function setName($nam="") 
	{
		$this->name = $nam;
	}
	
	public function setEmail($email="") 
	{
		$this->email = $email;
	}
	
	public function setPermissions($perms) 
	{
		$this->permissions = $perm;
	}
	
	public function setInitDate($dtIni) 
	{
		$this->initDate = $dtIni;
	}
	
	public function hasPermission($key) 
	{
		if (in_array(strtolower($key), $this->permissions)) 
		{
			return true;
		}
		return false;
	}
	
	private function createIfNotExists() 
	{
		if (!isset($_SESSION)) 
		{
			session_start();
		}
		if (!isset($_SESSION["CLEANGAB"])) 
		{
			$_SESSION["CLEANGAB"] = array();
		}
		if (!isset($_SESSION["CLEANGAB"]["objects"]))
		{
			$_SESSION["CLEANGAB"]["objects"] = array();
		}
	}
	
	private function prepareComponentsHost() 
	{
		$this->createIfNotExists();
		if (!isset($_SESSION['CLEANGAB']['xhtmlComponents'])) 
		{
			$_SESSION['CLEANGAB']['xhtmlComponents'] = array();
		}
	}
	
	public function addToSession($component) 
	{
		if (is_object($component) && $component instanceof XHTMLComponent) 
		{
			$this->prepareComponentsHost();
			$_SESSION["CLEANGAB"]["xhtmlComponents"][$component->getIdName()] = serialize($component);
			return true;
		}
		return false;
	}
	
	public static function hostObject($uniqueName, $mixedObject)
	{
		Session::createIfNotExists();
		$_SESSION["CLEANGAB"]["objects"][$uniqueName] = $mixedObject;
	}
	
	public static function getHostedObject($uniqueName)
	{
		return (isset($_SESSION["CLEANGAB"]["objects"][$uniqueName])) ? $_SESSION["CLEANGAB"]["objects"][$uniqueName] : false; 
	}
	
}
?>