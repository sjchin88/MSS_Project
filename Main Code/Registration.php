<?php
	//Call the AccountController.php
	require "private/AccountController.php";

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
		
		$password = $_POST['password'];
		if ((strlen($password)<8) ||(!preg_match("#[0-9]+#", $password)) || (!preg_match("#[A-Z]+#", $password)) || (!preg_match("#[a-z]+#", $password))){
		    $Error = "Please enter a valid password";
		}
		//Sanitize the input using escape function
		$email = esc($email);
		$username = esc($username);
		$password = esc($password);

		if($Error ==""){
			$accountcontroller = new AccountController($connection);
			/*Note: $connection is a global variable need to pass into the function for the stmt prepare*/
			if($accountcontroller->register_Account($email, $username, $password)){
				header("Location: Login.php");	
				die;				
			} else {
				$Error = "Account Registration Unsuccessful";
			}
		
		}
	} //End of POST

	//CSRF Token Generation 
	$_SESSION['token'] = get_random_string(60);

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title> Registration Page</title>
	<link rel="stylesheet" type="text/css" href="RegistrationStyle.css">
</head>
<body style="font-family: verdana;">
	<h1>Welcome to the Meeting Scheduling System!</h1>
	<h2>Please create an account to learn more about our features and access them.</h2>
	<img src="logo_mss.png" alt="MSS Logo" title="MSS Logo"/>
	<form method="post">
		<div><?php
			if(isset($Error) && $Error != ""){
				echo $Error;
			}
		?>
			
		</div>
		<div id="title"> Registration </div>
		<! type can be email (validation), password (hide text) or normal text, required means the field is required>
		<div>
			<label>Company Email</label>
		<input id="textbox" type ="email" name = "email" required>
		</div>
		<div>
			<label>Username</label>
			<input id="textbox" type ="text" name = "username" required>
		</div>
		<div>
			<label>Password</label>		
			<input id="textbox" type ="password" name = "password">	
		</div>
		<!CSRF>
		<input type ="hidden" name = "token" value = "<?=$_SESSION['token']?>">
		<input type = "submit" value = "Register">
        <div>
			<label>Already have an account? Login here:
		</div>
		<a href="Login.php" id="BUTTON">Login</a>	
	    
	</form>
    <div style="text-align: center"> The password need to consist at least 1 uppercase, 1 lower case and 1 numeric character with total length of more than 8</div>

</body>
</html>