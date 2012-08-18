<script type="text/javascript">
$(document).ready(function() {
	$('#filterButton').click(function() {
		var query = $('#filter').serialize();
		$('div.test').load('includes/email_selects.php?' + query);
//		alert();
	});
});
</script>	

<div class="recipients">            
<p class="control">Ctl + Click to select multiple riders</p>

<?php
$type = $_GET['acctType'];
$getList = $_GET['list'];
if ($_GET['vol'] != '') {$getVol = "&& Volunteering != ''";}
if ($_GET['rideLead'] != '') {$rideLead = "&& RideLeader != ''";}
if ($_GET['emailSearch'] != '') {$search = "&& lastname LIKE '".$_GET['emailSearch']."'";}
if ($type != '') {$acctType = "&& Type = '$type'";}

include("../includes/db_connect.php");

if ($getList == 'members60') {
$emails = mysql_query("SELECT * FROM wp_users WHERE DateExpire >= (DATE_ADD(curdate(), INTERVAL -2 MONTH)) && email1 != '' && emailOptOut != '1' ".$getVol." ".$rideLead." ".$search." ".$acctType." ORDER BY wp_users . lastname", $connection);
}

elseif ($getList == 'full') {
$emails = mysql_query("SELECT * FROM wp_users WHERE email1 != '' && emailOptOut != '1' ".$getVol." ".$rideLead." ".$search." ".$acctType." ORDER BY wp_users . lastname", $connection);
}

elseif ($getList == 'officers') {
$emails = mysql_query("SELECT * FROM wp_users WHERE officer != '' && officer != 'Buster'  ".$getVol." ".$rideLead." ".$search." ".$acctType, $connection);
echo '<input type="hidden" name="category" value="officers" />';
}

elseif ($getList == 'mlc') {
$emails = mysql_query("SELECT * FROM mlc_emails", $connection);
}

else {echo '<p>No List Name Given</p>';}

?>


<?php
echo '
<input type="hidden" name="list" value="'.$getList.'" />
<select class="select_multiple" id="recipients" multiple="multiple" name="recipients[]">';

if ($getList != 'mlc') {
    while ($recipients = mysql_fetch_array($emails)) {
    $riderID = $recipients["ID"];
    $name = str_replace('"','&quot;', $recipients["firstname"]) . ' ' . str_replace('"','\"', $recipients["lastname"]);
    $email = $name .'<' .$recipients["email1"]. '>';
    $email2 = $name .'<' .$recipients["email1"]. '>, '.$name .'<' .$recipients["email2"]. '>';

    if ($recipients["email2"] == '') {echo '<option id="member_'.$riderID.'" value="'.$email.'">'.$name.'</option>
    ';}
    else {echo '<option id="member_'.$riderID.'" value="'.$email2.'">'.$name.'</option>
    ';}
    }
}
else {
   while ($recipients = mysql_fetch_array($emails)) {
   $email = $recipients['email'];

    echo '<option id="mlc_'.$email.'" value="'.$email.'">'.$email.'</option>
    ';}
}

echo '
</select>
<p class="submit" id="select_all" onclick="selectAll();">Select All</p>

<form name="filter" id="filter" action="">
<h3>Filter Selection</h3>
<input type="hidden" name="list" id="list" value="'.$getList.'" />

<div class="clear">
<label for="vol">Volunteer:</label>
<div class="check">
<input name="vol" id="vol" value="1"'; 
if ($_GET['vol'] != ''){echo ' checked="checked"';}
echo ' type="checkbox" />
</div>
</div>

<div class="clear">
<label for="rideLead">Ride Leader:</label>
<div class="check">
<input name="rideLead" id="rideLead" value="1"'; 
if ($_GET['rideLead'] != ''){echo ' checked="checked"';}
echo ' type="checkbox" />
</div>
</div>

<div class="clear">
<label for="type">Acct Type:</label>
<div class="check">
<select id="acctType" name="acctType">
<option value="" selected="selected">Choose an Account Type</option>
<option value="I">Individual</option>
<option value="F">Family</option>
<option value="B">Business</option>
</select>
</div>
</div>

<div class="clear">
<label for="emailSearch">Last Name:</label>
<input class="emailSearch" name="emailSearch" id="emailSearch" type="text" />
</div>

<input type="button" id="filterButton" class="button" value="Apply Filter(s)" />
</form>
</div>';
?>


