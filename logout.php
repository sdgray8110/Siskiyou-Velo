<?php require_once("includes/auth.php"); ?>
<?php
	//Start session
	session_start();
	
	//Unset the variables stored in session
	unset($_SESSION['SESS_MEMBER_ID']);
	unset($_SESSION['SESS_FIRST_NAME']);
	unset($_SESSION['SESS_LAST_NAME']);
	unset($_SESSION['SESS_EMAIL']);
	unset($_SESSION['SESS_OFFICER']);
	unset($_SESSION['SESS_TITLE']);
?>
<?php include("includes/header.php"); ?>
Logout</title>
<?php include("includes/header_bottom.html"); ?>
<?php include("includes/login.php"); ?>
<?php include("includes/topnav.php"); ?>
<!------- BEGIN MAIN BODY ------->
<div id="leftContent">

<h1>Logout </h1>
<p align="center" class="err">You have been logged out.</p>
<!--<p align="center">Click here to <a href="login-form.php">Login</a></p>-->

<!-------- END MAIN BODY -------->
</div>
    
<?php include("includes/generic_feed.html"); ?>  
<?php include("includes/foot.html"); ?>