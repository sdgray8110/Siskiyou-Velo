<?php 
$getEmail = $_GET['email'];
include( $_SERVER['DOCUMENT_ROOT'] . '/includes/db_connect.php');

//////////////////////////////
//** SET EMAIL QUERY HERE **//
//////////////////////////////
if ($getEmail == 'join') {
$emails = mysql_query("SELECT * FROM wp_users WHERE DateJoined = (DATE_ADD(curdate(), INTERVAL 0 MONTH)) && email1 != '' && emailOptOut != '1' ORDER BY wp_users . lastname", $connection);
}

elseif ($getEmail == 'news') {
$emails = mysql_query("SELECT * FROM wp_users WHERE DateExpire >= (DATE_ADD(curdate(), INTERVAL -2 MONTH)) && email1 != '' && emailOptOut != '1' ORDER BY wp_users . lastname", $connection);
//$emails = mysql_query("SELECT * FROM wp_users WHERE ID = '155'", $connection); //TESTING//
}

elseif ($getEmail == 'profile') {
// THIS SENDS EMAILS TO MEMBERS WHO HAVEN'T ANSWERED ALL SURVEY QUESTIONS //
$emails = mysql_query("SELECT * FROM wp_users WHERE DateExpire >= (DATE_ADD(curdate(), INTERVAL -2 MONTH)) && email1 != '' && emailOptOut != '1' && bicycles = '' && riding_style = '' && riding_speed = '' && reason_for_joining = '' ORDER BY wp_users . lastname", $connection);
//$emails = mysql_query("SELECT * FROM wp_users WHERE ID = '155'", $connection); //TESTING//
}

elseif ($getEmail == 'renewal') {
$emails = mysql_query("SELECT * FROM wp_users WHERE DateRenewed = (DATE_ADD(curdate(), INTERVAL 0 MONTH)) && email1 != '' && emailOptOut != '1' ORDER BY wp_users . lastname", $connection);
}

elseif ($getEmail == 'reminder') {
$emails = mysql_query("SELECT * FROM wp_users WHERE DateExpire = (DATE_ADD(curdate(), INTERVAL +1 MONTH)) && email1 != '' && emailOptOut != '1' ORDER BY wp_users . lastname", $connection);
}

elseif ($getEmail == 'expired') {
$emails = mysql_query("SELECT * FROM wp_users WHERE DateExpire = (DATE_ADD(curdate(), INTERVAL -1 MONTH)) && email1 != '' && emailOptOut != '1' ORDER BY wp_users . lastname", $connection);
}

//////////////////////////////
//** END SET EMAIL QUERY  **//
//////////////////////////////

if (mysql_num_rows($emails) == 0) {echo '<p>No records found.</p>';}

else {

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
$membershipEmail = 'membership@siskiyouvelo.org';
$memberID = $emailRow['ID'];
$includePath = $_SERVER['DOCUMENT_ROOT'] . '/email_templates/';

if ($getEmail == 'join' || $getEmail == 'renewal') {$sendto = $email.', '.$email2.', '.$membershipEmail;}
else {$sendto = $email.', '.$email2.', gray8110@gmail.com';}

/////////////////////////////
//** SET EMAIL BODY HERE **//
/////////////////////////////
if ($getEmail == 'join') {include($includePath . 'newmember.php');}

elseif ($getEmail == 'news' && $emailRow['user_pass'] == $default) {include($includePath . 'news_default.php');}
elseif ($getEmail == 'news' && $emailRow['user_pass'] != $default) {include($includePath . 'news_custom.php');}

elseif ($getEmail == 'renewal' && $emailRow['user_pass'] == $default) {include($includePath . 'renewal_default.php');}
elseif ($getEmail == 'renewal' && $emailRow['user_pass'] != $default) {include($includePath . 'renewal_custom.php');}

elseif ($getEmail == 'profile' && $emailRow['user_pass'] == $default) {include($includePath . 'profile_default.php');}
elseif ($getEmail == 'profile' && $emailRow['user_pass'] != $default) {include($includePath . 'profile_custom.php');}

////////////////////////////////
// ONLY MAIL WITH VALID INPUT //
////////////////////////////////
if ($getEmail != '') {
mail($sendto, $subject, $message, $headers);

echo '
    <p>'.$sendto.'</p>
' . $message;
}

else {echo '<p>No valid email template selected.</p>';}
}
echo '<hr />Done';
}

if ($getEmail == 'news') {
	
$newsletter = mysql_query("SELECT * FROM sv_newsbrief ORDER BY ID DESC LIMIT 1", $connection);
$newsRow = mysql_fetch_array($newsletter);
$newsID = $newsRow['ID'];
	
//Create INSERT query

$queryResult = mysql_query("UPDATE gray8110_svblogs.sv_newsbrief SET sent = '1' WHERE sv_newsbrief.ID = '$newsID' LIMIT 1", $connection);

}
?>
