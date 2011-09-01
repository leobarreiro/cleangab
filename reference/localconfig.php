<?php
define ("CLEANGAB_DB_DRIVER", "mysql");
define ("CLEANGAB_DB_HOST", "localhost");
define ("CLEANGAB_DB_PORT", "3306");
define ("CLEANGAB_DB_USER", "root");
define ("CLEANGAB_DB_PWD", "");
define ("CLEANGAB_DB_DB", "portal");
define ("CLEANGAB_URL_BASE_APP", "http://localhost:800/portal-br");
define ("CLEANGAB_PATH_BASE_APP", __DIR__);
define ("CLEANGAB_DEFAULT_SQL_LIMIT", "10");
define ("CLEANGAB_LOG_FILE", __DIR__ . DIRECTORY_SEPARATOR . "log" . DIRECTORY_SEPARATOR . "cleangab.log");
define ("CLEANGAB_APP_ENV", "DEV");
define ("CLEANGAB_APP_INTERFACE_TYPE", "CLI"); // Valores possiveis: CLI (PHP CLI), WEB (PHP Web).
define ("CLEANGAB_EMAIL_PARAMS", serialize(array("email_sender"=>"ononono@onono.com", "auth"=>true, "host"=>"ssl://smtp.gmail.com", "username"=>"ononono@onono.com", "password"=>"123123123", "port"=>"465")));
?>