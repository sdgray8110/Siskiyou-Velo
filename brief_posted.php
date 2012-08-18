<?php require_once("includes/auth.php"); ?>
<?php include("includes/header.php"); ?>Post Newsletter Briefs</title>
<?php include("includes/header_bottom.html"); ?>
<?php include("includes/login.php"); ?>
<?php include("includes/topnav.php"); ?>
<!------- BEGIN MAIN BODY ------->
<div id="leftContent">

<h1>Posted Brief</h1>
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
$result = mysql_query("SELECT * FROM sv_newsbrief ORDER BY ID DESC LIMIT 12", $connection);

if (!result) {
	die("Database query failed: " . mysql_error());
}

while ($row = mysql_fetch_array($result)) {

$filename = $row["filename"];
$issue = $row["issue"];
$item1 = $row["item1"];
$item2 = $row["item2"];
$item3 = $row["item3"];

echo 
    "<h2><a target='_blank' href='../images/Newsletters/PDF/" . $filename . "'>" . $issue . "</a></h2>
        <ul class='subNews'>
            <li>" . $item1 . "</li>
            <li>" . $item2 . "</li>";
if ($item3 == "") {
}

else {
     echo  "<li>" . $item3 . "</li>";
}
echo    "</ul>";

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