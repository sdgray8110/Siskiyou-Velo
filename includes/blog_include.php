<?php 

//HOW LONG AGO
function getHowLongAgo($date, $display = array('Year', 'Month', 'Day', 'Hour', 'Minute', 'Second'), $ago = 'Ago')
{
    $date = getdate(strtotime($date));
    $current = getdate();
    $p = array('year', 'mon', 'mday', 'hours', 'minutes', 'seconds');
    $factor = array(0, 12, 30, 24, 60, 60);

    for ($i = 0; $i < 6; $i++) {
        if ($i > 0) {
            $current[$p[$i]] += $current[$p[$i - 1]] * $factor[$i];
            $date[$p[$i]] += $date[$p[$i - 1]] * $factor[$i];
        }
        if ($current[$p[$i]] - $date[$p[$i]] > 1) {
            $value = $current[$p[$i]] - $date[$p[$i]];
            return $value . ' ' . $display[$i] . (($value != 1) ? 's' : '') . ' ' . $ago;
        }
    }

    return '';
}

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
$result = mysql_query("SELECT * FROM sv_blogposts ORDER BY ID DESC LIMIT 4", $connection);

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
    "<h1>" . $header . "</h1>"
		. $body . 
		"<p class='blogInfo'>Posted by " . $author . " " . getHowLongAgo($timestamp);
if ($author == ($_SESSION['SESS_FIRST_NAME']. " " . $_SESSION['SESS_LAST_NAME'])) {
	echo " | <a href='blog_post_edit.php?id=" . $post_id .  "'>Edit Post</a>";
}
	echo "</p>";

}
?>

<?php
//Close connection
mysql_close($connection);

?>

