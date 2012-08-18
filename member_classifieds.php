<?php require_once("includes/auth.php"); ?>
<?php include_once("fckeditor/fckeditor.php");?>
<?php include("includes/header.php"); ?>New Classified Posting</title>
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

<h1>New Classified Posting</h1>
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
$seller = $firstname . " " . $lastname;
$email = $row["email1"];
$phone = $row["phone"];

echo "
<form id='profile' action='includes/classified_exec.php' method='post' enctype='multipart/form-data' onSubmit='return validate();'>

<dl>
<h2 style='padding:0 0 3px 0; border-bottom:1px dotted #000; '>Seller Details</h2>

<dt><label for='seller'>Name:</label></dt>
<dd><input type='seller' name='seller' id='seller' value='" . $seller . "' class='required' minlength='2' /></dd>

<dt><label for='email'>Email Address:</label></dt>
<dd><input type='email' name='email' id='email' value='" . $email . "' class='required email' /></dd>

<dt><label for='phone'>Phone Number:</label></dt>
<dd><input type='phone' name='phone' id='phone' value='" . $phone . "' class='required' minlength='7' maxlength='16' /></dd>
</dl>

<h2 style='padding:0 0 3px 0; border-bottom:1px dotted #000; clear:both;'>Sale Details</h2>

<dl>
<dt><label for='item'>Item for sale:</label></dt>
<dd><input type='text' name='item' id='item' class='required' minlength='2' maxlength='40' /></dd>

<dt><label for='price'>Price:</label></dt>
<dd><input type='price' name='price' id='price' /></dd>
</dl>

<h2 style='padding:0 0 3px 0; border-bottom:1px dotted #000; clear:both; '>Upload Image*</h2>

<dl>
<dt><label for='upload'>Choose a File:<br /><span style='font-weight:normal; font-style:italic;'>Images should be .jpg files no larger than 1Mb.</span></label></dt>
<dd style='padding-bottom:7px;'><input name='uploaded' type='file' /></dd>
</dl>

<h2 style='margin: 12px 0 7px 0; clear:both;'>Item Description</h2>


<div style='margin:0 0 15px 14px;'>";
$FCKeditor = new FCKeditor('FCKeditor1') ;
$FCKeditor->BasePath = '/fckeditor/' ;
$FCKeditor->Height = '300px';
$FCKeditor->Width = '613px';
$FCKeditor->Create() ;
echo "</div>";

}

?>
    <input type="hidden" id="memberID" name="memberID" value="<?=$_SESSION['SESS_MEMBER_ID'];;?>">
    <input type="submit" value="Submit" class="submit">
  </form>
  
<?php
//Close connection
mysql_close($connection);

?>

<h1>Notes</h1>
<ul class="mainlist">
    <li class="head"><strong>Duration</strong>:</li>
    <li>Listings will remain active for 6 months. If the item sells, please return to the classifieds page to delete the entry.</li>
    
    <li class="head"><strong>Corrections</strong>:</li>
    <li>Corrections and modifications can be made by clicking &ldquo;Edit Listing&rdquo; from the listing&rsquo;s page.</li>    
    
    <li class="head"><strong>Moderation</strong>:</li>
    <li>The officers of the Siskiyou Velo reserve the right to remove or edit content as deemed appropriate.</li> 
    
    <li class="head"><strong>Photos</strong>:</li>
    <li>A single jpg image can be attached to your auction. The image will be resized to work with our site.  The maximum file size is 1Mb</li>    
    
</ul>

</div>
<!-------- END MAIN BODY -------->
    
<?php include("includes/generic_feed.html"); ?>  
<?php include("includes/foot.html"); ?>