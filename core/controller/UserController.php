<?php
include ("model/UserModel.php");

class UserController extends CleanGabController {

	public function index() {
		//phpinfo();
		//die();
		Session::verify();
		$model = new UserModel();
		$tableUsers = new TableList("users", $model);
		$view = new CleanGabEngineView("User", "index", $tableUsers);
		$view->renderize();
	}
	
	public function add() {
		$name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
		$email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
		$created = new date();
	}
	
	public function login() {
		$view = new CleanGabEngineView("User", "login");
		echo "Login";
	}
	
	public function authenticate() {
		echo "Authenticate";
	}
	
	public function show() {
		echo get_class($this);
	}
}
?>