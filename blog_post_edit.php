<?php require_once("login/auth_officer.php"); ?>
<?php include_once("fckeditor/fckeditor.php");?>
<?php include("includes/header.php"); ?> Edit Homepage Blog Post</title>
<?php include("includes/header_bottom.html"); ?>
<?php include("includes/login.php"); ?>
<?php include("includes/topnav.old.php"); ?>
<!------- BEGIN MAIN BODY ------->
<div id="leftContent">

<h1>Edit Homepage Blog Post</h1>

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

$urlID = $_GET['id'];

$result = mysql_query("SELECT * FROM sv_blogposts WHERE ID = $urlID ", $connection);
					  
if (!result) {
   die("Database query failed: " . mysql_error());
}

while ($row = mysql_fetch_array($result)) {

$header = $row["header"];
$body = $row["body"];
$author = $row["author"];
$timestamp = $row["timestamp"];
$post_id = $row["ID"];

echo 
"<form id='profile' action='blog_edit_exec.php' method='post'>

<dl><dt><label for='header'>Headline:</label></dt>
<dd><input type='text' name='header' id='header' value='" . $header . "' /></dd></dl>";


$FCKeditor = new FCKeditor('FCKeditor1') ;
$FCKeditor->BasePath = '/fckeditor/' ;
$FCKeditor->Height = '550px';
$FCKeditor->Value = $body;
$FCKeditor->Create() ;

echo "
    <br /><br />
	<input type='hidden' name ='postID' id='postID' value = '" . $urlID . "' >
    <span class='delete'>
        <input type='checkbox' name='deletePost' id='deletePost' value='true' />
        <label for='deletePost'>Delete Post</label>
    </span>
    <input type='submit' value='Submit' class='submit'>
  </form>

</div>
";
}
?>

<!-------- END MAIN BODY -------->
    
<?php include("includes/generic_feed.html"); ?>  
<?php include("includes/foot.html"); ?>