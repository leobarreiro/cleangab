<?php
include ("model/UserModel.php");

class UserController extends CleanGabController {

	public function index() 
	{
		//Session::verify();

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
			CleanGab::debug($this->getUserInput("pg"));
		}
		
		// Prepare List

		$model->prepareList();

		// XHTMLComponent
		$tableUsers = new TableList("users", get_class($this), $model);
		//$tableUsers->setOperations(array("show", "edit"));

		// View

		$view = new CleanGabEngineView("User", "index", $tableUsers);
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
		$view = new CleanGabEngineView("User", "login");
		$view->renderize();
	}
	
	public function logoff()
	{
		//TODO: destroy the data in SESSION...
	}
	
	public function auth()
	{
		$user = $this->getUserInput("user");
		$passwd = $this->getUserInput("passwd");
		$authenticate = Session::login($user, $passwd);
		if ($authenticate)
		{
			header("Location: " . CLEANGAB_URL_BASE_APP . "/user/index");
		}
		else 
		{
			//TODO: Add an error message here. Maybe it can be in a message board on $_SESSION.
			header("Location: " . CLEANGAB_URL_BASE_APP . "/user/login");
		}
	}

	public function show($key) 
	{
		echo get_class($this);
		echo "<br/>";
		echo $key;
	}
	
	public function newItem()
	{
		
	}
	
	public function edit($key)
	{
		echo $key;
	}
}
?>