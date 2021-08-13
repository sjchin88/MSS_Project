<?php
	require "private/ComplaintController.php";
	$login_controller = new LoginController($connection);

	$complaint = new Complaint();
	$username = "";
	$Error ="";

	//Check the login status
	if(!$login_controller->check_login()){
		header("Location: Login.php");
		die;
	} else if(isset($_SESSION['username'])){
		//Get the session username
		$username = $_SESSION['username'];
		//Initialize the complaint controller object with the connection 
		$complaint_controller = new ComplaintController($connection);	
	} 

	//Trigger the logout function
	if(($_SERVER['REQUEST_METHOD']) == "POST" && isset($_POST['logout'])){
		unset($_POST['logout']);
		$login_controller->logout();
	}

	//POST Method, check also if the CSRF token from the form is same as session token
	if($_SERVER['REQUEST_METHOD']=="POST" &&isset($_POST['save_complaint']) && check_CSRF()){
        unset($_POST['save_complaint']);
		
		$complaint->subject = $_POST['subject'];
		$complaint->description = $_POST['description'];
		$complaint->email= $_POST['email'];

		if($complaint_controller->save_complaint($complaint)){
			$Error = "Save successful";			
		} else {
			$Error = "Save Unsuccessful";
		}
				
	}

	if((time()>$_SESSION['expire'])){
		$login_controller->logout();
	}

	//CSRF Token Generation 
	$_SESSION['token'] = get_random_string(60);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title> Complaint </title>
	<link rel="stylesheet" type="text/css" href="Style.css">
</head>
<body>
	<div id="header">
		<?php if($username != ""): ?>
			<h1>Hi <?= htmlspecialchars($_SESSION['username'])?></h1>
		<?php endif;?>
	</div>

	<h2>This is the Create Complaint Page</h2>
	<img src="https://msst4.000webhostapp.com/logo_mss.png" alt="MSS Logo" title="MSS Logo"/>
	<form method="post" class="information">
		<div><?php
			if(isset($Error) && $Error != ""){
				echo $Error;
			}
		?>
			
		</div>
		<h5> File a complaint here by filling out the following details: </h5>
		<! type can be email (validation), password (hide text) or normal text, required means the field is required>
		<div>
			<label>Subject</label>
			<input id="textbox" type ="text" name = "subject" >
		</div>
		<div>
			<label>Description</label>		
			<textarea rows="5" cols="50" id="textbox" type ="text" name = "description">
            </textarea>
		</div>
		<div>
			<label>Contact email</label>		
			<input id="textbox" type ="text" name = "email" >	
		</div>
		<!CSRF>
        <input type ="hidden" name = "token" value = "<?=$_SESSION['token']?>">
		<input type = "submit" class="BUTTON" name = "save_complaint" value = "Save Complaint">

	</form>
	<div class="center">
		<a href = "index.php" class="BUTTON"> Main Page </a>
	</div>
	<form method = "post" class="borderless">
	    <input type ="hidden" name = "token" value = "<?=$_SESSION['token']?>">
	    <input type = "submit" name="logout" class="logout" value = "Logout">
	</form>

</body>
</html>
