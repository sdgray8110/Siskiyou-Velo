<?php

include('resultsJSON.php');
$db = 'svblogs';
$table = 'stxc1Results';
$sort = 'place';
$order = 'ASC';
$select = 'DISTINCT field';
$where = false;
$results = new resultsJSON();

$results->displayResults();

?>