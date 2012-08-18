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
	
$raceID = $_GET["id"];
$riderID = $_GET["rider"];
$userResult = mysql_query("SELECT * FROM obra where ID = $raceID", $connection);
$row = mysql_fetch_array($userResult);
$event = $row["event"];
$maybe = $row["maybe_name"];
$definite = $row["definite_name"];
	$maybe = substr_replace($maybe, '', -2) . '';
	$definite = substr_replace($definite, '', -2) . '';	
$test = explode("--",$maybe);
$test2 = explode("--",$definite);
$racingYear = date('Y');
$pageTitle = 'Riders attending ' . $racingYear . ' ' . $event;

include('includes/header.php');
?>
<body>
<div id="wrapper">

	<?php include("includes/nav.php"); ?>
    
  <div id="leftContent">
  
<?php 
echo '<h1>' . $event . '</a></h1>';
		
if ($definite != '') {
echo '<p class="postInfo"><span>These riders have indicated they are definitely attending this event:</span></p>
		<div class="post">
		<ul class="riderList">';
foreach ($test2 as $value) {
    echo '<li>'.$value.'</li>';
	}
echo '	</ul>
		</div>';
}

if ($maybe != '') {
echo '<p class="postInfo"><span>These riders have indicated they may be attending this event:</span></p>
		<div class="post">
		<ul class="riderList">';
foreach ($test as $value) {
    echo '<li>'.$value.'</li>';
}
echo '	</ul>
		</div>';
}

if ($definite == '' && $maybe == '') {
echo '<p class="postInfo">No riders have added this event to their calendar.</p>';
}

echo '<p style="text-align:right;"><a href="rider_schedule.php?id='.$riderID.'">Return to previous page &raquo;</a></p>';

//Close connection
mysql_close($connection);

?>

  </div>
    
<?php include("includes/right_nav_include.php"); ?>

</div>

     <?php include("includes/footer.php"); ?>
     <?php include("includes/ga.php"); ?>   
</body>
</html>
