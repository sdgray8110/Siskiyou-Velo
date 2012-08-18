<ul class="mainSchedule">
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

$result = mysql_query("SELECT * FROM sv_schedule WHERE (date + 72000) > $currTime ORDER BY date ASC LIMIT 20", $connection);

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
$ID = $row["ID"];

echo 
"<li>
	<h3>" . $date . "<a class='quickView' href='' rel='superbox[ajax][ride_overlay.php?rideID=".$rideID."[436x380]' href='#'></a></h3>";
	
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


if ($distance == 'TBD' && $pace == 'TBD') {echo "<p><strong>Distance &amp; Pace TBD</strong></p>";}
	else if ($distance == 'TBD' && $pace != 'TBD') {echo "<p><strong>Distance TBD</strong>, " . $pace . "</p>";}
	else if ($distance != 'TBD' && $pace == 'TBD') {echo "<p><strong>".$distance . " miles, Pace TBD</strong></p>";}
	else {echo "<p><strong>" . $distance . " miles</strong>, " . $pace . "</strong></p>";}

if ($ride_leader == 'No Ride Leader' || $ride_leader == ''){echo "<p>No Ride Leader</p>";}
	else if ($ride_leader != 'No Ride Leader' && $email == '') {echo "<p>Contact: <strong>" . $ride_leader . "</strong> - " . $phone . "</p>";}
	else if ($ride_leader != 'No Ride Leader' && $email != '') {echo "<p>Contact: <a href='mailto:" . $email . "'>" . $ride_leader . "</a> - " . $phone . "</p>";}
	
if (!isset($_SESSION['SESS_MEMBER_ID']) || (trim($_SESSION['SESS_OFFICER']) == '')) {}
else {echo "<p class='blogInfo'><a href='post_schedule_edit.php?id=" . $ID . "'>Edit Ride Details</a></p>";}

echo "</li>";	

}
?>
</ul>

<?php
//Close connection
mysql_close($connection);

?>

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

<h1 style="clear:both;">
	Recurring Rides
    <span><a class="print" href="">Print Schedule</a></span>
</h1>
<ul class="mainSchedule">
<?php

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
$nonclub = $rows["nonclub"];


echo 
"<li>
	<h3>" . $timestamp1 . "</h3>";
	
//RIDE NAME CONDITIONAL	

if ($ride_name1 != 'TBD') {
	echo "<p style='font-weight:bold;'>" . $ride_name1. "</p>";
	}
else {
	echo "<p style='font-weight:bold;'>Route TBD</p>";
	}

//NON VELO RIDE CONDITIONAL

if ($nonclub == 1) {echo "<p style='font-size:10px !important; font-style:italic; padding-bottom:7px;'>* Not A Siskiyou Velo Sponsored Ride</p>";}
	
//START TIME CONDITIONAL	

if ($time1 == "Call") {
	echo "<p><strong>Call For Start Time</strong>";
}

else {
	echo "<p><strong>" . $time1 . "</strong>";
}

//START LOCATION CONDITIONAL

if ($start_link1 != "") {
	echo ": Meet at " . $start_link1 . "</p>";
}

elseif ($start1 == "Varies") {
	echo ": Start Location Varies</p>";
}

else {
	echo ": Meet at " . "<a href='http://maps.google.com/?q=" . $start1 . "+in+". $start_city1 . "+OR'>" . $start1 . " in " . $start_city1 . "</a></p>";
}

//DISTANCE AND PACE CONDITIONAL

if ($distance1 == 'TBD' && $pace1 == 'TBD') {
	echo "<p><strong>Distance &amp; Pace TBD</strong></p>";
}

	else if ($distance1 == 'TBD' && $pace1 != 'TBD') {
		echo "<p><strong>Distance TBD, " . $pace1 . " pace</strong></p>";
	}
	
	else if ($distance1 != 'TBD' && $pace1 == 'TBD') {
		echo $distance1 . " miles, Pace TBD</p>";
	}
	
	else if ($distance1 == 'Varies') {
		echo "<p>Distance " . $distance1 . ", " . $pace1 . "</p>";
	}

else {
	echo "<p><strong>" . $distance1 . " miles</strong>, " . $pace1 . "</p>";
}

//RIDE LEADER CONDITIONAL

if ($ride_leader1 == 'No Ride Leader' || $ride_leader1 == ''){
	echo "<p>No Ride Leader</p>";
}

	else if ($ride_leader1 != 'No Ride Leader' && $emaila == '') {
		echo "<p>Contact: <strong>" . $ride_leader1 . "</strong> - " . $phone1 . "</p>";
	}
	
	else if ($ride_leader1 != 'No Ride Leader' && $emaila != '') {
		echo "<p>Contact: <a href='mailto:" . $emaila . "'>" . $ride_leader1 . "</a> - " . $phone1 . "</p>";
	}

	
if (!isset($_SESSION['SESS_MEMBER_ID']) || (trim($_SESSION['SESS_OFFICER']) == '')) {
}

else {
	echo "<p class='blogInfo'><a href='recurring_schedule_edit.php?id=" . $ID1 . "'>Edit Ride Details</a></p>";
}

echo "</li>";


}
?>
</ul>

<?php
//Close connection
mysql_close($connection);

?>
