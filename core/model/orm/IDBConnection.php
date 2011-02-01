<?php

interface IDBConnection {
	
	public function connect($host, $port, $database, $user, $pwd);
	public function close();
}

?>