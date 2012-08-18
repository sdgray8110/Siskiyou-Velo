<?php
//Start session
session_start();

function curPageName() {
 return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
}
$pageName = curPageName();

	if (strpos($pageName, 'member' ) !== false) {

		//Check whether the session variable SESS_MEMBER_ID is present or not
		if (!isset($_SESSION['SESS_MEMBER_ID']) || (trim($_SESSION['SESS_MEMBER_ID']) == '')) {
			header("location: ./access-denied.php");
		}

		else {}

	}

?>