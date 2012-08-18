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
$title = clean($_POST['title']);
$description = clean($_POST['title']);
$tags = clean($_POST['tags']);
$category = clean($_POST['category']);
$custom_cat = clean($_POST['custom_cat']);
$draft = clean($_POST['draft']);
$post = clean($_POST['FCKeditor1']);
$author = $_SESSION['SESS_FIRST_NAME']. " " . $_SESSION['SESS_LAST_NAME'];
$authorID = $_SESSION['SESS_MEMBER_ID'];

	//Create INSERT queries
	$quarry = "UPDATE users SET
			blogger = '1'
			WHERE $authorID = id";				
	
if ($custom_cat == '') {	
	$qry = "INSERT INTO gray8110_etna . posts(
			title,
			description,
			tags,
			category,
			post,
			username,
			draft,
			userID
			)
			VALUES ('$title', '$description', '$tags', '$category', '$post', '$author', '$draft', '$authorID')";
}

else {
	$qry = "INSERT INTO gray8110_etna . posts(
			title,
			description,
			tags,
			category,
			post,
			username,
			userID
			)
			VALUES ('$title', '$description', '$tags', '$custom_cat', '$post', '$author', '$authorID')";
}	
	
	$result = @mysql_query($qry);
	$fresult = @mysql_query($quarry);
	
	//Check whether the query was successful or not
	if($result) {
		header("location: ../");
		exit();
	}else {
		die("Query failed");
	}
?>