<?php

$email = $_POST["honeypot"];
$comment = $_POST["comments"];
$hazard = $_POST["hazard"];
$author = $_POST["name"];
$spam = $_POST["email"];
$to = 'Siskiyou Velo Webmaster <gray8110@gmail.com>';

if ($spam != '' || $email == '') {echo '<p>Your comment appears to contain spam or other malicious content. If you believe this to be in error, please use the contact us page to notify the webmaster.</p>';}

else {

$headers = 'From: ' . $author . ' <' . $email . '>' . "\r\n" .
    'Reply-To: ' . $email . "\r\n" .
    'X-Mailer: PHP/' . phpversion() .
	'MIME-Version: 1.0' . "\r\n" .
	'Content-type: text/html; charset=iso-8859-1' . "\r\n";


$subject = 'Siskiyou Velo Hazard Report';
$message = '
<html>
<head>
  <title>' . $hazard . '</title>
</head>
<body style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
	<p><strong>'.$hazard.'</strong></p>
	<p>' . $comment . '</p>
</body>
</html>
	
';

// Mail it
mail($to, $subject, $message, $headers);
}

		header("location: advocacy.php?pageID=Email%20Sent");
		exit();

?>
