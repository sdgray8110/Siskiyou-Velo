<?php

//Connect to database
$connection = mysql_connect("localhost","gray8110","8aU_V{^{,RJC");

//Debug
if (!$connection) {
	die ("Database connection failed: " . mysql_error());
}

//Select database
$new_db = mysql_select_db("gray8110_events",$connection);

//Debug
if (!$new_db) {
	die("Database selection failed: " . mysql_error());
}

?>