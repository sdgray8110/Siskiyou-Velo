<?php include ('includes/db_connect.php');?>

<?php
$result = mysql_query("SELECT * FROM sv_newsbrief ORDER BY ID DESC LIMIT 12", $connection);

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
    "<li><a target='_blank' href='../images/Newsletters/PDF/" . $filename . "'>" . $issue . "</a>
        <ul class='subNews" . $position . "'>
         <li class='preview'>In This Issue:</li>
            <li>" . $item1 . "</li>
            <li>" . $item2 . "</li>";
if ($item3) {
     echo  "<li>" . $item3 . "</li>";
}
echo         
      "</ul>
   </li>";
   
    $position++; // increment the position
}

?>

<?php

$pageName = curPageName();
	if (strpos($pageName, 'ride_detail' ) != false) {

//Close connection
mysql_close($connection);
	}

?>

