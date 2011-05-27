<?php

class CleanGab {
	
	static function debug($mixedVar) {
		if (ini_get("error_reporting") > 0) {
			echo "<pre>\n";
			print_r($mixedVar);
			echo "</pre>\n";
		}
	}
	
	static function stackTraceDebug($exception) {
		
		$file = (defined("CLEANGAB_STACKTRACEDEBUG_FILE")) ? CLEANGAB_STACKTRACEDEBUG_FILE : "log" . SEPARATOR . "cleangab.log";
		$handle = (is_writable($file)) ? fopen($file, "a") : false;
		if ($handle) {
			fwrite($handle, "\n\n[" . date("Y.m.d H:i:s") . "] CG-" . strtoupper(CLEANGAB_APP_ENV) . " ");
			switch (strtoupper(CLEANGAB_APP_ENV)) {
				case "DEV":
					fwrite($handle, " msg: {" . $exception->getMessage() . "}, at:");
					foreach ($exception->getTrace() as $traced) {
						fwrite($handle, "\n\t" . $traced["file"]);
						fwrite($handle, " (line " . $traced["line"] . ")");
						if (isset($traced["class"]) && isset($traced["function"])) {
							fwrite($handle, "\n\t\t\t  ..." . $traced["class"] . "->" . $traced["function"]);
						}
					}
				break;
				case "TEST":
				break;
				default: //PROD
				break;
			}
		}
	}
		
}
?>