<?php 

	/* Initiated by CSJ
	* This define the required constant, like the 
	* database name, username, password
	*/
	
	define('DB_HOST', 'localhost');
	define('DB_USER', 'id16988047_t4admin');
	define('DB_PASS', 'DZj04lHp%?lP2vCY');
	define('DB_NAME', 'id16988047_msst4');

	if(!$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME)){
		die("Failed to connect");
	}

	/*
	$string = "mysql:host =".DB_HOST."; dbname = ".DB_NAME;
	if(!$connection = new PDO($string, DB_USER, DB_PASS)){
		die("Failed to connect");
	}
	*/