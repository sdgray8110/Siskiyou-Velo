<?php
	//Start session
	session_start();
	
	//Include database connection details
	require_once('../login/config.php');
	
	//Array to store validation errors
	$errmsg_arr = array();
	
	//Validation error flag
	$errflag = false;
	
	//Connect to mysql server
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	if(!$link) {
		die('Failed to connect to server: ' . mysql_error());
	}
	
	//Select database
	$db = mysql_select_db(DB_DATABASE);
	if(!$db) {
		die("Unable to select database");
	}
	
	//Function to sanitize values received from the form. Prevents SQL injection
	function clean($str) {
		$str = @trim($str);
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		return mysql_real_escape_string($str);
	}
	
	//Sanitize the POST values
$memberID = clean($_POST['memberID']);
$firstname = clean($_POST['firstname']);
$lastname = clean($_POST['lastname']);
$address = clean($_POST['address']);
$city = clean($_POST['city']);
$state = clean($_POST['state']);
$zip = clean($_POST['zip']);
$email1 = clean($_POST['email1']);
$email2 = clean($_POST['email2']);
$phone = clean($_POST['phone']);
$password = clean($_POST['passwd']);
$emailOptOut = $_POST['emailOptOut'];
if ($emailOptOut == '') {$emailOptOut = '1';}
$AddressL2 = $_POST['address2'];
$FamilyMembers = $_POST['family'];
$website = $_POST['website'];
$Age = $_POST['age'];
$DisplayContact = $_POST['contact'];
$DisplayAddress = $_POST['dispAddress'];
$Comments = $_POST['comments'];
$riding_style = $_POST['ridingStyle'];
$riding_speed = $_POST['ridingSpeed'];
$Volunteering = $_POST['volunteer'];
$RideLeader = $_POST['rideLeader'];
$bicycles = $_POST['nobike'].$_POST['cityBike'].$_POST['roadBike'].$_POST['mountain'].$_POST['recumbent'].$_POST['tandem'].$_POST['fixedgear'];
$reason_for_joining = $_POST['nobikes'].$_POST['socialize'].$_POST['social_rides'].$_POST['advocacy'].$_POST['green'];
$oldPW = $_POST['existensial'];
$renewal = $_POST['renewal'];
$paymentType = $_POST['paymentType'];
$referrer = $_POST['referrer'];
$memberType = $_POST['memberType'];
    if ($memberType == 'I') {$renewalString = 'Siskiyou Velo - Individual Membership Renewal'; $renewalCost = '15.00';}
    if ($memberType == 'F') {$renewalString = 'Siskiyou Velo - Family Membership Renewal'; $renewalCost = '20.00';}
    if ($memberType == 'B') {$renewalString = 'Siskiyou Velo -  Membership Renewal'; $renewalCost = '25.00';}

if ($password == '') {$md5PW = $oldPW;}
else {$md5PW = md5("$password");}

	//Create INSERT query
	$qry = "UPDATE wp_users SET firstname = '$firstname',  
	lastname = '$lastname',
	address = '$address',
	city = '$city',
	state = '$state',
	zip = '$zip',
	email1 = '$email1',
	email2 = '$email2',
	phone = '$phone',
	emailOptOut = '$emailOptOut',
	AddressL2 = '$AddressL2',
	FamilyMembers = '$FamilyMembers',
	website = '$website',
	Age = '$Age',
	DisplayContact = '$DisplayContact',
	DisplayAddress = '$DisplayAddress',
	Comments = '$Comments',
	riding_style = '$riding_style',
	riding_speed = '$riding_speed',
	Volunteering = '$Volunteering',
	RideLeader = '$RideLeader',
	bicycles = '$bicycles',
	reason_for_joining = '$reason_for_joining',
        referrer = '$referrer',
        Type = '$memberType',
	user_pass = '$md5PW' WHERE ID = '$memberID'";

    //Email Sepecific Variables
    $headers = 'From: webmaster@siskiyouvelo.org'. "\r\n" .
    'Reply-To: webmaster@siskiyouvelo.org'. "\r\n" .
    'X-Mailer: PHP/' . phpversion() .
        'MIME-Version: 1.0' . "\r\n" .
        'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
	$result = @mysql_query($qry);
	
	//Check whether the query was successful or not
	if($result) {
		if ($renewal == 'yes' && $paymentType == 'paypal') {
			header("location: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=webmaster@siskiyouvelo.org&undefined_quantity=1&item_name=".$renewalString."&amount=".$renewalCost."&custom=".$memberID."|renewal&return=http://www.siskiyouvelo.org/member_renewal.php&rm=1&cancel_return=http://www.siskiyouvelo.org&currency_code=USD");
            //echo "https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=webmaster@siskiyouvelo.org&undefined_quantity=1&item_name=".$renewalString."&amount=".$renewalCost."&custom=".$memberID."|renewal&return=http://www.siskiyouvelo.org/member_renewal.php&rm=1&cancel_return=http://www.siskiyouvelo.org&currency_code=USD";
			exit();
		}

		elseif ($renewal == 'yes' && $paymentType == 'check') {
            mail('gray8110@gmail.com, treasurer@siskiyouvelo.org, membership@siskiyouvelo.org', 'Member Renewal - Payment By Check', 'Siskiyou Velo Member '.$firstname.' '.$lastname.' has chosen to renew their membership and mail a check. The member data has been updated accordingly. Upon receipt of the payment, please renew their membership within the <a href="http://www.siskiyouvelo.org/admin">Admin Tool</a>.', $headers);
			header("location: member-profile.php?renewed=payByCheck");
			exit();
		}
		
		else {
			header("location: member-profile.php?updated=yes");
			exit();	
		}
	}else {
		die("Query failed");
	}
?>

