<?php 
	// authenticate.php
	require_once 'login_project.php';
	$connection = new mysqli($hn, $un, $pw, $db);
	if ($connection->connect_error) die($connection->connect_error);
	
	if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']))
	{
	    $un_temp = mysql_entities_fix_string($connection, $_SERVER['PHP_AUTH_USER']);
	    $pw_temp = mysql_entities_fix_string($connection, $_SERVER['PHP_AUTH_PW']);
		
		// find username from DB
	    $query = "SELECT * FROM users WHERE username='$un_temp'";
	    $result = $connection->query($query);

		if (!$result) die($connection->error);
		
		elseif ($result->num_rows) 
		{
			$row = $result->fetch_array(MYSQLI_NUM);
			$result->close();
			$salt1 = "qm&h*";
			$salt2 = "pg!@";
			
			//string is then passed to the hash function, which returns a 32-character hexadecimal value in $token
			$token = hash('ripemd128', "$salt1$pw_temp$salt2");
			//check token with pwd hash value in the DB
			if ($token == $row[3]) 
				echo "$row[0] $row[1] : Hi $row[0], you are now logged in as '$row[2]'";
			else die("Invalid username/password combination");
		}
		else die("Invalid username/password combination");
	}

	else  { 	
		// if ($_SERVER['PHP_AUTH_USER’])  and  ($_SERVER['PHP_AUTH_PW’]) are not set
		header('WWW-Authenticate: Basic realm="Restricted Section"');
		header('HTTP/1.0 401 Unauthorized');
		die ("Please enter your username and password");
	}
	$connection->close();

	function mysql_entities_fix_string($connection, $string) {
		//remove any html from a string such as <b>hi</b> into &lt;b&gt;hi&lt;/b&gt;
		return htmlentities(mysql_fix_string($connection, $string));
	}
	function mysql_fix_string($connection, $string) {
		//sanitize the string; Affects HTTP Request data (GET, POST, and COOKIE)
		if (get_magic_quotes_gpc()) $string = stripslashes($string);
			return $connection->real_escape_string($string);
	}

?>