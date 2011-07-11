<?php
/**
 * CleanGab Framework
 * Session.php
 * Date: 	2011-05-18
 * Author: 	Leopoldo Barreiro
 * 
 */

require_once("Entity.php");

class Session {

	public static function authenticate($username, $passwd) 
	{
		try 
		{
			if ($username == null || $passwd == null) 
			{
				throw new Exception("username or password invalid", "1");
			}
			$entity = new Entity("user");
			$entity->init();
			$entity->addArgument("user", $username, "=");
			$entity->addArgument("passwd", $passwd, "MD5");
			$rs = $entity->retrieve(CLEANGAB_SQL_VERIFY_LOGIN);
			Session::createIfNotExists();
			if (count($rs->getRecords()) == 0) 
			{
				$_SESSION["CLEANGAB"]["user"] = array();
				Session::addUIMessage("Login or password invalid.");
				throw new Exception("login fail", "2");
			}
			else 
			{
				Session::addUIMessage("Log in performed correctly");
				$record = $rs->get();
				$_SESSION["CLEANGAB"]["user"] = array();
				$_SESSION["CLEANGAB"]["user"]["started"] = true;
				$_SESSION["CLEANGAB"]["user"]["id"] = $record->id;
				$_SESSION["CLEANGAB"]["user"]["user"] = $record->user;
				$_SESSION["CLEANGAB"]["user"]["name"] = $record->name;
				$_SESSION["CLEANGAB"]["user"]["email"] = $record->email;
				$_SESSION["CLEANGAB"]["user"]["user"] = $record->user;
			}
			return (count($_SESSION["CLEANGAB"]["user"]) > 0); 
		} 
		catch (Exception $e) 
		{
			CleanGab::stackTraceDebug($e);
			return false;
		}
	}
	
	public static function logoff()
	{
		$_SESSION["CLEANGAB"] = array();
		$_SESSION["CLEANGAB"]["uimessages"] = array();
		Session::addUIMessage("Logoff performed correctly");
		header("Location: " . CLEANGAB_URL_BASE_APP . "/user/login");
	}
	
	public static function verify() 
	{
		$isValidSession = (isset($_SESSION) && isset($_SESSION['CLEANGAB']) && isset($_SESSION['CLEANGAB']['user']) && 
							is_array($_SESSION['CLEANGAB']['user']) && isset($_SESSION['CLEANGAB']['user']['name']) && 
							strlen($_SESSION['CLEANGAB']['user']['name']) > 0);
		CleanGab::debug("Session::verify");
		CleanGab::debug($_SESSION);
		if (!$isValidSession)
		{
			Session::addUIMessage("Session is not valid. Please, proceed to log in");
			CleanGab::debug("Session is not valid");
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
		if (!isset($_SESSION['CLEANGAB']['uimessages'])) 
		{
			$_SESSION['CLEANGAB']['uimessages'] = array();
		}
		if (!isset($_SESSION['CLEANGAB']['redir'])) 
		{
			$_SESSION['CLEANGAB']['redir'] = array();
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
	
	public static function addUIMessage($message)
	{
		Session::createIfNotExists();
		$msg = filter_var($message, FILTER_SANITIZE_STRING);
		$_SESSION['CLEANGAB']['uimessages'][] = (object) array('timestamp'=>time(), 'msg'=>$msg, 'read'=>false);
	}
	
	public static function getLastUIMessage()
	{
		Session::createIfNotExists();
		if (count($_SESSION['CLEANGAB']['uimessages']) == 0)
		{
			return false;
		}
		$lastMessage = $_SESSION['CLEANGAB']['uimessages'][count($_SESSION['CLEANGAB']['uimessages'])-1];
		$lastMessage->read = true;
		$_SESSION['CLEANGAB']['uimessages'][count($_SESSION['CLEANGAB']['uimessages'])-1] = $lastMessage;
		return $lastMessage;
	}
	
	public static function addRedir($controller, $action)
	{
		Session::createIfNotExists();
		$_SESSION["CLEANGAB"]["redir"][] = array("control"=>$controller, "action"=>$action);
	}
	
	public static function getLastRedir()
	{
		if (is_array($_SESSION["CLEANGAB"]["redir"]) && count($_SESSION["CLEANGAB"]["redir"]) > 0)
		{
			$lastRedir = $_SESSION["CLEANGAB"]["redir"][count($_SESSION["CLEANGAB"]["redir"])-1];
			return CLEANGAB_URL_BASE_APP . "/" . $lastRedir["control"] . "/" . $lastRedir["action"];
		}
		else 
		{
			return CLEANGAB_URL_BASE_APP . "/welcome.php";
		}
	}
}
?>