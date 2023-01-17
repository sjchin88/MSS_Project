<?php
/*Login Controller Class*/
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 
	//Call the Autoload.php & the Account.php
	require_once "private/CommonFunction.php";
	require_once "private/DbConnect.php";
	require_once "private/Account.php";

	class LoginController{
		/*Fields/properties*/
		private $connection;

		//Constructor
		function __construct($connection){
			$this->connection = $connection; 
		}

		/*login function*/
		function login($in_username, $in_password){
			$username = $in_username;
			$password = $in_password;
			//$password = password_hash($in_password, PASSWORD_DEFAULT);
			//$query = "select USERNAME from ACCOUNT where USERNAME = ? && PASSWORD = ? limit 1";
			$query = "select USERNAME, IS_ADMIN, PASSWORD from ACCOUNT where USERNAME = ? limit 1";
			$stm = $this->connection->prepare($query);
			$stm->bind_param("s", $username);
			$check = $stm->execute();	
			if($check){
				
				$stm->store_result();
				$stm->bind_result($username_return, $is_admin, $password_hashed);
				$stm->fetch();
				if(password_verify($password, $password_hashed)){
				    $this->setLogin($username_return, $is_admin);		
				    return true;				    
				}
			} else {
				return false;
			}
		}

		function setLogin($username, $is_admin){
			$_SESSION ['username'] = $username;
			$_SESSION ['admin'] = $is_admin;
			//Set the expire session timing
			$_SESSION ['expire'] = time() + (1800);
		}

		function check_login()
		{
			if(isset($_SESSION['username']))
			{
				$username = $_SESSION['username'];
				$query = "select * from ACCOUNT where USERNAME = ? limit 1";
				$stm = $this->connection->prepare($query);
				$stm->bind_param("s",$username);
				$check = $stm->execute();

				if($check){
					return true;
				}
			}
			return false;

		}

		function check_admin()
		{
			if(isset($_SESSION['admin'])){
				if($_SESSION['admin']){
					return true;
				}
			}
			return false;
		}

		function logout(){
			
			session_unset();
			session_destroy();
			header("Location:Login.php");
			die;
		}

	}







