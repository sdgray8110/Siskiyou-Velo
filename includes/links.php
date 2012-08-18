<?php

//Connect to database
$connection = mysql_connect("localhost","gray8110","8aU_V{^{,RJC");

//Debug
if (!$connection) {
	die ("Database connection failed: " . mysql_error());
}

//Select database
$db_select = mysql_select_db("gray8110_svblogs",$connection);

//Debug
if (!db_select) {
	die("Database selection failed: " . mysql_error());
}

echo '<div class="basic" style="float:left; margin:10px 0;" id="list1a">
			<a class="open">Cycling Organizations</a>
  <ul class="links">';

//////////////////////////
// Cycling Organizations//
//////////////////////////

//DB Query
$result = mysql_query("SELECT * FROM links WHERE category = 'Cycling Organizations' ORDER BY name ASC", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}

//Use returned data
while ($row = mysql_fetch_array($result)) {
	echo '
		<li><a class="links" target="_blank" href="'.$row["url"].'" />'.$row["name"].'</a></li>				
		 ';
}

//////////////////////////
// News and Publications//
//////////////////////////

echo '</ul>
			<a class="open">News and Publications</a>
  		<ul class="links">';
  
//DB Query
$result = mysql_query("SELECT * FROM links WHERE category = 'News and Publications' ORDER BY name ASC", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}

//Use returned data
while ($row = mysql_fetch_array($result)) {
	echo '
		<li><a target="_blank" href="'.$row["url"].'" />'.$row["name"].'</a></li>				
		 ';
}

///////////////////////////
///// Local Centuries /////
///////////////////////////

echo '</ul>
			<a class="open">Local Centuries &amp; Event Rides</a>
  		<ul class="links">';
  
//DB Query
$result = mysql_query("SELECT * FROM links WHERE category = 'Local Centuries' ORDER BY name ASC", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}

//Use returned data
while ($row = mysql_fetch_array($result)) {
	echo '
		<li><a target="_blank" href="'.$row["url"].'" />'.$row["name"].'</a></li>				
		 ';
}

///////////////////////////
// Local Cycling Business//
///////////////////////////

echo '</ul>
			<a class="open">Local Cycling Business</a>
  <ul class="links">';
  
//DB Query
$result = mysql_query("SELECT * FROM links WHERE category = 'Local Cycling Business' ORDER BY name ASC", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}

//Use returned data
while ($row = mysql_fetch_array($result)) {
	echo '
		<li><a target="_blank" href="'.$row["url"].'" />'.$row["name"].'</a></li>				
		 ';
}

  
///////////////////////////
// Southern Oregon Links//
///////////////////////////

echo '</ul>
			<a class="open">Southern Oregon Links</a>
  	  <ul class="links">';
  
//DB Query
$result = mysql_query("SELECT * FROM links WHERE category = 'Southern Oregon Links' ORDER BY name ASC", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}

//Use returned data
while ($row = mysql_fetch_array($result)) {
	echo '
		<li><a target="_blank" href="'.$row["url"].'" />'.$row["name"].'</a></li>				
		 ';
}

///////////////////////////
// Manufacturers///////////
///////////////////////////

echo '</ul>
			<a class="open">Manufacturers</a>
  	  <ul class="links">';
  
//DB Query
$result = mysql_query("SELECT * FROM links WHERE category = 'Manufacturers' ORDER BY name ASC", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}

//Use returned data
while ($row = mysql_fetch_array($result)) {
	echo '
		<li><a target="_blank" href="'.$row["url"].'" />'.$row["name"].'</a></li>				
		 ';
}

///////////////////////////
// Cool Stuff /////////////
///////////////////////////

echo '</ul>
			<a class="open">Cool Stuff</a>
  		<ul class="links">';
  
//DB Query
$result = mysql_query("SELECT * FROM links WHERE category = 'Cool Stuff' ORDER BY name ASC", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}

//Use returned data
while ($row = mysql_fetch_array($result)) {
	echo '
		<li><a target="_blank" href="'.$row["url"].'" />'.$row["name"].'</a></li>				
		 ';
}

echo '</ul>   
			</div>';

?>

  
<?php
//Close connection
mysql_close($connection);

?>