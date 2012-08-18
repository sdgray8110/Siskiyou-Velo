<?php
require_once('/home/gray8110/public_html/sonicboom/wp-load.php');

$teamdata = new resultsScraper;

foreach ($teamdata->results as $rider) {
    update_user_meta($rider->id,'results',$rider);
    echo 'done! <br />';
}


print_r(get_user_meta(1,'results',true));

$strava = new strava(1673);
$strava->save_ride_data(123);

print_r(get_post_meta(123,'rides'));