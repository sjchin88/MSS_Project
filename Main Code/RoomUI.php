<?php
	require "private/Room.php";
	$login_controller = new LoginController($connection);

	$room_object = new RoomObject();
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
		$room_controller = new Room($connection);	
	} 

	//Trigger the logout function
	if(($_SERVER['REQUEST_METHOD']) == "POST" && isset($_POST['logout'])){
		unset($_POST['logout']);
		$login_controller->logout();
	}

	//POST Method, check also if the CSRF token from the form is same as session token
	if($_SERVER['REQUEST_METHOD']=="POST" &&isset($_POST['add_room']) && check_CSRF()){
        unset($_POST['add_room']);
	
		$room_object->room_id = $_POST['room_id'];
		if($_POST['special']=="NO"){
			$room_object->is_special = (int)false;
		} else {
			$room_object->is_special = (int)true;
		}
		$room_object->capacity = $_POST['capacity'];
		$room_object->location = $_POST['location'];
		$room_controller->add_room($room_object);
	}

	//POST Method, check also if the CSRF token from the form is same as session token
	if($_SERVER['REQUEST_METHOD']=="POST" &&isset($_POST['delete_room']) && check_CSRF()){
        unset($_POST['delete_room']);
        $room_id = $_POST['room_id'];
		$room_controller->delete_room($room_id);
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
	<title> Room Management </title>
	<link rel="stylesheet" type="text/css" href="Style.css">
</head>
<body>
	<div id="header">
		<?php if($username != ""): ?>
			<h1>Hi <?= htmlspecialchars($_SESSION['username'])?></h1>
		<?php endif;?>
	</div>

	<h1>This is the Room Management Page. All the rooms are listed below:</h1>
	<table class="table-format"> 
		<tr>
			<th>Room ID</th>
			<th>IS_SPECIAL</th>
			<th>Capacity</th>
			<th>Location</th>
		</tr>
		<?=$room_controller->view_room()?>
	</table>

	<form method="post" class="information">
		<div><?php
			if(isset($Error) && $Error != ""){
				echo $Error;
			}
		?>
				
		</div>
		<h4> Create new Room </h4>
		<! type can be email (validation), password (hide text) or normal text, required means the field is required>
		<div>
			<label>Room ID</label>
			<input id="textbox" type ="text" name = "room_id" >
		</div>
		<div>
			<label>Is the room special?</label>		
		    <select name="special">
		    	<option value='NO'>NO </option>
		    	<option value='YES'>YES </option>
	        </select>	
		</div>
		<div>
			<label>Capacity</label>		
			<input id="textbox" type ="number" name = "capacity" >	
		</div>
		<div>
			<label>Location</label>		
			<input id="textbox" type ="text" name = "location" >	
		</div>
		<!CSRF>
	    <input type ="hidden" name = "token" value = "<?=$_SESSION['token']?>">
		<input type = "submit" class="BUTTON" name = "add_room" value = "Save Room">
	</form>

	<form method ="post" class="information">
		<h4>Select the room to delete</h4>
	    <select name="room_id">
        <!-- retrieve choices from DB -->
        <?php
            $room_controller->getRooms();                           
        ?>
        </select>	
    	<input type ="hidden" name = "token" value = "<?=$_SESSION['token']?>">
		<input type = "submit" name="delete_room" class="BUTTON" value = "Delete Selected Room" style="white-space: normal;">	
	</form>

	<div class="center">
		<a href = "index.php" class="BUTTON"> Main Page </a>
	</div>
	<div class="center">
	<form method = "post" class="borderless">
	    <input type = "submit" name="logout" class="logout" value = "Logout">
	</form>
	</div>

</body>
</html>
