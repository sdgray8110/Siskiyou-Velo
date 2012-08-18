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
$date = clean($_POST['date']);
$time = clean($_POST['time']);
$start = clean($_POST['start']);
$city = clean($_POST['city']);
$start_link = clean($_POST['start_link']);
$ride_name = clean($_POST['ride_name']);
$pace = clean($_POST['pace']);
$distance = clean($_POST['distance']);
$ride_leader = clean($_POST['ride_leader']);
$phone = clean($_POST['phone']);
$email = clean($_POST['email']);
$rideID = clean($_POST['rideID']);
$nonclub = clean($_POST['nonclub']);
$delete = clean($_POST['delete']);

if ($delete == 'delete') {
	//Create DELTE query
	$qry = "DELETE FROM sv_recurring WHERE sv_recurring . ID = $rideID";

	$result = @mysql_query($qry);
	
	//Check whether the query was successful or not
	if($result) {
		header("location: ../schedule.php");
		exit();
	}else {
		die("Delete Query Failed");
	}
}

else {
	//Create update query
	$qry = "UPDATE sv_recurring SET
			date = '$date',
			time = '$time',
			start = '$start',
			start_city = '$city',
			start_link = '$start_link',
			ride_name = '$ride_name',
			pace = '$pace',
			distance = '$distance',
			ride_leader = '$ride_leader',
			phone = '$phone',
			email = '$email',
			nonclub = '$nonclub'
			WHERE ID = '$rideID'";
	
	$result = @mysql_query($qry);
	
	//Check whether the query was successful or not
	if($result) {
		header("location: ../schedule.php");
		exit();
	}else {
		die("Query failed");
	}
}
?>