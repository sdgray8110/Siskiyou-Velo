<head>
<style>
body {width:1045px;}
h1 {font-family:Arial, Helvetica, sans-serif; font-size:24px; margin-bottom:15px;}
h2 {clear:both; margin:12px 0 0 0;}
ul {list-style-type:none; font-family:Arial, Helvetica, sans-serif; font-size:12px; width:333px; margin-right:15px; float:left; padding:0;}
li {height:15px;}
li.odd {background:#eee;}
li:hover {background:#4b4b4b; color:#fff;}
span.standing {float:left; width:28px;}
span.category {float:left; width:50px;}
span.name {float:left; width:175px;}
span.ttt {float:left; width:225px;}
span.time {float:left; width:75px;}
</style>
</head>
<body>

<h1>Agate Lake Time Trial</h1>

<?php include('includes/db_connect.php');
include('includes/functions.php'); ?>

<?php

//DB Query
$result = mysql_query("SELECT * FROM tt_series ORDER BY ID ASC", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}

/** OVERALL **/

echo '<ul>
<h2>Overall Individual Results</h2>';

$search = array ('0','1','2','3','4','5','6','7','8','9');
$replace = array ('00','01','02','03','04','05','06','07','08','09');

//Use returned data
$position = 1;
while ($row = mysql_fetch_array($result)) {
$obra = $row["obra_number"];
$category = $row ["category"];
$lastname = $row ["lastname"];
$firstname = $row ["firstname"];
$team = $row ["team"];
	if ($row["ag_m"] < 10) {$ag_m = str_replace($search, $replace, $row["ag_m"]);}
	else $ag_m = $row["ag_m"];
	
	if ($row["ag_s"] < 10) {$ag_s = str_replace($search, $replace, $row["ag_s"]);}
	else $ag_s = $row["ag_s"];
	
	if ($row["ag_d"] < 10) {$ag_d = str_replace($search, $replace, $row["ag_d"]);}
	else $ag_s = $row["ag_d"];
	
	if ($row["ag_m"] != '') {$cumulative = $ag_m.':'.$ag_s.'.'.$ag_d;}
	else {$cumulative = 'DNF';}
$ag_o = $row ["ag_o"];
$ag_spec = $row["ag_spec"];

if ($firstname != '' && ag_o != '') {
	if(checkNum($position) === TRUE){
	echo '<li><span class="standing">'.$position. '</span><span class="category">' .$category. '</span><span class="name">' . $firstname . ' ' . $lastname . '</span><span class="time">' . $cumulative . '</span></li>
	';
	}
	else {
	echo '<li class="odd"><span class="standing">'.$position. '</span><span class="category">' .$category. '</span><span class="name">' . $firstname . ' ' . $lastname . '</span><span class="time">' . $cumulative . '</span></li>
	';
	}

	$position ++;
}

}
echo '</ul>

<ul>';

/* 1/2/3*/

echo '<h2>Senior Men 1/2/3</h2>';

$result = mysql_query("SELECT * FROM tt_series WHERE category = 'M123' ORDER BY ID ASC", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}

$position = 1;
//Use returned data
while ($row = mysql_fetch_array($result)) {
$obra = $row["obra_number"];
$category = $row ["category"];
$lastname = $row ["lastname"];
$firstname = $row ["firstname"];
$team = $row ["team"];
	if ($row["ag_m"] < 10) {$ag_m = str_replace($search, $replace, $row["ag_m"]);}
	else $ag_m = $row["ag_m"];
	
	if ($row["ag_s"] < 10) {$ag_s = str_replace($search, $replace, $row["ag_s"]);}
	else $ag_s = $row["ag_s"];
	
	if ($row["ag_d"] < 10) {$ag_d = str_replace($search, $replace, $row["ag_d"]);}
	else $ag_s = $row["ag_d"];
	
	if ($row["ag_m"] != '') {$cumulative = $ag_m.':'.$ag_s.'.'.$ag_d;}
	if ($row["ag_m"] != '') {$cumulative = $ag_m.':'.$ag_s.'.'.$ag_d;}
	else {$cumulative = 'DNF';}
$ag_o = $row ["ag_o"];
$ag_spec = $row["ag_spec"];

if ($firstname != '' && ag_o != '') {
	if(checkNum($position) === TRUE){
	echo '<li><span class="standing">'.$position. '</span><span class="category">' .$category. '</span><span class="name">' . $firstname . ' ' . $lastname . '</span><span class="time">' . $cumulative . '</span></li>
	';
	}
	else {
	echo '<li class="odd"><span class="standing">'.$position. '</span><span class="category">' .$category. '</span><span class="name">' . $firstname . ' ' . $lastname . '</span><span class="time">' . $cumulative . '</span></li>
	';
	}

	$position ++;
}

}
/* 4/5*/

echo '<h2>Senior Men 4/5</h2>';

$result = mysql_query("SELECT * FROM tt_series WHERE category = 'M45' ORDER BY ID ASC", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}

$position = 1;
//Use returned data
while ($row = mysql_fetch_array($result)) {
$obra = $row["obra_number"];
$category = $row ["category"];
$lastname = $row ["lastname"];
$firstname = $row ["firstname"];
$team = $row ["team"];
	if ($row["ag_m"] < 10) {$ag_m = str_replace($search, $replace, $row["ag_m"]);}
	else $ag_m = $row["ag_m"];
	
	if ($row["ag_s"] < 10) {$ag_s = str_replace($search, $replace, $row["ag_s"]);}
	else $ag_s = $row["ag_s"];
	
	if ($row["ag_d"] < 10) {$ag_d = str_replace($search, $replace, $row["ag_d"]);}
	else $ag_s = $row["ag_d"];
	
	if ($row["ag_m"] != '') {$cumulative = $ag_m.':'.$ag_s.'.'.$ag_d;}
	if ($row["ag_m"] != '') {$cumulative = $ag_m.':'.$ag_s.'.'.$ag_d;}
	else {$cumulative = 'DNF';}
$ag_o = $row ["ag_o"];
$ag_spec = $row["ag_spec"];

if ($firstname != '' && ag_o != '') {
	if(checkNum($position) === TRUE){
	echo '<li><span class="standing">'.$position. '</span><span class="category">' .$category. '</span><span class="name">' . $firstname . ' ' . $lastname . '</span><span class="time">' . $cumulative . '</span></li>
	';
	}
	else {
	echo '<li class="odd"><span class="standing">'.$position. '</span><span class="category">' .$category. '</span><span class="name">' . $firstname . ' ' . $lastname . '</span><span class="time">' . $cumulative . '</span></li>
	';
	}

	$position ++;
}

}


/* 1/2/3 */

echo '<h2>Senior Women 1/2/3</h2>';

$result = mysql_query("SELECT * FROM tt_series WHERE category = 'W123' ORDER BY ID ASC", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}

$position = 1;
//Use returned data
while ($row = mysql_fetch_array($result)) {
$obra = $row["obra_number"];
$category = $row ["category"];
$lastname = $row ["lastname"];
$firstname = $row ["firstname"];
$team = $row ["team"];
	if ($row["ag_m"] < 10) {$ag_m = str_replace($search, $replace, $row["ag_m"]);}
	else $ag_m = $row["ag_m"];
	
	if ($row["ag_s"] < 10) {$ag_s = str_replace($search, $replace, $row["ag_s"]);}
	else $ag_s = $row["ag_s"];
	
	if ($row["ag_d"] < 10) {$ag_d = str_replace($search, $replace, $row["ag_d"]);}
	else $ag_s = $row["ag_d"];
	
	if ($row["ag_m"] != '') {$cumulative = $ag_m.':'.$ag_s.'.'.$ag_d;}
	if ($row["ag_m"] != '') {$cumulative = $ag_m.':'.$ag_s.'.'.$ag_d;}
	else {$cumulative = 'DNF';}
$ag_o = $row ["ag_o"];
$ag_spec = $row["ag_spec"];

if ($firstname != '' && ag_o != '') {
	if(checkNum($position) === TRUE){
	echo '<li><span class="standing">'.$position. '</span><span class="category">' .$category. '</span><span class="name">' . $firstname . ' ' . $lastname . '</span><span class="time">' . $cumulative . '</span></li>
	';
	}
	else {
	echo '<li class="odd"><span class="standing">'.$position. '</span><span class="category">' .$category. '</span><span class="name">' . $firstname . ' ' . $lastname . '</span><span class="time">' . $cumulative . '</span></li>
	';
	}

	$position ++;
}

}


/* Women 4 */

echo '<h2>Women 4</h2>';

$result = mysql_query("SELECT * FROM tt_series WHERE category = 'W4' ORDER BY ID ASC", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}

$position = 1;
//Use returned data
while ($row = mysql_fetch_array($result)) {
$obra = $row["obra_number"];
$category = $row ["category"];
$lastname = $row ["lastname"];
$firstname = $row ["firstname"];
$team = $row ["team"];
	if ($row["ag_m"] < 10) {$ag_m = str_replace($search, $replace, $row["ag_m"]);}
	else $ag_m = $row["ag_m"];
	
	if ($row["ag_s"] < 10) {$ag_s = str_replace($search, $replace, $row["ag_s"]);}
	else $ag_s = $row["ag_s"];
	
	if ($row["ag_d"] < 10) {$ag_d = str_replace($search, $replace, $row["ag_d"]);}
	else $ag_s = $row["ag_d"];
	
	if ($row["ag_m"] != '') {$cumulative = $ag_m.':'.$ag_s.'.'.$ag_d;}
	if ($row["ag_m"] != '') {$cumulative = $ag_m.':'.$ag_s.'.'.$ag_d;}
	else {$cumulative = 'DNF';}
$ag_o = $row ["ag_o"];
$ag_spec = $row["ag_spec"];

if ($firstname != '' && ag_o != '') {
	if(checkNum($position) === TRUE){
	echo '<li><span class="standing">'.$position. '</span><span class="category">' .$category. '</span><span class="name">' . $firstname . ' ' . $lastname . '</span><span class="time">' . $cumulative . '</span></li>
	';
	}
	else {
	echo '<li class="odd"><span class="standing">'.$position. '</span><span class="category">' .$category. '</span><span class="name">' . $firstname . ' ' . $lastname . '</span><span class="time">' . $cumulative . '</span></li>
	';
	}

	$position ++;
}

}

/* Women 4 */

echo '<h2>Masters Women 35+</h2>';

$result = mysql_query("SELECT * FROM tt_series WHERE category = 'W35' ORDER BY ID ASC", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}

$position = 1;
//Use returned data
while ($row = mysql_fetch_array($result)) {
$obra = $row["obra_number"];
$category = $row ["category"];
$lastname = $row ["lastname"];
$firstname = $row ["firstname"];
$team = $row ["team"];
	if ($row["ag_m"] < 10) {$ag_m = str_replace($search, $replace, $row["ag_m"]);}
	else $ag_m = $row["ag_m"];
	
	if ($row["ag_s"] < 10) {$ag_s = str_replace($search, $replace, $row["ag_s"]);}
	else $ag_s = $row["ag_s"];
	
	if ($row["ag_d"] < 10) {$ag_d = str_replace($search, $replace, $row["ag_d"]);}
	else $ag_s = $row["ag_d"];
	
	if ($row["ag_m"] != '') {$cumulative = $ag_m.':'.$ag_s.'.'.$ag_d;}
	if ($row["ag_m"] != '') {$cumulative = $ag_m.':'.$ag_s.'.'.$ag_d;}
	else {$cumulative = 'DNF';}
$ag_o = $row ["ag_o"];
$ag_spec = $row["ag_spec"];

if ($firstname != '' && ag_o != '') {
	if(checkNum($position) === TRUE){
	echo '<li><span class="standing">'.$position. '</span><span class="category">' .$category. '</span><span class="name">' . $firstname . ' ' . $lastname . '</span><span class="time">' . $cumulative . '</span></li>
	';
	}
	else {
	echo '<li class="odd"><span class="standing">'.$position. '</span><span class="category">' .$category. '</span><span class="name">' . $firstname . ' ' . $lastname . '</span><span class="time">' . $cumulative . '</span></li>
	';
	}

	$position ++;
}

}

/* 40+ */

echo '<h2>Masters Men 40+</h2>';

$result = mysql_query("SELECT * FROM tt_series WHERE category = '40+' ORDER BY ID ASC", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}

$position = 1;
//Use returned data
while ($row = mysql_fetch_array($result)) {
$obra = $row["obra_number"];
$category = $row ["category"];
$lastname = $row ["lastname"];
$firstname = $row ["firstname"];
$team = $row ["team"];
	if ($row["ag_m"] < 10) {$ag_m = str_replace($search, $replace, $row["ag_m"]);}
	else $ag_m = $row["ag_m"];
	
	if ($row["ag_s"] < 10) {$ag_s = str_replace($search, $replace, $row["ag_s"]);}
	else $ag_s = $row["ag_s"];
	
	if ($row["ag_d"] < 10) {$ag_d = str_replace($search, $replace, $row["ag_d"]);}
	else $ag_s = $row["ag_d"];
	
	if ($row["ag_m"] != '') {$cumulative = $ag_m.':'.$ag_s.'.'.$ag_d;}
	if ($row["ag_m"] != '') {$cumulative = $ag_m.':'.$ag_s.'.'.$ag_d;}
	else {$cumulative = 'DNF';}
$ag_o = $row ["ag_o"];
$ag_spec = $row["ag_spec"];

if ($firstname != '' && ag_o != '') {
	if(checkNum($position) === TRUE){
	echo '<li><span class="standing">'.$position. '</span><span class="category">' .$category. '</span><span class="name">' . $firstname . ' ' . $lastname . '</span><span class="time">' . $cumulative . '</span></li>
	';
	}
	else {
	echo '<li class="odd"><span class="standing">'.$position. '</span><span class="category">' .$category. '</span><span class="name">' . $firstname . ' ' . $lastname . '</span><span class="time">' . $cumulative . '</span></li>
	';
	}

	$position ++;
}

}

/* Masters 50 */

echo '<h2>Masters Men 50+</h2>';

$result = mysql_query("SELECT * FROM tt_series WHERE category = '50+' ORDER BY ID ASC", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}

$position = 1;
//Use returned data
while ($row = mysql_fetch_array($result)) {
$obra = $row["obra_number"];
$category = $row ["category"];
$lastname = $row ["lastname"];
$firstname = $row ["firstname"];
$team = $row ["team"];
	if ($row["ag_m"] < 10) {$ag_m = str_replace($search, $replace, $row["ag_m"]);}
	else $ag_m = $row["ag_m"];
	
	if ($row["ag_s"] < 10) {$ag_s = str_replace($search, $replace, $row["ag_s"]);}
	else $ag_s = $row["ag_s"];
	
	if ($row["ag_d"] < 10) {$ag_d = str_replace($search, $replace, $row["ag_d"]);}
	else $ag_s = $row["ag_d"];
	
	if ($row["ag_m"] != '') {$cumulative = $ag_m.':'.$ag_s.'.'.$ag_d;}
	if ($row["ag_m"] != '') {$cumulative = $ag_m.':'.$ag_s.'.'.$ag_d;}
	else {$cumulative = 'DNF';}
$ag_o = $row ["ag_o"];
$ag_spec = $row["ag_spec"];

if ($firstname != '' && ag_o != '') {
	if(checkNum($position) === TRUE){
	echo '<li><span class="standing">'.$position. '</span><span class="category">' .$category. '</span><span class="name">' . $firstname . ' ' . $lastname . '</span><span class="time">' . $cumulative . '</span></li>
	';
	}
	else {
	echo '<li class="odd"><span class="standing">'.$position. '</span><span class="category">' .$category. '</span><span class="name">' . $firstname . ' ' . $lastname . '</span><span class="time">' . $cumulative . '</span></li>
	';
	}

	$position ++;
}

}

/* Junior Men */

echo '<h2>Junior Men</h2>';

$result = mysql_query("SELECT * FROM tt_series WHERE category = 'JR' ORDER BY ID ASC", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}

$position = 1;
//Use returned data
while ($row = mysql_fetch_array($result)) {
$obra = $row["obra_number"];
$category = $row ["category"];
$lastname = $row ["lastname"];
$firstname = $row ["firstname"];
$team = $row ["team"];
	if ($row["ag_m"] < 10) {$ag_m = str_replace($search, $replace, $row["ag_m"]);}
	else $ag_m = $row["ag_m"];
	
	if ($row["ag_s"] < 10) {$ag_s = str_replace($search, $replace, $row["ag_s"]);}
	else $ag_s = $row["ag_s"];
	
	if ($row["ag_d"] < 10) {$ag_d = str_replace($search, $replace, $row["ag_d"]);}
	else $ag_s = $row["ag_d"];
	
	if ($row["ag_m"] != '') {$cumulative = $ag_m.':'.$ag_s.'.'.$ag_d;}
	if ($row["ag_m"] != '') {$cumulative = $ag_m.':'.$ag_s.'.'.$ag_d;}
	else {$cumulative = 'DNF';}
$ag_o = $row ["ag_o"];
$ag_spec = $row["ag_spec"];

if ($firstname != '' && ag_o != '') {
	if(checkNum($position) === TRUE){
	echo '<li><span class="standing">'.$position. '</span><span class="category">' .$category. '</span><span class="name">' . $firstname . ' ' . $lastname . '</span><span class="time">' . $cumulative . '</span></li>
	';
	}
	else {
	echo '<li class="odd"><span class="standing">'.$position. '</span><span class="category">' .$category. '</span><span class="name">' . $firstname . ' ' . $lastname . '</span><span class="time">' . $cumulative . '</span></li>
	';
	}

	$position ++;
}

}


/* Junior Women */

echo '<h2>Junior Women</h2>';

$result = mysql_query("SELECT * FROM tt_series WHERE category = 'JrW' ORDER BY ID ASC", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}

$position = 1;
//Use returned data
while ($row = mysql_fetch_array($result)) {
$obra = $row["obra_number"];
$category = $row ["category"];
$lastname = $row ["lastname"];
$firstname = $row ["firstname"];
$team = $row ["team"];
	if ($row["ag_m"] < 10) {$ag_m = str_replace($search, $replace, $row["ag_m"]);}
	else $ag_m = $row["ag_m"];
	
	if ($row["ag_s"] < 10) {$ag_s = str_replace($search, $replace, $row["ag_s"]);}
	else $ag_s = $row["ag_s"];
	
	if ($row["ag_d"] < 10) {$ag_d = str_replace($search, $replace, $row["ag_d"]);}
	else $ag_s = $row["ag_d"];
	
	if ($row["ag_m"] != '') {$cumulative = $ag_m.':'.$ag_s.'.'.$ag_d;}
	else {$cumulative = 'DNF';}	
$ag_o = $row["ag_o"];
$ag_spec = $row["ag_spec"];

if ($firstname != '' && ag_o != '') {
	if(checkNum($position) === TRUE){
	echo '<li><span class="standing">'.$position. '</span><span class="category">' .$category. '</span><span class="name">' . $firstname . ' ' . $lastname . '</span><span class="time">' . $cumulative . '</span></li>
	';
	}
	else {
	echo '<li class="odd"><span class="standing">'.$position. '</span><span class="category">' .$category. '</span><span class="name">' . $firstname . ' ' . $lastname . '</span><span class="time">' . $cumulative . '</span></li>
	';
	}

	$position ++;
}

}

echo '</ul>';

//* TTT */

echo '<ul>
<h2>Team Time Trial</h2>';

$result = mysql_query("SELECT * FROM tt_series WHERE firstname = ''", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}

$position = 1;
//Use returned data
while ($row = mysql_fetch_array($result)) {
$team = $row ["team"];
	if ($row["ag_m"] < 10) {$ag_m = str_replace($search, $replace, $row["ag_m"]);}
	else $ag_m = $row["ag_m"];
	
	if ($row["ag_s"] < 10) {$ag_s = str_replace($search, $replace, $row["ag_s"]);}
	else $ag_s = $row["ag_s"];
	
	if ($row["ag_d"] < 10) {$ag_d = str_replace($search, $replace, $row["ag_d"]);}
	else $ag_s = $row["ag_d"];
	
	if ($row["ag_m"] != '') {$cumulative = $ag_m.':'.$ag_s.'.'.$ag_d;}
	else {$cumulative = 'DNF';}
$ag_o = $row["ag_o"];
$ag_spec = $row["ag_spec"];

	if(checkNum($position) === TRUE){
	echo '<li><span class="standing">'.$position. '</span><span class="ttt">'. $team . '</span><span class="time">' . $cumulative. '</span></li>
	';
	}
	
	else {
	echo '<li class="odd"><span class="standing">'.$position. '</span><span class="ttt">'. $team . '</span><span class="time">' . $cumulative. '</span></li>
	';
	}

$position ++;

}

?>

</body>
