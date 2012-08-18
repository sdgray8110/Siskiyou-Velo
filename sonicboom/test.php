<?php
include('wp-load.php');

$strava = new strava(1673);
$strava->save_ride_data(123);
