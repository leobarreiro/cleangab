<?php
class CleanGab {
	
	static function debug($mixedVar) 
	{
		switch (strtoupper(CLEANGAB_APP_ENV))
		{
			case "DEV":
			case "TEST":
				echo "<pre class=\"cleangab-debug\">\n";
				echo gettype($mixedVar) . "\n";
				if (gettype($mixedVar) == "boolean") var_dump($mixedVar);
				else print_r($mixedVar);
				echo "</pre>\n";
			break;
			default: //PROD
			break;
		}
	}
	
	static function log($msg)
	{
		$file = (defined("CLEANGAB_LOG_FILE")) ? CLEANGAB_LOG_FILE : "log" . DIRECTORY_SEPARATOR . "cleangab.log";
		$handle = (file_exists($file)) ? fopen($file, "a") : false;
		if ($handle) 
		{
			fwrite($handle, "\n[" . date("Y.m.d H:i:s") . "] " . strtoupper(CLEANGAB_APP_ENV) . " ");
			$txt = (is_object($msg) || is_array($msg)) ? serialize($msg) : $msg; 
			fwrite($handle, trim($txt));
		}
	}
	
	static function stackTraceDebug($exception) 
	{
		$file = (defined("CLEANGAB_LOG_FILE")) ? CLEANGAB_LOG_FILE : "log" . SEPARATOR . "cleangab.log";
		$handle = (is_writable($file)) ? fopen($file, "a") : false;
		if ($handle) 
		{
			fwrite($handle, "\n[" . date("Y.m.d H:i:s") . "] CG-" . strtoupper(CLEANGAB_APP_ENV) . " ");
			switch (strtoupper(CLEANGAB_APP_ENV)) 
			{
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