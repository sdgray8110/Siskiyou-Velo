<?php
$pageTitle = 'Etna Brewing/DeSalvo Custom Cycles Team Roster';
include('includes/globalVars.php');
include('includes/header.php');
?>
<body>
<div id="wrapper">

	<?php include("includes/nav.php"); ?>
    
  <div id="leftContent">
      <h1>Team Roster</h1>
        <p class="postInfo"><span>The State of Jefferson's Team</span> | Stay tuned for updates.</span></p>
        <div class="post">
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

$result = mysql_query("SELECT * FROM users WHERE lastname != 'DeSalvo' ORDER BY lastname ASC", $connection);

if (!result) {
   die("Database query failed: " . mysql_error());
}

while ($row = mysql_fetch_array($result)) {

$riderID = $row["id"];
$firstname = $row["firstname"];
$lastname = $row["lastname"];
$city = $row["city"];
$blogger = $row["blogger"];
$road = $row["road"];
$mtn = $row["mtn"];
$cx = $row["cx"];
$obra = $row["obra"];
$uscf = $row["uscf"];

echo 	'<a name="' . $riderID . '"></a>';

if ($blogger == 0) {
echo	'<h2>' . $firstname . ' ' . $lastname . '</h2>';
}

else {
echo	'<span class="float"><a href="rider_posts.php?id='.$riderID.'">View Blog Posts &raquo;</a></span><h2>' . $firstname . ' ' . $lastname . '</h2>';
}
echo	'<p class="riderDetails">Hometown: ' . $city . '</p>
		<p class="riderDetails">';

if ($road) {echo 'Road Category: ' . $road;}
if ($mtn) {echo ' | Mountain Category: ' . $mtn;}
if ($cx) {echo ' | Cyclocross Category: ' . $cx;}
		echo '</p>';

// BEGIN OBRA & USCF LOGIC //
if ($obra != '0' || $uscf != '0') {echo '<p class="riderDetails">';}
if ($obra != '0') {echo '<a target="_blank" href="http://app.obra.org/people/' . $obra . '">OBRA Results</a>';}
if ($obra != '0' && $uscf != '0') {echo ' | ';}
if ($uscf !='0') {echo '<a target="_blank" href="http://www.usacycling.org/results/index.php?compid=' . $uscf . '">USCF Results</a>';}
if ($obra != '0' || $uscf != '0') {echo ' | <a href="rider_schedule.php?id='.$riderID.'">'.$varYear.' Schedule</a></p>';}
if ($obra == '0' && $uscf == '0') {echo '<p class="riderDetails"><a href="rider_schedule.php?id='.$riderID.'">'.$varYear.' Schedule</a></p>';}
// END OBRA & USCF LOGIC //

echo '<hr noshade="noshade" size="1" />
';

}
?>

<?php
//Close connection
mysql_close($connection);

?>
</div>
  </div>

<?php include("includes/right_nav_include.php"); ?>

</div>

     <?php include("includes/footer.php"); ?>
     <?php include("includes/ga.php"); ?>
</body>
</html>
