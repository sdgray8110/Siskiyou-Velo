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
$filename = clean($_POST['filename']);
$issue = clean($_POST['month'] . ' ' . $_POST['year']);
$item1 = clean($_POST['item1']);
$item2 = clean($_POST['item2']);
$item3 = clean($_POST['item3']);

	//Create INSERT query
	$qry = "INSERT INTO gray8110_svblogs . sv_newsbrief(
			filename,
			issue,
			item1,
			item2,
			item3
			)
			VALUES ('$filename', '$issue', '$item1', '$item2', '$item3')";
	
	$result = @mysql_query($qry);
	
	//Check whether the query was successful or not
	if($result) {
		header("location: ../post_news_briefs.php");
		exit();
	}else {
		die("Query failed");
	}
?>