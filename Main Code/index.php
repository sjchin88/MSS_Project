<?php
	require "private/Autoload.php";
	require_once "private/AccountController.php";
	$login_controller = new LoginController($connection);

	//Check the login status
	if(!$login_controller->check_login()){
		header("Location: Login.php");
		die;
	};

	//Check the admin status
	if($login_controller->check_admin()){
		header("Location: Admin.php");
		die;
	}

	$username = "";
	if(isset($_SESSION['username'])){
		$username = $_SESSION['username'];
	}

	//Trigger the logout function
	if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['logout']) &&  check_CSRF()){
		unset($_POST['logout']);
		$login_controller->logout();
	}

	//Trigger the delete_account function
	if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['delete_account']) &&  check_CSRF()){
		unset($_POST['delete_account']);
		$account_controller = new AccountController($connection);
		if($account_controller->delete_account($_SESSION['username'])){
			$login_controller->logout();		
		} else {
			echo "Account Deletion unsuccessful";
		}
	}

	if((time()>$_SESSION['expire'])){
		$login_controller->logout();
	}

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
			<h1>Login Successful. Welcome <?= htmlspecialchars($_SESSION['username'])?></h1>
		<?php endif;?>
	</div>
	<h2>Our features are listed below.</h2>
	<img src="logo_mss.png" alt="MSS Logo" title="MSS Logo"/>
	<form>
    	<a href="CreateMeetingView.php" class="BUTTON">Create A Meeting</a>
    	<a href="ViewMeetingView.php" class="BUTTON">View Weekly Meetings</a>
    	<a href="ProfileUI.php" class="BUTTON">View/Edit Profile</a>
    	<a href="EditParticipantsView.php" class="BUTTON">Edit Participants</a>
    	<a href="PaymentUI.php" class="BUTTON">Payment Information</a>
    	<a href="CreateComplaintUI.php" class="BUTTON">Create A Complaint</a>
    	
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