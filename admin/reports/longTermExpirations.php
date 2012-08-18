<?php
 $File = "longTermExpirations.csv";
 unlink($File);
 $Handle = fopen($File, 'a');
 $headers = array("Name","Email Address","Date Joined","Expiration Date");
 fputcsv($Handle, $headers);

include('../includes/db_connect.php');

$query = mysql_query("SELECT * FROM wp_users WHERE DateExpire < (DATE_ADD(curdate(), INTERVAL -1 MONTH)) && DateExpire > (DATE_ADD(curdate(), INTERVAL -6 MONTH)) ORDER BY wp_users . DateExpire ASC", $connection);
while ($row = mysql_fetch_array($query)) {

	$name = $row['firstname'].' '.$row['lastname'];
    $email = $row['email1'];
	$joined = strtotime($row['DateJoined']);
	$expire = strtotime($row['DateExpire']);
	$joined = date('n/j/Y', $joined);
	$expire = date('n/j/Y', $expire);

$memberstring =  array($name,$email,$joined,$expire);
fputcsv($Handle, $memberstring);

}

fclose($Handle);
?>