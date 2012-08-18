<?php
 
// Make sure SimplePie is included. You may need to change this to match the location of simplepie.inc.
require_once('simplepie/simplepie.inc');
 
// We'll process this feed with all of the default options.
$feed = new SimplePie();
 
// Set the feed to process.
$feed->set_feed_url('http://rogueathletes.blogspot.com/feeds/posts/default');
 
// Run SimplePie.
$feed->init();
 
// This makes sure that the content is sent to the browser as text/html and the UTF-8 character set (since we didn't change it).
$feed->handle_content_type();
 
// Let's begin our XHTML webpage code.  The DOCTYPE is supposed to be the very first thing, so we'll keep it on the same line as the closing-PHP tag.
?>

<?php
function shorten($string, $length) {
    // By default, an ellipsis will be appended to the end of the text.
    $suffix = '...';

    // Convert 'smart' punctuation to 'dumb' punctuation, strip the HTML tags,
    // and convert all tabs and line-break characters to single spaces.
    $short_desc = trim(str_replace(array("\r","\n", "\t"), ' ', strip_tags($string)));

    // Cut the string to the requested length, and strip any extraneous spaces
    // from the beginning and end.
    $desc = trim(substr($short_desc, 0, $length));

    // Find out what the last displayed character is in the shortened string
    $lastchar = substr($desc, -1, 1);

    // If the last character is a period, an exclamation point, or a question
    // mark, clear out the appended text.
    if ($lastchar == '.' || $lastchar == '!' || $lastchar == '?') $suffix='';

    // Append the text.
    $desc .= $suffix;

    // Send the new description back to the page.
    return $desc;
}
?>

<head>
	<title>Sample SimplePie Page</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />

    <link rel="stylesheet" type="text/css" href="css/simplepie.css" />
 
	<style type="text/css">

	</style>
 
</head>
<div class="simplepiebody">

	<!--<div class="simplepieheader">
		<h1><a href="<?php echo $feed->get_permalink(); ?>"><?php echo $feed->get_title(); ?></a></h1>
		<p><?php echo $feed->get_description(); ?></p>
	</div>-->

	<?php
	/*
	Here, we'll loop through all of the items in the feed, and $item represents the current item in the loop.
	*/

    $i = 0;
	foreach ($feed->get_items() as $item) {

        if ($i < 3) {
            echo '
            <div class="item">
                <h2><a href="'.$item->get_permalink().'">'.$item->get_title().'</a></h2>
                <p>'.shorten($item->get_description(), 140).'</p>
                <p><small>Posted on '.$item->get_date('j F Y | g:i a').'</small></p>
            </div>
            ';
        }

        $i++;
    }

    ?>

</div>