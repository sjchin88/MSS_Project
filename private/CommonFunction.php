<?php

//Store the common functions to be used by all the controllers and php UI page

/*Function to generate random string*/
function get_random_string($max_length)
{
	//Create an array to store 62 characters from 0-9, a-z and A-Z
	$array = array(0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

	//Create the return text
	$text = "";

	//Create a random number between minimum 5 to $maxLength
	$txt_length = rand(5, $max_length);

	//Start generating the random text with given length
	for ($i = 0; $i<$txt_length; $i++){
		$random_index = rand(0,61);
		$text .= $array[$random_index];
	}

	return $text;
}

/*Escape function to sanitize input*/
function esc($input_word){
	return addslashes($input_word);
}

/*Function to mask credit card number*/
function mask_credit_card($number){
	return str_repeat("*",12).substr($number, -4);
}

/*Function to check */
function check_CSRF(){
	if(isset($_SESSION['token']) && isset($_POST['token']) && $_SESSION['token'] == $_POST['token']){
		return true;
	}else{
		return false;
	}
}