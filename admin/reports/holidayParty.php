<?php
$adminContext = '/home/gray8110/public_html/admin/';
include($adminContext . 'includes/events/db_connect.php');

 $File = "holidayParty.csv";
 unlink($File);
 $Handle = fopen($File, 'a');
 $headers = array("Name","Email","Members Attending","Non-Memebers Attending", "Prime Rib", "Chicken", "Lasagna", "MLC Volunteer");
 fputcsv($Handle, $headers);

$query = mysql_query("SELECT * FROM registrants WHERE paypal = 'true'", $connection);
while ($row = mysql_fetch_array($query)) {

	$name = $row['firstName'].' '.$row['lastName'];
    $email = $row['email'];
	$membersAttending = $row['memberAttendees'];
	$nonMembersAttending = $row['nonMemberAttendees'];
    $primeRib = $row['merch1'];
    $chicken = $row['merch2'];
    $lasagna = $row['merch3'];
    $mlcVolunteer = $row['mlcVolunteer'];
    

$memberstring =  array($name,$email,$membersAttending,$nonMembersAttending,$primeRib,$chicken,$lasagna,$mlcVolunteer);
fputcsv($Handle, $memberstring);
}

$sumQuery = mysql_query("
SELECT SUM(memberAttendees) AS memberTotal, SUM(nonMemberAttendees) AS nonMemberTotal, SUM(merch1) AS merch1Total, SUM(merch2) AS merch2Total, SUM(merch3) AS merch3Total, SUM(mlcVolunteer) AS mlcVolunteerTotal FROM gray8110_events.registrants WHERE paypal = 'true'", $connection);
while ($totalRow = mysql_fetch_array($sumQuery)) {

    $memberTotal = $totalRow["memberTotal"];
    $nonMemberTotal = $totalRow["nonMemberTotal"];
    $merch1 = $totalRow["merch1Total"];
    $merch2 = $totalRow["merch2Total"];
    $merch3 = $totalRow["merch3Total"];
    $mlcVolunteer = $totalRow["mlcVolunteerTotal"];


$totalstring = array('Totals','',$memberTotal,$nonMemberTotal,$merch1,$merch2,$merch3,$mlcVolunteer);
fputcsv($Handle, $totalstring);
}

fclose($Handle);
?>