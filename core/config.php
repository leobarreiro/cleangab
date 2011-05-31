<?php
/**
 * Clean-Gab Framework
 * config.php
 * Date: 	2011-01-27
 * Author: 	Leopoldo Barreiro
 */
// Constants

define ("SEPARATOR", "/");
define ("CLEANGAB_FWK_ROOT", "/var/www/cleangab/core/");
define ("CLEANGAB_FWK_MODEL", CLEANGAB_FWK_ROOT . "model" . SEPARATOR);
define ("CLEANGAB_FWK_ORM", CLEANGAB_FWK_MODEL . "orm" . SEPARATOR);
define ("CLEANGAB_FWK_CONTROL", CLEANGAB_FWK_ROOT . "controller" . SEPARATOR);
define ("CLEANGAB_FWK_VIEW", CLEANGAB_FWK_ROOT . "view" . SEPARATOR);
define ("CLEANGAB_FWK_SECURITY", CLEANGAB_FWK_ROOT . "security" . SEPARATOR);
define ("CLEANGAB_FWK_FORMATTER", CLEANGAB_FWK_VIEW . SEPARATOR . "formatters" . SEPARATOR);

define ("CLEANGAB_WELCOME", "welcome.php");
define ("CLEANGAB_404", "404.php");
define ("CLEANGAB_XHTML_TEMPLATE", "base.xhtml");

require ("localconfig.php");

// Set the new Paths

ini_set ('include_path', 
			ini_get('include_path') . ';' . CLEANGAB_FWK_ROOT . ';' . CLEANGAB_FWK_CONTROL . ';' . 
			CLEANGAB_FWK_MODEL . ';' . CLEANGAB_FWK_VIEW . ';' . CLEANGAB_FWK_ORM . ';' .  
			CLEANGAB_FWK_SECURITY . ';' . CLEANGAB_FWK_FORMATTER . ';' . CLEANGAB_FWK_ORM . CLEANGAB_DB_DRIVER . SEPARATOR . ';');
?>