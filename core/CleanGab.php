<?php

class CleanGab {
	
	static function debug($mixedVar) {
		if (ini_get("error_reporting") > 0) {
			echo "<pre>\n";
			print_r($mixedVar);
			echo "</pre>\n";
		}
	}
	
}
?>