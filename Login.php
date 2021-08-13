<?php
	//Call the LoginController.php
	require "private/LoginController.php";

	//Define an Error variable
	$Error ="";
	$username = "";



	//POST Method, check also if the CSRF token from the form is same as session token
	if($_SERVER['REQUEST_METHOD']=="POST" && check_CSRF()){

		//Sanitize input
		$username = esc($_POST['username']);
		$password = esc($_POST['password']);

		if($Error ==""){
			$login_controller = new LoginController($connection);
			/*Note: $connection is a global variable need to pass into the function for the stmt prepare*/
			if($login_controller->login($username, $password)){
				header("Location: index.php");	
				die;				
			} else {
				$Error = "Login Unsuccessful";
			}
				
		}
	}

	//CSRF Token Generation 
	$_SESSION['token'] = get_random_string(60);
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title> Login Page</title>
	<link rel="stylesheet" type="text/css" href="LoginStyle.css">
</head>
<body style="font-family: verdana;">
    <h1>Welcome to the Meeting Scheduling System! Please login to access our features.</h1>
    <img src="https://msst4.000webhostapp.com/logo_mss.png" alt="MSS Logo" title="MSS Logo"/>
	<form method="post">
		<div><?php
			if(isset($Error) && $Error != ""){
				echo $Error;
			}
		?>
			
		</div>
		<div id="title"> Login </div>
		<! type can be email (validation), password (hide text) or normal text, required means the field is required>
		<div>
			<label>Username</label>
			<input id="textbox" type ="text" name = "username" required>
		</div>
		<div>
			<label>Password</label>		
			<input id="textbox" type ="password" name = "password" required>	
		</div>
		<!CSRF>
		<div>
		    <input type ="hidden" name = "token" value = "<?=$_SESSION['token']?>">
		    <input type = "submit" value = "Login" class="BUTTON">
		</div>
		
        <div>
			<label>Don't have an account? Register here:
		</div>
		<a href="https://msst4.000webhostapp.com/Registration.php" class="BUTTON">Register</a>
			
	
	</form>

</body>
</html>