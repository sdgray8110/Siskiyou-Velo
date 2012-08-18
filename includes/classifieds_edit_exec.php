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
$name = clean($_POST['seller']);
$email = clean($_POST['email']);
$phone = clean($_POST['phone']);
$item = clean($_POST['item']);
$price = clean($_POST['price']);
$post = clean($_POST['FCKeditor1']);
$memberid = $_SESSION['SESS_MEMBER_ID'];
$postID = clean($_POST['postID']);

	//Create update query
	$qry = "UPDATE classifieds SET
			name = '$name',
			email = '$email',
			phone = '$phone',
			item = '$item',
			price = '$price',
			post = '$post',	
			member_id = '$memberid'						
			WHERE ID = '$postID'";
	
	$result = @mysql_query($qry);
	
	//Check whether the query was successful or not
	if($result) {
		header("location: ../clubstore.php");
		exit();
	}else {
		die("Query failed");
	}

?>