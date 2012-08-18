<?php
//Connect to database
$connection = mysql_connect("localhost","gray8110","8aU_V{^{,RJC");
if (!$connection) {
	die ("Database connection failed: " . mysql_error());
}

$db_select = mysql_select_db("gray8110_etna",$connection);

if (!db_select) {
	die("Database selection failed: " . mysql_error());
} 

$raceID = $_GET["id"];
$userResult = mysql_query("SELECT * FROM users", $connection);
$userRow = mysql_fetch_array($userResult);
$riderID = $userResult["ID"];
$ridername = $userResult["firstname"] .' '. $userResult["lastname"];

$result = mysql_query("SELECT * FROM obra WHERE ID = $raceID", $connection);

while ($row = mysql_fetch_array($result)) {
$timestamp = strtotime($row["date"]);
$date = date('D F d', $timestamp);
$event = $row["event"];
$eventID = $row["ID"];
$discipline = $row["discipline"];
$definite = $row["definite"];
$maybe = $row["maybe"];
$defTest = strpos($definite, $userID);
$maybTest = strpos($maybe, $userID);

if ($maybe == '0' && $definite == '0') {echo '<p>'.$ridername[52].' currently have this race on their schedule.</p>';}

}
//Close connection
mysql_close($connection);

?>