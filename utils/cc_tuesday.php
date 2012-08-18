<?php
require_once('classes/twitter.php');

$user = 'CC_Tuesday'; // User Name
$message = 'Wear&Share your cap tomorrow!'; // Message
$followers = 15; // How Many Followers To Include


$twitter = new twitter($user,$message,$followers);

foreach ($twitter->tweets as $tweet) { ?>
    <p><?=$tweet;?></p>
<?php } ?>