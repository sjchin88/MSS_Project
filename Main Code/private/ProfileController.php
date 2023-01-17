<?php
/*Profile Controller Class*/
	//Call the Autoload.php & the Profile.php
	require "private/Autoload.php";
	require "private/Profile.php";

	class ProfileController{
		//Field 
		private $profile; 
		private $username; 
		private $connection;

		//Constructor
		function __construct($username, $connection){
			$this->profile = new Profile();
			$this->username = $username;
			$this->connection = $connection; 
			$query = "SELECT FULLNAME, CONTACT, JOBTITLE, BIOGRAPHY FROM ACCOUNT WHERE USERNAME = ?";
			$stmt= $connection->prepare($query);
			$stmt->bind_param("s", $username);
			$check = $stmt->execute();
			if($check){
				$stmt->store_result();
				$stmt->bind_result($fullname, $contact, $jobtitle, $biography);
				$stmt->fetch();
				$this->set_name($fullname);
				$this->set_contact($contact);
				$this->set_job($jobtitle);
				$this->set_biography($biography);
			}	
		}

		//Functions
		function save_profile(){
			$query = "UPDATE ACCOUNT SET FULLNAME = ?, CONTACT = ?, JOBTITLE = ?, BIOGRAPHY = ? WHERE USERNAME = ?";
			$stmt= $this->connection->prepare($query);
			$stmt->bind_param("sssss", $this->profile->fullname, $this->profile->contact, $this->profile->jobtitle, $this->profile->biography, $this->username);
			if($stmt->execute()){
				return true;
			}else{
				return false;
			}
		}


		function get_profile (){
			return $this->profile;
		}

		function set_name($in_name){
			$this->profile->fullname = $in_name;
		}

		function set_contact($in_contact){
			$this->profile->contact = $in_contact;
		}

		function set_job($in_job){
			$this->profile->jobtitle = $in_job;
		}

		function set_biography($in_biography){
			$this->profile->biography= $in_biography;
		}

		/* Get Functions not used at this moment
		function get_name(){
			return $this->profile->fullname;
		}

		function get_contact(){
			return $this->profile->contact;
		}

		function get_job(){
			return $this->profile->jobtitle;
		}

		function get_biography(){
			return $this->profile->biography;
		}*/
	}