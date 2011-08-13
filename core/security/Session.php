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

	public static function authenticate($entity, $sql=CLEANGAB_SQL_VERIFY_LOGIN) 
	{
		Session::createIfNotExists();
		$rs = $entity->retrieve($sql);
		if ($rs->hasRecords()) 
		{
			$_SESSION["CLEANGAB"]["user"] = (array) $rs->getRecord();
		}
		else 
		{
			$_SESSION["CLEANGAB"]["user"] = array();
		}
		return $rs->hasRecords();
	}
	
	/**
	 * logoff()
	 * Limpa a Sessao
	 * Utiliza o ultimo redir ao final da operacao
	 * Modo de utilizar:
	 * 
	 * Session::addRedir($controller, $action);
	 * Session::logoff();
	 */
	public static function logoff()
	{
		$_SESSION["CLEANGAB"] = array();
		$_SESSION["CLEANGAB"]["uimessages"] = array();
		Session::addUIMessage("Logoff performed correctly");
		header("Location: " . Session::getLastRedir());
		die();
	}
	
	public static function verify() 
	{
		$isValidSession = (isset($_SESSION) && isset($_SESSION['CLEANGAB']) && isset($_SESSION['CLEANGAB']['user']) && 
							is_array($_SESSION['CLEANGAB']['user']) && count($_SESSION['CLEANGAB']['user']) > 0);
		if (!$isValidSession)
		{
			Session::addUIMessage("Session is not valid. Please, proceed to log in");
			Session::goToRedir();
		}
	}
	
	public static function getUser() 
	{
		return (object) $_SESSION['CLEANGAB']['user'];
	}
	
	public static function getPermissions() 
	{
		return $this->permissions;
	}
	
	public static function setPermissions($perms) 
	{
		$this->permissions = $perm;
	}
	
	public static function hasPermission($key) 
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
		if (!isset($_SESSION['CLEANGAB']['uimessages'])) 
		{
			$_SESSION['CLEANGAB']['uimessages'] = array();
		}
		if (!isset($_SESSION['CLEANGAB']['redir'])) 
		{
			$_SESSION['CLEANGAB']['redir'] = array();
		}
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
		if ($lastMessage->read == true)
		{
			return false;
		}
		else 
		{
			$lastMessage->read = true;
			$_SESSION['CLEANGAB']['uimessages'][count($_SESSION['CLEANGAB']['uimessages'])-1] = $lastMessage;
			return $lastMessage;
		}
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
	
	public static function goToRedir()
	{
		if (is_array($_SESSION["CLEANGAB"]["redir"]) && count($_SESSION["CLEANGAB"]["redir"]) > 0)
		{
			$lastRedir = $_SESSION["CLEANGAB"]["redir"][count($_SESSION["CLEANGAB"]["redir"])-1];
			header("Location: " . CLEANGAB_URL_BASE_APP . "/" . $lastRedir["control"] . "/" . $lastRedir["action"]);
			die();
		}
		else 
		{
			header ("Location: " . CLEANGAB_URL_BASE_APP . "/welcome.php");
			die();
		}
	}
}
?>