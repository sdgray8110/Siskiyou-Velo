<?php

if ($_POST['username'] != '') {

	//Start session
	session_start();
	
	//Array to store validation errors
	$errmsg_arr = array();
	
	//Validation error flag
	$errflag = false;
	
include("db_connect.php");
	
	//Function to sanitize values received from the form. Prevents SQL injection
	function clean($str) {
		$str = @trim($str);
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		return mysql_real_escape_string($str);
	}
	
	//Sanitize the POST values
	$login = clean($_POST['username']);
	$password = clean($_POST['password']);
	
	//Input Validations
	if($login == '') {
		$errmsg_arr[] = 'Login ID missing';
		$errflag = true;
	}
	if($password == '') {
		$errmsg_arr[] = 'Password missing';
		$errflag = true;
	}
	
	//If there are input validations, redirect back to the login form
	if($errflag) {
		$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
		session_write_close();
		header("location: login-form.php");
		exit();
	}

$md5PW = md5("$password");
$date = date(Y . "-" .  m . "-" . d);
$minus60 = strtotime($date) - 5184000;	

	//Create query
	$qry="SELECT * FROM wp_users WHERE email1='$login' AND user_pass='$md5PW'";
	$result=mysql_query($qry);
$member = mysql_fetch_array($result);
$expire = $member["DateExpire"];
$timeStamp = strtotime($expire);
$inactive = $timeStamp + 5184000;	
	
	//Check whether the query was successful or not
	if($result) {
		if(mysql_num_rows($result) == 1 && ($inactive > $minus60)) {
			//Login Successful
			session_regenerate_id();	
			$_SESSION['SESS_MEMBER_ID'] = $member['ID'];
			$_SESSION['SESS_FIRST_NAME'] = $member['firstname'];
			$_SESSION['SESS_LAST_NAME'] = $member['lastname'];
			$_SESSION['SESS_EMAIL'] = $member['email1'];
			$_SESSION['SESS_OFFICER'] = $member['officer'];
			
			if ($member['officer'] == '') {$_SESSION['SESS_TITLE'] = 'Club Member';}
			else {$_SESSION['SESS_TITLE'] = $member['officer'];}
			
			if ($_SESSION['SESS_TITLE'] == 'Club Member' || $_SESSION['SESS_TITLE'] == 'Buster') {
				//Not An Officer
				header("location: ../?login=member");
				exit();			
			}
			
			else {			
			session_write_close();
			header("location: ../");
			exit();
			}
		}else {
			//Login failed
			header("location: ../?login=failed");
			exit();
		}
	}
	else {
		die("Query failed");
	}
}

else {
echo '
<div class="signinForm" style="display:none;">';

if ($_GET['login'] == 'failed') {echo '<h5>Login Failed</h5>
<p>Check your username and password</p>';}

echo '
<form name="signin" action="includes/login-form.php" method="post">
<label for "username">Email Address</label>
<input type="text" id="username" tabindex="4" name="username">
<label for "Password">Password</label>
<input id="password" type="password" tabindex="5" name="password">

<input class="submit" type="submit" tabindex="6" alt="Submit" name="submit" value="Sign in &raquo;"/>
</form>';

if ($_GET['login'] == 'failed') {echo '<p><a href="http://www.siskiyouvelo.org/login-failed.php">Forget your password?</a></p>';}

else if ($_GET['login'] == 'member') {echo '<h5>Club Officers Only</h5>
<p>You successfully logged in with a valid Club Member account, however this administration tool is only available to officers of the Siskiyou Velo.</p>
<p style="margin-top:7px;">Return to the <a href="http://www.siskiyouvelo.org">Siskiyou Velo Homepage</p>';}

echo '
</div>
';

}

?>
<script type="text/javascript">
$(document).ready(function() {
	$('.mainNav li').hide();
	$('.signinForm').slideDown(1000);
	$('.signinForm .submit').click(function() {
		$('.signinForm').slideUp(500);
	});
});
</script>