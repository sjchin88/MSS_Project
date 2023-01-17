<?php
    require "private/CreateMeetingController.php";
    //require "private/CommonFunction.php";
    // Time and date constants to be used for function calls to display button
    $monday = date("Y-m-d", strtotime("monday this week"));
    $tuesday = date("Y-m-d", strtotime("tuesday this week"));
    $wednesday = date("Y-m-d", strtotime("wednesday this week"));
    $thursday = date("Y-m-d", strtotime("thursday this week"));
    $friday = date("Y-m-d", strtotime("friday this week"));

    $eight = "08:00:00";
    $nine = "09:00:00";
    $ten = "10:00:00";
    $eleven = "11:00:00";
    $twelve = "12:00:00";
    $thirteen = "13:00:00";
    $fourteen = "14:00:00";
    $fifteen = "15:00:00";
    $sixteen = "16:00:00";

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
    <title>View Meetings</title>
    <link rel="stylesheet" type="text/css" href="private/ViewMeetingView.css">
</head>
<body>
    <form action="ViewMeetingDetails.php" method="post">
        <div id="week">
            <div class="day" id="monday">
                <div class="time" id="08:00:00 m">
                    <?php
                        $meeting_cntrl->getMeeting($monday, $eight);
                    ?>
                </div>
                <div class="time" id="09:00:00 m">
                    <?php
                        $meeting_cntrl->getMeeting($monday, $nine);
                    ?>
                </div>
                <div class="time" id="10:00:00 m"">
                    <?php
                        $meeting_cntrl->getMeeting($monday, $ten);
                    ?>
                </div>
                <div class="time" id="11:00:00 m">
                    <?php
                        $meeting_cntrl->getMeeting($monday, $eleven);
                    ?>
                </div>
                <div class="time" id="12:00:00 m">
                    <?php
                        $meeting_cntrl->getMeeting($monday, $twelve);
                    ?>
                </div>
                <div class="time" id="13:00:00 m">
                    <?php
                        $meeting_cntrl->getMeeting($monday, $thirteen);
                    ?>
                </div>
                <div class="time" id="14:00:00 m">
                    <?php
                        $meeting_cntrl->getMeeting($monday, $fourteen);
                    ?>
                </div>
                <div class="time" id="15:00:00 m"">
                    <?php
                        $meeting_cntrl->getMeeting($monday, $fifteen);
                    ?>
                </div>
                <div class="time" id="16:00:00 m">
                    <?php
                        $meeting_cntrl->getMeeting($monday, $sixteen);
                    ?>
                </div>
            </div>
            <div class="day" id="tuesday">
                <div class="time" id="08:00:00 t">
                    <?php
                    $meeting_cntrl->getMeeting($tuesday, $eight);
                    ?>
                </div>
                <div class="time" id="09:00:00 t">
                    <?php
                    $meeting_cntrl->getMeeting($tuesday, $nine);
                    ?>
                </div>
                <div class="time" id="10:00:00 t">
                    <?php
                    $meeting_cntrl->getMeeting($tuesday, $ten);
                    ?>
                </div>
                <div class="time" id="11:00:00 t">
                    <?php
                    $meeting_cntrl->getMeeting($tuesday, $eleven);
                    ?>
                </div>
                <div class="time" id="12:00:00 t">
                    <?php
                    $meeting_cntrl->getMeeting($tuesday, $twelve);
                    ?>
                </div>
                <div class="time" id="13:00:00 t">
                    <?php
                    $meeting_cntrl->getMeeting($tuesday, $thirteen);
                    ?>
                </div>
                <div class="time" id="14:00:00 t">
                    <?php
                    $meeting_cntrl->getMeeting($tuesday, $fourteen);
                    ?>
                </div>
                <div class="time" id="15:00:00 t">
                    <?php
                    $meeting_cntrl->getMeeting($tuesday, $fifteen);
                    ?>
                </div>
                <div class="time" id="16:00:00 t">
                    <?php
                    $meeting_cntrl->getMeeting($tuesday, $sixteen);
                    ?>
                </div>
            </div>
            <div class="day" id="wednesday">
                <div class="time" id="08:00:00 w">
                    <?php
                    $meeting_cntrl->getMeeting($wednesday, $eight);
                    ?>
                </div>
                <div class="time" id="09:00:00 w">
                    <?php
                    $meeting_cntrl->getMeeting($wednesday, $nine);
                    ?>
                </div>
                <div class="time" id="10:00:00 w">
                    <?php
                    $meeting_cntrl->getMeeting($wednesday, $ten);
                    ?>
                </div>
                <div class="time" id="11:00:00 w">
                    <?php
                    $meeting_cntrl->getMeeting($wednesday, $eleven);
                    ?>
                </div>
                <div class="time" id="12:00:00 w">
                    <?php
                    $meeting_cntrl->getMeeting($wednesday, $twelve);
                    ?>
                </div>
                <div class="time" id="13:00:00 w">
                    <?php
                    $meeting_cntrl->getMeeting($wednesday, $thirteen);
                    ?>
                </div>
                <div class="time" id="14:00:00 w">
                    <?php
                    $meeting_cntrl->getMeeting($wednesday, $fourteen);
                    ?>
                </div>
                <div class="time" id="15:00:00 w">
                    <?php
                    $meeting_cntrl->getMeeting($wednesday, $fifteen);
                    ?>
                </div>
                <div class="time" id="16:00:00 w">
                    <?php
                    $meeting_cntrl->getMeeting($wednesday, $sixteen);
                    ?>
                </div>
            </div>
            <div class="day" id="thursday">
                <div class="time" id="08:00:00 th">
                    <?php
                    $meeting_cntrl->getMeeting($thursday, $eight);
                    ?>
                </div>
                <div class="time" id="09:00:00 th">
                    <?php
                    $meeting_cntrl->getMeeting($thursday, $nine);
                    ?>
                </div>
                <div class="time" id="10:00:00 th">
                    <?php
                    $meeting_cntrl->getMeeting($thursday, $ten);
                    ?>
                </div>
                <div class="time" id="11:00:00 th">
                    <?php
                    $meeting_cntrl->getMeeting($thursday, $eleven);
                    ?>
                </div>
                <div class="time" id="12:00:00 th">
                    <?php
                    $meeting_cntrl->getMeeting($thursday, $twelve);
                    ?>
                </div>
                <div class="time" id="13:00:00 th">
                    <?php
                    $meeting_cntrl->getMeeting($thursday, $thirteen);
                    ?>
                </div>
                <div class="time" id="14:00:00 th">
                    <?php
                    $meeting_cntrl->getMeeting($thursday, $fourteen);
                    ?>
                </div>
                <div class="time" id="15:00:00 th">
                    <?php
                    $meeting_cntrl->getMeeting($thursday, $fifteen);
                    ?>
                </div>
                <div class="time" id="16:00:00 th">
                    <?php
                    $meeting_cntrl->getMeeting($thursday, $sixteen);
                    ?>
                </div>
            </div>
            <div class="day" id="friday">
                <div class="time" id="08:00:00 f">
                    <?php
                    $meeting_cntrl->getMeeting($friday, $eight);
                    ?>
                </div>
                <div class="time" id="09:00:00 f">
                    <?php
                    $meeting_cntrl->getMeeting($friday, $nine);
                    ?>
                </div>
                <div class="time" id="10:00:00 f">
                    <?php
                    $meeting_cntrl->getMeeting($friday, $ten);
                    ?>
                </div>
                <div class="time" id="11:00:00 f">
                    <?php
                    $meeting_cntrl->getMeeting($friday, $eleven);
                    ?>
                </div>
                <div class="time" id="12:00:00 f">
                    <?php
                    $meeting_cntrl->getMeeting($friday, $twelve);
                    ?>
                </div>
                <div class="time" id="13:00:00 f">
                    <?php
                    $meeting_cntrl->getMeeting($friday, $thirteen);
                    ?>
                </div>
                <div class="time" id="14:00:00 f">
                    <?php
                    $meeting_cntrl->getMeeting($friday, $fourteen);
                    ?>
                </div>
                <div class="time" id="15:00:00 f">
                    <?php
                    $meeting_cntrl->getMeeting($friday, $fifteen);
                    ?>
                </div>
                <div class="time" id="16:00:00 f">
                    <?php
                    $meeting_cntrl->getMeeting($friday, $sixteen);
                    ?>
                </div>
            </div>
        </div>
    </form>
</body>
</html>