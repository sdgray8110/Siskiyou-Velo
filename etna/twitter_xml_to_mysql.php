<?php
include('includes/functions.php');
//Connect to database
$connection = mysql_connect("localhost","gray8110","8aU_V{^{,RJC");
if (!$connection) {
	die ("Database connection failed: " . mysql_error());
}

$db_select = mysql_select_db("gray8110_etna",$connection);

if (!$db_select) {
	die("Database selection failed: " . mysql_error());
}

$sql = "TRUNCATE TABLE twitter";
$truncate = @mysql_query($sql);

if(!$truncate) {
    die($text."<br />");
}

$file = array(
    'http://twitter.com/statuses/user_timeline/14872581.xml?count=10&callback=?',
    'http://twitter.com/statuses/user_timeline/22804726.xml?count=10&callback=?',
    'http://twitter.com/statuses/user_timeline/27315879.xml?count=10&callback=?',
    'http://twitter.com/statuses/user_timeline/18694729.xml?count=10&callback=?',
    'http://twitter.com/statuses/user_timeline/21940312.xml?count=10&callback=?',
    'http://twitter.com/statuses/user_timeline/19002826.xml?count=10&callback=?'
);

foreach ($file as $feed) {
    $xml = simplexml_load_file($feed);

    foreach ($xml->status as $status) {
       $date = clean(strtotime($status->created_at));
       $name = clean($status->user->screen_name);
       $thumb = clean($status->user->profile_image_url);
       $textValue = clean($status->text);
       $sourceValue = clean($status->source);
       $reply_to_name = clean($status->in_reply_to_screen_name);
       $reply_to_id = clean($status->in_reply_to_status_id);
       $noPost = strpos($source,'twitterfeed');

       if ($noPost == false) {
            //Create INSERT query
            $qry = "INSERT INTO gray8110_etna.twitter (ID, date, name, thumb, text, source, reply_to_name, reply_to_id) VALUES ('$x','$date', '$name', '$thumb', '$textValue', '$sourceValue', '$reply_to_name', '$reply_to_id')";

            $result = @mysql_query($qry);

            if($result) {
            }else {
                die($text."<br />");
            }
       }
    }
}
?>