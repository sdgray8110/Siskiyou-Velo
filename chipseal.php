<style>
h1 {width:1180px; font-family:verdana; font-size:20px; text-align:center; text-decoration:underline; clear:both;}
div.chipseal {float:left; width: 280px; font-family:verdana; margin-right:15px;}
div.chipseal h2 {font-size:14px; width:100%; border-bottom:1px dashed #ccc;}
div.chipseal h4 {font-size:12px; margin:0;}
div.chipseal h4 span {color:red;}
div.chipseal p {font-size:11px; margin:0 0 10px 0}
</style>

<?php include('includes/db_connect.php');?>
<h1>2009 Jackson County Chip Seal Schedule</h1>

<div class="chipseal">
<h2>Medford Area</h2>
<?php

//DB Query
$chipseal = mysql_query("SELECT * FROM chipseal WHERE location = 'Medford'", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}

while ($row = mysql_fetch_array($chipseal)) {
$road = $row["road"];
$start = $row["start"];
$end = $row["end"];
$miles = $row["miles"];
$url = $row["url"];

echo '<h4>'.$road.'</h4>
	<p><a href="'.$url.'">'.$miles.' miles from '.$start.' to '.$end.'</a></p>';
	
}

?>
</div>

<div class="chipseal">
<h2>Talent Area</h2>
<?php

//DB Query
$chipseal = mysql_query("SELECT * FROM chipseal WHERE location = 'Talent'", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}

while ($row = mysql_fetch_array($chipseal)) {
$road = $row["road"];
$start = $row["start"];
$end = $row["end"];
$miles = $row["miles"];
$url = $row["url"];

echo '<h4>'.$road.'</h4>
	<p><a href="'.$url.'">'.$miles.' miles from '.$start.' to '.$end.'</a></p>';
	
}

?>
</div>

<div class="chipseal">
<h2>Ashland Area</h2>
<?php
//DB Query
$chipseal = mysql_query("SELECT * FROM chipseal WHERE location = 'Ashland'", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}

while ($row = mysql_fetch_array($chipseal)) {
$road = $row["road"];
$start = $row["start"];
$end = $row["end"];
$miles = $row["miles"];
$url = $row["url"];

echo '<h4>'.$road.'</h4>
	<p><a href="'.$url.'">'.$miles.' miles from '.$start.' to '.$end.'</a></p>';
	
}
?>
</div>

<div class="chipseal">
<h2>Jacksonville Area</h2>
<?php

//DB Query
$chipseal = mysql_query("SELECT * FROM chipseal WHERE location = 'Jacksonville'", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}

while ($row = mysql_fetch_array($chipseal)) {
$road = $row["road"];
$start = $row["start"];
$end = $row["end"];
$miles = $row["miles"];
$url = $row["url"];

echo '<h4>'.$road.'</h4>
	<p><a href="'.$url.'">'.$miles.' miles from '.$start.' to '.$end.'</a></p>';
	
}

?>
</div>

<h1>ODOT Chip Seal Schedule For Jackson County</h1>
<div class="chipseal">
<h4>HWY 62 - Crater Lake Highway <span>**COMPLETED**</span></h4>
<p><a href="http://maps.google.com/maps?f=d&source=s_d&saddr=Crater+Lake+Hwy%2FOR-62&daddr=42.692224,-122.600605&hl=en&geocode=FejpigIdhdOv-A%3B&mra=mi&mrsp=1,0&sz=15&sll=42.689975,-122.603559&sspn=0.021923,0.038581&ie=UTF8&ll=42.674611,-122.637978&spn=0.087714,0.154324&z=13">6 miles from Casey State Park to Peyton Bridge</a></p>

<h4>HWY 273 - Old 99 (Callahans)  <span>**COMPLETED**</span></h4>
<p><a href="http://maps.google.com/maps?f=d&source=s_d&saddr=42.13114,-122.612829&daddr=Old+Hwy+99+S+to:Old+Hwy+99+S&hl=en&geocode=%3BFdJJggIdDGex-A%3BFYD9gQIdHimx-A&mra=dme&mrcr=0&mrsp=0&sz=14&via=1&sll=42.126302,-122.615833&sspn=0.044242,0.077162&ie=UTF8&ll=42.102426,-122.606392&spn=0.088518,0.154324&z=13">6.7 miles from HWY 66 to Mt Ashland Access Rd</a></p>

<h4>HWY 66 (Green Springs) <span>**COMPLETED**</span></h4>
<p><a href="http://maps.google.com/maps?f=d&source=s_d&saddr=42.12923,-122.476015&daddr=Green+Springs+Hwy%2FOR-66&hl=en&geocode=%3BFTzPggIdazay-A&mra=dme&mrcr=0&mrsp=0&sz=14&sll=42.135086,-122.488632&sspn=0.044236,0.077162&ie=UTF8&ll=42.13515,-122.495155&spn=0.088472,0.154324&z=13">From MP 12 to MP 17</a></p>
