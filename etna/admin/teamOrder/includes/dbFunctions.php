<?php

function fullTableQuery($db,$table,$sort,$order,$select,$where) {
    $connection = dbConnect($db);
    $select ? $select = $select : $select = '*';
    $where ? $where = ' ' . $where . ' ' : $where = '';
    $query = 'SELECT '.$select.' FROM ' . $table . $where .' ORDER BY ' . $sort . ' ' .$order;

    //echo $query;

    return mysql_query($query,$connection);
}

function insertPost($db,$table) {
    $connection = dbConnect($db);
    $query = postSQLQuery($table);

    $result = mysql_query($query,$connection);

	if($result) {
		header('Location: ../addParts.php');
        exit();
        
	}else {
		die("Query failed: " . $query);
	}
}

function postSQLQuery($table) {
    $keys = array();
    $values = array();

    foreach ($_POST as $key => $value) {
        array_push($keys,$key);
        array_push($values,"'".clean($value)."'");
    }

    return buildInsertQuery($keys, $values, $table);
}

function buildInsertQuery($keys, $values, $table) {
    $keys = implode(',',$keys);
    $values = implode(',',$values);
    return 'INSERT INTO '.$table.' ('.$keys.') VALUES ('.$values.')';
}

function dbConnect($db) {
    $connection = mysql_connect("localhost","gray8110","8aU_V{^{,RJC");

    if (!$connection) {
        die ("Database connection failed: " . mysql_error());
    }

    $db_select = mysql_select_db("gray8110_".$db,$connection);

    if (!$db_select) {
	    die("Database selection failed: " . mysql_error());
    }

    return $connection;
}

function clean($str) {
    $str = @trim($str);
    if(get_magic_quotes_gpc()) {
            $str = stripslashes($str);
    }
    return mysql_real_escape_string($str);
}

?>