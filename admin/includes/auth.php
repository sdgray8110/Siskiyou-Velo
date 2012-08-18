<?php
//Start session
session_start();	

//Check whether the session variable SESS_MEMBER_ID is present or not
if (!isset($_SESSION['SESS_MEMBER_ID']) || (trim($_SESSION['SESS_MEMBER_ID']) == '')) {
	$status = '';
	$name = 'Please Login';
}

else {
	$status = 'Signed in as: ';
	$name = $_SESSION['SESS_FIRST_NAME'].' '.$_SESSION['SESS_LAST_NAME'];
	$from = $name.'<'.$_SESSION['SESS_EMAIL'].'>';
}

?>