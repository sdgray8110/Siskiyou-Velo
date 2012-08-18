<?php require_once("includes/auth.php"); ?>
<?php include("includes/header.php"); ?>
Members Directory</title>
<?php include("includes/header_bottom.html"); ?>
<?php include("includes/login.php"); ?>
<?php include("includes/topnav.php"); ?>

    
<!------- BEGIN MAIN BODY ------->
<div id="leftContent">
<h1>Siskiyou Velo Members Directory</h1>

<form id="profile" name="membersearch" method="post" action="members_search.php" />
<input style="margin-top:6px;" type="text" name="search" id="search" value="Search Members Directory by Last Name"  ONFOCUS="clearDefault(this)" />
<input style="margin-left:15px;" type="submit" class="submit" name="submit" value="Search" />
</form>

<?php include('includes/db_connect.php');?>

<!-- Below Code should be in HTML BODY -->
<?php
//DB Query
$result = mysql_query("SELECT * FROM wp_users WHERE DateExpire >= (DATE_ADD(curdate(), INTERVAL -2 MONTH))  ORDER BY wp_users . lastname", $connection);
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
$expire = $row["DateExpire"];
$displayAddress = $row["DisplayAddress"];
$displayContact = $row["DisplayContact"];

$clean = str_replace("#", "Apt ", $address);

echo "<div class='memberdivs'><h2>" ;
echo $firstname . " " . $lastname . "</h2>";

if ($displayAddress == 1) {

	if (strpos($address, 'Box' ) !== false) {
		echo "<p class='memberinfo'>" . $address . "</p>
			  <p class='memberinfo'>" . $city . ", " . $state . " ".$zip."</p>";
		}
		else if ($city == " " OR $city == "") {
	}
	
		else {
		echo "<a target='_blank' href='http://maps.google.com/?q=" . $clean . "+" . $city . "+" . $state . "+" . $zip . "'><img style='float:right; padding:3px;' src='http://maps.google.com/mapfiles/kml/pal5/icon15.png' width='32' height='32' border='0'  alt='View in Google Maps' title='View " . $address . " in Google Maps' /></a><p class='memberinfo'>" . $address . "<br />" . $city . ", " . $state . " " . $zip . "</a></p>";
	
		}
}

if ($displayAddress == 0 && $displayCity ==1) {
	echo "<p class='memberinfo'>" . $city . ", " . $state . " ".$zip."</p>";

}

if ($displayContact  == 3) {
echo "<p class='memberinfo'><a href='mailto:" . $email1 . "'>" . $email1 . "</a></p>";
if ($email2) {echo "<p class='memberinfo'><a href='mailto:" . $email2 . "'>" . $email2 . "</a></p>";}
echo "<p class='memberinfo'>" . $phone . "</p>";
}
elseif ($displayContact  == 2) {
echo "<p class='memberinfo'>" . $phone . "</p>";
}

elseif ($displayContact  == 1) {
echo "<p class='memberinfo'><a href='mailto:" . $email1 . "'>" . $email1 . "</a></p>";
if ($email2) {echo "<p class='memberinfo'><a href='mailto:" . $email2 . "'>" . $email2 . "</a></p>";}
}

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
<?php include("includes/foot.html"); ?>

