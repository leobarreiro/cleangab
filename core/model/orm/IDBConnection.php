<?php

interface IDBConnection {
	
	public function connect($host=CLEANGAB_DB_HOST, $port=CLEANGAB_DB_PORT, 
								$database=CLEANGAB_DB_DB, $user=CLEANGAB_DB_USER, $pwd=CLEANGAB_DB_PWD);
	public function close();
}

?>