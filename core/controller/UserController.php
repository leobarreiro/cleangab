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
		$view->addObject("uimessage", new UIMessageBase("uimessage", Session::getLastUIMessage()));
		$tableUsers = new TableListBase("users", $view, $model);
		$tableUsers->setFormFields(array("user", "email", "name"));
		//$view->addObject($tableUsers->getIdName(), $tableUsers);
		$view->renderize();
	}

	public function add() 
	{
		$name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
		$email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
		$created = date("dd/mm/YY");
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
			Session::addUIMessage("Log in performed correctly");
			$user = (object) $_SESSION['CLEANGAB']['user'];
			// TODO: verificar permissoes do usuario para definir qual sera sua pagina inicial apos o login
			Session::addRedir("hifi", "index");
		}
		else
		{
			Session::addRedir("user", "login");
			Session::addUIMessage("User or password invalid");
		}
		header("Location:" . Session::getLastRedir());
	}

	public function show($key) 
	{
		Session::addRedir("user", "login");
		Session::verify();
		Session::hasPermission("user_show");
		if ($key > 0)
		{
			$model = new UserModel();
			$user = $model->get($key);
			$arOpt = array("0"=>"N&atilde;o", "1"=>"Sim");
			$tinyActive = new TinyIntFormatter();
			$tinyActive->setOptions($arOpt);
			$tinyActive->setDisabled(true);
			$user->activeoptions = $tinyActive->toFormField("active", "active", $user->active);
			$user->renewoptions = $tinyActive->toFormField("renew", "renew", $user->renew_passwd);
			$xhtml = $this->listPermissions($user->id, true);
			$view = new CleanGabEngineView("User", "show");
			$view->addObject("uimessage", new UIMessageBase("uimessage", Session::getLastUIMessage()));
			$view->toolbar->addButton(new ToolbarButton("back", CLEANGAB_URL_BASE_APP . "/user/index", "user_list", "back active"));
			$view->addObject("visibleuser", $user);
			$view->addObject("permissions", implode("", $xhtml));
			$view->renderize();
		}
		else
		{
			header("Location: " . CLEANGAB_URL_BASE_APP . "/user/index");
		}
	}
	
	public function edit($key)
	{
		Session::addRedir("user", "login");
		Session::verify();
		Session::hasPermission("user_edit");
		
		$model = new UserModel();
		$user = $model->getUserById($key);
		
		$arOpt = array("0"=>"N&atilde;o", "1"=>"Sim");
		$tinyActive = new TinyIntFormatter();
		$tinyActive->setOptions($arOpt);
		$user->activeoptions = $tinyActive->toFormField("active", "active", $user->active);
		
		$tinyRenew = new TinyIntFormatter();
		$tinyRenew->setOptions($arOpt);
		$user->renewoptions = $tinyRenew->toFormField("renew", "renew", $user->renew_passwd);
		$created = new DateTimeFormatter();
		$created->toScreen($user->created);
		$user->created = $created;
		$user->first_page = new SelectInput("firstpage", $user->first_page);
		
		$xhtml = $this->listPermissions($user->id, false);
		
		// View
		$view = new CleanGabEngineView("User", "edit");
		$view->toolbar->addButton(new ToolbarButton("save", "javascript:document.show.submit()", "user_edit", "save active"));
		$view->toolbar->addButton(new ToolbarButton("back", CLEANGAB_URL_BASE_APP . "/user/index", "user_list", "back active"));
		$view->addObject("uimessage", new UIMessageBase("uimessage", Session::getLastUIMessage()));
		$view->addObject("editableuser", $user);
		$view->addObject("permissions", implode("", $xhtml));
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
		$model->addArgumentData("firstpage", $this->getUserInput("firstpage"));
		$model->addArgumentData("permission", $_POST["permission"]);
		
		if ($model->save())
		{
			Session::addRedir("user", "index");
			Session::addUIMessage("Registro salvo corretamente");
			Session::goToRedir();
		} 
		else 
		{
			Session::addUIMessage("Registro n&atilde;o salvo ou nada a alterar.");
			Session::goBack();
		}
	}
	
	private function listPermissions($userId, $editable)
	{
		$permissions = $this->getPermissionsByUserId($userId);
		
		if ($_SESSION["CLEANGAB"]["xmlmenu"] == null)
		{
			if (file_exists(CLEANGAB_PATH_BASE_APP . DIRECTORY_SEPARATOR . "security" . DIRECTORY_SEPARATOR . "permissions.xml"))
			{
				$xmlFile = CLEANGAB_PATH_BASE_APP . DIRECTORY_SEPARATOR . "security" . DIRECTORY_SEPARATOR . "permissions.xml";
			}
			else 
			{
				$xmlFile = CLEANGAB_FWK_SECURITY . DIRECTORY_SEPARATOR . "permissions.xml";
			}
			$xmlPermissions = simplexml_load_file($xmlFile);
			$_SESSION["CLEANGAB"]["xmlmenu"] = file_get_contents($xmlFile, FILE_TEXT);
		} 
		else 
		{
			$xmlPermissions = simplexml_load_string($_SESSION["CLEANGAB"]["xmlmenu"]);
		}
		
		$readonly = ($editable) ? " readonly=\"readonly\" disabled=\"true\" " : "";
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
				$toFirstPage = ($prm["menu"] == "yes") ? " rel=\"firstpage\" " : "";
				$xhtml[] = "<label><input type=\"checkbox\" id=\"permission_" . $prm["key"] . "\" name=\"permission[]\" " . $checked . " " . $readonly . " " . $toFirstPage . " value=\"" . $prm["key"] . "\" >" . $prm["name"] . "</input>";
				//$xhtml[] = $prm['name'];
				$xhtml[] = "</label></li>";
			}
			$xhtml[] = "</ul>";
			$xhtml[] = "</li>";
		}
		$xhtml[] = "</ul>";
		
		$xhtml[] = "<div class=\"field\">";
		$xhtml[] = "<span>First page after login</span>";
		$firstPage = new SelectInput("firstpage", "");
		$xhtml[] = $firstPage->toXhtml();
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