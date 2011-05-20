<?php
/**
 * Clean-Gab Framework
 * config.php
 * Date: 	2011-01-27
 * Author: 	Leopoldo Barreiro
 */
// Constants

define ("SEPARATOR", "\\");
define ("CLEANGAB_FWK_ROOT", "C:\\Disys\\apps\\xampp\\apache\\htdocs\\cleangab\\core\\");
define ("CLEANGAB_FWK_MODEL", CLEANGAB_FWK_ROOT . "model" . SEPARATOR);
define ("CLEANGAB_FWK_ORM", CLEANGAB_FWK_MODEL . "orm" . SEPARATOR);
define ("CLEANGAB_FWK_CONTROL", CLEANGAB_FWK_ROOT . "controller" . SEPARATOR);
define ("CLEANGAB_FWK_VIEW", CLEANGAB_FWK_ROOT . "view" . SEPARATOR);
define ("CLEANGAB_FWK_SECURITY", CLEANGAB_FWK_ROOT . "security" . SEPARATOR);
define ("CLEANGAB_FWK_FORMATTER", CLEANGAB_FWK_VIEW . SEPARATOR . "formatters" . SEPARATOR);
define ("CLEANGAB_DB_DRIVER", "mysql");
define ("CLEANGAB_WELCOME", "welcome.php");

// Database connection
define ("CLEANGAB_DB_HOST", "localhost");
define ("CLEANGAB_DB_PORT", "localhost");
define ("CLEANGAB_DB_USER", "root");
define ("CLEANGAB_DB_PWD", "root");
define ("CLEANGAB_DB_DB", "cleangab");

// Set the new Paths

ini_set ('include_path', 
			ini_get('include_path') . ';' . CLEANGAB_FWK_ROOT . ';' . CLEANGAB_FWK_CONTROL . ';' . 
			CLEANGAB_FWK_MODEL . ';' . CLEANGAB_FWK_VIEW . ';' . CLEANGAB_FWK_ORM . ';' .  
			CLEANGAB_FWK_SECURITY . ';' . CLEANGAB_FWK_FORMATTER . ';' . CLEANGAB_FWK_ORM . CLEANGAB_DB_DRIVER . SEPARATOR . ';');
?>