<?php
	//Start session
	session_start();
	
	//Unset the variables stored in session
	unset($_SESSION['SESS_MEMBER_ID']);
	unset($_SESSION['SESS_FIRST_NAME']);
	unset($_SESSION['SESS_LAST_NAME']);
	unset($_SESSION['SESS_EMAIL']);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Etna Brewing Co./DeSalvo Custom Cycles Team Blog | Admin Login</title>
<link href="../admin.css" rel="stylesheet" type="text/css" />
</head>
<body class="login_body">

<div class="login">
	<div class="login_top"></div>    
		<div class="login_body">
		<h2>Logout:</h2>
  		<p>You have been successfully logged out.</p>
        <p><a href="../../">Blog Homepage &raquo;</a></p>        
	</div>
	<div class="login_bottom"></div>    
</div>
</body></html>