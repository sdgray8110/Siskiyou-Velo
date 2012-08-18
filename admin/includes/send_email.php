<?php
$from = $_POST['from'];
$subject = $_POST['subject'];
$recipients = $_POST['recipients'];
$toOfficers = implode(", ", $recipients);
$body = stripslashes($_POST['emailBody']);
$mlc = $_POST['list'] == 'mlc';
$unsubscribe = $mlc ? 'http://www.mountainlakeschallenge.com/unsubscribe.php' : 'http://www.siskiyouvelo.org/unsubscribe.php';

/////////////////////////////////////////
// SEND ONE EMAIL TO SELECTED OFFICERS //
/////////////////////////////////////////
if ($_POST['category'] == 'officers') {

$headers = 'From: '.$from . "\r\n" .
    'Reply-To: ' . $from . "\r\n" .
    'X-Mailer: PHP/' . phpversion() .
	'MIME-Version: 1.0' . "\r\n" .
	'Content-type: text/html; charset=iso-8859-1' . "\r\n";

$message = '
<html>
<head>
  <title>' . $subject . '</title>
</head>
<body>
'.$body.'
</body>
</html>
';
////////////////////////////////
// ONLY MAIL WITH VALID INPUT //
////////////////////////////////

mail($toOfficers, $subject, $message, $headers);
}

/////////////////////////////////////////
// LOOP THRU RECIPIENTS - CREATE EMAIL //
/////////////////////////////////////////
else {

foreach ($recipients as $to) {
////////////////////////////////
// ONLY MAIL WITH VALID INPUT //
////////////////////////////////
$email =  split('[<>]', $to);
$unsubEmail = $mlc ? $to : $email[1];

$headers = 'From: '.$from . "\r\n" .
    'Reply-To: ' . $from . "\r\n" .
    'X-Mailer: PHP/' . phpversion() .
	'MIME-Version: 1.0' . "\r\n" .
	'Content-type: text/html; charset=iso-8859-1' . "\r\n";

$message = '
<html>
<head>
  <title>' . $subject . '</title>
</head>
<body>
'.$body.'

<p style="font-size:11px; color:#666; font-family:arial; width:700px; margin:10px auto; border-top:1px solid #ccc; padding-top:10px;">You are receiving this email because you are a member of the Siskiyou Velo Cycling Club. If you wish to modify your email subscriptions, you can do so via your member profile on the <a href="http://www.siskiyouvelo.org">Siskiyou Velo website</a>. <a href="'.$unsubscribe.'?email='.$unsubEmail.'">Click Here</a> to unsubscribe this address from all Siskiyou Velo email contacts.</p>

</body>
</html>
';

mail($to, $subject, $message, $headers);
}
}
	header("location: ../?email=sent");
	exit();

?>