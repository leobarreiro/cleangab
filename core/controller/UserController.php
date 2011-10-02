<?php
require_once ("CleanGabController.php");
require_once ("UserModel.php");
require_once ("TableListBase.php");
require_once ("DateTimeFormatter.php");
require_once ("UIMessageBase.php");
require_once ("TinyIntFormatter.php");
require_once ("SelectInput.php");

class UserController extends CleanGabController {

	public function index() 
	{
		Session::addRedir("user", "login");
		Session::verify();
		Session::hasPermission("user_list");
		$model = new UserModel();
		// Arguments
		$model->addArgumentData("name", $this->getUserInput("name"));
		$model->addArgumentData("user", $this->getUserInput("user"));
		$model->addArgumentData("email", $this->getUserInput("email"));
		// Sort
		$model->addArgumentData("sort", $this->getUserInput("sort"));
		// Pagination
		$model->addArgumentData("pg", $this->getUserInput("pg"));
		// Prepare List
		$model->prepareList();
		// View
		$view = new CleanGabEngineView("user", "index");
		$view->toolbar->addButton(new ToolbarButton("add", CLEANGAB_URL_BASE_APP . "/user/add", "user_add", "adduser active"));
		$view->addObject("uimessage", new UIMessageBase("uimessage", Session::getLastUIMessage()));
		$tableUsers = new TableListBase("users", $view, $model);
		$tableUsers->setFormFields(array("user", "email", "name"));
		//$view->addObject($tableUsers->getIdName(), $tableUsers);
		$view->renderize();
	}

	public function add() 
	{
		Session::verify();
		Session::hasPermission("user_add");
		$this->loadUserPage(null, false);
	}

	public function login()
	{
		$lastMessage = new UIMessageBase("uimessage", Session::getLastUIMessage());
		$view = new CleanGabEngineView("User", "login");
		$view->addObject("uimessage", $lastMessage);
		$view->renderize();
	}
	
	public function logoff()
	{
		Session::addRedir("user", "login");
		Session::logoff();
	}
	
	public function authenticate()
	{
		$user = $this->getUserInput("userlogin");
		$passwd = $this->getUserInput("passwdlogin");
		
		if (strtoupper(CLEANGAB_AUTH_METHOD) == "AD")
		{
			$auth = Session::authenticate_ldap($user, $passwd);
		}
		else 
		{
			$model = new UserModel();
			$model->addArgumentData("user", $user);
			$model->addArgumentData("passwd", $passwd);
			$auth = $model->authenticate();
		}

		if ($auth)
		{
			Session::loadPermissions();
			Session::loadXmlPermissions();
			Session::addUIMessage("Log in performed correctly");
			$user = (object) $_SESSION['CLEANGAB']['user'];
			$uriPermission = Session::getUriByPermission($user->first_page);
			$uri = array();
			if (strpos($uriPermission, "/") !== false)
			{
				$uriParts = explode("/", $uriPermission);
				foreach ($uriParts as $part)
				{
					if (strlen($part) > 0)
					{
						$uri[] = strtolower($part);
					}
				}
				Session::addRedir($uri[0], $uri[1]);
			} 
			else 
			{
				Session::addRedir("user", "option");
			}
			Session::goToRedir();
		}
		else
		{
			Session::addUIMessage("User or password invalid", "msgerror");
			Session::goBack();
		}
	}

	public function show($key) 
	{
		Session::addRedir("user", "login");
		Session::verify();
		Session::hasPermission("user_show");
		$this->loadUserPage($key, true);
	}
	
	public function edit($key)
	{
		Session::addRedir("user", "login");
		Session::verify();
		Session::hasPermission("user_edit");
		
		$this->loadUserPage($key, false);
	}
	
	private function loadUserPage($userId=null, $isReadonly=true) 
	{
		$model = new UserModel();
		$user = ($userId == null) ? $model->createEmptyObject() : $model->getUserById($userId);
		$arOpt = array("0"=>"N&atilde;o", "1"=>"Sim");
		$tinyActive = new TinyIntFormatter();
		$tinyActive->setOptions($arOpt);
		$user->activeoptions = $tinyActive->toFormField("active", "active", $user->active);
		
		$tinyRenew = new TinyIntFormatter();
		$tinyRenew->setOptions($arOpt);
		$user->renewoptions = $tinyRenew->toFormField("renew", "renew", $user->renew_passwd);
		if ($userId == null)
		{
			$user->uuid    = uniqid();
			$user->created = date("d/m/Y H:i:s");
		}
		$xhtml = $this->listPermissions($userId, false);
		// View
		$view = new CleanGabEngineView("User", "edit");
		if (!$isReadonly)
		{
			$view->toolbar->addButton(new ToolbarButton("save", "javascript:document.show.submit()", "user_edit", "save active"));
		}
		$view->toolbar->addButton(new ToolbarButton("back", CLEANGAB_URL_BASE_APP . "/user/index", "user_list", "back active"));
		$view->addObject("uimessage", new UIMessageBase("uimessage", Session::getLastUIMessage()));
		$view->addObject("pageuser", $user);
		$view->addObject("permissions", implode("", $xhtml));
		$pageTitle = ($isReadonly) ? "Show User" : "Edit User";
		$view->addObject("pagetitle", $pageTitle);
		$readOnlyScript = ($isReadonly) ? "jQuery(\"input\").add(\"select\").attr('disabled', 'disable').addClass('readonly');" : "<!-- -->";
		$view->addObject("readonlyscript", $readOnlyScript);
		$view->renderize();
	}
	
	public function save()
	{
		$model = new UserModel();
		$model->addArgumentData("iduser", $this->getUserInput("iduser"));
		$model->addArgumentData("uuid", $this->getUserInput("uuid"));
		$model->addArgumentData("name", $this->getUserInput("name"));
		$model->addArgumentData("user", $this->getUserInput("user"));
		$model->addArgumentData("email", $this->getUserInput("email"));
		$model->addArgumentData("senha", $this->getUserInput("senha"));
		$model->addArgumentData("repitaSenha", $this->getUserInput("repitaSenha"));
		$model->addArgumentData("active", $this->getUserInput("active"));
		$model->addArgumentData("renew", $this->getUserInput("renew"));
		$model->addArgumentData("created", $this->getUserInput("created"));
		$model->addArgumentData("first_page", $this->getUserInput("first_page"));
		$model->addArgumentData("permission", $_POST["permission"]);
		
		if ($model->save())
		{
			Session::addRedir("user", "index");
			Session::addUIMessage("Registro salvo corretamente", "msgsuccess");
			Session::goToRedir();
		} 
		else 
		{
			Session::addUIMessage("Registro n&atilde;o salvo ou nada a alterar.", "msgerror");
			Session::goBack();
		}
	}
	
	private function listPermissions($userId=null, $readonly=true)
	{
		$permissions = ($userId != null) ? $this->getPermissionsByUserId($userId) : array();
		if ($_SESSION["CLEANGAB"]["xmlmenu"] == null)
		{
			Session::loadXmlPermissions();
		} 
		$xmlPermissions = simplexml_load_string($_SESSION["CLEANGAB"]["xmlmenu"]);
		$disabled = ($readonly) ? " readonly=\"readonly\" disabled=\"true\" " : "";
		$xhtml = array();
		$xhtml[] = "<ul>";
		
		foreach ($xmlPermissions as $module) 
		{
			$xhtml[] = "<li>";
			$xhtml[] = "<b>" . $module['name'] . "</b>";
			$xhtml[] = "<ul>";
			foreach ($module->permission as $prm) 
			{
				$xhtml[] = "<li>";
				$checked = (in_array($prm['key'], $permissions)) ? "checked=\"true\"" : "";
				$toFirstPage = ($prm["menu"] == "yes") ? " class=\"firstpage\" " : "";
				$xhtml[] = "<label><input type=\"checkbox\" id=\"permission_" . $prm["key"] . "\" name=\"permission[]\" " . $checked . " " . $disabled . " " . $toFirstPage . " value=\"" . $prm["key"] . "\" rel=\"" . $prm["name"] . "\" >" . $prm["name"] . "</input>";
				$xhtml[] = "</label></li>";
			}
			$xhtml[] = "</ul>";
			$xhtml[] = "</li>";
		}
		$xhtml[] = "</ul>";
		$xhtml[] = "</div>";
		return $xhtml;
	}
	
	private function getPermissionsByUserId($userId)
	{
		$model = new PermissionModel();
		$recordset = $model->loadPermissions($userId);
		$permissions = array();
		while ($recordset->hasNext())
		{
			$permissions[] = $recordset->getRecord()->permission;
		}
		return $permissions;
	}
}
?>