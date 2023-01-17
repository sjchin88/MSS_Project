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
	} 

	//Check the admin status
	if(!$login_controller->check_admin()){
		header("Location: index.php");
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
	if($_SERVER['REQUEST_METHOD']=="POST" &&isset($_POST['view_complaint']) && check_CSRF()){
        unset($_POST['view_complaint']);
	
		$complaint_controller->view_complaint();
	}

	//POST Method, check also if the CSRF token from the form is same as session token
	if($_SERVER['REQUEST_METHOD']=="POST" &&isset($_POST['resolve_complaint']) && check_CSRF()){
        unset($_POST['resolve_complaint']);
        $complaint_id = $_POST['select_complaint'];
		$complaint_controller->resolve_complaint($complaint_id);
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
	<title>View Complaints </title>
	<link rel="stylesheet" type="text/css" href="Style.css">
</head>
<body>
	<div id="header">
		<?php if($username != ""): ?>
			<h1>Hi <?= htmlspecialchars($_SESSION['username'])?></h1>
		<?php endif;?>
	</div>

	<h2>This is the View Complaint Page</h2>
	<img src="logo_mss.png" alt="MSS Logo" title="MSS Logo"/>
	<table class="table-format"> 
		<tr>
			<th>Complaint ID</th>
			<th>Subject</th>
			<th>Description</th>
			<th>Contact Email</th>
		</tr>
		<?=$complaint_controller->view_complaint()?>
	</table>
	<form method ="post" id="resolve_complaint" class="information">
	    <h4>Select the complaint id to resolve</h4>
	    <select form="resolve_complaint" id="complaint" name="select_complaint">
        <!-- retrieve choices from DB -->
        
        <?php
            $complaint_controller->get_complaint_id();                           
        ?>
        </select>	
    	<input type ="hidden" name = "token" value = "<?=$_SESSION['token']?>">
		<input type = "submit" name="resolve_complaint" class="BUTTON" value = "Resolve Complaint">	
	</form>

	<div class="center">
		<a href = "index.php" class="BUTTON"> Main Page </a>
	</div>
	<form method = "post" class="borderless">
	    <input type ="hidden" name = "token" value = "<?=$_SESSION['token']?>">
	    <input type = "submit" class="logout" name="logout" value = "Logout">
	</form>

</body>
</html>
