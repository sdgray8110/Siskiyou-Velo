<?php require_once("includes/auth.php"); ?>
<?php include("includes/header.php"); ?>
Club Supporting Business Directory</title>
<?php include("includes/header_bottom.html"); ?>
<?php include("includes/login.php"); ?>
<?php include("includes/topnav.php"); ?>

    
<!------- BEGIN MAIN BODY ------->
<div id="leftContent">
<h1>Club Supporting Business Directory</h1>

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
//DB Query
$result = mysql_query("SELECT * FROM wp_users WHERE type = 'B' ORDER BY wp_users . lastname", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}


//Use returned data
while ($row = mysql_fetch_array($result)) {

$firstname = $row["firstname"];
$lastname = $row["lastname"];
$address = $row["AddressL2"];
$city = $row["city"];
$state = $row["state"];
$zip = $row["zip"];
$email1 = $row["email1"];
$phone = $row["phone"];
$expire = $row["DateExpire"];
$website = $row["website"];

$timeStamp = strtotime($expire);
$date = date(Y . "-" .  m . "-" . d);
$minus60 = strtotime($date) - 5184000;

$clean = str_replace("#", "Apt ", $address);


if ($timeStamp > $minus60) {

	
	echo 
		"<div class='businessdivs'><h2>" . $firstname . " " . $lastname . "</h2>
		<p class='memberinfo'>" . $address . "</p>
		<p class='memberinfo'>" . $city . ", " . $state . " " . $zip . "</p>
		<p class='memberinfo'>" . $phone . "</p>";
	if ($website != "") {		
		echo "<h5 class='memberinfo'>Website:</h5>
		<p class='memberinfo'><a href='" . $website . "'>" . $website . "</a></p>";
	}
	
	if ($email1 != "") {
		echo "<h5 class='memberinfo'>Email:</h5>
		<p class='memberinfo'><a href='mailto:" . $email1 . "'>" . $email1 . "</a></p>";
	}
	
	echo "</div>";

}
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

