<?php require_once("includes/auth.php"); ?>
<?php include("includes/header.php"); ?>Member Profile - <?php echo $_SESSION['SESS_FIRST_NAME'] . " " . $_SESSION['SESS_LAST_NAME']; ?></title>
<script src="includes/js/lib/jquery.js"></script>  
<script type="text/javascript" src="includes/js/jquery.validate.js"></script>
  <script>
  $(document).ready(function(){
    $("#profile").validate();
  });
  </script>
<?php include("includes/header_bottom.html"); ?>
<?php include("includes/login.php"); ?>
<?php include("includes/topnav.php"); ?>
<!------- BEGIN MAIN BODY ------->
<div id="leftContent">

<?php

//Connect to database
$connection = mysql_connect("localhost","gray8110","8aU_V{^{,RJC");

//Debug
if (!$connection) {
	die ("Database connection failed: " . mysql_error());
}

//Select database
$db_select = mysql_select_db("gray8110_svblogs",$connection);

//Debug
if (!db_select) {
	die("Database selection failed: " . mysql_error());
}
?>
	

<!-- Below Code should be in HTML BODY -->
<?php
$postEmail = $_POST['email'];
$resetPW = $_GET['resetpw'];
$getEmail = $_GET['email'];

if ($resetPW == yes) {
	
$result = mysql_query("SELECT * FROM wp_users WHERE email1 = '$getEmail'", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}

//Use returned data
while ($row = mysql_fetch_array($result)) {

$firstname = $row["firstname"];
$lastname = $row["lastname"];
$id = $row["ID"];


echo "<h1>Reset Password For " . $firstname . " " . $lastname . "</h1>
<form id='profile' name='profile' action='includes/pw_reset.php' method='post' onSubmit='return validate();'>

<dl><dt><label for='passwd'>Change Password:</label></dt>
<dd><input type='password' name='passwd' id='passwd' minlength='6'  /></dd></dl>

<dl><dt><label for='curpass'>Verify New Password:</label></dt>
<dd><input type='password' name='curpass' id='curpass'  minlength='6' equalTo='#passwd' /></dd></dl>

<dl><dt><label>&nbsp;</label><input type='hidden' name='id' id='id' value='" . $id . "'</dt>
<dd><input class='submit' type='submit' alt='Submit' name='submit' value='Submit Changes &raquo;'/></dd></dl>
</form>";

}
}
else {

//DB Query
$result = mysql_query("SELECT * FROM wp_users WHERE '$postEmail' = email1", $connection);
if(mysql_num_rows($result)==0){ 
	echo "
	<h1>Email Address Not Found</h1>
	<p><strong>" . $postEmail ."</strong> is not an active primary email address within the Siskiyou Velo members database. It is possible that another email address may set as your primary email address. If you believe this to be in error, please <a href='mailto:webmaster@siskiyouvelo.org'>contact the webmaster</a>.'";
	
	include("login/login-form.php");
}

else {

//Use returned data
$row = mysql_fetch_array($result);
$firstname = $row["firstname"];
$lastname = $row["lastname"];
$email1 = $row["email1"];
$id = $row["ID"];
$expire = $row["DateExpire"];
$timeStamp = strtotime($expire);
$inactive = $timeStamp + 5184000;
$date = date(Y . "-" .  m . "-" . d);
$minus60 = strtotime($date) - 5184000;

if(mysql_num_rows($result) == 1 && ($inactive > $minus60)) {
// EMAIL //
$to .= $email1;

$headers = 'From: Siskiyou Velo Webmaster <webmaster@siskiyouvelo.org>' . "\r\n" .
    'Reply-To: webmaster@siskiyouvelo.org' . "\r\n" .
    'X-Mailer: PHP/' . phpversion() .
	'MIME-Version: 1.0' . "\r\n" .
	'Content-type: text/html; charset=iso-8859-1' . "\r\n";


$subject = 'Siskiyou Velo Password Reset';
$message = '
<html>
<head>
  <title>Siskiyou Velo Password Reset</title>
</head>
<body style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
	<p>Dear ' . $firstname . ' ' . $lastname . ',</p>
	<p>To reset your Siskiyou Velo Membership password, please click on the link below or copy and paste the link into an already open browser window.</p>
	<p><a href="http://www.siskiyouvelo.org/pw_retrieval.php?resetpw=yes&id=' . $id . '&email=' . $email1 . '">http://www.siskiyouvelo.org/pw_retrieval.php?resetpw=yes&id=' . $id . '&email=' . $email1 . '</a></p>
	<p>If you believe you are getting this message in error, please respond to this message with an explanation of your problem</p>
	<p>Thank You - Siskiyou Velo Web</p>
</body>
</html>
	
';

// Mail it
mail($to, $subject, $message, $headers);

echo "<p>An email has been sent to " . $email1 . " with instructions to reset your password.</p>";
}

elseif (mysql_num_rows($result) == 1 && ($inactive < $minus60)) {
echo "<h1>Inactive Account</h1>
	<p><strong>" . $postEmail . "</strong> is tied to an account, however it appears your membership expired on " . date('m/d/Y', $timeStamp) . ". If you believe this to be in error, please <a href='mailto:webmaster@siskiyouvelo.org'>contact the webmaster</a>.";
}

else {
echo '<p>There appears to be a problem with your account - please <a href="mailto:webmaster@siskiyouvelo.org">contact the webmaster</a> and mention this error:</p>
<p><strong>Duplicate Account Error</strong></p>
';
}

}
}

?>

<?php
//Close connection
mysql_close($connection);

?>

    </div>
    
<!-------- END MAIN BODY -------->
    
<?php include("includes/generic_feed.html"); ?>  
<?php include("includes/foot.html"); ?>
