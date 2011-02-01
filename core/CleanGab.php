<?php

class CleanGab {
	
	static function debug($mixedVar) {
		if (ini_get("error_reporting") > 0) {
			echo "<pre>\n";
			var_dump($mixedVar);
			echo "</pre>\n";
		}
	}
	
}
?>