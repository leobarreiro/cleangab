<?php
include ("model/UserModel.php");

class UserController extends CleanGabController {

	public function index() 
	{
		//Session::verify();

		$model = new UserModel();

		// Arguments

		if ($this->getUserInput("name")) {
			$model->addArgumentData("name", $this->getUserInput("name"));
		}
		if ($this->getUserInput("user")) {
			$model->addArgumentData("user", $this->getUserInput("user"));
		}
		if ($this->getUserInput("email")) {
			$model->addArgumentData("email", $this->getUserInput("email"));
		}
		
		if ($this->getUserInput("cgParam", "get"))
		{
			$model->addArgumentData("pg", $this->getUserInput("cgParam", "get"));
		}
		
		// Prepare List

		$model->prepareList();

		// XHTMLComponent
		$tableUsers = new TableList("users", $model);

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
		echo "Login";
	}

	public function authenticate() 
	{
		echo "Authenticate";
	}

	public function show() 
	{
		echo get_class($this);
	}
}
?>