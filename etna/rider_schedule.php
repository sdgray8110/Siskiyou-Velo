<?php
include('includes/globalVars.php');

//Connect to database
$connection = mysql_connect("localhost","gray8110","8aU_V{^{,RJC");
if (!$connection) {
	die ("Database connection failed: " . mysql_error());
}

$db_select = mysql_select_db("gray8110_etna",$connection);

if (!db_select) {
	die("Database selection failed: " . mysql_error());
} 
$riderID = $_GET["id"];
$userID = ' '.$riderID.' ';

$result = mysql_query("SELECT * FROM users WHERE ID = $riderID", $connection);
$riderArray = mysql_fetch_array($result);
$name = $riderArray["firstname"] .' '. $riderArray["lastname"];
$racingYear = date('Y');
$pageTitle = $racingYear . ' Schedule For ' . $name;

include('includes/header.php');
?>
<body>
<div id="wrapper">

	<?php include("includes/nav.php"); ?>
    
  <div id="leftContent">
	<?php include("includes/rider_schedule.php"); ?>
  </div>
    
<?php include("includes/right_nav_include.php"); ?>

</div>

     <?php include("includes/footer.php"); ?>
     <?php include("includes/ga.php"); ?>   
</body>
</html>