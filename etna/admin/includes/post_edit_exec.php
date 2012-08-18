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
$postID = $_POST['postID'];
$delete = $_POST['delete'];

if ($delete != '') {

	//Create DELETE query
$qry = "DELETE FROM posts WHERE posts . ID = $delete";

}

else {

	//Create INSERT query
if ($custom_cat == '') {	
	$qry = "UPDATE posts SET
			title = '$title',
			description = '$description',
			tags = '$tags',
			category = '$category',
			post = '$post',
			draft = '$draft'
			WHERE $postID = ID";
}

else {
	$qry = "UPDATE posts SET
			title = '$title',
			description = '$description',
			tags = '$tags',
			category = '$custom_cat',
			post = '$post',
			draft = '$draft'
			WHERE $postID = ID";
}
}
	
	$result = @mysql_query($qry);
	//Check whether the query was successful or not
	if($result) {
		header("location: ../?pageID=posts");
		exit();
	}else {
		die("Query failed");
	}

?>