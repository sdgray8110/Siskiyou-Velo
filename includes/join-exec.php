<?php
include('../includes/functions.php');
include('../includes/db_connect.php');

	//Sanitize the POST values
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
$renewal = $_POST['renewal'];
$paymentType = $_POST['paymentType'];
$referrer = $_POST['referrer'];
$memberType = $_POST['memberType'];
    if ($memberType == 'I') {$regString = 'Siskiyou Velo - New Individual Membership Registration'; $regCost = '15.00';}
    if ($memberType == 'F') {$regString = 'Siskiyou Velo - New Family Membership Registration'; $regCost = '20.00';}
    if ($memberType == 'B') {$regString = 'Siskiyou Velo -  Membership Registration'; $regCost = '25.00';}

$md5PW = md5($_POST['passwd']);

$qry = "INSERT INTO gray8110_svblogs . wp_users(firstname, lastname, address, city, state, zip, email1, email2, phone, emailOptOut, AddressL2, FamilyMembers, website, Age, DisplayContact, DisplayAddress, Comments, riding_style, riding_speed, Volunteering, RideLeader, bicycles, reason_for_joining, referrer, Type, user_pass, pendingPayment)
        VALUES ('$firstname', '$lastname', '$address', '$city', '$state', '$zip', '$email1', '$email2', '$phone', '$emailOptOut', '$AddressL2', '$FamilyMembers', '$website', '$Age,', '$DisplayContact,', '$DisplayAddress', '$Comments', '$riding_style', '$riding_speed', '$Volunteering', '$RideLeader', '$bicycles', '$reason_for_joining', '$referrer', '$memberType', '$md5PW', '1')";


//Email Sepecific Variables
$headers = 'From: webmaster@siskiyouvelo.org'. "\r\n" .
'Reply-To: webmaster@siskiyouvelo.org'. "\r\n" .
'X-Mailer: PHP/' . phpversion() .
    'MIME-Version: 1.0' . "\r\n" .
    'Content-type: text/html; charset=iso-8859-1' . "\r\n";

    $result = @mysql_query($qry);

    //Check whether the query was successful or not
    if($result) {
            if ($paymentType == 'paypal') {
                    header("location: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=webmaster@siskiyouvelo.org&undefined_quantity=1&item_name=".$regString."&amount=".$regCost."&custom=".$email1."|registration&return=http://www.siskiyouvelo.org/member_renewal.php&rm=1&cancel_return=http://www.siskiyouvelo.org&currency_code=USD");
                    exit();
            }

            elseif ($paymentType == 'check') {
                    mail('gray8110@gmail.com, treasurer@siskiyouvelo.org, membership@siskiyouvelo.org', 'Member Renewal - Payment By Check', $firstname.' '.$lastname.' has joined the Siskiyou Velo and chosen to mail a check. Upon receipt of the payment, please activate their membership within the <a href="http://www.siskiyouvelo.org/admin">Admin Tool</a>.', $headers);
                    header("location: ../join.php?payByCheck=payByCheck&regCost=".$regString);
                    exit();
            }
    }else {
            die("Query failed");
    }
?>
