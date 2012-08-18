<?php

//Connect to database
$connection = mysql_connect("localhost","gray8110","8aU_V{^{,RJC");

//Debug
if (!$connection) {
	die ("Database connection failed: " . mysql_error());
}

$sql = 'DROP DATABASE gray8110_phpbb3';

if (!sql) {
	die ("Database connection failed: " . mysql_error());
}
else {
mysql_query( $sql, $connection );
}

?>