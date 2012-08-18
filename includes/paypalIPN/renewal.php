<?php
/* [] Renewal IPN
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
    $result = mysql_query("SELECT * FROM wp_users WHERE ID = '$custom'", $connection);

////////////////////
// Database Values//
////////////////////
    while ($row = mysql_fetch_array($result)) {
    $name = $row["firstname"].' '.$row["lastname"];
    $expireTime = strtotime($row["DateExpire"]);
    $expire = date('n/d/Y', $expireTime);
    $lastRenew = $row["DateRenewed"];

//////////////////////////
// Determine renew date //
//////////////////////////
    	if ($date > ($expireTime + 5184000)) {$newRenewDate = date('Y-m-d 00:00:00', $date + 31536000);}
	else {$newRenewDate = date('Y-m-d 00:00:00', ($expireTime + 31536000));}

	$dateRenewed = date('Y-m-d 00:00:00', $date);
    if (in_array($payment_status, $acceptedPayment)) {
///////////////////////////////////
//Create INSERT query For Renewal//
///////////////////////////////////
    $renewQry = "UPDATE wp_users SET
            DateExpire = '$newRenewDate',
            DateRenewed = '$dateRenewed'
            WHERE ID = '$custom'";

    $content = $name.' renewed for 1 year with a previous expiration date of '.$expire.' and a new expiration date of '.date('n/d/Y',strtotime($newRenewDate)).'.';
    if ($payment_status == 'Completed' && ($date - strtotime($lastRenew)) < 1209600) {
        mail('gray8110@gmail.com, treasurer@siskiyouvelo.org, membership@siskiyouvelo.org', 'Nothing Here', 'Nada');
    }
    else {
        $renewResult = @mysql_query($renewQry, $connection);
/////////////////////////
//Email Renewal Details//
/////////////////////////
        mail('gray8110@gmail.com, treasurer@siskiyouvelo.org, membership@siskiyouvelo.org', 'Member Renewal - Payment '.$payment_status, '<strong>Payment Details: </strong><br />Email address: '.$payer_email.'<br /> Payment status: '.$payment_status.'<br />Payment amount: '.$payment_amount.'<br /> Member id: '.$custom.'<br /><br />'.$content, $headers);
    }

    }

    else {
////////////////////////
//Failed Renewal Email//
////////////////////////
    $content = $name.' attempted a renewal with payment via paypal. The payment has not been processed. Paypal returned '.$payment_status.' as the payment status.';
    mail('gray8110@gmail.com, treasurer@siskiyouvelo.org, membership@siskiyouvelo.org', 'Member Renewal - Payment Status Alert -- Payment '.$payment_status, '<strong>Payment Details: </strong><br />Email address: '.$payer_email.'<br /> Payment status: '.$payment_status.'<br />Payment amount: '.$payment_amount.'<br /> Member id: '.$custom.'<br /><br />'.$content, $headers);
    }
    }
}
else if (strcmp ($res, "INVALID") == 0) {
    mail('gray8110@gmail.com', 'ipn test', $res . ' test not verified -- Member ID: '.$custom, $headers);
}
}
fclose ($fp);
}

?>