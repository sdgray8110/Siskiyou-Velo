<h1 class="recent">
	<span>Recent Posts</span>
</h1>
        
<ul class="rightNav">

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
$result = mysql_query("SELECT * FROM posts WHERE draft != '1' ORDER BY ID DESC LIMIT 10", $connection);

if (!result) {
   die("Database query failed: " . mysql_error());
}

while ($row = mysql_fetch_array($result)) {

$title = $row["title"];
$post_id = $row["ID"];

echo 
	'
	<li><a href="full_entry.php?id=' . $post_id . '">' . $title . '</a></li>';

}
?>

<?php
//Close connection
mysql_close($connection);

?>

</ul>
        
<div class="navBottom">
</div>