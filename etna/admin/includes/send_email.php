<?php
$relpath = 'src="/images/upload/image/';
$abspath = 'src="http://www.etna-desalvo.com/images/upload/image/';
$email = $_POST["from"];
$sendto = $_POST["email"];
$subject = $_POST["subject"];
$clean = stripslashes($_POST["FCKeditor1"]);
$body = str_replace($relpath, $abspath, $clean);

$headers = 'From: '.$email . "\r\n" .
    'Reply-To: ' . $email . "\r\n" .
    'X-Mailer: PHP/' . phpversion() .
	'MIME-Version: 1.0' . "\r\n" .
	'Content-type: text/html; charset=iso-8859-1' . "\r\n";

$message = '
<html>
<head>
  <title>' . $subject . '</title>
</head>
<body style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
	<p>' . $body . '</p>
	<p>----------------------------</p>
	<p>Sent via the Etna Brewing Co./DeSalvo Custom Cycles website</p>
</body>
</html>
';

// Mail it
foreach ($sendto as $to) {
    mail($to, $subject, $message, $headers);
}
		header("location: ../");
		exit();
?>
