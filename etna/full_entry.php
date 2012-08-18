<?php 

//Connect to database
$connection = mysql_connect("localhost","gray8110","8aU_V{^{,RJC");
if (!$connection) {
	die ("Database connection failed: " . mysql_error());
}

$db_select = mysql_select_db("gray8110_etna",$connection);

if (!db_select) {
	die("Database selection failed: " . mysql_error());
} 



$getID = $_GET['id'];
$fullURL = 'http://www.etna-desalvo.com/full_entry.php?id=' . $getID;
$result = mysql_query("SELECT * FROM posts WHERE ID = $getID", $connection);

setcookie("URL",$fullURL, time()+3600);
$pageTitle = 'View Full Blog Post';
include('includes/header.php');
?>

<body>
<div id="wrapper">

	<?php include("includes/nav.php"); ?>
    
  <div id="leftContent">
	<?php include("includes/full_post_include.php"); ?>
  </div>
    
<?php include("includes/right_nav_include.php"); ?>

</div>

     <?php include("includes/footer.php"); ?>
     <?php include("includes/ga.php"); ?>   
</body>
</html>