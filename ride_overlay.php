<?php
include('includes/db_connect.php');
$rideID = $_GET['rideID'];
$result = mysql_query("SELECT * FROM sv_schedule WHERE ID = '$rideID'", $connection);

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
$map_link = $row["map_link"];
$terrain = $row["terrain"];
$pace = $row["pace"];
$distance = $row["distance"];
$ride_leader = $row["ride_leader"];
$phone = $row["phone"];
$email = $row["email"];
$notes = $row["notes"];

echo '
<div class="rideOverlay">
	<h1>'.$date.'<a class="close" href="javascript: $.superbox.close()"></a></h1>
    <div class="overlayDetails">
	    <h2>';
		
		if ($ride_link == "" && $map_link == "") {echo $ride_name;}
		else if ($ride_link != "") {echo "<a href='" . $ride_link . "'>". $ride_name ."</a>";}
		else {echo "<a href='" . $map_link . "'>". $ride_name ."</a>";}		
		
		echo '</h2>
        
        <dl>
        	<dd>Start Time:</dd>
            <dt>'.$time.'</dt>
			
        	<dd>Location:</dd>
            <dt>';
			
			if ($start_link != "") {echo $start_link;}
			elseif ($startlink == "" && $start != "") {echo "<a href='http://maps.google.com/?q=" . $start . "+in+". $start_city . "+OR'>" . $start . " in " . $start_city . "</a>";}
			elseif ($startlink == "" && $start == "") {echo "<a href='http://maps.google.com/?q=" . $start_city . "+OR'>" . $start_city . "</a>";}
			
			echo '</dt>
            
        	<dd>Distance:</dd>
            <dt>';

			if ($distance == 'TBD' || $distance == 'TBA') {echo "To Be Decided...";}
			else {echo $distance . " miles";}
			
			echo'</dt>
            
        	<dd>Pace:</dd>	
            <dt>';
			
			if ($pace == 'TBD' || $pace == 'TBA') {echo "To Be Decided...";}
			else {echo $pace;}
			
			echo '</dt>
            
        	<dd>Terrain:</dd>
            <dt>'.$terrain.'</dt>
            
        	<dd>Ride Leader:</dd>
            <dt>';
			
			if ($ride_leader == 'No Ride Leader' || $ride_leader == ''){echo "No Ride Leader";}
			else if ($ride_leader != 'No Ride Leader' && $email == '') {echo "<strong>" . $ride_leader . "</strong>";}			
			else if ($ride_leader != 'No Ride Leader' && $email != '') {echo "<a href='mailto:" . $email . "'>" . $ride_leader . "</a>";}
			if ($phone != '') {echo " - " . $phone;}
			
			
			echo '</dt>
            
        	<dd class="comments">Comments:</dd>
            <dt class="comments">'.$notes.'</dt>
        </dl>
    </div>
    <div class="overlayBottom"></div>
</div>
';

}
?>

<?php
//Close connection
mysql_close($connection);

?>