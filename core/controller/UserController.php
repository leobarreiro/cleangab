<?php
require_once ("CleanGabController.php");
require_once ("Session.php");
require_once ("UserModel.php");
require_once ("TableListBase.php");
require_once ("UIMessageBase.php");

class UserController extends CleanGabController {

	public function index() 
	{
		Session::addRedir("user", "login");
		Session::verify();
		Session::hasPermission("list_users");
		
		$model = new UserModel();

		// Arguments

		if ($this->getUserInput("name")) 
		{
			$model->addArgumentData("name", $this->getUserInput("name"));
		}
		if ($this->getUserInput("user")) 
		{
			$model->addArgumentData("user", $this->getUserInput("user"));
		}
		if ($this->getUserInput("email")) 
		{
			$model->addArgumentData("email", $this->getUserInput("email"));
		}
		if ($this->getUserInput("sort")) 
		{
			$model->addArgumentData("sort", $this->getUserInput("sort"));
		}
		// Pagination
		if ($this->getUserInput("pg"))
		{
			$model->addArgumentData("pg", $this->getUserInput("pg"));
		}
		
		// Prepare List

		$model->prepareList();

		// XHTMLComponent
		$tableUsers = new TableListBase("users", get_class($this), $model);
		//$tableUsers->setOperations(array("show", "edit"));

		// View

		$view = new CleanGabEngineView("User", "index", $tableUsers);
		$view->addObject($tableUsers->getIdName(), $tableUsers);
		$view->renderize();
	}

	public function add() 
	{
		$name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
		$email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
		$created = new date();
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
		
		$model = new UserModel();
		$model->addArgumentData("user", $user);
		$model->addArgumentData("passwd", $passwd);
		$auth = $model->authenticate();
		if ($auth)
		{
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

	public function show($keyValue) 
	{
		if ($keyValue > 0)
		{
			$model = new UserModel();
			$user = $model->get($keyValue);
			$view = new CleanGabEngineView("User", "show");
			$view->addObject("user", $user);
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
		// View

		$view = new CleanGabEngineView("User", "edit", null);
		$view->renderize();
		echo $key;
	}
}
?>