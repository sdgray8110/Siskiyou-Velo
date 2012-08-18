<?php
 $File = "YourFile.csv";
 $Handle = fopen($File, 'a');
 //$headers = '"Name","Member Type","City","State","email1","email2","Phone","Age","Date Joined","Expiration","Volunteer","Ride Leader","No Bicycles","City Bike","Road Bike","Mountain","Recumbent","Tandem","Fixie","No Bike","Socialize","Social Rides","Advocacy","Green Lifestyle","Riding Style","Riding Speed"\n';
 //fwrite($Handle, $headers);

include('../includes/db_connect.php');

$query = mysql_query("SELECT * FROM wp_users WHERE DateExpire >= (DATE_ADD(curdate(), INTERVAL -2 MONTH)) ORDER BY wp_users . lastname", $connection);
while ($row = mysql_fetch_array($query)) {

	$name = $row['firstname'].' '.$row['lastname'];
	$type = $row['Type'];
		if ($type == 'I') {$type = 'Individual';}
		elseif ($type == 'F') {$type = 'Family';}
		elseif ($type == 'B') {$type = 'Business';}
	$joined = strtotime($row['DateJoined']);
	$expire = strtotime($row['DateExpire']);
	$joined = date('n/j/Y', $joined);
	$expire = date('n/j/Y', $expire);
	$volunteer = $row['Volunteering'];
	$rideLead = $row['RideLeader'];
	$bicycles = $row['bicycles'];
	$style = $row['riding_style'];
	$speed = $row['riding_speed'];
	$reason = $row['reason_for_joining'];

		if ($volunteer != '') {$volunteer = 'yes';}
		if ($rideLead != '') {$rideLead = 'yes';}

		if (strpos($bicycles,'a') !== false) {$nobike = 'yes';}
		else {$nobike = '';}
		if (strpos($bicycles,'b') !== false) {$cityBike = 'yes';}
		else {$cityBike = '';}
		if (strpos($bicycles,'c') !== false) {$roadBike = 'yes';}
		else {$roadBike = '';}
		if (strpos($bicycles,'d') !== false) {$mountain = 'yes';}
		else {$mountain = '';}
		if (strpos($bicycles,'e') !== false) {$recumbent = 'yes';}
		else {$recumbent = '';}
		if (strpos($bicycles,'f') !== false) {$tandem = 'yes';}
		else {$tandem = '';}
		if (strpos($bicycles,'g') !== false) {$fixie = 'yes';}
		else {$fixie = '';}

		if (strpos($reason,'a') !== false) {$nobike1 = 'yes';}
		else {$nobike1 = '';}
		if (strpos($reason,'b') !== false) {$socialize = 'yes';}
		else {$socialize = '';}
		if (strpos($reason,'c') !== false) {$social_rides = 'yes';}
		else {$social_rides = '';}
		if (strpos($reason,'d') !== false) {$advocacy = 'yes';}
		else {$advocacy = '';}
		if (strpos($reason,'e') !== false) {$green = 'yes';}
		else {$green = '';}

		if ($style == 'a') {$style = 'Rides Alone';}
		elseif ($style == 'b') {$style = 'With Friends';}
		elseif ($style == 'c') {$style = 'Small Groups';}
		elseif ($style == 'd') {$style = 'Any Group Ride';}
		elseif ($style == 'e') {$style = 'Does Not Ride';}

		if ($speed == 'a') {$speed = 'Under 10mph';}
		elseif ($speed == 'b') {$speed = '10-12mph';}
		elseif ($speed == 'c') {$speed = '12-14mph';}
		elseif ($speed == 'd') {$speed = '15+mph';}
		elseif ($speed == 'e') {$speed = 'Does Not Ride';}


$memberstring =  '"'.$name.'","'.$type.'","'.$row['city'].'","'.$row['state'].'","'.$row['email1'].'","'.$row['email2'].'","'.$row['phone'].'","'.$row['Age'].'","'.$joined.'","'.$expire.'","'.$volunteer.'","'.$rideLead.'","'.$nobike.'","'.$cityBike.'","'.$roadBike.'","'.$mountain.'","'.$recumbent.'","'.$tandem.'","'.$fixie.'","'.$nobike1.'","'.$socialize.'","'.$social_rides.'","'.$advocacy.'","'.$green.'","'.$style.'","'.$speed.'" /n';

fwrite($Handle, $memberstring);

}

echo 'done';
fclose($Handle);
?>