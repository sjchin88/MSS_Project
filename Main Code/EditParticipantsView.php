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
    if(($_SERVER['REQUEST_METHOD']) == "POST" && isset($_POST['add_user']) && check_CSRF()){
        unset($_POST['add_user']);
        $meeting_controller->addAttendee();
    }

    if(($_SERVER['REQUEST_METHOD']) == "POST" && isset($_POST['rmv_user']) && check_CSRF()){
        unset($_POST['rmv_user']);
        $meeting_controller->removeAttendee();
    }

    //CSRF Token Generation
    $_SESSION['token'] = get_random_string(60);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="Style.css">
  <title>Edit Participants</title>
</head>
<body style="font-family: veranda">


<div id="main">
    <div id="header">
		<?php if($username != ""): ?>
			<h1>Hi <?= htmlspecialchars($_SESSION['username'])?></h1>
		<?php endif;?>
	</div>
  <h2>This is the Edit Participants page.</h2>
  
  <img src="logo_mss.png" alt="MSS Logo" title="MSS Logo"/>
  <div class="participantForm">
    <form id="editParticipantsForm" method="post">
        <h4>Add/Remove Participants</h4>
      <div class="inputs">
        <label for="usernameBox" class="label-title">Enter a username to add/remove: </label>
          <input type="text" id="usernameBox" name="usernameBox"><br>
          
          <label for="meetingIDbox" class="label-title">Enter a valid Meeting ID: </label>
          <input type="text" id="meeting_id" name="meeting_id"></br>
          <input type ="hidden" name = "token" value = "<?=$_SESSION['token']?>">
          <input type="submit" name="add_user" value="Add User" class="BUTTON">
          <input type="submit" name="rmv_user" value="Remove User"class="BUTTON">
      </div>
    </form>
  </div>
  <div class="center">
		<a href = "index.php" class="BUTTON"> Main Page </a>
	</div>
	
		<form method = "post" class="borderless">
		    <input type ="hidden" name = "token" value = "<?=$_SESSION['token']?>">
		    <input type = "submit" name="logout" class="logout" value = "Logout">
		</form>
</div>
</body>
</html>