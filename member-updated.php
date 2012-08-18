<?php require_once("includes/auth.php"); ?>
<?php include("includes/header.php"); ?>Member Profile - <?php echo $_SESSION['SESS_FIRST_NAME'] . " " . $_SESSION['SESS_LAST_NAME']; ?></title>
<script src="includes/js/lib/jquery.js"></script>  
<script type="text/javascript" src="includes/js/jquery.validate.js"></script>
  <script>
  $(document).ready(function(){
    $("#profile").validate();
  });
  </script>
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

echo "<h1>Member Profile For " . $firstname . " " . $lastname . "</h1>"
. "<div class='memberProfile'><h2>Your Information Has Been Updated</h2>"
. "<p class='memberinfo'>Please verify that this information is correct - Thank You.</p></div>";


echo "<form id='profile' name='profile' action='includes/profile-exec.php' method='post' onSubmit='return validate();'>

<dl><dt><label for='firstname'>First Name:</label></dt>
<dd><input type='text' class='required'  minlength='2' name='firstname' id='firstname' value='" . $firstname . "' /></dd></dl>

<dl><dt><label for='lastname'>Last Name:</label></dt>
<dd><input type='text' class='required'  minlength='2' name='lastname' id='lastname' value='" . $lastname . "' /></dd></dl>

<dl><dt><label for='email1'>Primary Email:</label></dt>
<dd><input type='text' class='required email' name='email1' id='email1' value='" . $email1 . "' /></dd></dl>

<dl><dt><label for='email2'>Secondary Email:</label></dt>
<dd><input type='text' name='email2' id='email2' value='" . $email2 . "' /></dd></dl>

<dl><dt><label for='address'>Mailing Address:</label></dt>
<dd><input type='text' name='address' id='address' value='" . $address . "' /></dd></dl>

<dl><dt><label for='city'>City:</label></dt>
<dd><input type='text' name='city' id='city' value='" . $city . "' /></dd></dl>

<dl><dt><label for='state'>State:</label></dt>
<dd><input type='text' name='state' id='state'  maxlength='3' value='" . $state . "' /></dd></dl>

<dl><dt><label for='zip'>Zip Code:</label></dt>
<dd><input type='text' name='zip' id='zip' maxlength='10' value='" . $zip . "' /></dd></dl>

<dl><dt><label for='phone'>Phone Number:</label></dt>
<dd><input type='text' name='phone' id='phone'  maxlength='14' value='" . $phone . "' /></dd></dl>

<dl class='separate'><dt><label for='passwd'>Change Password:</label></dt>
<dd><input type='password' name='passwd' id='passwd' minlength='6'  /></dd></dl>

<dl><dt><label for='curpass'>Verify New Password:</label></dt>
<dd><input type='password' name='curpass' id='curpass'  minlength='6' equalTo='#passwd' /></dd></dl>

<dl><dt><label>&nbsp;</label></dt>
<dd><input class='submit' type='submit' alt='Submit' name='submit' value='Submit Changes &raquo;'/></dd></dl>
</form>";
}

?>

<?php
//Close connection
mysql_close($connection);

?>

    </div>
    
<!-------- END MAIN BODY -------->
    
<?php include("includes/generic_feed.html"); ?>  
<?php include("includes/foot.html"); ?>