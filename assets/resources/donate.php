<?
/* for debugging, add ?debug=true to the doname form action attribute
 * <form action="donate.php?debug=true>
 * 
 * $('#donation-form').attr('action', $('#donation-form').attr('action') + "?debug=true");
 */
/////////////////////////////////////////
// requirements of necessary php files //
/////////////////////////////////////////
require_once ('config.php'); // connectiont o database
require_once('lib/functions.php'); // setup of email functions
require_once('lib/swift/swift_required.php'); // require of email resources
session_start();
$debug = ($_GET['debug'] == "true" ? true : false);
//session values
$session_key = rand ( 1 , 10000 );
$time = time();

$amount = mysql_real_escape_string($_POST['amount']);
$amount = intval($amount);
$send_amount = $amount * 100;
$token = $_POST['stripeToken'];
$name = mysql_real_escape_string($_POST['name']);
$email = mysql_real_escape_string($_POST['email']);
$message = mysql_real_escape_string($_POST['message']);
$optin = mysql_real_escape_string($_POST['optin']);
$response = array();

$_SESSION['key'] = $session_key;

/////////////////////////////////////////////////////////////////////////
// start of variable collecting, inserting into DB, and sending emails //
/////////////////////////////////////////////////////////////////////////
$gross = $_GET['mc_gross'];
if (isset($gross)) {
	/////////////////////////////////////////////////////////////////////////////////////////
	// PAYPAL returns the 'mc_gross' value, so if that is set we jump into the PAYPAL code //
	/////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////
	// start of PAYPAL code //
	//////////////////////////
	$fname = $_GET['first_name'];
    $lname = $_GET['last_name'];
    $name = "$fname $lname";
    $amount = $_GET['payment_gross'];
    $message = $_GET['item_name'];
    $email = $_GET['payer_email'];
    $optin = '1';

    $eemail = md5($email);
    $_SESSION['email'] = $eemail;

    //insert into database
    $add_new_donate = mysql_query("INSERT INTO donate values(NULL,'$amount','$name','$eemail','$message','$optin','$time','1',$session_key)");
    mysql_query($add_new_donate);
    //send email to company
    $info = array(
		'name' => $name,
		'amount' => $amount,
		'email' => $email,
		'message' => $message);
	send_donateba($info);
	//send email to donor
	$info = array(
		'name' => $name,
		'email' => $email,
		'amount' => $amount);
	send_donateuser($info);
	header("Location: /thank-you/?success=true");
	////////////////////////
	// end of PAYPAL code //
	////////////////////////

} else {
	/////////////////////////////////////////////////////////////////////////////////////////////
	// PAYPAL returns the 'mc_gross' value, so if that is NOT set we jump into the STRIPE code //
	/////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////
	// start of STRIPE code //
	//////////////////////////
	try {
		require_once('lib/Stripe.php'); // require of Stripe.php resource
		Stripe::setApiKey("YOUR STRIPE TESTING/LIVE KEY"); // Stripe API key
		$stripeParams = array(
			"amount" => $send_amount,
			"currency" => "usd",
			"card" => $token,
			"description" => $email
		);
		$charge = Stripe_Charge::create($stripeParams);
		if ($charge->paid == true) {
			$response['status'] = 'true';
			$response['message'] = 'success';
			$eemail = md5($email);
			$_SESSION['email'] = $eemail;
			

			//insert into database
			$add_new_donate = mysql_query("INSERT INTO donate values(NULL,'$amount','$name','$eemail','$message','$optin','$time','0',$session_key)");
			mysql_query($add_new_donate);
			//send email to company
			send_donateba($_POST);
			//send email to donor
			send_donateuser($_POST);
			$response['status'] = "success";
		} else {
			$response['status'] = 'false';
			$response['message'] = "Card was not charged";
			$response['status'] = "fail";
		}
	// STRIPE card error
	} catch (Stripe_CardError $e) {
		$response['status'] = 'card_error';
		$e_json = $e->getJsonBody();
		$err = $e_json['error'];
		$errors['stripe'] = $err['message'];
		$response['message'] = $err['message'];
	// STRIPE api connection error
	} catch (Stripe_ApiConnectionError $e) {
		$response['status'] = 'api_connection_error';
		$e_json = $e->getJsonBody();
		$err = $e_json['error'];
		$errors['stripe'] = $err['message'];
		$response['message'] = $err['message'];
	// STRIPE invalid request error
	} catch (Stripe_InvalidRequestError $e) {
		$response['status'] = 'invalid_request_error';
		$e_json = $e->getJsonBody();
		$err = $e_json['error'];
		$errors['stripe'] = $err['message'];
		$response['message'] = $err['message'];
	// STRIPE api error
	} catch (Stripe_ApiError $e) {
		$response['status'] = 'api_error';
		$e_json = $e->getJsonBody();
		$err = $e_json['error'];
		$errors['stripe'] = $err['message'];
		$response['message'] = $err['message'];
	// STRIPE card error
	} catch (Stripe_CardError $e) {
		$response['status'] = 'card_error';
		$e_json = $e->getJsonBody();
		$err = $e_json['error'];
		$errors['stripe'] = $err['message'];
		$response['message'] = $err['message'];
	// base exception
	} catch (Exception $e) {
		$response['status'] = 'other_error';
		$response['message'] = $e->getMessage();
	}
	if ($response['status'] == "success") {
		if (!$debug) {
			//go to thank you page if no errors are present and debug is not set
			header("Location: /thank-you/?success=true");
		} else {
			//remain on donate.php page and show debug info
			print "Form values: <br />";
			print "<pre>";
			print_r($_POST);
			print "</pre>";
			print "Stripe params: <br />";
			print "<pre>";
			print_r($stripeParams);
			print "\n\nResponse object:\n";
			print_r($response);
			print "</pre>";
		}
	} else {
		if (!$debug) {
			//go back to donate page with error response
			header("Location: /donate/?success=" . $response['status']);
		} else {
			//remain on donate.php page and show debug info
			print "Form values: <br />";
			print "<pre>";
			print_r($_POST);
			print "</pre>";
			print "Stripe params: <br />";
			print "<pre>";
			print_r($stripeParams);
			print "\n\nResponse object:\n";
			print_r($response);
			print "</pre>";
		}
	}
}
?>