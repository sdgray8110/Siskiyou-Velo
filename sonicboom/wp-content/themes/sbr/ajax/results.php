<?php
if ($_GET['year']) {
    include('ajaxConfig.php');
    include($ajaxConfig['path'] . 'wp-load.php');
    $year = $_GET['year'];
} else {
    $years = resultsScraper::get_race_years();
    $year = $years[0];
}

$results = resultsScraper::combined_results_by_month($year);
markupHelper::results($results);