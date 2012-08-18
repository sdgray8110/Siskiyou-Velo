<?php
require_once('twitter.php');

$message = 'Wear&Share your cap tomorrow!';
$count = $_GET['count'] ? $_GET['count'] : 15;
$twitter = new twitter('CC_Tuesday',$message,$count);

foreach ($twitter->tweets as $tweet) { ?>
    <p><?=$tweet;?></p>
<?php } ?>