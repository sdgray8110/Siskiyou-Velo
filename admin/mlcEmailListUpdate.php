<?php
include('includes/db_connect.php');

$file = 'mlcemails.csv';
$csv = file($file);

$empty = mysql_query('TRUNCATE TABLE mlc_emails ', $connection);

if ($empty) {
    foreach ($csv as $address) {
        $qry = 'INSERT INTO mlc_emails (email) VALUES ("' . $address .'")';
        $insert = mysql_query($qry,$connection);

        if ($insert) {
            echo $address . ' inserted <br />';
        }
    }

    echo 'done';
}

?>