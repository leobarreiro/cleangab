<?php
require_once ("CleanGab.php");
require_once ("CleanGabController.php");
require_once ("RedirModel.php");
require_once ("Session.php");

class RedirController extends CleanGabController {

	public function add() 
	{
		$requestURI = $_SERVER["REQUEST_URI"];
		$url = str_replace("redir/add", "", strtolower($requestURI));
		$url = CLEANGAB_URL_BASE_APP . substr($url, (strrpos($url, "//")+1));
		$model = new RedirModel();
		echo $model->addRedir($url);
	}
	
	public function goto($uuid)
	{
		$logged = Session::isUserLogged();
		if ($logged)
		{
			$model = new RedirModel();
			$redir = $model->getRedirByUuid($uuid);
			if ($redir)
			{
				header("Location: " . $redir->url);
			}
		}
		else
		{
			CleanGab::log("User is not logged to redirection URL, go to Login page");
			header("Location: " . CLEANGAB_URL_BASE_APP . "/user/login/" . $uuid);
		}
	}
	
}
?>