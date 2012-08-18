<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../2009.css" rel="stylesheet" type="text/css" />
<title>Etna Brewing/DeSalvo Custom Cycles | Home</title>


<?php

$email = $_POST["from"];
$sendto = $_POST["to"];
$comment = $_POST["comment"];
$date = date('n/j/Y - h:i a');
$author = $_POST["author"];;

$to = $sendto;

$headers = 'From: ' . $author . ' <' . $email . '>' . "\r\n" .
    'Reply-To: ' . $email . "\r\n" .
    'X-Mailer: PHP/' . phpversion() .
	'MIME-Version: 1.0' . "\r\n" .
	'Content-type: text/html; charset=iso-8859-1' . "\r\n";


$subject = 'Etna/DeSalvo Blog Website Contact';
$message = '
<html>
<head>
  <title>' . $_POST["comment"] . '</title>
</head>
<body style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
	<p>' . $comment . '</p>
</body>
</html>
	
';

// Mail it
mail($to, $subject, $message, $headers);

echo '
<body class="emailBody">
<div id="emailContent">
	<h1>Email sent:</h1>
        <p class="postInfo">' . $date . ' | <span>Your email has been sent.</span></p>
		<div style="clear:both;" class="post"><p>Thanks for sharing our blog with your friends.</p>
		<p><a href="javascript:window.close();">Close Window &raquo;</a></p></div>
</div>
</body></html>';


?>