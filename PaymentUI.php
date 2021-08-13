<?php
	require "private/PaymentController.php";
	$login_controller = new LoginController($connection);
	$credit_card = new PaymentInfo();
	$username = "";
	$Error ="";

	//Check the login status
	if(!$login_controller->check_login()){
		header("Location: Login.php");
		die;
	} else if(isset($_SESSION['username'])){
		$username = $_SESSION['username'];
		//Initialize the profile controller object and retrieve the profile
		$payment_controller = new PaymentController($username, $connection);
		$credit_card = $payment_controller->get_card();		
	} 

	//Trigger the logout function
	if(($_SERVER['REQUEST_METHOD']) == "POST" && isset($_POST['logout'])){
		unset($_POST['logout']);
		$login_controller->logout();
	}

	//POST Method, check also if the CSRF token from the form is same as session token
	if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST['savecard']) &&  check_CSRF() ){
		unset($_POST['savecard']);
		//Sanitize input
		$cardowner = esc($_POST['cardowner']);
		$cardnumber = esc($_POST['cardnumber']);
		if(!preg_match("/^[0-9]{16,18}$/",$cardnumber)){
			$Error = "Please enter a valid cardnumber";
		}
		$cvv = esc($_POST['cvv']);
		if(!preg_match("/^[0-9]{3,4}$/",$cvv)){
			$Error = "Please enter a valid Cvv";
		}
		$exp_date = esc($_POST['exp_date']);

		if($Error ==""){
			$new_card = new PaymentInfo();
			$new_card->cardnumber=$cardnumber;
			$new_card->cvv = $cvv;
			$new_card->cardowner= $cardowner;
			$new_card->exp_date = $exp_date;
			
			if($payment_controller->save_card($new_card)){
				$Error = "Save successful";	
				header("Location: PaymentUI.php");
			} else {
				$Error = "Save Unsuccessful";
			}
				
		}
	}

	//Trigger the delete card function 
	if(($_SERVER['REQUEST_METHOD']) == "POST" && isset($_POST['deletecard']) && check_CSRF()){
		unset($_POST['deletecard']);
		if($payment_controller->delete_card($credit_card->card_id)){
			$Error = "Card Deleted";	
			header("Location: PaymentUI.php");		
		} else {
			$Error = "Card deletion Unsuccessful";
		}
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
	<title> Payment Information </title>
	<link rel="stylesheet" type="text/css" href="PaymentUIStyle.css">
</head>
<body>
	<div id="header">
		<?php if($username != ""): ?>
			<h1>Hi <?= htmlspecialchars($_SESSION['username'])?></h1>
		<?php endif;?>
	</div>
    
	<h1>This is the Payment  info Page </h1>
	<h2>Here is your current card information:</h2>
	<img src="https://msst4.000webhostapp.com/logo_mss.png" alt="MSS Logo" title="MSS Logo"/>
	
		<h3> Card Owner: <?= htmlspecialchars($credit_card->cardowner)?></h3>
		<h3> Card Number: <?= htmlspecialchars(mask_credit_card($credit_card->cardnumber))?></h3>
		<h3> Expiry Date: <?= htmlspecialchars($credit_card->exp_date)?></h3>	
	</div>
	<form method="post">
		<div><?php
			if(isset($Error) && $Error != ""){
				echo $Error;
			}
		?>
			
		</div>
		<h4> Update Credit Card </h4>
		<! type can be email (validation), password (hide text) or normal text, required means the field is required>
		<div>
			<label>Card Owner</label>
			<input id="textbox" type ="text" name = "cardowner" required >
		</div>
		<div>
			<label>Card Number</label>		
			<input id="textbox" type ="text" name = "cardnumber" required>	
		</div>
		<div>
			<label>Cvv</label>		
			<input id="textbox" type ="password" name = "cvv" required>	
		</div>
		<div>
			<label>Exp_date</label>		
			<input id="textbox" type ="text" name = "exp_date" required>	
		</div>
		<!CSRF>
		<input type ="hidden" name = "token" value = "<?=$_SESSION['token']?>">
		<input type = "submit" name="savecard" class="BUTTON" value = "Save Changes">

	</form>
	<form method = "post">
		<input type ="hidden" name = "token" value = "<?=$_SESSION['token']?>">
		<input type = "submit" name="deletecard" class="BUTTON" value = "Delete Card">
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
