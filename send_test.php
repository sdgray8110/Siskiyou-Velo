<?php 
$getID = $_GET['id'];
include('includes/db_connect.php');

//$emails = mysql_query("SELECT * FROM wp_users WHERE id = $getID", $connection);
$emails = mysql_query("SELECT * FROM wp_users WHERE DateJoined = (DATE_ADD(curdate(), INTERVAL 0 MONTH)) && email1 != ''  ORDER BY wp_users . lastname", $connection);
$default = '39b0d86a2144a5cba348f7ec1557e20e';

while ($emailRow = mysql_fetch_array($emails)) {
$email = $emailRow['email1'];
$email2 = $emailRow['email2'];
$name = $emailRow['firstname'].' '.$emailRow['lastname'];
$expire = date('n/d/Y', strtotime($emailRow["DateExpire"]));
$address = $emailRow['address'];
$city = $emailRow['city'];
$state = $emailRow['state'];
$zip = $emailRow['zip'];
$phone = $emailRow['phone'];

/////////////////////////////////////////////////////////
///////////*********EMAIL*********///////////////////////
/////////////////////////////////////////////////////////

$from = 'Siskiyou Velo Membership <membership@siskiyouvelo.org>';
$sendto = $email;
$subject = 'Siskiyou Velo - Test Email';


$headers = 'From: '.$from . "\r\n" .
    'Reply-To: ' . $from . "\r\n" .
    'X-Mailer: PHP/' . phpversion() .
	'MIME-Version: 1.0' . "\r\n" .
	'Content-type: text/html; charset=iso-8859-1' . "\r\n";

include('email_templates/newmember.php');

//$to = implode(", ",$sendto);
// Mail it
mail($sendto, $subject, $message, $headers);

}
echo 'Done';
?>