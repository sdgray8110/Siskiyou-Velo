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

$result = mysql_query("SELECT * FROM sv_schedule WHERE (date + 72000) > $currTime ORDER BY date ASC LIMIT 4", $connection);

if (!result) {
   die("Database query failed: " . mysql_error());
}

while ($row = mysql_fetch_array($result)) {

$rideID = $row["ID"];
$timestamp = $row["date"];
$date = date('D. F j', $timestamp);
$time = $row["time"];
$start = $row["start"];
$start_city = $row["start_city"];
$start_link = $row["start_link"];
$ride_name = $row["ride_name"];
$ride_link = $row["ride_link"];
$map_link = $row["map_link"];
$terrain = $row["terrain"];
$pace = $row["pace"];
$distance = $row["distance"];
$ride_leader = $row["ride_leader"];
$phone = $row["phone"];
$email = $row["email"];
$notes = $row["notes"];

echo 
"<li>
	<h3>" . $date . "</h3>
	<a class='quickView' href='' rel='superbox[ajax][ride_overlay.php?rideID=".$rideID."[436x380]' href='#'></a>";

if ($ride_link == "" && $map_link == "") {	
	echo "<p>" . $ride_name. "</p>";
}

else if ($ride_link != "") {
	echo "<p><a href='" . $ride_link . "'>". $ride_name ."</a></p>";
}

else {
	echo "<p><a href='" . $map_link . "'>". $ride_name ."</a></p>";
}

if ($start_link != "") {echo "<p><strong>" . $time . "</strong>: Meet at " . $start_link . "</p>";}
elseif ($startlink == "" && $start != "") {echo "<p><strong>" . $time . "</strong>: Meet at " . "<a href='http://maps.google.com/?q=" . $start . "+in+". $start_city . "+OR'>" . $start . " in " . $start_city . "</a></p>";}
elseif ($startlink == "" && $start == "") {echo "<p><strong>" . $time . "</strong>: Meet in " . "<a href='http://maps.google.com/?q=" . $start_city . "+OR'>" . $start_city . "</a></p>";}


if ($distance == 'TBD' && $pace == 'TBD') {
	echo "<p><strong>Distance &amp; Pace TBD</strong></p>";
}

	else if ($distance == 'TBD' && $pace != 'TBD') {
		echo "<p><strong>Distance TBD</strong>, " . $pace . "</p>";
	}
	
	else if ($distance != 'TBD' && $pace == 'TBD') {
		echo "<p><strong>".$distance . " miles</strong>, Pace TBD</p>";
	}

else {
	echo "<p><strong>" . $distance . " miles</strong>, " . $pace . "</p>";
}

if ($ride_leader == 'No Ride Leader' || $ride_leader == ''){
	echo "<p>No Ride Leader</p>";
}

	else if ($ride_leader != 'No Ride Leader' && $email == '') {
		echo "<p>Contact: <strong>" . $ride_leader . "</strong> - " . $phone . "</p>";
	}
	
	else if ($ride_leader != 'No Ride Leader' && $email != '') {
		echo "<p>Contact: <a href='mailto:" . $email . "'>" . $ride_leader . "</a> - " . $phone . "</p>";
	}
echo "</li>";	

}
?>

<?php
//Close connection
mysql_close($connection);

?>