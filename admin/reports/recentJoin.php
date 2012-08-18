<?php
 $File = "recentJoin.csv";
 unlink($File);
 $Handle = fopen($File, 'a');
 $headers = array("Name","Date Joined","Expiration Date");
 fputcsv($Handle, $headers);

include('../includes/db_connect.php');

$query = mysql_query("SELECT * FROM wp_users WHERE DateJoined >= (DATE_ADD(curdate(), INTERVAL -1 MONTH)) && DateJoined <= (DATE_ADD(curdate(), INTERVAL 0 MONTH)) ORDER BY wp_users . lastname", $connection);
while ($row = mysql_fetch_array($query)) {

	$name = $row['firstname'].' '.$row['lastname'];
	$joined = strtotime($row['DateJoined']);
	$expire = strtotime($row['DateExpire']);
	$joined = date('n/j/Y', $joined);
	$expire = date('n/j/Y', $expire);

$memberstring =  array($name,$joined,$expire);
fputcsv($Handle, $memberstring);

}

fclose($Handle);
?>