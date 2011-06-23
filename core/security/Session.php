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

	public static function login($username, $passwd) 
	{
		try 
		{
			if ($username == null || $passwd == null) 
			{
				throw new Exception("username or password invalid", "1");
			}
			$entity = new Entity("user");
			$entity->init();
			$entity->addArgument("user", $username);
			$entity->addArgument("passwd", $passwd, "MD5");
			$rs = $entity->retrieve(CLEANGAB_SQL_VERIFY_LOGIN);
			Session::createIfNotExists();
			if (count($rs->getRecords()) == 0) 
			{
				$_SESSION["CLEANGAB"]["user"] = array();
				throw new Exception("login fail", "2");
			} 
			else 
			{
				
				foreach ($rs->get() as $key=>$value) 
				{
					$_SESSION["CLEANGAB"]["user"][$key] = $value;
				}
			}
			return (count($_SESSION["CLEANGAB"]["user"]) > 0); 
		} 
		catch (Exception $e) 
		{
			CleanGab::stackTraceDebug($e);
			return false;
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
		if (!isset($_SESSION["CLEANGAB"]["user"])) 
		{
			$_SESSION["CLEANGAB"]["user"] = array();
		}
		if (!isset($_SESSION["CLEANGAB"]["objects"]))
		{
			$_SESSION["CLEANGAB"]["objects"] = array();
		}
		if (!isset($_SESSION['CLEANGAB']['xhtmlComponents'])) 
		{
			$_SESSION['CLEANGAB']['xhtmlComponents'] = array();
		}
	}
	
	public function addToSession($component) 
	{
		if (is_object($component) && $component instanceof XHTMLComponent) 
		{
			Session::createIfNotExists();
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