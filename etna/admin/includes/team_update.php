<?php
	//Start session
	session_start();
	
	//Include database connection details
	require_once('../login/config.php');
	
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
$memberID = $_SESSION['SESS_MEMBER_ID'];
$firstname = clean($_POST["firstname"]);
$lastname = clean($_POST["lastname"]);
$email = clean($_POST["email"]);
$city = clean($_POST["city"]);
$road = clean($_POST["road"]);
$mtn = clean($_POST["mtn"]);
$cx = clean($_POST["cx"]);
$obra = clean($_POST["obra"]);
$uscf = clean($_POST["uscf"]);
	
	//Create INSERT queries	
	
	$qry = "UPDATE users SET
			firstname = '$firstname',
			lastname = '$lastname',
			email = '$email',
			city = '$city',
			road = '$road',
			mtn = '$mtn',
			cx = '$cx',
			obra = '$obra',
			uscf = '$uscf'
			WHERE $memberID = ID";
	
	$result = @mysql_query($qry);
	
	//Check whether the query was successful or not
	if($result) {
		header("location: ../");
		exit();
	}else {
		die("Query failed");
	}
?>