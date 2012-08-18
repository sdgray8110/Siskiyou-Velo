<?php
$riderID = $_POST['riderID'];
$riderName = $_POST['riderName'];

//Connect to database
$connection = mysql_connect("localhost","gray8110","8aU_V{^{,RJC");
if (!$connection) {
	die ("Database connection failed: " . mysql_error());
}

$db_select = mysql_select_db("gray8110_etna",$connection);

if (!db_select) {
	die("Database selection failed: " . mysql_error());
}

$userID = ' ' . $riderID . ' ';
$initResult = mysql_query("SELECT * FROM obra ORDER BY ID ASC", $connection);
$increment = 1;
$count = mysql_num_rows($initResult);

while ($row = mysql_fetch_array($initResult)) {
$definite = $row["definite"];
$definite_name = $row["definite_name"];
$definite_replace = str_replace($userID,'',$definite);
$definiteN_replace = str_replace($riderName,'',$definite_name);
$maybe = $row["maybe"];
$maybe_name = $row["maybe_name"];
$maybe_replace = str_replace($userID,'',$maybe);
$maybeN_replace = str_replace($riderName,'',$maybe_name);
$raceID = $row["ID"];

$qry = "UPDATE obra SET
			definite = '$definite_replace',
			maybe = '$maybe_replace',
			definite_name = '$definiteN_replace',
			maybe_name = '$maybeN_replace'
			WHERE ID = '$raceID'";

if (!empty($qry))
    {
    mysql_query($qry);
    }
unset($qry);
$increment++; // increment the position
}

$result = mysql_query("SELECT * FROM obra ORDER BY ID ASC", $connection);
$numRows = mysql_num_rows($result) + 1;
$definite = $row["definite"];
$maybe = $row["maybe"];
$position = 1;

while ($position <= $numRows) {
	$name = $_POST["$position"];
	
	if ($name == 'definite') {
			$qry = "UPDATE obra SET
					definite = concat(definite, '$userID'),
					definite_name = concat(definite_name, '$riderName')
					WHERE $position = ID";
	}
	
	elseif ($name == 'maybe') {
			$qry = "UPDATE obra SET
					maybe = concat(maybe, '$userID'),
					maybe_name = concat(maybe_name, '$riderName')
					WHERE $position = ID";					
	}

if (!empty($qry))
    {
    mysql_query($qry);
    }
unset($name);
unset($qry);
$position++; // increment the position
}

		header("location: ../");
		exit();

?>