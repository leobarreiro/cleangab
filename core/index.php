<?php
if (isset($_GET) && count($_GET) > 0) {
	$params = array_values($_GET);
	$cgParams = explode("/", $params[0]);
	try {
		$controllerClass = $cgParams[0] . 'Controller';
		$controller = new $controllerClass;
		if (isset($cgParams[1]) && strlen($cgParams[1]) > 0) {
			$controller->$cgParams[1]();
		} else {
			$controller->index();
		}
	} catch (Exception $e) {
		echo $e->getMessage();
	}
}
?>