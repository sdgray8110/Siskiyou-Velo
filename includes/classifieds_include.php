<script src="http://www.siskiyouvelo.org/js/prototype.js" type="text/javascript"></script>
<script src="http://www.siskiyouvelo.org/js/scriptaculous.js?load=effects" type="text/javascript"></script>
<script src="http://www.siskiyouvelo.org/js/lightbox.js" type="text/javascript"></script>
<link rel="stylesheet" href="http://www.siskiyouvelo.org/lightbox.css" type="text/css" media="screen" />


<?php 

//Connect to database
$connection = mysql_connect("localhost","gray8110","8aU_V{^{,RJC");
if (!$connection) {
	die ("Database connection failed: " . mysql_error());
}

$db_select = mysql_select_db("gray8110_svblogs",$connection);

if (!db_select) {
	die("Database selection failed: " . mysql_error());
} 
?>

<?php

$postID = $_GET['id'];
$officer = trim($_SESSION['SESS_OFFICER']);
$result = mysql_query("SELECT * FROM classifieds WHERE ID = $postID", $connection);

if (!result) {
   die("Database query failed: " . mysql_error());
}

while ($row = mysql_fetch_array($result)) {

$name = $row["name"];
$email = $row["email"];
$phone = $row["phone"];
$item = $row["item"];
$price = $row["price"];
$image = $row["image"];
$post = $row["post"];
$timestamp = strtotime($row["timestamp"]);
$member_id = $row["member_id"];
$id = $row["ID"];
$postdate = date('M, d Y', $timestamp);

echo "<li><h1>" . $item . "</h1>

<div class='classified_details'>";
if ($price == "") {
	echo "<p>Price: <strong> Not Provided</strong></p>";
}

elseif ($price != "" && ord($price) != "36") {
	echo "<p>Price: <strong>$" . $price . "</strong></p>";
}
else {
    echo "<p>Price: <strong>" . $price . "</strong></p>";
}
	echo "<h5>Contact Details</h5>
    <p class='contactDetails'>Email: <strong><a href='mailto:" . $email . "?subject=Siskiyou Velo Classifieds: Inquiry regarding " . $item . "'>" . $name . "</a></strong></p>
    <p class='contactDetails'>Phone: <strong>" . $phone . "</strong></p>
</div>   

		" . $post;

if ($image != '') {
		echo "<div class='classifiedFull'><a rel='lightbox' title='" . $item . "' href='images/classifieds/" . $image . "'><img src='image.php/classified" . $id . ".jpg?width=600&height=600&image=http://www.siskiyouvelo.org/images/classifieds/" . $image . "' border='0' alt='" . $item . "' /></a></div>";
}
		
		echo "<p class='blogInfo'><strong>Posted by</strong>: <a href='mailto:" . $email . "?subject=Siskiyou Velo Classifieds: Inquiry regarding " . $item . "'>" . $name . "</a> on " . $postdate;

if ($member_id == ($_SESSION['SESS_MEMBER_ID'])) {
	echo " | <a href='classifieds_edit.php?id=" . $id .  "'>Edit Entry</a> | <a onClick=\"return confirm('Are you sure you wish to delete his entry?');\" href='classifieds_edit.php?delete=yes&id=" . $id . "'>Delete Entry</a>";
}	
	elseif (!isset($_SESSION['SESS_MEMBER_ID']) || (trim($_SESSION['SESS_OFFICER']) == '')) {
    }

else {
echo " | <a href='classifieds_edit.php?id=" . $id .  "'>Officer Moderation</a> | <a onClick=\"return confirm('Are you sure you wish to delete his entry?');\" href='classifieds_edit.php?delete=yes&id=" . $id . "'>Delete Entry</a>";	
}

	echo "</p>
		
	</li>
";

}
?>

<?php
//Close connection
mysql_close($connection);

?>