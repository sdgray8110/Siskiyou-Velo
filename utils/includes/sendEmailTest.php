<?php
$from = 'Email Test <emailtest@siskiyouvelo.org>';
$subject = $_POST['subject'];
$headers = 'From: '.$from . "\r\n" .
    'Reply-To: ' . $from . "\r\n" .
    'X-Mailer: PHP/' . phpversion() .
	'MIME-Version: 1.0' . "\r\n" .
	'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$message = stripslashes($_POST['htmlContent']);
$to = $_POST['emailAddresses'];

mail($to, $subject, $message, $headers);

echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Email Test</title>
<link href="../css/emailTest.css" type="text/css" rel="stylesheet" />
</head>
<body>

    <p>Email sent.</p>
    <p><a href="../emailTest.php">Return to Email Form &raquo;</a></p>

<script src="http://ajax.microsoft.com/ajax/jQuery/jquery-1.4.4.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../js/emailTest.js"></script>
</body>
</html>

';
?>