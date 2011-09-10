<?php
require_once ("CleanGabController.php");
require_once ("UserModel.php");
require_once ("TableListBase.php");
require_once ("UIMessageBase.php");
require_once ("TinyIntFormatter.php");

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
			// TODO: verificar permissoes do usuario para definir qual sera sua pagina inicial apos o login
			$user = (object) $_SESSION['CLEANGAB']['user'];			
			Session::addRedir("user", "index");
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
			$view = new CleanGabEngineView("User", "show");
			$view->addObject("uimessage", new UIMessageBase("uimessage", Session::getLastUIMessage()));
			$view->toolbar->addButton(new ToolbarButton("back", CLEANGAB_URL_BASE_APP . "/user/index", "user_list", "back active"));
			$view->addObject("visibleuser", $user);
			$view->renderize();
		}
		else
		{
			header("Location: " . CLEANGAB_URL_BASE_APP . "/user/index");
		}
	}
	
	public function newItem()
	{
		
	}
	
	public function edit($key)
	{
		Session::addRedir("user", "login");
		Session::verify();
		Session::hasPermission("user_edit");
		
		$model = new UserModel();
		$objUser = $model->getUserById($key);
		
		$arOpt = array("0"=>"N&atilde;o", "1"=>"Sim");
		$tinyActive = new TinyIntFormatter();
		$tinyActive->setOptions($arOpt);
		$objUser->activeoptions = $tinyActive->toFormField("active", "active", $objUser->active);
		
		$tinyRenew = new TinyIntFormatter();
		$tinyRenew->setOptions($arOpt);
		$objUser->renewoptions = $tinyRenew->toFormField("renew", "renew", $objUser->renew_passwd);
		
		// View
		$view = new CleanGabEngineView("User", "edit");
		$view->toolbar->addButton(new ToolbarButton("save", "javascript:document.show.submit()", "user_edit", "save active"));
		$view->toolbar->addButton(new ToolbarButton("back", CLEANGAB_URL_BASE_APP . "/user/index", "user_list", "back active"));
		$view->addObject("uimessage", new UIMessageBase("uimessage", Session::getLastUIMessage()));
		$view->addObject("editableuser", $objUser);
		$view->renderize();
	}
	
	public function save()
	{
		$model = new UserModel();
		$model->addArgumentData("iduser", $this->getUserInput("iduser"));
		$model->addArgumentData("name", $this->getUserInput("name"));
		$model->addArgumentData("user", $this->getUserInput("user"));
		$model->addArgumentData("email", $this->getUserInput("email"));
		$model->addArgumentData("senha", $this->getUserInput("senha"));
		$model->addArgumentData("repitaSenha", $this->getUserInput("repitaSenha"));
		$model->addArgumentData("active", $this->getUserInput("active"));
		$model->addArgumentData("renew", $this->getUserInput("renew"));
		
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
}
?>