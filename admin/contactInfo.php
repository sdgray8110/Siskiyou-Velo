<?php
include('../includes/db_connect.php');
$result = mysql_query("SELECT * FROM sv_newsbrief ORDER BY ID ASC", $connection);

for ($i = 6; ; $i++) {
    if ($i > 30) {
        break;
	}

//Create INSERT query
$qry = "UPDATE wp_users SET
	sent = '1'
	WHERE ID = '$i'";	

$queryResult = @mysql_query($qry, $connection);

//Check whether the query was successful or not
if(queryResult) {echo 'Success';}
else {
	die("Query failed");

}
}

?>