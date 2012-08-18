<?php include('db_connect.php') ?>

<?php	
	
	//Function to sanitize values received from the form. Prevents SQL injection
	function clean($str) {
		$str = @trim($str);
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		return mysql_real_escape_string($str);
	}

	//Sanitize the POST values
$name = clean($_POST['name']);
$honeypot = $_POST['email'];
$email = clean($_POST['scooby']);
$subject = clean($_POST['subject']);
$comment = clean($_POST['comment']);
$postID = clean($_POST['postID']);
$title = clean($_POST['title']);
$ipAddress = $_POST['ipAddress'];
$blockedIPs = array('113.71.231.44', '218.64.218.114');
 
 	if (!$honeypot && in_array($ipAddress, $blockedIPs) == false) {
		$addClient  = "INSERT INTO sv_blogposts_comments (name,email,title,subject,comment,postID,ipAddress) VALUES ('$name','$email','$title','$subject','$comment','$postID','$ipAddress')";
		mysql_query($addClient) or die(mysql_error());
	}
 
?>