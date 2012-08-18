<?php
include('../includes/global/config.php');
$id = $_GET['id'];
$race = $_GET['race'];
$value = $_GET['value'];

updateSponsors($value, $id, $race);
?>