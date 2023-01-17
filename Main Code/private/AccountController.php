<?php
/*Account Controller Class*/
	//Call the Autoload.php & the Account.php
	require "private/Autoload.php";
	require "private/Account.php";

	class AccountController{
		//Field
		private $connection;

		//Constructor
		function __construct($connection){
			$this->connection = $connection; 
		}

		//Functions

		/*Function to register the account*/
		function register_Account($in_email, $in_username, $in_password ){
			$email = $in_email;
			$username = $in_username;
			$password = password_hash($in_password, PASSWORD_DEFAULT);
			$is_admin = (int)false;

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

		/*Function to check the email given*/
		function check_email($in_email){
			$email = $in_email;
			/*Check if email exists in EMAIL Table*/
			$query = "select COMPANY_EMAIL from EMAIL where COMPANY_EMAIL = ? limit 1";
			$stmt = $this->connection->prepare($query);
			$stmt->bind_param("s", $email);
			$check = $stmt->execute();
			if($check){
				$stmt->store_result();
				$stmt->bind_result($email_return);
				$stmt->fetch();
				if($email == $email_return){
					/*Check if email registered before*/
					$query = "select COMPANY_EMAIL from ACCOUNT where COMPANY_EMAIL = ? limit 1";
					$stmt = $this->connection->prepare($query);
					$stmt->bind_param("s", $email);
					$check = $stmt->execute();	
					if($check){
						$stmt->store_result();
						$stmt->bind_result($email_return);
						$stmt->fetch();
						if($email == $email_return){
							return false;
						} else {
							return true;
						}
					}//End of inner_check				
				} 
			}	//End of if_check

			//return false if either check fail
			return false;
		}	//End of function check_email

		/*Function to check the username given*/
		function check_username($in_username){
			$username = $in_username;
			$query = "select USERNAME from ACCOUNT where USERNAME = ? limit 1";
			$stmt = $this->connection->prepare($query);
			$stmt->bind_param("s", $username);
			$check = $stmt->execute();	
			if($check){
				$stmt->store_result();
				$stmt->bind_result($username_return);
				$stmt->fetch();
				if($username == $username_return){
					return false;
				} else {
					return true;
				}			
			}
			return true; 
		}	//End of function check_username	


		/*Function to delete the account */
		function delete_account($in_username){
			$username = $in_username;
			$query = "DELETE from ACCOUNT where USERNAME = ?";	
			$stmt = $this->connection->prepare($query);
			$stmt->bind_param("s", $username);
			if($stmt->execute()){
				return true;
			} else {
				return false;
			}
		}
	}	//End of class
?>