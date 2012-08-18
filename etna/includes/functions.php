<?php
function db_connect($table) {
//Connect to database
$connection = mysql_connect("localhost","gray8110","8aU_V{^{,RJC");

//Debug
if (!$connection) {
	die ("Database connection failed: " . mysql_error());
}

//Select database
$new_db = mysql_select_db($table,$connection);

//Debug
if (!$new_db) {
	die("Database selection failed: " . mysql_error());
}
}
return $new_db;

function clean($str) {
        $str = @trim($str);
        if(get_magic_quotes_gpc()) {
                $str = stripslashes($str);
        }
        return mysql_real_escape_string($str);
}

// checks if iterated value is positive
function checkNum($num){
  return ($num%2) ? TRUE : FALSE;
}
?>