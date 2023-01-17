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
	if(($_SERVER['REQUEST_METHOD']) == "POST" && isset($_POST['pay'])){
		unset($_POST['pay']);
		if ($_POST['cvv'] == $credit_card->cvv){
			echo "Payment Successful";
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
	<link rel="stylesheet" type="text/css" href="PaymentProcessingUIStyle.css">
</head>
<body>
	<div>
		<?php if($username != ""): ?>
			<h1>Hi <?= htmlspecialchars($_SESSION['username'])?></h1>
		<?php endif;?>
	</div>
	<h2> This is the Payment Processing. This is your credit card information</h2> 

	<h3> Card Owner: <?= htmlspecialchars($credit_card->cardowner)?></h3>
	<h3> Card Number: <?= htmlspecialchars(mask_credit_card($credit_card->cardnumber))?></h3>
	<h3> Expiry Date: <?= htmlspecialchars($credit_card->exp_date)?></h3>

	<h2>Enter your CVV information to proceed with the payment of $100 to secure the special room.</h2>
	<form method="post" class="information">
		<div><?php
			if(isset($Error) && $Error != ""){
				echo $Error;
			}
		?>
		</div>
		<h4>CCV</h4>
		<div>		
			<input id="textbox" type ="password" name = "cvv" required>	
		</div>
		<!CSRF>
		<input type ="hidden" name = "token" value = "<?=$_SESSION['token']?>">
		<input type = "submit" class="BUTTON" name="pay" value = "Pay for special room">



	<h5> Don't have a card added? Click here to add your card:</h5>
	<a href = "PaymentUI.php" class="BUTTON"> Payment Info Page </a>
	</form>
	<a href = "index.php" class="BUTTON"> Main Page </a>
	<form method = "post" class="borderless">
		<input type = "submit" class="BUTTON" name="logout" value = "Logout">
	</form>


</body>
</html>
