<?php
$target = "/home/gray8110/public_html/images/classifieds/";
$target = $target . basename( $_FILES['uploaded']['name']) ;
$ok=1;

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
$image = basename( $_FILES['uploaded']['name']);
$post = clean($_POST['FCKeditor1']);
$memberid = clean($_POST['memberID']);

if ($image != '') {

//This is our size condition
if ($uploaded_size > 3000000)
{
echo "Your file is too large.<br>";
$ok=0;
}

//This is our limit file type condition
if ($uploaded_type =="text/php")
{
echo "No PHP files<br>";
$ok=0;
}

//Here we check that $ok was not set to 0 by an error
if ($ok==0)
{
Echo "Sorry your file was not uploaded";
}

//If everything is ok we try to upload it
else
{
if(move_uploaded_file($_FILES['uploaded']['tmp_name'], $target))
{


	//Create INSERT query
	$qry = "INSERT INTO gray8110_svblogs . classifieds(
			name, email, phone, item, price, image, post, member_id
			)
			VALUES ('$name', '$email', '$phone', '$item', '$price', '$image', '$post', '$memberid')";
	
	$result = @mysql_query($qry);
	
	//Check whether the query was successful or not
	if($result) {
		header("location: ../clubstore.php");
		exit();
	}else {
		die("Query failed");
	}
}

else
{
echo "Sorry, there was a problem uploading your file.";
}
}
}

else {

	//Create INSERT query
	$qry = "INSERT INTO gray8110_svblogs . classifieds(
			name, email, phone, item, price, post, member_id
			)
			VALUES ('$name', '$email', '$phone', '$item', '$price', '$post', '$memberid')";
	
	$result = @mysql_query($qry);
	
	//Check whether the query was successful or not
	if($result) {
		header("location: ../clubstore.php");
		exit();
	}else {
		die("Query failed");
	}

}


?>