<ul style="margin-top:9px;" class="rightNav">
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
?>

<?php
$result = mysql_query("SELECT * FROM links WHERE active = 1 ORDER BY name ASC", $connection);

if (!result) {
   die("Database query failed: " . mysql_error());
}

while ($row = mysql_fetch_array($result)) {

$name = $row["name"];
$url = $row["url"];

echo 
	'
	<li><a target="_blank" href="' . $url . '">' . $name . '</a></li>';

}
?>

<?php
//Close connection
mysql_close($connection);

?>
</ul>
        
<div class="navBottom">
</div>


