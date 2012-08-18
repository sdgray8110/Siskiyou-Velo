<?php

echo '<h1>'.$name.'&rsquo;s '.$varYear.' Racing Schedule</h1>
		<p class="postInfo"><span>Based on '.$varYear.' OBRA schedule and select Non-OBRA events.</span></p>
		<div class="post">
';

$result = mysql_query("SELECT * FROM obra WHERE definite LIKE '%$userID%' OR maybe LIKE '%$userID%' ORDER BY date", $connection);

if (mysql_num_rows($result) == '0') {
	echo '<p>'.$name.' has not submitted a '.$varYear.' schedule</p>';
}
else {
	echo '<ul class=riderSched>';	
while ($row = mysql_fetch_array($result)) {
$timestamp = strtotime($row["date"]);
$date = date('D F d', $timestamp);
$event = $row["event"];
$eventID = $row["ID"];
$discipline = $row["discipline"];
$definite = $row["definite"];
$maybe = $row["maybe"];
$cancelled = $row["cancelled"];
$defTest = strpos($definite, $userID);
$maybTest = strpos($maybe, $userID);
$mtb = "Mountain Bike";

if ($cancelled != 1) {

if ($maybTest !== false) {
	echo '
<li>
    <div class="schedLeft">
        <p class="date">'.$date.'</p>
        <p class="eventName"><a href="race.php?id='.$eventID.'&rider='.$riderID.'">'.$event.'</a></p>
    </div>
    
    <div class="schedMid">
        <p class="date">Event Type</p>';
if ($discipline == $mtb) {
echo 	'<p class="eventName">MTB</p>';
}
else {
echo     '<p class="eventName">'.$discipline.'</p>';
}
echo'    </div>
    
    <div class="schedRight">
        <p class="date">Attendance</p>
        <p class="eventMaybe">Possible</p>
    </div>
</li>';
}

elseif ($defTest !== false) {
	echo  '
<li>
    <div class="schedLeft">
        <p class="date">'.$date.'</p>
        <p class="eventName"><a href="race.php?id='.$eventID.'&rider='.$riderID.'">'.$event.'</a></p>
    </div>
    
    <div class="schedMid">
        <p class="date">Event Type</p>';
if ($discipline == $mtb) {
echo 	'<p class="eventName">MTB</p>';
}
else {
echo     '<p class="eventName">'.$discipline.'</p>';
}
echo'    </div>
    
    <div class="schedRight">
        <p class="date">Attendance</p>
        <p class="eventName">Definite</p>
    </div>
</li>';
}
}
}
echo '</ul>';
}
?>
	<p style="text-align:right;"><a href="team.php">Back to team page &raquo;</a></p></div>
<?php
//Close connection
mysql_close($connection);

?>

