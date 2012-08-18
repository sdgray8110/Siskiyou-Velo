<style>
th, td {font-size:10px; font-family:Verdana, Geneva, sans-serif; text-align:left; padding:2px;}
th em {font-weight:normal;}
td.radio, th.radio {text-align:center; vertical-align:middle;}
tr {background:#ebebec;}
tr.odd {background:#ccc;}
th, tr:hover {background:#333; color:#fff;}
table {margin:5px 0 10px 0; border:1px solid #333;}
</style>

<?php
$riderID = $_SESSION['SESS_MEMBER_ID'];

function checkNum($num){
  return ($num%2) ? TRUE : FALSE;
}

//Connect to database
$connection = mysql_connect("localhost","gray8110","8aU_V{^{,RJC");
if (!$connection) {
	die ("Database connection failed: " . mysql_error());
}

$db_select = mysql_select_db("gray8110_etna",$connection);

if (!db_select) {
	die("Database selection failed: " . mysql_error());
} 
?>
        <div id="mainContainer">
        
            <h1 class="title">Blog Administration</h1>
            
            <div class="mainBody">
                <div class="mainBody_top"></div>
<form id="schedule" name="schedule" action="includes/schedule.php" method="POST" />

<div style="width:750px; float:left; margin:10px 27px;">
<h2>Road Races</h2>
<table width="750" cellpadding="0" cellspacing="0">
	<tr>
		<th width="145">Start Date</th>
        <th width="266">Event</th>
        <th width="115" class="check">Definitely Racing</th>
        <th width="124" class="check">Would Like to Race</th>
        <th width="100" class="check">Clear Selection</th>
	</tr>

<?php
$result = mysql_query("SELECT * FROM obra WHERE discipline = 'Road' ORDER BY date ASC", $connection);

if (!result) {
   die("Database query failed: " . mysql_error());
}
$position = 1;
while ($row = mysql_fetch_array($result)) {
$timestamp = strtotime($row["date"]);
$date = date('D F d', $timestamp);
$event = $row["event"];
$eventID = $row["ID"];
$name = $_SESSION['SESS_FIRST_NAME'] . ' ' . $_SESSION['SESS_LAST_NAME'] . '--';
$member_ID = ' '.$_SESSION['SESS_MEMBER_ID'].' ';
$definite = $row["definite"];
$maybe = $row["maybe"];

if(checkNum($position) === TRUE){
echo '<tr>';
}

else {
echo '<tr class="odd">';
}
echo   '<td>'.$date.'</td>
    	<td width="255">'.$event.'</td>
    	<td class="radio"><input align="middle" type="radio" name="'.$eventID.'" id="definite" value="definite"'; 
if (strpos($definite, $member_ID) != false) {
	echo 'checked=checked';}
	echo ' /></td>
    	<td class="radio"><input align="middle" type="radio" name="'.$eventID.'" id="maybe" value="maybe"'; 
if (strpos($maybe, $member_ID) != false) {
	echo 'checked=checked';}
	echo ' /></td>
		<td class="radio"><input align="middle" type="radio" name="'.$eventID.'" id="no" value="clear"  /></td> 
    </tr>
	';
$position++; // increment the position
}
?>

</table>

<h2>Stage Races</h2>
<table width="750" cellpadding="0" cellspacing="0">
	<tr>
		<th width="145">Start Date</th>
        <th width="266">Event</th>
        <th width="115" class="check">Definitely Racing</th>
        <th width="124" class="check">Would Like to Race</th>
        <th width="100" class="check">Clear Selection</th>
	</tr>

<?php
$result = mysql_query("SELECT * FROM obra WHERE discipline = 'Stage' ORDER BY date ASC", $connection);

if (!result) {
   die("Database query failed: " . mysql_error());
}
$position = 1;
while ($row = mysql_fetch_array($result)) {
$timestamp = strtotime($row["date"]);
$date = date('D F d', $timestamp);
$event = $row["event"];
$eventID = $row["ID"];
$name = $_SESSION['SESS_FIRST_NAME'] . ' ' . $_SESSION['SESS_LAST_NAME'] . '--';
$member_ID = ' '.$_SESSION['SESS_MEMBER_ID'].' ';
$definite = $row["definite"];
$maybe = $row["maybe"];

if(checkNum($position) === TRUE){
echo '<tr>';
}

else {
echo '<tr class="odd">';
}
echo   '<td>'.$date.'</td>
    	<td width="255">'.$event.'</td>
    	<td class="radio"><input align="middle" type="radio" name="'.$eventID.'" id="definite" value="definite"'; 
if (strpos($definite, $member_ID) != false) {
	echo 'checked=checked';}
	echo ' /></td>
    	<td class="radio"><input align="middle" type="radio" name="'.$eventID.'" id="maybe" value="maybe"'; 
if (strpos($maybe, $member_ID) != false) {
	echo 'checked=checked';}
	echo ' /></td>
		<td class="radio"><input align="middle" type="radio" name="'.$eventID.'" id="no" value="clear"  /></td> 
    </tr>
	';
$position++; // increment the position
}
?>

</table>

<h2>Time Trials</h2>
<table width="750" cellpadding="0" cellspacing="0">
	<tr>
		<th width="145">Start Date</th>
        <th width="266">Event</th>
        <th width="115" class="check">Definitely Racing</th>
        <th width="124" class="check">Would Like to Race</th>
        <th width="100" class="check">Clear Selection</th>
	</tr>

<?php
$result = mysql_query("SELECT * FROM obra WHERE discipline = 'Time Trial' ORDER BY date ASC", $connection);

if (!result) {
   die("Database query failed: " . mysql_error());
}

while ($row = mysql_fetch_array($result)) {
$timestamp = strtotime($row["date"]);
$date = date('D F d', $timestamp);
$event = $row["event"];
$eventID = $row["ID"];
$name = $_SESSION['SESS_FIRST_NAME'] . ' ' . $_SESSION['SESS_LAST_NAME'] . '--';
$member_ID = ' '.$_SESSION['SESS_MEMBER_ID'].' ';
$definite = $row["definite"];
$maybe = $row["maybe"];

if(checkNum($position) === TRUE){
echo '<tr>';
}

else {
echo '<tr class="odd">';
}
echo   '<td>'.$date.'</td>
    	<td width="255">'.$event.'</td>
    	<td class="radio"><input align="middle" type="radio" name="'.$eventID.'" id="definite" value="definite"'; 
if (strpos($definite, $member_ID) != false) {
	echo 'checked=checked';}
	echo ' /></td>
    	<td class="radio"><input align="middle" type="radio" name="'.$eventID.'" id="maybe" value="maybe"'; 
if (strpos($maybe, $member_ID) != false) {
	echo 'checked=checked';}
	echo ' /></td>
		<td class="radio"><input align="middle" type="radio" name="'.$eventID.'" id="no" value="clear"  /></td> 
    </tr>
	';
$position++; // increment the position
}
?>

</table>

<h2>Criteriums</h2>
<table width="750" cellpadding="0" cellspacing="0">
	<tr>
		<th width="145">Start Date</th>
        <th width="266">Event</th>
        <th width="115" class="check">Definitely Racing</th>
        <th width="124" class="check">Would Like to Race</th>
        <th width="100" class="check">Clear Selection</th>
	</tr>

<?php
$result = mysql_query("SELECT * FROM obra WHERE discipline = 'Criterium' ORDER BY date ASC", $connection);

if (!result) {
   die("Database query failed: " . mysql_error());
}

while ($row = mysql_fetch_array($result)) {
$timestamp = strtotime($row["date"]);
$date = date('D F d', $timestamp);
$event = $row["event"];
$eventID = $row["ID"];
$name = $_SESSION['SESS_FIRST_NAME'] . ' ' . $_SESSION['SESS_LAST_NAME'] . '--';
$member_ID = ' '.$_SESSION['SESS_MEMBER_ID'].' ';
$definite = $row["definite"];
$maybe = $row["maybe"];

if(checkNum($position) === TRUE){
echo '<tr>';
}

else {
echo '<tr class="odd">';
}
echo   '<td>'.$date.'</td>
    	<td width="255">'.$event.'</td>
    	<td class="radio"><input align="middle" type="radio" name="'.$eventID.'" id="definite" value="definite"'; 
if (strpos($definite, $member_ID) != false) {
	echo 'checked=checked';}
	echo ' /></td>
    	<td class="radio"><input align="middle" type="radio" name="'.$eventID.'" id="maybe" value="maybe"'; 
if (strpos($maybe, $member_ID) != false) {
	echo 'checked=checked';}
	echo ' /></td>
		<td class="radio"><input align="middle" type="radio" name="'.$eventID.'" id="no" value="clear"  /></td> 
    </tr>
	';
$position++; // increment the position
}
?>

</table>

<h2>Mountain Bike &amp; Downhill</h2>
<table width="750" cellpadding="0" cellspacing="0">
	<tr>
		<th width="145">Start Date</th>
        <th width="266">Event</th>
        <th width="115" class="check">Definitely Racing</th>
        <th width="124" class="check">Would Like to Race</th>
        <th width="100" class="check">Clear Selection</th>
	</tr>

<?php
$result = mysql_query("SELECT * FROM obra WHERE discipline = 'Downhill' OR  discipline = 'Mountain Bike' ORDER BY date ASC", $connection);

if (!result) {
   die("Database query failed: " . mysql_error());
}

while ($row = mysql_fetch_array($result)) {
$timestamp = strtotime($row["date"]);
$date = date('D F d', $timestamp);
$event = $row["event"];
$eventID = $row["ID"];
$name = $_SESSION['SESS_FIRST_NAME'] . ' ' . $_SESSION['SESS_LAST_NAME'] . '--';
$member_ID = ' '.$_SESSION['SESS_MEMBER_ID'].' ';
$definite = $row["definite"];
$maybe = $row["maybe"];

if(checkNum($position) === TRUE){
echo '<tr>';
}

else {
echo '<tr class="odd">';
}
echo   '<td>'.$date.'</td>
    	<td width="255">'.$event.'</td>
    	<td class="radio"><input align="middle" type="radio" name="'.$eventID.'" id="definite" value="definite"'; 
if (strpos($definite, $member_ID) != false) {
	echo 'checked=checked';}
	echo ' /></td>
    	<td class="radio"><input align="middle" type="radio" name="'.$eventID.'" id="maybe" value="maybe"'; 
if (strpos($maybe, $member_ID) != false) {
	echo 'checked=checked';}
	echo ' /></td>
		<td class="radio"><input align="middle" type="radio" name="'.$eventID.'" id="no" value="clear"  /></td> 
    </tr>
	';
$position++; // increment the position
}
?>

</table>

<h2>Cyclocross</h2>
<table width="750" cellpadding="0" cellspacing="0">
	<tr>
		<th width="145">Start Date</th>
        <th width="266">Event</th>
        <th width="115" class="check">Definitely Racing</th>
        <th width="124" class="check">Would Like to Race</th>
        <th width="100" class="check">Clear Selection</th>
	</tr>

<?php
$result = mysql_query("SELECT * FROM obra WHERE discipline = 'Cyclocross' ORDER BY date ASC", $connection);

if (!result) {
   die("Database query failed: " . mysql_error());
}

while ($row = mysql_fetch_array($result)) {
$timestamp = strtotime($row["date"]);
$date = date('D F d', $timestamp);
$event = $row["event"];
$eventID = $row["ID"];
$name = $_SESSION['SESS_FIRST_NAME'] . ' ' . $_SESSION['SESS_LAST_NAME'] . '--';
$member_ID = ' '.$_SESSION['SESS_MEMBER_ID'].' ';
$definite = $row["definite"];
$maybe = $row["maybe"];

if(checkNum($position) === TRUE){
echo '<tr>';
}

else {
echo '<tr class="odd">';
}
echo   '<td>'.$date.'</td>
    	<td width="255">'.$event.'</td>
    	<td class="radio"><input align="middle" type="radio" name="'.$eventID.'" id="definite" value="definite"'; 
if (strpos($definite, $member_ID) != false) {
	echo 'checked=checked';}
	echo ' /></td>
    	<td class="radio"><input align="middle" type="radio" name="'.$eventID.'" id="maybe" value="maybe"'; 
if (strpos($maybe, $member_ID) != false) {
	echo 'checked=checked';}
	echo ' /></td>
		<td class="radio"><input align="middle" type="radio" name="'.$eventID.'" id="no" value="clear"  /></td> 
    </tr>
	';
$position++; // increment the position
}
?>

</table>

<h2>Non-OBRA Events</h2>
<table width="750" cellpadding="0" cellspacing="0">
	<tr>
		<th width="145">Start Date</th>
        <th width="266">Event</th>
        <th width="115" class="check">Definitely Racing</th>
        <th width="124" class="check">Would Like to Race</th>
        <th width="100" class="check">Clear Selection</th>
	</tr>

<?php
$result = mysql_query("SELECT * FROM obra WHERE discipline = 'Non-OBRA' ORDER BY date ASC", $connection);

if (!result) {
   die("Database query failed: " . mysql_error());
}

while ($row = mysql_fetch_array($result)) {
$timestamp = strtotime($row["date"]);
$date = date('D F d', $timestamp);
$event = $row["event"];
$eventID = $row["ID"];
$name = $_SESSION['SESS_FIRST_NAME'] . ' ' . $_SESSION['SESS_LAST_NAME'] . '--';
$member_ID = ' '.$_SESSION['SESS_MEMBER_ID'].' ';
$definite = $row["definite"];
$maybe = $row["maybe"];

if(checkNum($position) === TRUE){
echo '<tr>';
}

else {
echo '<tr class="odd">';
}
echo   '<td>'.$date.'</td>
    	<td width="255">'.$event.'</td>
    	<td class="radio"><input align="middle" type="radio" name="'.$eventID.'" id="definite" value="definite"'; 
if (strpos($definite, $member_ID) != false) {
	echo 'checked=checked';}
	echo ' /></td>
    	<td class="radio"><input align="middle" type="radio" name="'.$eventID.'" id="maybe" value="maybe"'; 
if (strpos($maybe, $member_ID) != false) {
	echo 'checked=checked';}
	echo ' /></td>
		<td class="radio"><input align="middle" type="radio" name="'.$eventID.'" id="no" value="clear"  /></td> 
    </tr>
	';
$position++; // increment the position
}

echo '<input type="hidden" name="riderID" id="riderID" value="'.$riderID .'">';
echo '<input type="hidden" name="riderName" id="riderName" value="'.$name .'">';

?>
</table>
<p class="sublink" style="float:left"><a href="http://www.etna-desalvo.com/admin/?pageID=edit_schedule">Add New Event to Calendar</a></p>
<input type="submit" name="submit" class="mainSubmit" style="float:right;" value="Post Schedule" />
</div>
                <div class="mainBody_bottom"></div>
            </div>
                        
		</div>
</form>
