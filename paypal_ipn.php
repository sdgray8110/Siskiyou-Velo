<?php
$adminContext = '/home/gray8110/public_html/admin/';

// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';

foreach ($_POST as $key => $value) {
$value = urlencode(stripslashes($value));
$req .= "&$key=$value";
}

// post back to PayPal system to validate
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
//$fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);
$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);

$date = time();
// assign posted variables to local variables
$item_name = $_POST['item_name'];
$item_number = $_POST['item_number'];
$payment_status = $_POST['payment_status'];
$payment_amount = $_POST['mc_gross'];
$payment_currency = $_POST['mc_currency'];
$txn_id = $_POST['txn_id'];
$receiver_email = $_POST['receiver_email'];
$payer_email = $_POST['payer_email'];
$customIntact = $_POST['custom'];
$customExplode = explode('|', $customIntact);
    $custom = $customExplode[0]; // Primary Value Returned From Ebay
    $custom1 = $customExplode[1]; // Payment Type
    $custom2 = $customExplode[2]; // Additional Variable
    $custom3 = $customExplode[3]; // Additional Variable
$acceptedPayment = array('Completed','Pending');

switch($custom1) {
    case 'registration':
        include('includes/paypalIPN/newRegistration.php');
        break;
    case 'renewal':
        include('includes/paypalIPN/renewal.php');
        break;
    case 'event':
        include('includes/paypalIPN/eventRegistration.php');
        break;
	default:
		 mail('gray8110@gmail.com', 'Paypal IPN -- Problem', 'A paypal IPN was received but the payment type was not properly defined. <br /> Item name: ' . $item_name . ' <br /> Payment Amount: ' . $payment_amount . ' <br /> Payer Email: ' . $payer_email . ' <br />Member ID: ' . $custom . ' <br /> Payment Type: ' . $custom1, $headers);
}

?>