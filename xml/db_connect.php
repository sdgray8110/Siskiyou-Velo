<?php

//Connect to database
$connection = mysql_connect("med-mysql.musiciansfriend.com:3306","web_utils","web_utils");

//Debug
if (!$connection) {
	die ("Database connection failed: " . mysql_error());
}

?>