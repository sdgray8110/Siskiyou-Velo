<?php
/* [] New Registration IPN
--------------------------------**/
//Email Sepecific Variables
$headers = 'From: webmaster@siskiyouvelo.org'. "\r\n" .
'Reply-To: webmaster@siskiyouvelo.org'. "\r\n" .
'X-Mailer: PHP/' . phpversion() .
    'MIME-Version: 1.0' . "\r\n" .
    'Content-type: text/html; charset=iso-8859-1' . "\r\n";

if (!$fp) {
// HTTP ERROR
} else {
fputs ($fp, $header . $req);
while (!feof($fp)) {
$res = fgets ($fp, 1024);
if (strcmp ($res, "VERIFIED") == 0) {

    include('includes/db_connect.php');
    $result = mysql_query("SELECT * FROM wp_users WHERE email1 = '$custom'", $connection);

////////////////////
// Database Values//
////////////////////
    while ($row = mysql_fetch_array($result)) {
    $name = $row["firstname"].' '.$row["lastname"];
    $expireDate = date('Y-m-d 00:00:00',($date + 31536000));
    $dateJoined = date('Y-m-d 00:00:00', $date);

    if (in_array($payment_status, $acceptedPayment)) {
///////////////////////////////////
//Create INSERT query For Registration//
///////////////////////////////////
    $joinQry = "UPDATE wp_users SET
            DateExpire = '$expireDate',
            DateJoined = '$dateJoined',
            pendingPayment = '0'
            WHERE email1 = '$custom'";


$joinResult = @mysql_query($joinQry, $connection);

    $content = $name.' joined for 1 year and completed payment via paypal.<br /><br />';
    $joinResult = @mysql_query($joinQry, $connection);
/////////////////////////
//Email Activation Details//
/////////////////////////
     mail('gray8110@gmail.com', 'Member Activation - Payment '.$payment_status, '<br /><br /><strong>Payment Details: </strong><br />Email address: '.$payer_email.'<br /> Payment status: '.$payment_status.'<br />Payment amount: '.$payment_amount.'<br /> Member id: '.$custom.'<br /><br />'.$content, $headers);
     //mail('gray8110@gmail.com, treasurer@siskiyouvelo.org, membership@siskiyouvelo.org', 'Member Activation - Payment '.$payment_status, '<strong>Payment Details: </strong><br />Email address: '.$payer_email.'<br /> Payment status: '.$payment_status.'<br />Payment amount: '.$payment_amount.'<br /> Member id: '.$custom.'<br /><br />'.$content, $headers);
    }

    else {
////////////////////////
//Failed Activation Email//
////////////////////////
    $content = $name.' attempted to register with payment via paypal. The payment has not been processed. Paypal returned '.$payment_status.' as the payment status.';
    mail('gray8110@gmail.com', 'Member Activation - Payment Status Alert -- Payment '.$payment_status, '<strong>Payment Details: </strong><br />Email address: '.$payer_email.'<br /> Payment status: '.$payment_status.'<br />Payment amount: '.$payment_amount.'<br /> Member id: '.$custom.'<br /><br />'.$content, $headers);
    }
    }
}
else if (strcmp ($res, "INVALID") == 0) {
    mail('gray8110@gmail.com', 'ipn test | Join', $fp . '<br /><br />' . $res . ' test not verified -- Member ID: '.$custom, $headers);
}
}
fclose ($fp);
}

?>
