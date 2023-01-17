<?php
    require "private/CreateMeetingController.php";
    //require "Private/LoginController.php";
    $meeting_start = $_POST['submit'];
    echo $meeting_start;
    $user = $_SESSION['username'];
    
    $meeting_cntrl = new CreateMeetingController($connection);
    $login_controller = new LoginController($connection);

    //Check the login status
    if(!$login_controller->check_login()){
        header("Location: Login.php");
        die;
    };

    //Check the admin status
    if($login_controller->check_admin()){
       //header("Location: Admin.php");
        //die;
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
    
    //Trigger the delete card function 
	if(($_SERVER['REQUEST_METHOD']) == "POST" && isset($_POST['create_meeting']) && check_CSRF()){
		unset($_POST['create_meeting']);
		$meeting_controller->createMeeting();
	}
	
	//CSRF Token Generation 
	$_SESSION['token'] = get_random_string(60);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Meeting Details</title>
</head>
<body>
    <?php
        $meeting_cntrl->getMeetingDetails($meeting_start);
    ?>
</body>
</html>
