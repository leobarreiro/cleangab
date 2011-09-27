<?php
/**
 * CleanGab Framework
 * Session.php
 * Date: 	2011-05-18
 * Author: 	Leopoldo Barreiro
 *
 */
require_once ("Entity.php");
require_once ("PermissionModel.php");
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

	public static function authenticate_ldap($user, $password)
	{
		Session::createIfNotExists();
		$ad = @ldap_connect(CLEANGAB_AD_HOST);
		if ($ad)
		{
			$adUser = CLEANGAB_AD_DOMAIN . "\\" . $user;
			$auth = @ldap_bind($ad, $adUser, $password);
			if ($auth)
			{
				$entity = new Entity("user");
				$entity->init();
				$entity->addArgument("user", $user, "=");
				$rs = $entity->retrieve();
				if ($rs->hasRecords())
				{
					$_SESSION["CLEANGAB"]["user"] = (array) $rs->getRecord();
				}
				else
				{
					$_SESSION["CLEANGAB"]["user"] = array("user"=>$user);
					Session::addUIMessage("You don't have a user record in the System. Please contact your manager to grant permissions for you");
					CleanGab::log("The user `" . $user . "` was logged in MS-AD but not registered in the database system.");
				}
			}
			return $auth;
		}
		else
		{
			return false;
		}
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
		$_SESSION["CLEANGAB"]["user"] 			= array();
		$_SESSION["CLEANGAB"]["uimessages"] 	= array();
		$_SESSION["CLEANGAB"]["toolbarButtons"] = array();
		$_SESSION["CLEANGAB"]["xmlmenu"] 		= null;
		//$_SESSION["CLEANGAB"]["redir"] 		= array();
		Session::addUIMessage("Logoff performed correctly");
		Session::goToRedir();
	}

	public static function verify()
	{
		if (!Session::isUserLogged())
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
		if (in_array(strtolower($key), $_SESSION["CLEANGAB"]["user"]["permissions"]))
		{
			return true;
		}
		else
		{
			Session::addUIMessage("You don't have permissions to access this content. Please, proceed to log in");
			CleanGab::log("The user `" . $_SESSION["CLEANGAB"]["user"]["user"] . "` tried to access the permission `" . $key . "`");
			return false;
		}
	}

	public static function permissionExists($key)
	{
		return (in_array(strtolower($key), $_SESSION["CLEANGAB"]["user"]["permissions"]));
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
		if (!isset($_SESSION["CLEANGAB"]["xmlmenu"]))
		{
			$_SESSION["CLEANGAB"]["xmlmenu"] = null;
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

	public static function addUIMessage($message, $type="msginfo")
	{
		Session::createIfNotExists();
		$msg = filter_var($message, FILTER_SANITIZE_STRING);
		$_SESSION['CLEANGAB']['uimessages'][] = (object) array('timestamp'=>time(), 'msg'=>$msg, 'type'=>$type, 'read'=>false);
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

	public static function goBack()
	{
		if (isset($_SERVER["HTTP_REFERER"]))
		{
			header("Location: " . $_SERVER["HTTP_REFERER"]);
		}
		die();
	}

	public static function loadPermissions()
	{
		$model = new PermissionModel();
		$recordSet = $model->loadPermissions();
		$permissions = array();
		while ($recordSet->hasNext())
		{
			$obPermission = $recordSet->getRecord();
			$permissions[] = strtolower($obPermission->permission);
		}
		$_SESSION["CLEANGAB"]["user"]["permissions"] = $permissions;
		return $permissions;
	}

	public static function getParameter($parameterName)
	{
		$model = new ParameterModel();
		$param = $model->getParameter($parameterName);
		switch (strtolower($param->type))
		{
			case "integer":
				$value = intval($param->value);
				break;
					
			case "float":
				$value = floatval($param->value);
				break;

			default:
				$value = $param->value;
				break;
		}
		return $value;
	}

	public static function getWPOption($optionName)
	{
		$entity = new Entity("wp_options");
		$entity->init();
		$entity->setCountThis(false);
		$entity->setLimit(1);
		$entity->addArgument("option_name", $optionName, "=");
		$recordset = $entity->retrieve();
		if ($recordset->hasRecords())
		{
			$record = $recordset->getRecord();
			return unserialize($record->option_value);
		}
		else
		{
			return false;
		}
	}

	public static function isUserLogged() {
		$isValidSession = (isset($_SESSION) && isset($_SESSION['CLEANGAB']) && isset($_SESSION['CLEANGAB']['user']) &&
		is_array($_SESSION['CLEANGAB']['user']) && count($_SESSION['CLEANGAB']['user']) > 0);
		return $isValidSession;
	}
	
	public static function loadXmlPermissions() {
		if (file_exists(CLEANGAB_PATH_BASE_APP . DIRECTORY_SEPARATOR . "security" . DIRECTORY_SEPARATOR . "permissions.xml"))
		{
			$xmlFile = CLEANGAB_PATH_BASE_APP . DIRECTORY_SEPARATOR . "security" . DIRECTORY_SEPARATOR . "permissions.xml";
		}
		else 
		{
			$xmlFile = CLEANGAB_FWK_SECURITY . DIRECTORY_SEPARATOR . "permissions.xml";
		}
		$_SESSION["CLEANGAB"]["xmlmenu"] = file_get_contents($xmlFile);
	}
	
	public static function getUriByPermission($key) 
	{
		$xml = simplexml_load_string($_SESSION["CLEANGAB"]["xmlmenu"]);
		foreach ($xml as $module) 
		{
			foreach ($module->permission as $prm) 
			{
				if ($prm["key"] == $key) 
				{
					return $prm["uri"];
				}
			}
		}
		return false;
	}
	
}
?>