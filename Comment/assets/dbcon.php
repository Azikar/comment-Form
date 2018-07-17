<?php
class db{

private $server;
private $username;
private $pass;
private $database;

//database connection 
public function connect()
	{
	$this->server="localhost";
	$this->username="root";
	$this->pass="";
	$this->database="commenting";

	$con= new mysqli($this->server,$this->username,$this->pass,$this->database);
	return $con;
	}

}

?>