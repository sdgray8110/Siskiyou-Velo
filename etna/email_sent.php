<?php
$pageTitle = 'Email Sent';
include('includes/header.php');
?>
<body>
<div id="wrapper">

	<?php include("includes/nav.php"); ?>
    
  <div id="leftContent">

<?php

$contact_type = $_POST["contact_type"];
$author = $_POST["author"];
$email = $_POST["email"];
$honeypot = $_POST["honeypot"];
$comment = $_POST["comment"];
$date = date('n/j/Y | h:i a');

if ($email != '') {echo '<p>I&rsquo;m sorry, we only accept emails from humans. No bots allowed!</p>';}

else {

$to .= $contact_type;

$headers = 'From: ' . $author . ' <' . $honeypot . '>' . "\r\n" .
    'Reply-To: ' . $email . "\r\n" .
    'X-Mailer: PHP/' . phpversion() .
	'MIME-Version: 1.0' . "\r\n" .
	'Content-type: text/html; charset=iso-8859-1' . "\r\n";


$subject = 'Etna/DeSalvo Blog Website Contact: ' . $_POST["subject"];
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

echo '<h1>Email sent:</h1>
        <p class="postInfo">' . $date . ' | <span>We have received your email.</span></p>
		<div style="clear:both;" class="post"><p>Please allow 24-48 hours for a response</p>
		<p><a href="../">Return to blog home &raquo;</a></p></div>';
}		

?>

  </div>
    
  <div id="rightContent">
     <?php include("includes/links_include.php"); ?>
     <?php include("includes/recent_include.php"); ?>
     <?php include("includes/category_nav_include.php"); ?>
   </div>

</div>

     <?php include("includes/footer.php"); ?>

</body>
</html>