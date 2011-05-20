<?php
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
			if (isset($_GET['cgParam'])) {
				$controller->$_GET['cgAction']($_GET['cgParam']);
			} else {
				$controller->$_GET['cgAction']();
			}
		} else {
			$controller->index();
		}
	} catch (Exception $e) {
		echo $e->getMessage();
	}
}
?>