<div class="featuredRide">
    <h1>Featured Rides &raquo;</h1>

<?php
if ($_GET['rideRotate']) {
    $includes = '../includes/';
    }
else {
    $includes = '';
}

include ($includes.'db_connect.php');

//DB Query
$result = mysql_query("SELECT * FROM sv_rides WHERE homepage ='1' ORDER BY RAND()", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}
$rideCount = 1;
while ($row = mysql_fetch_array($result)) {
$id = $row['ID'];
$ridename = $row['ridename'];
$description = $row["description"];
$shortDesc = shortenText($description);

if ($rideCount == 1) {
	echo '<div class="thisRide active">
	';
}

else {
	echo '
<div class="thisRide">
';
}

echo '
    <a href="ride_detail.php?id='.$id.'"><img id="link" src="images/rides/homethumbs/'.$id.'.jpg"width="188" height="75" border="0" alt="Featured Ride - '.$ridename.'" /></a>
    <h3>'.$ridename.'</h3>
    <p>'.$shortDesc.' &hellip;</p>
    <p><a href="ride_detail.php?id=5">Continue Reading &raquo;</a></p>
</div>';

$rideCount ++;
}

?>

</div>