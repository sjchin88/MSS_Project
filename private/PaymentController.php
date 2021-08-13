<?php
/*Payment Controller Class*/
	//Call the Autoload.php & the PaymentInfo.php
	require_once "private/Autoload.php";
	require_once "private/PaymentInfo.php";

	class PaymentController{
		//Field 
		private $credit_card; 
		private $username; 
		private $connection;
		private $key = "encryptionkey1";

		//Constructor
		function __construct($username, $connection){
			$this->credit_card = new PaymentInfo();
			$this->username = $username;
			$this->connection = $connection; 
			$query = "SELECT CARD_ID, CC_NUMBER, CVV, CARD_OWNER, EXP_DATE FROM PAYMENTINFO WHERE USERNAME = ?";
			$stmt= $connection->prepare($query);
			$stmt->bind_param("s", $username);
			$check = $stmt->execute();
			if($check){
				$stmt->store_result();
				$stmt->bind_result($card_id, $cardnumber, $cvv, $cardowner, $exp_date);
				$stmt->fetch();
				$cardnumber = $this->decrypt_number($cardnumber);
				$cvv = $this->decrypt_number($cvv);
				$this->set_id($card_id);
				$this->set_ccnumber($cardnumber);
				$this->set_ccv($cvv);
				$this->set_owner($cardowner);
				$this->set_expdate($exp_date);
			}	
		}

		//Functions

		/*To save card via insert method*/
		function save_card($new_card){
			$query = "INSERT INTO PAYMENTINFO (CC_NUMBER, CVV, CARD_OWNER, EXP_DATE, USERNAME) values (?,?,?,?,?)";
			$stmt= $this->connection->prepare($query);
			$new_card->cardnumber=$this->encrypt_number($new_card->cardnumber);
			$new_card->cvv=$this->encrypt_number($new_card->cvv);
			$stmt->bind_param("sssss", $new_card->cardnumber, $new_card->cvv, $new_card->cardowner, $new_card->exp_date, $this->username);
			if($stmt->execute()){
				return true;
			}else{
				return false;
			}
		}


		function get_card (){
			return $this->credit_card;
		}


		/*Delete card using card_number*/
		function delete_card($card_id){
			
			$query = "DELETE FROM PAYMENTINFO WHERE CARD_ID = ?";
			$stmt= $this->connection->prepare($query);
			$stmt->bind_param("i", $card_id);
			if($stmt->execute()){
				return true;
			} else {
				return false;
			}
		}

		
		function set_id($card_id){
			$this->credit_card->card_id = $card_id;
		}


		function set_ccnumber($cardnumber){
			$this->credit_card->cardnumber = $cardnumber;
		}

		function set_ccv($cvv){
			$this->credit_card->cvv = $cvv;
		}

		function set_owner($cardowner){
			$this->credit_card->cardowner = $cardowner;
		}

		function set_expdate($exp_date){
			$this->credit_card->exp_date= $exp_date;
		}

		/* Get Functions not in used at this moment*/


		/*Function to encrypt and decrypt the card number and ccv*/
		function encrypt_number($number){
			$encryption_key = base64_decode($this->key);
			$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
			$encrypted = openssl_encrypt($number, 'aes-256-cbc', $encryption_key, 0, $iv);
			return base64_encode($encrypted . '::' . $iv);
		}

		function decrypt_number($encrypted_number){
			$encryption_key = base64_decode($this->key);
			list($encrypted_data, $iv) = array_pad(explode('::', base64_decode($encrypted_number), 2),2,null);
			return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
		}
	}