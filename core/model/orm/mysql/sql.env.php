<?php
define ("CLEANGAB_SQL_RETRIEVE_TABLE_FIELDS", "SHOW COLUMNS FROM `[database]`.`[table]` ");
define ("CLEANGAB_SQL_RETRIEVE_PERMISSIONS", "SELECT * FROM `[database]`.permission WHERE user_id = [user_id] ");
define ("CLEANGAB_SQL_INSERT", "INSERT INTO `[database`.`[table]` ([insert_fields]) VALUES ([insert_values])");
define ("CLEANGAB_SQL_UPDATE", "UPDATE `[database]`.`[table]` [update_values] WHERE [update_conditions] ");
?>