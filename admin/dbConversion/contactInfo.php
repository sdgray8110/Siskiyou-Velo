<?php
include('../../includes/db_connect.php');
$result = mysql_query("SELECT * FROM wp_users", $connection);

//Use returned data
while ($row = mysql_fetch_array($result)) {

$id = $row["ID"];
$pC = $row["DisplayPhone"];
$eC = $row["DisplayEmail1"];
if ($pC == 1 && $eC == 1) {$contact = '3';}
if ($pC == 1 && $eC == 0) {$contact = '2';}
if ($pC == 0 && $eC == 1) {$contact = '1';}
if ($pC == 0 && $eC == 0) {$contact = '0';}

//Create INSERT query
$qry = "UPDATE wp_users SET
	DisplayContact = '$contact'
	WHERE ID = '$id'";	

$result = @mysql_query($qry, $connection);

//Check whether the query was successful or not
if($result) {echo 'Success';}
else {
	die("Query failed");
}
}

?>