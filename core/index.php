<?php
if (isset($_SERVER['SCRIPT_NAME']) && 
	(preg_match("/\/cleangab\/core\/index.php/", $_SERVER['SCRIPT_NAME']) > 0)) {
	exit();
}

$friendlyUrl = array();
$friendlyUrl['cgController'] 	= filter_input(INPUT_GET, "cgController", FILTER_SANITIZE_STRING);
$friendlyUrl['cgAction'] 		= filter_input(INPUT_GET, "cgAction", FILTER_SANITIZE_STRING);
$friendlyUrl['cgParam'] 		= filter_input(INPUT_GET, "cgParam", FILTER_SANITIZE_STRING);

try {
	if ($friendlyUrl['cgController'] != null && strlen($friendlyUrl['cgController']) > 0) {
		$controllerClass = ucfirst($_GET['cgController']) . "Controller";
		if (in_array($controllerClass, get_declared_classes())) {
			$controller = new $controllerClass;
		} else {
			include (CLEANGAB_404);
			die();
		}
	} else {
			include (CLEANGAB_WELCOME);
			die();
	}
	
	if ($friendlyUrl['cgAction'] != null && strlen($friendlyUrl['cgAction']) > 0) {
		if (method_exists($controller, $friendlyUrl['cgAction'])) {
			if ($friendlyUrl['cgParam'] != null) {
				$controller->$friendlyUrl['cgAction']($friendlyUrl['cgParam']);
			} else {
				$controller->$friendlyUrl['cgAction']();
			}
		} else {
			include (CLEANGAB_404);
			die();
		}
	} else {
		$controller->index();
	}
} catch (Exception $e) {
	echo $e->getMessage();
}
?>