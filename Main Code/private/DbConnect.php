<?php 

	/* Initiated by CSJ
	* This define the required constant, like the 
	* database name, username, password
	*/
	
	define('DB_HOST', 'localhost');
	define('DB_USER', 'id20160790_mssadmin');
	define('DB_PASS', '?N%<G2o$_xc3@HLX');
	define('DB_NAME', 'id20160790_mssdb');
	define('WEB_URL', 'https://meetingschedulingsystem.000webhostapp.com/');
	
	if(!$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME)){
		die("Failed to connect");
	}

	/*
	$string = "mysql:host =".DB_HOST."; dbname = ".DB_NAME;
	if(!$connection = new PDO($string, DB_USER, DB_PASS)){
		die("Failed to connect");
	}
	*/