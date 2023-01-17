<?php
	//Call the AccountController.php
	require "private/AccountController.php";

	class AdminController extends AccountController
	{
		/*Overide Function to register the account*/
		function register_admin_account($in_email, $in_username, $in_password){
			$email = $in_email;
			$username = $in_username;
			$password = $in_password;
			$is_admin = (int)true;

			if($this->check_email($email) && $this->check_username($username)){
				$query = "insert into ACCOUNT (USERNAME, PASSWORD, IS_ADMIN,COMPANY_EMAIL) values (?,?,?,?);";
				$stmt=$this->connection->prepare($query);
				$stmt->bind_param("ssis", $username,$password,$is_admin,$email);
				if($stmt->execute()){
					return true;
				}				
			} 
			return false;
		}	//End of function register_Account
	}