<?php
	require "private/ProfileController.php";
	$login_controller = new LoginController($connection);

	$profile = new Profile();
	$username = "";
	$Error ="";

	//Check the login status
	if(!$login_controller->check_login()){
		header("Location: Login.php");
		die;
	} else if(isset($_SESSION['username'])){
		//Get the session username
		$username = $_SESSION['username'];
		//Initialize the profile controller object with the username and connection and retrieve the profile
		$profile_controller = new ProfileController($username, $connection);
		$profile = $profile_controller->get_profile();		
	} 

	//Trigger the logout function
	if(($_SERVER['REQUEST_METHOD']) == "POST" && isset($_POST['logout'])){
		unset($_POST['logout']);
		$login_controller->logout();
	}

	//POST Method, check also if the CSRF token from the form is same as session token
	if($_SERVER['REQUEST_METHOD']=="POST" &&isset($_POST['save_profile']) && check_CSRF()){
        unset($_POST['save_profile']);
		//Sanitize input
		$fullname = $_POST['fullname'];
		$contact = $_POST['contact'];
		if($contact != "" && !preg_match("/^[0-9-]+$/",$contact)){
			$Error = "Please enter a valid contact number";
		}
		$jobtitle = $_POST['jobtitle'];
		$biography = $_POST['biography'];


		if($Error ==""){
			if($fullname != ""){
				$profile_controller->set_name($fullname);				
			}
			if($contact != ""){
				$profile_controller->set_contact($contact);			
			}
			if($jobtitle != ""){
				$profile_controller->set_job($jobtitle);				
			}
			if($biography != ""){
				$profile_controller->set_biography($biography);			
			}
			
			/*Note: $connection is a global variable need to pass into the function for the stmt prepare*/
			if($profile_controller->save_profile()){
				$Error = "Save successful";			
			} else {
				$Error = "Save Unsuccessful";
			}
				
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
	<title> Profile</title>
	<link rel="stylesheet" type="text/css" href="ProfileUIStyle.css">
</head>
<body>
	<div id="header">
		<?php if($username != ""): ?>
			<h1>Hi <?= htmlspecialchars($_SESSION['username'])?></h1>
		<?php endif;?>
	</div>

	<h1>This is the Profile Page</h1>
	<h2>Here is your current account information:</h2>
	<img src="logo_mss.png" alt="MSS Logo" title="MSS Logo"/>
	<h3> Full Name: <?= htmlspecialchars($profile->fullname)?></h3>
	<h3> Contact: <?= htmlspecialchars($profile->contact)?></h3>
	<h3> Job title: <?= htmlspecialchars($profile->jobtitle)?></h3>
	<h3> Biography: <?= htmlspecialchars($profile->biography)?></h3>

	<form method="post">
		<div><?php
			if(isset($Error) && $Error != ""){
				echo $Error;
			}
		?>
			
		</div>
		<h4> Update Profile </h4>
		<! type can be email (validation), password (hide text) or normal text, required means the field is required>
		<div>
			<label>Full Name</label>
			<input id="textbox" type ="text" name = "fullname" >
		</div>
		<div>
			<label>Contact Number</label>		
			<input id="textbox" type ="text" name = "contact" >	
		</div>
		<div>
			<label>Job title</label>		
			<input id="textbox" type ="text" name = "jobtitle" >	
		</div>
		<div>
			<label>Biography</label>	
			<textarea rows="5" cols="50" id="textbox" type ="text" name = "biography">

            </textarea>
		</div>
		<!CSRF>
        <input type ="hidden" name = "token" value = "<?=$_SESSION['token']?>">
		<input type = "submit" class="BUTTON" name = "save_profile" value = "Save Changes">

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
