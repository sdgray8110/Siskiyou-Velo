<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/wordpress/wp-content/classes/class.officers.php');
////////////////////
//GLOBAL VARIABLES//
////////////////////

$officers = new officers();
$officers->init();
$roles = $officers->get_officer_roles();
$getID = $_GET['id'];
$getNewMember = $_GET['newMember'];
$expireTime = $_POST['expire'];
$date = time();

include($_SERVER['DOCUMENT_ROOT'] . '/admin/includes/db_connect.php');
include($_SERVER['DOCUMENT_ROOT'] . '/admin/includes/functions.php');

/////////////////////////
//*** UPDATE MEMBER ***//
/////////////////////////
if ($getNewMember) {
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
$notes=clean($_POST['notes']);
$printNewsletter=clean($_POST['printNewsletter']);
$joined=date('Y-m-d 00:00:00', $date);
$renewal=date('Y-m-d 00:00:00', $date + 31536000);
$password='39b0d86a2144a5cba348f7ec1557e20e';

$keys = array('firstname', 'lastname', 'address', 'city', 'state', 'zip', 'phone', 'email1', 'email2', 'AddressL2', 'FamilyMembers', 'website', 'Age', 'Type', 'emailOptOut', 'Comments', 'officer', 'officer2', 'officer3', 'Volunteering', 'RideLeader', 'Other', 'DisplayAddress', 'DisplayContact', 'DateJoined', 'DateExpire', 'user_pass', 'Newsletter');

$vals = array($firstname, $lastname, $address, $city, $state, $zip, $phone, $email1, $email2, $AddressL2, $FamilyMembers, $website, $Age, $Type, $emailOptOut, $comments, $officerSelect, $officerSelect2, $officerSelect3,$volunteer, $RideLeader, $notes, $dispAddress, $contact, $joined, $renewal, $password, $printNewsletter);


//Create INSERT query
$fmt = "INSERT INTO wp_users(%s) VALUES (%s)";
$qry = sprintf($fmt,implode(',',$keys),'"' . implode('","',$vals) . '"');

$result = @mysql_query($qry, $connection);

//Check whether the query was successful or not
if($result) {}
else {
	die("Query failed");
}

}


///////////////////////
///////////////////////
//*** BUILD FORM ***//
///////////////////////
///////////////////////

else {
$date = time();
echo '<h2 class="createHead">Create New Member</h2>
<p><strong>Joined Date</strong>: '.date('n/d/Y', $date).'<br /><strong>Renewal Date</strong>: '.date('n/d/Y', $date + 31536000).'</p>';

echo '
<form id="newMember" name="newMember" method="post" action="new_member.php">
<div class="memberEdit">
<h5>Vital Info</h5>

<label for="firstname">First Name:</label>
<input type="text" name="firstname" id="firstname" onChange="changeH2();" value="" />

<label for="lastname">Last Name:</label>
<input type="text" name="lastname" id="lastname" onChange="changeH2();" value="" />

<label for="lastname">Address:</label>
<input type="text" name="address" id="address" value="" />

<label for="lastname">City:</label>
<input type="text" name="city" id="city" value="" />

<label for="lastname">State:</label>
<input type="text" name="state" id="state" value="" />

<label for="lastname">Zip:</label>
<input type="text" name="zip" id="zip" value="" />

<label for="lastname">Phone:</label>
<input type="text" name="phone" id="phone" value="" />
</div>

<div class="memberEdit">
<h5>Member Details</h5>

<label for="firstname">Primary Email Address:</label>
<input type="text" name="email1" id="email1" value="" />

<label for="lastname">Secondary Email Address:</label>
<input type="text" name="email2" id="email2" value="" />

<label for="lastname">Secondary/Physical Address:</label>
<input type="text" name="AddressL2" id="AddressL2" value="" />

<label for="lastname">Family Members:</label>
<input type="text" name="FamilyMembers" id="FamilyMembers" value="" />

<label for="lastname">Website:</label>
<input type="text" name="website" id="website" value="" />

<label for="lastname">Age:</label>
<select id="ageSelect" name="Age">
<option class="compare" value="18-35">18-35</option>
<option class="compare" value="36-45">36-45</option>
<option selected="selected" class="compare" value="46-55">46-55</option>
<option class="compare" value="56-65">56-65</option>
<option class="compare" value="66+">66+</option>
</select>
</div>

<div class="memberEdit">
<h5>Settings</h5>

<label for="type">Account Type:</label>
<select id="typeSelect" name="Type">
<option selected="selected" class="compare" value="I">Individual</option>
<option class="compare" value="F">Family</option>
<option class="compare" value="B">Business</option>
</select>

<label for="contact">Display Contact Info:</label>
<select id="contactSelect" name="contact">
<option selected="selected" value="3" >All Contact Info</option>
<option value="2" >Phone Only</option>
<option value="1" >Email Only</option>
<option value="0" >No Contact Info</option>
</select>

<label for="contact">Display Address:</label>
<select id="addressSelect" name="dispAddress">
<option selected="selected" value="1">Display Address</option>
<option value="0">Keep Address Private</option>
</select>

<label for="contact">Email Opt-out:</label>
<select id="optOut" name="emailOptOut">
<option selected="selected" value="0">Receive Club Emails</option>
<option value="1">Opt Out of Club Emails</option>
</select>

<label for="printNewsletter">Print Newsletter:</label>
<select id="printNewsletter" name="printNewsletter">
<option value="0" selected="selected">Web Newsletter Only</option>
<option value="1">Receive Print Newsletter</option>
</select>


<label for="lastname">Comments:</label>
<textarea name="comments" id="comments"></textarea>

</div>

<div class="memberEdit">
<h5>Volunteering</h5>
<input type="hidden" id="officerTag" value="'.$row["officer"].'" />
<input type="hidden" id="officer2Tag" value="'.$row["officer2"].'" />
<input type="hidden" id="officer3Tag" value="'.$row["officer3"].'" />

<label for="officerSelect">Officer Position:</label>
<select id="officerSelect" name="officerSelect">
<option class="compare" value="">Not An Officer</option>';
$officers->build_officer_select();
echo '
</select>

<label for="officerSelect2">Secondary Officer Position:</label>
<select id="officerSelect2" name="officerSelect2">
<option class="compare" value="">Not Applicable</option>';
$officers->build_officer_select();
echo '
</select>

<label for="officerSelect3">Tertiary Officer Position:</label>
<select id="officerSelect3" name="officerSelect3">
<option class="compare" value="">Not Applicable</option>';
$officers->build_officer_select();
echo '
</select>

<label for="contact">Interested in Volunteering:</label>
<select id="volunteer" name="volunteer">

<option selected="selected" value="0">No</option>
  <option value="1">Yes</option>
</select>
<label for="contact">Interested in Ride Leading:</label>
<select id="RideLeader" name="RideLeader">
<option selected="selected" value="0">No</option>
<option value="1">Yes</option>
</select>

</div>

<div class="memberEdit">

<label for="notes">Notes:</label>
<textarea name="notes" id="n"></textarea>

</div>

<div class="submit">
	<p id="create">Complete New Member Registration &raquo;</p>
</div>

</form>
';
}

?>

<script type="text/javascript">
function changeH2() {
	if ($('#firstname').val() != '' || $('#firstname').val() != '') {
		$('h2.createHead').html('New Member: <span style="color:#f00;">'+ $('#firstname').val() +' '+ $('#lastname').val() +'</span>');
	}
}

$(document).ready(function() {				   						   
	$('div.submit p').click(function () {

			var formData = $('#newMember').serialize();
			var newContent = '<h3>Member Added &amp; Registration Complete</h3><h4>Member will receive a confirmation of renewal via email at 11:50pm PT.</h4>';

			$.ajax({
				type: 'POST',
				url: 'includes/new_member.php?newMember=yes',
				data: formData,
				success:function() {
					var riderID = $('input#id').val();
					$('#TB_ajaxContent').load('includes/member_edit.php?id=new', function() {
						$('div.submit').html(newContent);	
					});
				},
				error: function(){
					alert('Error');
				}
		});
	});
});
</script>