<?php
    require "private/CreateMeetingController.php";
    //require "private/CommonFunction.php";
    $login_controller = new LoginController($connection);
    $meeting_controller = new CreateMeetingController($connection);

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
    <link rel="stylesheet" href="CreateMeetingViewStyle.css">
    <title>Create Meeting</title>
</head>
<body style="font-family: veranda">
    <style type="text/css">
        form{
            margin:  auto;
            border:  solid thin #aaa;
            padding: 6px;
            max-width: 200px;
        }
    </style>
    
    <div id="header">
		<?php if($username != ""): ?>
			<h1>Hi <?= htmlspecialchars($_SESSION['username'])?></h1>
		<?php endif;?>
	</div>
    <h2>Create a New Meeting</h2>
    <img src="https://msst4.000webhostapp.com/logo_mss.png" alt="MSS Logo" title="MSS Logo"/>

    <form method="post" class="information">
        <div>
            <label for="meetingTitle" class="label-title">Meeting Title: </label>
            <input type="text" id="meetingTitle" name="meetingTitle"><br>
    
            <label for="meetingTime" class="label-title">Meeting Time: </label>
            <select name="meetingTime">
                <option value="08:00:00">8:00 a.m. - 9:00 a.m. </option>
                <option value="09:00:00">9:00 a.m. - 10:00 a.m. </option>
                <option value="10:00:00">10:00 a.m. - 11:00 a.m. </option>
                <option value="11:00:00">11:00 a.m. - 12:00 p.m. </option>
                <option value="12:00:00">12:00 p.m. - 1:00 p.m. </option>
                <option value="13:00:00">1:00 p.m. - 2:00 p.m. </option>
                <option value="14:00:00">3:00 p.m. - 4:00 p.m. </option>
                <option value="15:00:00">4:00 p.m. - 5:00 p.m. </option>
            </select><br>
    
            <label for="meetingDate" class="label-title">Meeting Date: </label>
            <input type="date" id="meetingDate" name="meetingDate"><br>
    
            <label for="meetingRoom" class="label-title">Meeting Room: </label>
            <select name="meetingRoom">
                <!-- retrieve choices from DB -->
                <?php
                    //$rooms = $meeting_controller->retrieveRooms();
                    $meeting_controller->retrieveRooms();
                    
                ?>
            </select>
        </div>
        <input type ="hidden" name = "token" value = "<?=$_SESSION['token']?>">
        <input type="submit" name="create_meeting" value="Create" class="BUTTON">
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