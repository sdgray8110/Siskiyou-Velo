<?php
	//Start session
	session_start();
	
	//Include database connection details
	require_once('../includes/config.php');
	
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
$author = clean($_POST['author']);
$email = clean($_POST['honeypot']);
$spamemail = $_POST['email'];
$website = clean($_POST['url']);
$comment = clean($_POST['comment']);
$postid = clean($_POST['postid']);

if ($spamemail == '') {

	//Create INSERT query
	$qry = "INSERT INTO gray8110_etna . comments(
			postID,
			name,
			email,
			website,
			comment
			)
			VALUES ('$postid', '$author', '$email', '$website', '$comment')";
	
	$result = @mysql_query($qry);
	
	//Check whether the query was successful or not
	if($result) {
		header("location: ../full_entry.php?id=".$postid."#comments");
		exit();
	}else {
		die("Query failed");
	}
}
else {echo '<p>Your comment is being populated in a way that is indicitave of a malicious post or spam. The webmaster has been notified. If you believe this to be in error, please use the contact us page to notify the webmaster.</p>';}	
?>