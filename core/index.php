
<?php
require_once ("CleanGab.php");
require_once ("Toolbar.php");
require_once ("UIMessageBase.php");
require_once ("ToolbarButton.php");
require_once ("Session.php");

if (isset($_SERVER['SCRIPT_NAME']) && (preg_match("/\/cleangab\/core\/index.php/", $_SERVER['SCRIPT_NAME']) > 0)) 
{
	exit();
}
if (!isset($_SESSION))
{
	session_start();
	session_name("CleanGab");
}
$friendlyUrl 				 = array();
$friendlyUrl['cgController'] = filter_input(INPUT_GET, "cgController", FILTER_SANITIZE_STRING);
$friendlyUrl['cgAction'] 	 = filter_input(INPUT_GET, "cgAction", FILTER_SANITIZE_STRING);
$friendlyUrl['cgParam'] 	 = filter_input(INPUT_GET, "cgParam", FILTER_SANITIZE_STRING);
$friendlyUrl['cgParam2'] 	 = filter_input(INPUT_GET, "cgParam2", FILTER_SANITIZE_STRING);
$friendlyUrl['cgParam3'] 	 = filter_input(INPUT_GET, "cgParam3", FILTER_SANITIZE_STRING);

// Controller

if ($friendlyUrl['cgController'] != null && 
	strlen($friendlyUrl['cgController']) > 0) 
{
	$controllerClass = ucfirst($_GET['cgController']) . "Controller";
	if (!class_exists($controllerClass))
	{
		require_once ($controllerClass . ".php");
	}
	$controller = new $controllerClass;
} 
else 
{
	include (CLEANGAB_WELCOME);
	die();
}

// Action

if ($friendlyUrl['cgAction'] != null && 
	strlen($friendlyUrl['cgAction']) > 0 && 
	method_exists($controller, $friendlyUrl['cgAction'])) 
{
	
	// Params
	
	if ($friendlyUrl['cgParam'] != null && $friendlyUrl['cgParam2'] != null) 
	{
		$controller->$friendlyUrl['cgAction']($friendlyUrl['cgParam'], $friendlyUrl['cgParam2']);
	} 
	else if ($friendlyUrl['cgParam'] != null) 
	{
		$controller->$friendlyUrl['cgAction']($friendlyUrl['cgParam']);
	}
	else 
	{
		$controller->{$friendlyUrl['cgAction']}();
	}
}
else 
{
	$controller->index();
}
?>