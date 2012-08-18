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

    include($adminContext . 'includes/events/db_connect.php');
    $result = mysql_query("SELECT * FROM registrants WHERE memberID = '$custom' && eventID = '$custom2'", $connection);

while ($row = mysql_fetch_array($result)) {
    $key = $row['ID'];

    $regQry = "UPDATE registrants SET
        paypal = 'true'
        WHERE ID = '$key'";

    $regResult = @mysql_query($regQry, $connection);
    
    if (in_array($payment_status, $acceptedPayment)) {
///////////////////////////////////
//Create INSERT query For Registration//
///////////////////////////////////
    $regQry = "UPDATE registrants SET
            paypalAuth = 'true'
            WHERE ID = '$key'";


    $regResult = @mysql_query($regQry, $connection);

$content = $payer_email.' has registered for the 2010 Siskiyou Velo Holiday Party and completed payment via paypal.<br /><br />';
//////////////////////////////
//Email Registration Details//
//////////////////////////////
     mail('gray8110@gmail.com, ecrawfordzell@yahoo.com', 'Holiday Party Registration - Payment '.$payment_status, '<br /><br /><strong>Payment Details: </strong><br />Email address: '.$payer_email.'<br /> Payment status: '.$payment_status.'<br />Payment amount: '.$payment_amount.'<br /> Member id: '.$custom.'<br /><br />'.$content, $headers);
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
    mail('gray8110@gmail.com', 'ipn test | event registration', 'fgets($fp,1) = ' . fgets($fp,1) . '<br /><br />' . '$item_name = ' . $item_name . ' test not verified -- Member ID: '.$custom, $headers);
}
}
fclose ($fp);
}

?>
