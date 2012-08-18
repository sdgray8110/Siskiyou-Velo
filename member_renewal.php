<?php require_once("includes/auth.php"); ?>
<?php include("includes/header.php"); ?>Member Profile - <?php echo $_SESSION['SESS_FIRST_NAME'] . " " . $_SESSION['SESS_LAST_NAME']; ?></title>
<?php include("includes/header_bottom.html"); ?>
<?php include("includes/login.php"); ?>
<?php include("includes/topnav.php"); ?>
<!------- BEGIN MAIN BODY ------->
<div id="leftContent">

<?php

//Connect to database
$connection = mysql_connect("localhost","gray8110","8aU_V{^{,RJC");

//Debug
if (!$connection) {
	die ("Database connection failed: " . mysql_error());
}

//Select database
$db_select = mysql_select_db("gray8110_svblogs",$connection);

//Debug
if (!db_select) {
	die("Database selection failed: " . mysql_error());
}
?>


<!-- Below Code should be in HTML BODY -->
<?php
$memberID = $_SESSION['SESS_MEMBER_ID'];
$getUpdate = $_GET['updated'];
$getRenewal = $_GET['renewal'];

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
	if ($row["Type"] == 'I') {$type = 'Individual';}
	else if ($row["Type"] == 'F') {$type = 'Family';}
	else {$type = 'Business';}
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

echo "<h1>Renew Your Siskiyou Velo Membership</h1>

<h2 class='memberDetails'>Membership Details</h2>
<ul class='memberDetails'>
	<li><p class='ident'>Name:</p> <p>" . $firstname . " " . $lastname . "</p></li>
	<li><p class='ident'>Membership Type:</p> <p>".$type."</p></li>
	<li><p class='ident'>Renewal Date:</p>  <p>".date('m/d/Y',strtotime($renewal))."</p></li>
        <li><p class='ident'>Membership Card:</p> <p><a href='member_card.php?id=".$memberID."'>Download</a></p></li>
</ul>";
}
?>


<h1>Thank You For Renewing</h1>
<p>Thank you again for your continued interest and participation in the Siskiyou Velo. You should receive an email with confirmation of your renewal by the end of the day. If you have any questions, please don't hesitate to <a href="mailto:membership@siskiyouvelo.org">Contact Us</a>.</p>

<?php
//Close connection
mysql_close($connection);

?>

    </div>   
<!-------- END MAIN BODY -------->
    
<?php include("includes/generic_feed.html"); ?>  


<?php include("includes/foot.html"); ?>