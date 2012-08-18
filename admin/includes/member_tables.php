<script type="text/javascript">
	tb_reinit('a.thickbox, area.thickbox, input.thickbox');
</script>

<?php
include('../includes/db_connect.php');
include('../../includes/functions.php');

$queryName = $_GET['type'];
$getFilter = $_GET['filter'];

if ($queryName) {

if ($queryName == 'fullList_tab') {
	{$result = mysql_query("SELECT * FROM wp_users ORDER BY wp_users . lastname", $connection);}
}

elseif ($queryName == 'members60_tab') {
	{$result = mysql_query("SELECT * FROM wp_users WHERE DateExpire >= (DATE_ADD(curdate(), INTERVAL -2 MONTH))  ORDER BY wp_users . lastname", $connection);}	
}

elseif ($queryName == 'pendingActivation') 
	{$result = mysql_query("SELECT * FROM wp_users WHERE pendingPayment = 1  ORDER BY wp_users . lastname", $connection);}
}

///////////////////////////////////////
//* GENERATE SEARCH/FILTER QUERIES **//
///////////////////////////////////////

elseif ($getFilter == 'yes') {
	$getStatus = $_GET['status'];
		if ($getStatus == 'current') {$status = 'DateExpire >= (DATE_ADD(curdate(), INTERVAL -2 MONTH))';}
		elseif ($getStatus == 'noEmail') {$status = 'DateExpire >= (DATE_ADD(curdate(), INTERVAL -2 MONTH)) && email1 ="" && email2 = ""';}
		elseif ($getStatus == 'officer') {$status = 'DateExpire >= (DATE_ADD(curdate(), INTERVAL -2 MONTH)) && officer != ""';}
		else {$status = 'ID != ""';}
	
	$getType = $_GET['advType'];
	$getAge = $_GET['advAge'];
	$getVol = $_GET['advVol'];
	$getRideLead = $_GET['rideLead'];
	$getAdvSearchField = $_GET['advSearchField'];
	$getSearch = $_GET['advSearch'];
	$getNewMember = $_GET['newMembers'];
	$getNewExpire = $_GET['newExpire'];
	
	
	if ($getType) {$type = "&& Type = '$getType'";}
	if ($getAge) {$age = "&& Age = '$getAge'";}
	if ($getVol) {$vol = "&& Volunteering != ''";}
	if ($getRideLead) {$rideLead = "&& RideLeader != ''";}
	if ($getAdvSearchField) {$search = "&& ".$getSearch." LIKE '%$getAdvSearchField%'";}
	if ($getNewMember) {$newMember = "&& DateJoined >= (DATE_ADD(curdate(), INTERVAL -1 MONTH))";}
	if ($getNewExpire) {$newExpire = "&& DateExpire <= (DATE_ADD(curdate(), INTERVAL -2 MONTH)) && DateExpire >= (DATE_ADD(curdate(), INTERVAL -3 MONTH))";}
	
	$result = mysql_query("SELECT * FROM wp_users WHERE ".$status." ".$newMember." ".$newExpire." ".$search." ".$type." ".$age." ".$vol." ".$rideLead." ORDER BY wp_users . lastname", $connection);
}

echo '
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<thead>
<tr>
<th>&nbsp;</th>
<th width="130">Edit</th>
<th>First Name</th>
<th>Last Name</th>
<th>Email 1</th>
<th>Email 2</th>
<th>Street Address</th>
<th>City</th>
<th>State</th>
<th>Zip</th>
<th>Phone</th>
</tr>
</thead>
<tbody>
';

$num = 1;
//Use returned data
while ($row = mysql_fetch_array($result)) {

$id = $row["ID"];
$edit = '<td><a class="thickbox" href="includes/member_edit.php?id='.$id.'&height=500&width=720" title="Member ID: '.$id.'">View/Edit Member &raquo;</a></td>';
$firstname = '<td>'.$row["firstname"].'</td>';
$lastname = '<td>'.$row["lastname"].'</td>';
$address = '<td>'.$row["address"].'</td>';
$city = '<td>'.$row["city"].'</td>';
$state = '<td>'.$row["state"].'</td>';
$zip = '<td>'.$row["zip"].'</td>';
$email1 = '<td>'.$row["email1"].'</td>';
$email2 = '<td>'.$row["email2"].'</td>';
$phone = '<td>'.$row["phone"].'</td>
';

if(checkNum($num) === TRUE){echo '<tr>
';}

else {echo '<tr class ="odd">
';}

echo '<td class="num">'.$num.'</td>'.
$edit.
$firstname.
$lastname.
$email1.
$email2.
$address.
$city.
$state.
$zip.
$phone
.'</tr>';
$num ++;
}

echo '
</tbody>
</table>';

if (mysql_num_rows($result) == 0) {echo '<h3>No Records Found.</h3>';}

?>
