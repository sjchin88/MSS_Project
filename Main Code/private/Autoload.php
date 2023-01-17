<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

//Set display_errors = 0 to hide all error message, 1 to show the error message. 
ini_set("display_errors", 1);
require_once "private/CommonFunction.php";
require_once "private/DbConnect.php";
require_once "private/LoginController.php";