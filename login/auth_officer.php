<?php

function curPageName() {
 return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
}
$pageName = curPageName();

	if (strpos($pageName, 'post') !== false) {

		//Start session
		session_start();
		
		//Check whether the session variable SESS_MEMBER_ID is present or not
		if (trim($_SESSION['SESS_OFFICER']) == '' || !isset($_SESSION['SESS_OFFICER']))  {
			header("location: ./access-denied-officers.php");
		}
		
		else {}
	
	}
	
	else {
		//Start session
		session_start();
	}
		

?>

