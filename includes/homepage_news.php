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
<h1>Current Newsletter &raquo;</h1>


<?php
$result = mysql_query("SELECT * FROM sv_newsbrief ORDER BY ID DESC LIMIT 1", $connection);

if (!result) {
   die("Database query failed: " . mysql_error());
}

$position = 0;

while ($row = mysql_fetch_array($result)) {

$filename = $row["filename"];
$issue = $row["issue"];
$item1 = $row["item1"];
$item2 = $row["item2"];
$item3 = $row["item3"];

echo 
    "<a target='_blank' href='../images/Newsletters/PDF/" . $filename . "'><img src='images/newsletter.jpg' width='188' height='75' border='0' alt='Current Newsletter' /></a>
	<h3>" . $issue . "</h3>
	<ul>
		  <li>" . $item1 . "</li>
		  <li>" . $item2 . "</li>";
if ($item3 == "") {
}

else {
   echo  "<li>" . $item3 . "</li>";
}
echo         
      "</ul>
		<p><a target='_blank' href='../images/Newsletters/PDF/" . $filename . "'>View Current Newsletter &raquo;</a></p>";   

}

?>

<?php
//Close connection
mysql_close($connection);

?>

