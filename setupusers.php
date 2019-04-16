<?php	 // setupusers.php
	require_once 'login_project.php';
	$connection = new mysqli($hn, $un, $pw, $db);	
	if ($connection->connect_error) die($connection->connect_error);
	
	$query = "CREATE TABLE users (
		forename VARCHAR(32) NOT NULL,
		surname VARCHAR(32) NOT NULL,
		username VARCHAR(32) NOT NULL UNIQUE,
		password VARCHAR(32) NOT NULL
	)";

	$result = $connection->query($query);
	if (!$result) die($connection->error);

	// setup salt
	$salt1 = "qm&h*";
	$salt2 = "pg!@";

	//create first user
	$forename = "Su";
	$surname = "Tan";
	$username = "stan";
	$password = "mysecret";

	$token = hash("ripemd128", "$salt1$password$salt2");

	add_user($connection, $forename, $surname, $username, $token);

	//create second user
	$forename = "John";
	$surname = "Smith";
	$username = "jsmith";
	$password = "acrobat";

	$token = hash("ripemd128", "$salt1$password$salt2");

	add_user($connection, $forename, $surname, $username, $token);

	function add_user($connection, $fn, $sn, $un, $pw) 
	{
		$query = "INSERT INTO users VALUES('$fn', '$sn', '$un', '$pw')";
		$result = $connection->query($query);
		if (!$result) die($connection->error);
	}


?>