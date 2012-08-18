<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/wordpress/wp-content/classes/class.ride.php');
$ride = new ride;
$ride->init($_GET['id']);

echo $ride->get_elevation_data();
?>