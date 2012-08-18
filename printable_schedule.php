<style>
th {text-align:left;}
</style>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <th>Date</th>
    <th>Time</th>
    <th>Start Location</th>
    <th>Route</th>
    <th>Pace</th>
    <th>Miles</th>
    <th>Contact</th>
</tr>

<?php 

//Connect to database
$connection = mysql_connect("localhost","gray8110","8aU_V{^{,RJC");
if (!$connection) {
	die ("Database connection failed: " . mysql_error());
}

$db_select = mysql_select_db("gray8110_svblogs",$connection);

if (!db_select) {
	die("Database selection failed: " . mysql_error());
} 
?>

<?php

$currTime = time();

$result = mysql_query("SELECT * FROM sv_schedule ORDER BY date ASC LIMIT 20", $connection);

if (!result) {
   die("Database query failed: " . mysql_error());
}

while ($row = mysql_fetch_array($result)) {

$timestamp = $row["date"];
$date = date('D. F j', $timestamp);
$time = $row["time"];
$start = $row["start"];
$start_city = $row["start_city"];
$start_link = $row["start_link"];
$ride_name = $row["ride_name"];
$ride_link = $row["ride_link"];
$pace = $row["pace"];
$distance = $row["distance"];
$ride_leader = $row["ride_leader"];
$phone = $row["phone"];
$email = $row["email"];
$ID = $row["ID"];
$now = date(m);
$month = date(m, $timestamp);

if ($now +1 == 13){
	$now = 0;
}

if ($now +1 == $month){

echo "<tr>
		<td><p>" . $date . "</p></td>
		<td><p style='text-transform:capitalize'>" . $time . "</p></td>";

if ($start != '') {
		echo "<td><p>" . $start . " </p></td>";
}

else {
		echo "<td><p>" . strip_tags($start_link) . "</p></td>";
}

		echo" <td><p>" . $ride_name . "</p></td>
		<td><p>" . $pace . "</p></td>
		<td><p>" . $distance . "</p></td>
		<td><p>" . $ride_leader . "<br />" . $phone .  "</p></td>
	  </tr>";
}
}
?>

<?php

$currTime = time();

$result = mysql_query("SELECT * FROM sv_recurring ORDER BY ID ASC LIMIT 20", $connection);

if (!result) {
   die("Database query failed: " . mysql_error());
}

while ($rows = mysql_fetch_array($result)) {

$timestamp1 = $rows["date"];
$time1 = $rows["time"];
$start1 = $rows["start"];
$start_city1 = $rows["start_city"];
$start_link1 = $rows["start_link"];
$ride_name1 = $rows["ride_name"];
$pace1 = $rows["pace"];
$distance1 = $rows["distance"];
$ride_leader1 = $rows["ride_leader"];
$phone1 = $rows["phone"];
$emaila = $rows["email"];
$ID1 = $rows["ID"];

echo "<tr>
		<td><p>" . $timestamp1 . "</p></td>
		<td><p>" . $time1 . "</p></td>
		<td><p>" . $start1 . "</p></td>
		<td><p>" . $ride_name1 . "</p></td>
		<td><p>" . $pace1 . "</p></td>
		<td><p>" . $distance1 . "</p></td>
		<td><p>" . $ride_leader1 . "<br />" . $phone1 .  "</p></td>
	  </tr>";
}

?>


<?php
//Close connection
mysql_close($connection);

?>
    
</table>