<?php
if (isset($_SERVER['SCRIPT_NAME']) && 
	(preg_match("/\/cleangab\/core\/index.php/", $_SERVER['SCRIPT_NAME']) > 0)) {
	exit();
}

if (isset($_GET) && count($_GET) > 0) {
	try {
		if (isset($_GET['cgController'])) {
			if (strlen($_GET['cgController']) > 0) {
				$controllerClass = $_GET['cgController'] . "Controller";
				$controller = new $controllerClass;	
			} else {
				header("Location: " . CLEANGAB_WELCOME);
			}
		}
		if (isset($_GET['cgAction']) && strlen($_GET['cgAction']) > 0) {
			if (method_exists($controller, $_GET['cgAction'])) {
				if (isset($_GET['cgParam'])) {
					$controller->$_GET['cgAction']($_GET['cgParam']);
				} else {
					$controller->$_GET['cgAction']();
				}
			} else {
				header("Location: " . CLEANGAB_404);
			}
		} else {
			$controller->index();
		}
	} catch (Exception $e) {
		echo $e->getMessage();
	}
}
?>