<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/wordpress/wp-content/classes/class.svdb.php');
$svdb = new svdb;
$membership = $svdb->membershipVP();


?>