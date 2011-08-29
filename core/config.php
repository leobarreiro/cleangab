<?php
/**
 * CleanGab Framework
 * http://github.com/leobarreiro/cleangab/
 * config.php
 * Date: 	2011-01-27
 * Author: 	Leopoldo Barreiro
 */
// Constants

//define ("CLEANGAB_FWK_ROOT", "C:\\Dev\\apps\\apache\\htdocs\\cleangab\\core");
define ("CLEANGAB_FWK_ROOT", "/var/www/cleangab/core");
define ("CLEANGAB_FWK_MODEL", CLEANGAB_FWK_ROOT . DIRECTORY_SEPARATOR . "model");
define ("CLEANGAB_FWK_ORM", CLEANGAB_FWK_MODEL . DIRECTORY_SEPARATOR . "orm");
define ("CLEANGAB_FWK_CONTROL", CLEANGAB_FWK_ROOT . DIRECTORY_SEPARATOR . "controller");
define ("CLEANGAB_FWK_VIEW", CLEANGAB_FWK_ROOT . DIRECTORY_SEPARATOR . "view");
define ("CLEANGAB_FWK_SECURITY", CLEANGAB_FWK_ROOT . DIRECTORY_SEPARATOR . "security");
define ("CLEANGAB_FWK_FORMATTER", CLEANGAB_FWK_VIEW . DIRECTORY_SEPARATOR . "formatters");

define ("CLEANGAB_WELCOME", "welcome.php");
define ("CLEANGAB_404", "404.php");
define ("CLEANGAB_501", "501.php");
define ("CLEANGAB_XHTML_TEMPLATE", "base.xhtml");

// Set the new Paths

ini_set ('include_path', 
			ini_get('include_path') . PATH_SEPARATOR . CLEANGAB_FWK_ROOT . PATH_SEPARATOR . CLEANGAB_FWK_CONTROL . PATH_SEPARATOR . 
			CLEANGAB_FWK_MODEL . PATH_SEPARATOR . CLEANGAB_FWK_VIEW . PATH_SEPARATOR . CLEANGAB_FWK_ORM . PATH_SEPARATOR .  
			CLEANGAB_FWK_SECURITY . PATH_SEPARATOR . CLEANGAB_FWK_FORMATTER . PATH_SEPARATOR . CLEANGAB_FWK_ORM . DIRECTORY_SEPARATOR . CLEANGAB_DB_DRIVER . PATH_SEPARATOR);
?>