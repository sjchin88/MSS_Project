<?php
	require "private/Autoload.php";
	require "private/AdminController.php";
	$login_controller = new LoginController($connection);

	//Check the login status
	if(!$login_controller->check_login()){
		header("Location: Login.php");
		die;
	};

	//Check the admin status
	if(!$login_controller->check_admin()){
		header("Location: index.php");
		die;
	}

	$username = "";
	if(isset($_SESSION['username'])){
		$username = $_SESSION['username'];
	}

	//Trigger the logout function
	if(($_SERVER['REQUEST_METHOD']) == "POST" && isset($_POST['logout'])){
		unset($_POST['logout']);
		$login_controller->logout();
	}

	//Define an Error variable
	$Error ="";

	//POST The registration details & check if the CSRF token of the form match the session
	if($_SERVER['REQUEST_METHOD']=="POST" && check_CSRF()){

		$email = $_POST['email'];
		//Check if email match required pattern
		if(!preg_match("/^[\w\-]+@[\w\-]+.[\w\-]+$/",$email)){
			$Error = "Please enter a valid email";
		}

		$username = trim($_POST['username']);
		if(!preg_match("/^[a-zA-Z0-9_]+$/",$username)){
			$Error = "Please enter a valid username";
		}
		//Sanitize the input using escape function
		$username = esc($_POST['username']);
		$password = esc($_POST['password']);

		if($Error ==""){
			$admincontroller = new AdminController($connection);
			/*Note: $connection is a global variable need to pass into the function for the stmt prepare*/
			if($admincontroller->register_Account($email, $username, $password)){
				$Error = "Admin Account Creation Successful";		
			} else {
				$Error = "Account Registration Unsuccessful";
			}
		
		}
	} //End of POST

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
	<title>Dashboard</title>
	<link rel="stylesheet" type="text/css" href="DashboardStyle.css">
</head>
<body style="font-family: verdana;">
	<div id="header">
		<?php if($username != ""): ?>
			<h1>Administrator Login Successful. Welcome <?= htmlspecialchars($_SESSION['username'])?></h1>
		<?php endif;?>

	</div>
	<h2>Our features are listed below.</h2>
	<img src="https://msst4.000webhostapp.com/logo_mss.png" alt="MSS Logo" title="MSS Logo"/>
	<form>
	<a href="https://msst4.000webhostapp.com/ViewMeetingView.php" class="BUTTON">View Meetings</a>
	<a href="https://msst4.000webhostapp.com/ProfileUI.php" class="BUTTON">View/Edit Profile</a>
	<a href="https://msst4.000webhostapp.com/UpdateUserProfileUI.php" class="BUTTON">Update Client Profile</a>
 	<a href="https://msst4.000webhostapp.com/RoomUI.php" class="BUTTON">Update Meeting Rooms</a>
	<a href="https://msst4.000webhostapp.com/ViewComplaintUI.php" class="BUTTON">View Complaints</a>
	<a href="https://msst4.000webhostapp.com/AdminRegistration.php" class="BUTTON">Create Another Admin Account</a>	
	</form>
	<form method = "post">
		<!CSRF Token>
		<input type ="hidden" name = "token" value = "<?=$_SESSION['token']?>">
		<input class="logout" type = "submit" name="logout" value = "Logout">
    </form>
    <form method = "post">
		<!CSRF Token>
		<input type ="hidden" name = "token" value = "<?=$_SESSION['token']?>">
		<input class="logout" type = "submit" name="delete_account" value = "Delete Account">
	</form>
</body>
</html>
