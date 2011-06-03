
<?php

define ("CLEANGAB_SQL_STRING_TYPES", serialize(array("CHAR", "VARCHAR", "TEXT")));
define ("CLEANGAB_SQL_NUMERIC_TYPES", serialize(array("INT", "NUMERIC", "FLOAT")));
define ("CLEANGAB_SQL_LIKE", "LIKE '[like]'");

define ("CLEANGAB_SQL_RETRIEVE_ALL", "SELECT [listable_fields] FROM `[database]`.`[table]` [args] [order] [limit] ");
define ("CLEANGAB_SQL_COUNT_ALL", "SELECT COUNT([pk]) AS total FROM `[database]`.`[table]` [args] ");
define ("CLEANGAB_SQL_RETRIEVE_TABLE_FIELDS", "SHOW COLUMNS FROM `[database]`.`[table]` ");
define ("CLEANGAB_SQL_RETRIEVE_USERS", "SELECT * FROM `[database]`.usuario WHERE id = [user_id] ");
define ("CLEANGAB_SQL_RETRIEVE_PERMISSIONS", "SELECT * FROM `[database]`.permission WHERE user_id = [user_id] ");
define ("CLEANGAB_SQL_INSERT", "INSERT INTO `[database`.`[table]` ([insert_fields]) VALUES ([insert_values])");
define ("CLEANGAB_SQL_UPDATE", "UPDATE `[database]`.`[table]` [update_values] WHERE [update_conditions] ");
define ("CLEANGAB_SQL_VERIFY_LOGIN", "SELECT id, usuario AS user, nome AS name, email FROM `[database]`.`[table]` WHERE usuario = '[username]' AND senha = MD5('[password]') ");

?>