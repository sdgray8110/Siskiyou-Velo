<?php
//Connect to database
$mainConnection = mysql_connect("localhost","gray8110","8aU_V{^{,RJC");

//Debug
if (!$mainConnection) {
	die ("Database connection failed: " . mysql_error());
}

//Select database
$main_db = mysql_select_db("gray8110_svblogs",$mainConnection);

//Debug
if (!$main_db) {
	die("Database selection failed: " . mysql_error());
}

?>