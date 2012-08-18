<?php require_once("login/auth_officer.php"); ?>
<?php include_once("fckeditor/fckeditor.php");?>
<?php include("includes/header.php"); ?>New Classified Posting</title>
<?php include("includes/header_bottom.html"); ?>
<?php include("includes/login.php"); ?>
<?php include("includes/topnav.old.php"); ?>
<!------- BEGIN MAIN BODY ------->
<?php

$delete = $_GET['delete'];
$postID = $_GET['id'];

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
$postID = $_GET['id'];


if ($delete == yes) {

	//Create DELTE query
	$qry = "DELETE FROM classifieds WHERE $postID = ID";

	$result = @mysql_query($qry);
	
	//Check whether the query was successful or not
	if($result) {
		echo "<div id='leftContent'>
		<h1>Entry Deleted</h1>
		<p><a href='clubstore.php'>&laquo; Return to Club Store &amp; Classifieds Page</a></p>";
	}else {
		die("Delete Query Failed");
	}
}


else {

//DB Query
$result = mysql_query("SELECT * FROM classifieds WHERE ID = $postID", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}

echo "<div id='leftContent'>

<h1>New Classified Posting</h1>";



//Use returned data
while ($row = mysql_fetch_array($result)) {

$name = $row['name'];
$email = $row['email'];
$phone = $row['phone'];
$item = $row['item'];
$price = $row['price'];
$post = $row['post'];

echo "
<form id='profile' action='includes/classifieds_edit_exec.php' method='post' enctype='multipart/form-data' >

<dl>
<h2 style='padding:0 0 3px 0; border-bottom:1px dotted #000; '>Seller Details</h2>

<dt><label for='seller'>Name:</label></dt>
<dd><input type='seller' name='seller' id='seller' value='" . $name . "' /></dd>

<dt><label for='email'>Email Address:</label></dt>
<dd><input type='email' name='email' id='email' value='" . $email . "' /></dd>

<dt><label for='phone'>Phone Number:</label></dt>
<dd><input type='phone' name='phone' id='phone' value='" . $phone . "' /></dd>
</dl>

<h2 style='padding:0 0 3px 0; border-bottom:1px dotted #000; clear:both;'>Sale Details</h2>

<dl>
<dt><label for='item'>Item for sale:</label></dt>
<dd><input type='text' name='item' id='item' value='" . $item . "' /></dd>

<dt><label for='price'>Price:</label></dt>
<dd><input type='price' name='price' id='price' value='" . $price . "' /></dd>
</dl>

<h2 style='margin: 12px 0 7px 0; clear:both;'>Item Description</h2>

<input type='hidden' value='" . $postID . "' name='postID' id='postID' />

<div style='margin:0 0 15px 14px;'>";
$FCKeditor = new FCKeditor('FCKeditor1') ;
$FCKeditor->BasePath = '/fckeditor/' ;
$FCKeditor->Height = '300px';
$FCKeditor->Width = '613px';
$FCKeditor->Value = $post;
$FCKeditor->Create() ;
echo "</div>";

}

echo "<input type='submit' value='Submit' class='submit'>
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