<?php
	//Start session
	session_start();
	
	//Include database connection details
require_once('login/config.php');
	
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

$delete = $_POST['deletePost'];
$header = clean($_POST['header']);
$body = clean($_POST['FCKeditor1']);
$post_id = clean($_POST['postID']);

if ($delete == 'true') {
    $qry = 'DELETE FROM sv_blogposts WHERE ID = ' . $post_id;

	$result = @mysql_query($qry);

	//Check whether the query was successful or not
	if($result) {
		header("location: ../");
		exit();
	}else {
		die("Query failed");
    }    
} else {

	//Create update query
	$qry = "UPDATE sv_blogposts SET
			header = '$header',
			body = '$body'
			WHERE ID = '$post_id'";
	
	$result = @mysql_query($qry);
	
	//Check whether the query was successful or not
	if($result) {
		header("location: ../");
		exit();
	}else {
		die("Query failed");
    }
}
?>