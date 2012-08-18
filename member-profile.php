<?php require_once("login/auth.php"); ?>
<?php include("includes/functions.php"); ?>
<?php include("includes/header.php"); ?>Member Profile - <?php echo $_SESSION['SESS_FIRST_NAME'] . " " . $_SESSION['SESS_LAST_NAME']; ?></title>

<?php include("includes/header_bottom.html"); ?>
<?php include("includes/login.php"); ?>
<?php include("includes/topnav.old.php"); ?>
<!------- BEGIN MAIN BODY ------->
<div id="leftContent">

<?php

db_connect("gray8110_svblogs");
?>
	

<!-- Below Code should be in HTML BODY -->
<?php
$memberID = $_SESSION['SESS_MEMBER_ID'];
$getUpdate = $_GET['updated'];
$getRenewal = $_GET['renewal'];
$getRenewed = $_GET['renewed'];

require_once($_SERVER['DOCUMENT_ROOT'] . '/wordpress/wp-content/classes/class.svdb.php');
$svdb = new svdb;
$membership = $svdb->membershipVP();

//DB Query
$result = mysql_query("SELECT * FROM wp_users WHERE ID = $memberID", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}

//Use returned data
while ($row = mysql_fetch_array($result)) {

$firstname = $row["firstname"];
$lastname = $row["lastname"];
$address = $row["address"];
$city = $row["city"];
$state = $row["state"];
$zip = $row["zip"];
$email1 = $row["email1"];
$email2 = $row["email2"];
$phone = $row["phone"];
$emailOptOut = $row["emailOptOut"];
$originalType = $row["Type"];
	if ($row["Type"] == 'I') {$type = 'Individual'; $cost = 15;}
	else if ($row["Type"] == 'F') {$type = 'Family';  $cost = 20;}
	else {$type = 'Business';  $cost = 25;}
$renewal = $row["DateExpire"];

$AddressL2 = $row["AddressL2"];
$FamilyMembers = $row["FamilyMembers"];
$website = $row["website"];
$Age = $row["Age"];
$DisplayContact = $row["DisplayContact"];
$DisplayAddress = $row["DisplayAddress"];
$Comments = $row["Comments"];
$riding_style = $row["riding_style"];
$riding_speed = $row["riding_speed"];
$Volunteering = $row["Volunteering"];
$RideLeader = $row["RideLeader"];
$bicycles = $row["bicycles"];
$reason_for_joining = $row["reason_for_joining"];
$referrer = $row["referrer"];

// Select Population arrays
$ageArray = array('18-35', '36-45', '46-55', '56-65', '66+');
$contactArray = array(3,2,1,0);
    $contactDescription = array("All Contact Info","Phone Only","Email Only","No Contact Info");
$addressArray = array(1,0);
    $addressDescription = array('Display Address', 'Keep Address Private');
$emailArray = array(0,1);
    $emailDescription = array('Receive Club Emails', 'Opt Out Of Club Emails');
$styleArray = array('a','b','c','d','e');
    $styleDescription = array('Ride only by myself','With significant other or close friends','Small groups – social','Any group rides','Don&rsquo;t Ride');
$speedArray = array('a','b','c','d','e');
    $speedDescription = array('Under 10 mph average speed','Between 10-12 mph','Between 12-15 mph','Over 15 mph','Don&rsquo;t Ride');

// Checkbox/Radio Population Arrays
$bicyclesID = array('nobike','cityBike','roadBike','mountain','recumbent','tandem','fixedgear');
$bicyclesArray = array('a','b','c','d','e','f','g');
$bicyclesLabel = array('Do not own a bike','City Bike','Road Bike, CX, Touring or Hybrid','Mountain Bike','Recumbent','Tandem','Fixed Gear');
$reasonID = array('nobikes','socialize','social_rides','advocacy','green');
$reasonArray = array('a','b','c','d','e');
$reasonLabel = array('Do not own a bike','To socialize','Ride and socialize','Support cycling (Rights, education, advocacy, etc.)','Support a &ldquo;green&rdquo; lifestyle (alternative transportation, etc.)');
$referrerID = array('friend','seo','localShop','shopMaterial','otherMaterial','other');
$referrerArray = array('a','b','c','d','e','f');
$referrerLabel = array('From a friend','Used a search engine to find the website','Referred by a local bike shop','Learned of the club from promotional material at a bike shop','Learned of the club from materials at another location','Other');

if ($getRenewal == 'yes') {}
else {
    echo "<h1>Member Profile For " . $firstname . " " . $lastname . "</h1>

    <h2 class='memberDetails'>Membership Details</h2>
    <ul class='memberDetails'>
	<li><p class='ident'>Membership Type:</p> <p>".$type."</p></li>
	<li><p class='ident'>Renewal Date:</p> ";

	if (!$getRenewal) {echo "<p>".date('m/d/Y',strtotime($renewal))."</p></li>";}
	else {echo "<p><em>Renewal In-Progress</em></p>";}

echo "
	<li><p class='ident'>Membership Card:</p> <p><a href='member_card.php?id=".$memberID."'>Download</a></p></li>
	<div style='clear:both;'></div>
    </ul>
";

}

if ($getRenewal) {
    echo "<h1>Renew Your Siskiyou Velo Membership</h1>
        <h2 class='memberDetails'>Membership Details For " . $firstname . " " . $lastname . "</h2>
    <ul class='memberDetails'>
	<li><p class='ident'>Membership Type:</p> <p>".$type."</p></li>
	<li><p class='ident'>Renewal Date:</p> ";

	if (!$getRenewal) {echo "<p>".date('m/d/Y',strtotime($renewal))."</p></li>";}
	else {echo "<p><em>Renewal In-Progress</em></p>";}

echo "
	<li><p class='ident'>Membership Card:</p> <p><a href='member_card.php?id=".$memberID."'>Download</a></p></li>
	<div style='clear:both;'></div>
    </ul>
    
<div class='agreement'>
<p>Thank you for your continued membership with the Siskiyou Velo and for your interest and participation in the club. Renewing your membership will only take a few minutes and your payment can now be processed online via paypal using a check or credit card.</p>

<p>If you wish to mail payment, please download the <a href='images/PDF/join.pdf'>Membership Registration Form</a>, complete it, and mail it together with your check to: </p>

    <dl class='address'>
        <dt><strong>" . $membership['firstname'] . " " . $membership['lastname'] . "</strong></dt>
        <dt>Siskiyou Velo Membership</dt>
        <dt>PO Box 974</dt>
        <dt>Ashland, OR 97520</dt>
    </dl>

<p>You currently have an <strong>".$type." Membership</strong>. Your annual dues are <strong>$".$cost."</strong>. Other membership types are detailed below &mdash; you'll be able to change your membership type on the next page.</p>

<dl>
    <dt>Individual:</dt><dd>$15 Annual Fee</dd>
    <dt>Family:</dt><dd>$20 Annual Fee</dd>
    <dt>Business:</dt><dd>$25 Annual Fee</dd>
</dl>

<p>As you proceed with registration, please be sure to verify that your contact information and preferences are up-to-date. The details you provide will allow us to provide a better club experience for you and be assured that any data you provide will never be shared with third parties.  To continue with the registration process, you must also agree to the <a href='' rel='superbox[ajax][includes/terms.php?terms=waiver[700x550]'>Ride Waiver</a> and <a href='' rel='superbox[ajax][includes/terms.php?terms=proud[620x520]'>Committment to Cycling Excellence</a> agreement below. Though you can certainly &ldquo;click through&rdquo; this portion of the process, we encourage you to read these two documents. The waiver has been designed to protect you, the club and the officers of the club in case of law suits, and you should be informed about this. The other document concerns a club program to &ldquo;RIDE PROUD&rdquo; - a way of expressing the club&rsquo;s values in ettiquette on the road, observing laws and sharing the road. You can be PROUD to be informed.</p>

<p>If you have any questions, please contact our <a href='mailto:membership@siskiyouvelo.org'>Membership VP</a>

<p class='disclaimer'>*By selecting the &ldquo;I agree&rdquo; box and submitting this form, you indicate that you have read, understood and agree to the terms in our <a href='' rel='superbox[ajax][includes/terms.php?terms=waiver[700x550]'>Ride Waiver</a> and <a href='' rel='superbox[ajax][includes/terms.php?terms=proud[620x520]'>Committment to Cycling Excellence</a>.</p>
<label for='termsAgree'>
    <input type='checkbox' name='termsAgree' id='termsAgree' value='yes'></input>
    I Agree*
</label>

<input type='submit' class=submit' id='agreeToTerms' value='Continue Renewal &raquo'></input>

</div>

<div class='hideStuff'>
";
}

echo "
<div class='memberProfile'>";

// Condition [Profile Update -- Update Completed]
if ($getUpdate != '') {
echo "
<h2>Your Information Has Been Updated</h2>
<p class='memberinfo'>Please verify that this information is correct - Thank You.</p></div>
";
}

// Condition [Renewal -- Mid Renewal]
elseif ($getRenewal == 'yes') {
echo "
<h2>Please Verify Your Contact Info &amp; Preferences</h2>
<p class='memberinfo'>Before we continue with the renewal, please verify that your preferences and contact information listed in the dropdown menus below are correct.</p>
</div>
";
}


// Condition [Pay By Check -- After Renewal Update]
elseif ($getRenewed == 'payByCheck') {
echo "
<h2>Your Renewal is almost complete.</h2>
<p class='memberinfo'>Thank you for updating your information. To complete your renewal, mail a check for $".$cost." to:</p>
<ul>
    <li>Siskiyou Velo</li>
    <li>PO BOX 974</li>
    <li>Ashland, Or 97520</li>
</ul>

<p class='memberinfo'>Once we have received your check, you'll receive an email confirming that your renewal has been processed. If you have any questions, please contact our <a href'mailto:membership@siskiyouvelo.org'>Membership VP</a>. Please verify the information listed below is correct. You can update it now, or by accessing your member profile page at any time. Thank you for renewing your membership with Siskiyou Velo.</p>

</div>
";
}

else {
echo "
<h2>Edit Your Information</h2>
<p class='memberinfo'>Please note that this information may be viewable by other members. See your contact preferences section below for specific details.</p></div>
";
}

echo "
<div id='memberAccordion'>
<form id='profile' name='profile' action='profile-exec.php' method='post' onSubmit='return validate();'>";

// Condition [Renewal -- Mid Renewal]
if ($getRenewal == 'yes') {
    echo "<h2 class='memberDetails active'>Membership Settings &amp; Payment Preference</h2>
<div class='memberDetails' style='display:block;'>
    <p>At the completion of this form, you will be redirected to Paypal for payment. Paypal is a quick, easy and secure way of making payments with credit card or check online. You are not required to create an account with PayPal though it does make the process simpler for future payments. </p>

    <!--<label for='paymentType'>Payment Method:</label>-->
    <select style='display:none' id='paymentType' name='paymentType'>
        <option selected='selected' value='paypal'>Paypal</option>
        <!--<option value='check'>Check by Mail</option>-->
    </select>

    <label for='contactSelect'>Membership Type:</label>
    <select id='memberType' name='memberType'>
        <option value='I'";
		if ($type == 'Individual') {echo "selected='selected'";}
		echo ">Individual - $15 Annual Membership</option>
        <option value='F'";
		if ($type == 'Family') {echo "selected='selected'";}
		echo ">Family - $20 Annual Membership</option>
        <option value ='B'";
		if ($type == 'Business') {echo "selected='selected'";}
		echo ">Business - $25 Annual Membership</option>
    </select>
</div>

<h2 class='memberDetails'>Vital Information</h2>
<div class='memberDetails'>
";
}

else {
echo "
<h2 class='memberDetails active'>Vital Information</h2>
<div class='memberDetails' style='display:block;'>
    <input type='hidden' id='memberType' name='memberType' value='".$originalType."' />
    ";

}

echo "
    <label for='firstname'>First Name:</label>
    <input type='text' class='required'  minlength='2' name='firstname' id='firstname' value='" . $firstname . "' />
    
    <label for='lastname'>Last Name:</label>
    <input type='text' class='required'  minlength='2' name='lastname' id='lastname' value='" . $lastname . "' />
    
    <label for='email1'>Primary Email:</label>
    <input type='text' class='required email' name='email1' id='email1' value='" . $email1 . "' />
    
    <label for='email2'>Secondary Email:</label>
    <input type='text' class='email' name='email2' id='email2' value='" . $email2 . "' />
    
    <label for='address'>Mailing Address:</label>
    <input type='text' name='address' id='address' value='" . $address . "' />
    
    <label for='city'>City:</label>
    <input type='text' name='city' id='city' value='" . $city . "' />
    
    <label for='state'>State:</label>
    <input type='text' name='state' id='state'  maxlength='3' value='" . $state . "' />
    
    <label for='zip'>Zip Code:</label>
    <input type='text' name='zip' id='zip' maxlength='10' value='" . $zip . "' />
    
    <label for='phone'>Phone Number:</label>
    <input type='text' name='phone' id='phone'  maxlength='14' value='" . $phone . "' />
	
	<div class='separate'>
		<label for='passwd'>Change Password:</label>
		<input type='password' name='passwd' id='passwd' value='' minlength='6'  />
	
		<label for='curpass'>Verify New Password:</label>
		<input type='password' name='curpass' id='curpass'  minlength='6' equalTo='#passwd' />
		
		<input type='hidden' name='existensial' value='".$row['user_pass']."' />
	</div>
</div>

<h2 class='memberDetails'>Additional Information</h2>
<div class='memberDetails'>
    <label for='address2'>Secondary/Physical Address:</label>
    <input type='text' name='address2' id='address2' value='" . $AddressL2 . "' />";
    
if ($type != 'Individual') {
echo "
    <label for='family'>Family Members:</label>
    <input type='text' name='family' id='family' value='" . $FamilyMembers . "' />";
}

echo "
    <label for='address'>Website:</label>
    <input type='text' name='website' id='website' value='" . $website . "' />
    
    <label for='age'>Age:</label>
    <select name='age' id='age'>
";
populateSelect($ageArray, $Age, null);
echo "
    </select>
    
</div>

<h2 class='memberDetails'>Contact Preferences</h2>
<div class='memberDetails'>
	<p>The Siskiyou Velo as a club makes member contact information available to our members through our Membership Directory. If you'd prefer that your contact information not be shared with other members you can set your preferences below. This information is only available to club members and never made available to a third party. The club also occasionally sends out emails to members. These range from newsletter notifications and renewal reminders to club rides you might be interested in. You can opt-out of these below.</p>
    <label for='contactSelect'>Display Contact Info:</label>
    <select id='contactSelect' name='contact'>
";
populateSelect($contactArray, $DisplayContact, $contactDescription);
echo "
    </select>
    
    <label for='contact'>Display Address:</label>
    <select id='addressSelect' name='dispAddress'>
";
populateSelect($addressArray, $DisplayAddress, $addressDescription);
echo "
    </select>
    
    <label for='contact'>Email Opt-out:</label>
    <select id='optOut' name='emailOptOut'>";
populateSelect($emailArray, $emailOptOut, $emailDescription);
echo "
    </select>

    
    <label for='comments'>Comments:</label>
    <textarea name='comments' id='comments'>".$Comments."</textarea>
    
</div>


<h2 class='memberDetails'>Volunteering &amp; Cycling Preferences</h2>
	<div class='memberDetails cyclingInfo'>
		<h2>Cycling Preferences</h2>
			<label class='normal' for ='ridingStyle'>Riding Style</label>
				<select id='ridingStyle' name='ridingStyle'>
					<option value=''>Choose Your Riding Style</option>";
populateSelect($styleArray, $riding_style, $styleDescription);
echo "
				</select>	
	
			<label class='normal' for ='ridingSpeed'>Riding Speed</label>
				<select id='ridingSpeed' name='ridingSpeed'>
					<option value=''>Choose Your Riding Speed</option>";
populateSelect($speedArray, $riding_speed, $speedDescription);
echo "                             
				</select>

		<h2>Club Activities</h2>
";
buildCheckbox('volunteer','1',$Volunteering,'Interested in Volunteering?');
buildCheckbox('rideLeader','1',$RideLeader,'Interested in Ride Leading?');
echo "				

        <h2>Bicycle Stable <span>(Check All That Apply)</span></h2>
";
buildCheckbox($bicyclesID,$bicyclesArray,$bicycles,$bicyclesLabel);
echo "
        <h2>Why I belong to Siskiyou Velo Cycling club <span>(Check All That Apply)</span></h2>
";
buildCheckbox($reasonID,$reasonArray,$reason_for_joining,$reasonLabel);
echo "

        <h2>Where did you learn of the Siskiyou Velo?</h2>
";
buildRadio($referrerID,'referrer',$referrerArray,$referrer,$referrerLabel);
echo "
    </div>
		<label for='submit'></label>";
		if ($getRenewal == 'yes') {echo "<input type='hidden' id='renewal' name='renewal' value='yes'/><input class='submit' type='submit' alt='Submit' name='submit' value='Continue Renewal &raquo;'/>";}
		else {echo "<input class='submit' type='submit' alt='Submit' name='submit' value='Save Changes &raquo;'/>";}

		echo "
		<input type='hidden' name='memberID' value='".$memberID."' />
	</form>
</div>
";
}

if ($getRenewal) {
    echo "</div>";
}

?>

<?php
//Close connection
mysql_close($connection);

?>

    </div>   
<!-------- END MAIN BODY -------->
    
<?php include("includes/generic_feed.html"); ?>  

<script type="text/javascript" src="includes/js/memberAccordion.js"></script>
<?php include("includes/foot.html"); ?>