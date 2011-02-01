<?php

include ("IDBConnection.php");

class Connection implements IDBConnection {

	public $resource;
	
	public function connect($host, $port, $database, $user, $pwd)
	{
		$this->resource = mysql_connect($host.":".$port, $user, $pwd);
		mysql_select_db($database);
	}
	
	public function __construct($host=CG_DB_HOST, $port=CG_DB_PORT, $database=CG_DB_NAME, $user=CG_DB_USER, $pwd=CG_DB_PWD)
	{
		$this->connect($host, $port, $database, $user, $pwd);
	}
	
	public function close()
	{
		mysql_close($this->resource);
	}
	
	public function __destroy()
	{
		$this->close();
	}
	
}
?>