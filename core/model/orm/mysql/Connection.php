<?php

include ("IDBConnection.php");

class Connection implements IDBConnection {

	public $resource;
	
	public function connect($host, $port, $database, $user, $pwd)
	{
		$this->resource = new mysqli($host, $user, $pwd, $database, $port);
	}
	
	public function __construct($host=CLEANGAB_DB_HOST, $port=CLEANGAB_DB_PORT, 
								$database=CLEANGAB_DB_DB, $user=CLEANGAB_DB_USER, $pwd=CLEANGAB_DB_PWD)
	{
		$this->connect($host, $port, $database, $user, $pwd);
	}
	
	public function close()
	{
		$this->resource->close();
	}
	
	public function __destroy()
	{
		$this->close();
	}
}
?>