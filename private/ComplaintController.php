<?php
/*Complaint Controller Class*/
	//Call the Autoload.php & the Profile.php
	require "private/Autoload.php";
	require "private/Complaint.php";

	class ComplaintController{
		//Field 
		private $complaint; 
		private $connection;

		//Constructor
		function __construct($connection){
			$this->complaint = new Complaint();
			$this->connection = $connection; 
		}

		//Functions
		function save_complaint($complaint){
			$is_resolved = (int)false;
			$query = "INSERT INTO COMPLAINT (SUBJECT, DESCRIPTION, EMAIL, IS_RESOLVED) values (?,?,?,?)";
			$stmt= $this->connection->prepare($query);
			$stmt->bind_param("sssi", $complaint->subject, $complaint->description, $complaint->email, $is_resolved);
			if($stmt->execute()){
				return true;
			}else{
				return false;
			}
		}

		/*Function to view complaint*/
		function view_complaint(){
			$query = "SELECT COMPLAINT_ID, SUBJECT, DESCRIPTION, EMAIL from COMPLAINT where IS_RESOLVED = ?";
			$stmt= $this->connection->prepare($query);
			$is_resolved = (int)false;
			$stmt->bind_param('i',$is_resolved);
			$stmt->execute();
			$stmt->store_result();
			
			if($stmt->num_rows >0){
			    $stmt->bind_result($complaint_id, $subject, $description, $email);
				while ($stmt->fetch()){
					echo "<tr><td>".$complaint_id."</td><td>".$subject."</td>
					<td>".$description."</td><td>".$email."</td><tr>";
				}
			}
		}


		/*Function to get complaint id*/
		function get_complaint_id(){
			$query = "SELECT COMPLAINT_ID from COMPLAINT where IS_RESOLVED = ?";
			$stmt= $this->connection->prepare($query);
			$is_resolved = (int)false;
			$stmt->bind_param('i',$is_resolved);
			$stmt->execute();
			$stmt->store_result();
			
			if($stmt->num_rows >0){
			    $stmt->bind_result($complaint_id);
				while ($stmt->fetch()){
					echo "<option value='$complaint_id'>$complaint_id</option>";
				}
			}
		}

		/*Function to resolve complaint*/
		function resolve_complaint($complaint_id){
			$query = "UPDATE COMPLAINT SET IS_RESOLVED = ? WHERE COMPLAINT_ID = ?";
			$stmt = $this->connection->prepare($query);
			$is_resolved = (int)true;
			$stmt->bind_param("ii", $is_resolved, $complaint_id);
			if($stmt->execute()){
				echo"complaint resolve";
			} else {
				echo"complaint not resolve";
			}
		}
	}