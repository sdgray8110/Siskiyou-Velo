<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/wordpress/wp-content/classes/class.officers.php');
$officers = new officers();
$officers->init();
$roles = $officers->get_officer_roles();

////////////////////
//GLOBAL VARIABLES//
////////////////////

$getID = $_GET['id'];
$getUpdate = $_GET['update'];
$postRenew = $_POST['renew'];
$postActivate = $_POST['activate'];
$expireTime = $_POST['expire'];
$date = time();

include('../includes/db_connect.php');
include('../includes/functions.php');

/////////////////////////
//*** UPDATE MEMBER ***//
/////////////////////////
if ($getUpdate) {
$postID = $_POST['id'];
$firstname=clean($_POST['firstname']);
$lastname=clean($_POST['lastname']);
$address=clean($_POST['address']);
$city=clean($_POST['city']);
$state=clean($_POST['state']);
$zip=clean($_POST['zip']);
$phone=clean($_POST['phone']);
$email1=clean($_POST['email1']);
$email2=clean($_POST['email2']);
$AddressL2=clean($_POST['AddressL2']);
$FamilyMembers=clean($_POST['FamilyMembers']);
$website=clean($_POST['website']);
$Age=clean($_POST['Age']);
$Type=clean($_POST['Type']);
$contact=clean($_POST['contact']);
$dispAddress=clean($_POST['dispAddress']);
$emailOptOut=clean($_POST['emailOptOut']);
$comments=clean($_POST['comments']);
$officerSelect=clean($_POST['officerSelect']);
$officerSelect2=clean($_POST['officerSelect2']);
$officerSelect3=clean($_POST['officerSelect3']);
$volunteer=clean($_POST['volunteer']);
$RideLeader=clean($_POST['RideLeader']);
$printNewsletter=clean($_POST['printNewsletter']);
$notes=clean($_POST['notes']);

//Create INSERT query
$qry = "UPDATE wp_users SET
	firstname = '$firstname',
	lastname = '$lastname',
	address = '$address',
	city = '$city',
	state = '$state',
	zip = '$zip',
	phone = '$phone',
	email1 = '$email1',
	email2 = '$email2',
	AddressL2 = '$AddressL2',
	FamilyMembers = '$FamilyMembers',
	website = '$website',
	Age = '$Age',
	Type = '$Type',
	emailOptOut = '$emailOptOut',
	Comments = '$comments',
	officer = '$officerSelect',
	officer2 = '$officerSelect2',
	officer3 = '$officerSelect3',
	Volunteering = '$volunteer',
	RideLeader = '$RideLeader',
	Other = '$notes',
	Newsletter = '$printNewsletter',
	DisplayAddress = '$dispAddress',
	DisplayContact = '$contact'
	WHERE ID = '$postID'";	

$result = @mysql_query($qry, $connection);

//Check whether the query was successful or not
if($result) {}
else {
	die("Query failed");
}

}

//////////////////////////
///*** RENEW MEMBER ***///
//////////////////////////
// DATE FORMAT ** 2008-10-21 00:00:00 ** //
if ($postRenew == 'yes') {
	if ($date > ($expireTime + 5184000)) {$newRenewDate = date('Y-m-d 00:00:00', $date + 31536000);}
	else {$newRenewDate = date('Y-m-d 00:00:00', ($expireTime + 31536000));}
	
	$dateRenewed = date('Y-m-d 00:00:00', $date);
	
//Create INSERT query
$renewQry = "UPDATE wp_users SET
	DateExpire = '$newRenewDate',
	DateRenewed = '$dateRenewed'
	WHERE ID = '$postID'";
	
$renewResult = @mysql_query($renewQry, $connection);

//Check whether the query was successful or not
if($renewResult) {}
else {
	die("Query failed");
}

}

/////////////////////////////
///*** ACTIVATE MEMBER ***///
/////////////////////////////
// DATE FORMAT ** 2008-10-21 00:00:00 ** //
if ($postActivate == 'yes') {
        $expireDate = date('Y-m-d 00:00:00',($date + 31536000));
	$dateJoined = date('Y-m-d 00:00:00', $date);

//Create INSERT query
$renewQry = "UPDATE wp_users SET
	DateExpire = '$expireDate',
	DateJoined = '$dateJoined',
        pendingPayment = '0'
	WHERE ID = '$postID'";

$renewResult = @mysql_query($renewQry, $connection);

//Check whether the query was successful or not
if($renewResult) {}
else {
	die("Query failed");
}

}

/////////////////////////
/////////////////////////
//*** POPULATE FORM ***//
/////////////////////////
/////////////////////////

else {
if ($getID == 'new') {$result = mysql_query("SELECT * FROM wp_users ORDER BY ID DESC LIMIT 1", $connection);}
else {$result = mysql_query("SELECT * FROM wp_users WHERE ID = '$getID'", $connection);}

//Use returned data
while ($row = mysql_fetch_array($result)) {
$name = $row["firstname"].' '.$row["lastname"];
$expireTime = strtotime($row["DateExpire"]);
$expire = date('n/d/Y', $expireTime);
$joinedTime = strtotime($row["DateJoined"]);
$joined = date('n/d/Y', $joinedTime);
$contact = $row["DisplayContact"];
$aC = $row["DisplayAddress"];
$pending = $row["pendingPayment"];

echo '<h2>';

if (($date - $expireTime) > 5184000) echo '<span style="font-weight:700; color:#f00; float:right; font-size:14px;">Membership Expired</span>';

echo $name.'</h2>
<p><strong>Date Joined</strong>: '.$joined.'<br /><strong>Renewal Date</strong>: '.$expire.'</p>';

echo '
<form id="memberEdit" name="memberEdit" method="post" action="memberEdit.php">
<div class="memberEdit">
<h5>Vital Info</h5>

<label for="firstname">First Name:</label>
<input type="text" name="firstname" id="firstname" value="'.$row["firstname"].'" />

<label for="lastname">Last Name:</label>
<input type="text" name="lastname" id="lastname" value="'.$row["lastname"].'" />

<label for="lastname">Address:</label>
<input type="text" name="address" id="address" value="'.$row["address"].'" />

<label for="lastname">City:</label>
<input type="text" name="city" id="city" value="'.$row["city"].'" />

<label for="lastname">State:</label>
<input type="text" name="state" id="state" value="'.$row["state"].'" />

<label for="lastname">Zip:</label>
<input type="text" name="zip" id="zip" value="'.$row["zip"].'" />

<label for="lastname">Phone:</label>
<input type="text" name="phone" id="phone" value="'.$row["phone"].'" />
</div>

<div class="memberEdit">
<h5>Member Details</h5>

<label for="firstname">Primary Email Address:</label>
<input type="text" name="email1" id="email1" value="'.$row["email1"].'" />

<label for="lastname">Secondary Email Address:</label>
<input type="text" name="email2" id="email2" value="'.$row["email2"].'" />

<label for="lastname">Secondary/Physical Address:</label>
<input type="text" name="AddressL2" id="AddressL2" value="'.$row["AddressL2"].'" />

<label for="lastname">Family Members:</label>
<input type="text" name="FamilyMembers" id="FamilyMembers" value="'.$row["FamilyMembers"].'" />

<label for="lastname">Website:</label>
<input type="text" name="website" id="website" value="'.$row["website"].'" />

<label for="lastname">Age:</label>
<input type="hidden" id="Age" value="'.$row["Age"].'" />
<select id="ageSelect" name="Age">
<option class="compare" value="18-35">18-35</option>
<option class="compare" value="36-45">36-45</option>
<option class="compare" value="46-55">46-55</option>
<option class="compare" value="56-65">56-65</option>
<option class="compare" value="66+">66+</option>
</select>
</div>

<div class="memberEdit">
<h5>Settings</h5>

<label for="type">Account Type:</label>
<input type="hidden" id="type" value="'.$row["Type"].'" />
<select id="typeSelect" name="Type">
<option class="compare" value="I">Individual</option>
<option class="compare" value="F">Family</option>
<option class="compare" value="B">Business</option>
</select>

<label for="contactSelect">Display Contact Info:</label>
<select id="contactSelect" name="contact">
<option value="0" ';
if ($contact == 0) {echo 'selected="selected"';}
echo '>No Contact Info</option>
<option value="1" ';
if ($contact == 1) {echo 'selected="selected"';}
echo '>Email Only</option>
<option value="2" ';
if ($contact == 2) {echo 'selected="selected"';}
echo '>Phone Only</option>
<option value="3" ';
if ($contact == 3) {echo 'selected="selected"';}
echo '>All Contact Info</option>
</select>

<label for="contact">Display Address:</label>
<select id="addressSelect" name="dispAddress">
<option value="1" ';
if ($contact == 1) {echo 'selected="selected"';}
echo '>Display Address</option>
<option value="0" ';
if ($contact == 0) {echo 'selected="selected"';}
echo '>Keep Address Private</option>
</select>

<label for="contact">Email Opt-out:</label>
<select id="optOut" name="emailOptOut">
<option value="1" ';
if ($row["emailOptOut"] == 1) {echo 'selected="selected"';}
echo '>Opt Out of Club Emails</option>
<option value="0" ';
if ($row["emailOptOut"] == 0) {echo 'selected="selected"';}
echo '>Receive Club Emails</option>
</select>

<label for="printNewsletter">Print Newsletter:</label>
<select id="printNewsletter" name="printNewsletter">
<option value="" ';
if ($row["Newsletter"] !== 1) {echo 'selected="selected"';}
echo '>Web Newsletter Only</option>
<option value="1" ';
if ($row["Newsletter"] == 1) {echo 'selected="selected"';}
echo '>Receive Print Newsletter</option>
</select>

<label for="lastname">Comments:</label>
<textarea name="comments" id="comments">'.$row["Comments"].'</textarea>

</div>

<div class="memberEdit">
<h5>Volunteering</h5>
<input type="hidden" id="officerTag" value="'.$row["officer"].'" />
<input type="hidden" id="officer2Tag" value="'.$row["officer2"].'" />
<input type="hidden" id="officer3Tag" value="'.$row["officer3"].'" />

<label for="officerSelect">Officer Position:</label>
<select id="officerSelect" name="officerSelect">';
$officers->build_officer_select();
echo '<option class="compare" value="">Not An Officer</option>
</select>

<label for="officerSelect2">Secondary Officer Position:</label>
<select id="officerSelect2" name="officerSelect2">';
$officers->build_officer_select();
echo '<option class="compare" value="">Not Applicable</option>
</select>

<label for="officerSelect3">Tertiary Officer Position:</label>
<select id="officerSelect3" name="officerSelect3">';
$officers->build_officer_select();
echo '<option class="compare" value="">Not Applicable</option>
</select>

<label for="contact">Interested in Volunteering:</label>
<select id="volunteer" name="volunteer">
<option value="1" ';
if ($row["Volunteering"] == 1) {echo 'selected="selected"';}
echo '>Yes</option>
<option value="0" ';
if ($row["Volunteering"] != 1) {echo 'selected="selected"';}
echo '>No</option>
</select>

<label for="contact">Interested in Ride Leading:</label>
<select id="RideLeader" name="RideLeader">
<option value="1" ';
if ($row["RideLeader"] == 1) {echo 'selected="selected"';}
echo '>Yes</option>
<option value="0" ';
if ($row["RideLeader"] != 1) {echo 'selected="selected"';}
echo '>No</option>
</select>

</div>

<div class="memberEdit">

<label for="notes">Notes:</label>
<textarea name="notes" id="n">'.$row["Other"].'</textarea>

</div>

<div class="submit">
	<p id="update">Update Member Details  &raquo;</p>
        ';

        if ($pending == 1) {
            echo '<p id="activate">Activate Member &raquo;</p>';
        } else {
            echo '<p id="renew">Renew Member &raquo;</p>';
        }
echo '
</div>

<input type="hidden" name="id" id="id" value="'.$getID.'" />
<input type="hidden" name="expire" id="expire" value="'.$expireTime.'" />

</form>
';
}
}
?>

<script type="text/javascript">
$(document).ready(function() {

    officerSelections();
    ageTypeSelections();

	$('div.submit p').click(function () {
		
		if ($(this).html().match('Renew')) {
			var formData = $('#memberEdit').serialize()+'&renew=yes';
			var newContent = '<h3>Member Data Updated &amp; Renewal Complete</h3><h4>Member will receive a confirmation of renewal via email at 11:50pm PT.</h4>';
		}

		else if ($(this).html().match('Activate')) {
			var formData = $('#memberEdit').serialize()+'&activate=yes';
			var newContent = '<h3>Member Data Updated &amp; Activation Complete</h3><h4>Member will receive a confirmation of renewal via email at 11:50pm PT.</h4>';
		}
		else {
			var formData = $('#memberEdit').serialize();
			var newContent = '<h3>Member Data Updated</h3>';
		}

                $('div.submit').fadeOut(400,function() {
			$.ajax({
				type: 'POST',
				url: 'includes/member_edit.php?update=yes',
				data: formData,
				success:function() {
					var riderID = $('input#id').val();
					$('#TB_ajaxContent').load('includes/member_edit.php?id=' + riderID, function() {
						$('div.submit').html(newContent);
                                                $('div.submit').fadeIn(400);
					});

				},
				error: function(){
					$('div.submit').html('<p>There was an error loading this content, please try again or <a href="mailto:webmaster@siskiyouvelo.org"> contact the webmaster</a> if problems continue.</p>');
                                        $('div.submit').fadeIn(400);
				}
                        });
                });

	});
});
</script>