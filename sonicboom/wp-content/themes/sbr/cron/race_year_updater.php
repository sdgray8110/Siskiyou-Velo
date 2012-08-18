<?php
require_once('/home/gray8110/public_html/sonicboom/wp-load.php');

$years = resultsScraper::race_years();
update_post_meta(108,'race_years',$years);

print_r(resultsScraper::get_race_years());