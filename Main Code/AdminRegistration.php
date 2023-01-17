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
	if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST["registeradmin"]) && check_CSRF()){

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
			if($admincontroller->register_admin_account($email, $username, $password)){
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
	<title>Admin Home</title>
	<link rel="stylesheet" type="text/css" href="RegistrationStyle.css">
</head>
<body style="font-family: verdana;">
	<h2></h2><div id="header">
		<?php if($username != ""): ?>
			<div> Hi <?= htmlspecialchars($_SESSION['username'])?></div>
		<?php endif;?>
		<div style ="float:right">
			<form method = "post">
				<input type = "submit" name="logout" value = "Logout">
		 	</form>
		</div>
	</div></h2>

	<h4>This is the page to register admin account</h4>

	<form method="post">
		<div><?php
			if(isset($Error) && $Error != ""){
				echo $Error;
			}
		?>
			
		</div>
		<div id="title"> Admin Account Creation </div>
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
		<input type = "submit" name = "registeradmin" value = "Registration">

	</form>
</body>
</html>
