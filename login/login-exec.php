<?php
	//Start session
	session_start();

	//Include database connection details
	require_once('config.php');

	//Array to store validation errors
	$errmsg_arr = array();

	//Validation error flag
	$errflag = false;

	//Connect to mysql server
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	if(!$link) {
		die('Failed to connect to server: ' . mysql_error());
	}

	//Select database
	$db = mysql_select_db(DB_DATABASE);
	if(!$db) {
		die("Unable to select database");
	}

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
            $_SESSION['SESS_TITLE'] = 'Club Member' ? $member['officer'] == '' : $member['officer'] == '';

			session_write_close();
			header("location: ../");
			exit();
		}else {
			//Login failed
			header("location: ../login-failed.php");
			exit();
		}
	}
	else {
		die("Query failed");
	}
?>