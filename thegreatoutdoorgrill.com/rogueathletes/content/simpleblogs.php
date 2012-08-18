<?php
// Functions - these should be moved to an include
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

function stripBlogId($id) {
    $strip = 'tag:blogger.com,1999:blog-';
    $str = str_replace($strip, '', $id);
    $str = explode('.', $str);

    return $str[0];
}
// End functions


// Make sure SimplePie is included. You may need to change this to match the location of simplepie.inc.
require_once('simplepie/simplepie.inc');

// We'll process this feed with all of the default options.
$feed = new SimplePie();

// Set the feed to process.
$feed->set_feed_url(array(
    'http://rogueathletes.blogspot.com/feeds/posts/default',
    'http://goridebikes.blogspot.com/feeds/posts/default',
    'http://sidewalknarrative.blogspot.com/feeds/posts/default'
    ));

// We'll use favicon caching here (Optional)
$feed->set_favicon_handler('handler_image.php');


// Run SimplePie.
$feed->init();

// This makes sure that the content is sent to the browser as text/html and the UTF-8 character set (since we didn't change it).
$feed->handle_content_type();

// Let's begin our XHTML webpage code.  The DOCTYPE is supposed to be the very first thing, so we'll keep it on the same line as the closing-PHP tag.
?>

<head>
	<title>Sample SimplePie Page</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />

    <link rel="stylesheet" type="text/css" href="css/simpleblog.css" />
    <link rel="stylesheet" type="text/css" href="css/default.css"/>

	<style type="text/css">

	</style>

</head>
<div class="mainBackground">
<div class="maincontent">
<div class="simplepiebody">

        <?php if ($feed->error): ?>
		<p><?php echo $feed->error; ?></p>
		<?php endif; ?>
    

	<!--<div class="simplepieheader">
		<h1><a href="<?php echo $feed->get_permalink(); echo $feed->get_favicon(); ?>"><?php echo $feed->get_title(); ?></a></h1>
		<p><?php echo $feed->get_description(); ?></p>
	</div>-->

	<?php
	/*
	Here, we'll loop through all of the items in the feed, and $item represents the current item in the loop.
	*/

    // The unique blog ID for each blogger
    $rachel = '5663273968970224032';
    $travis = '1216852259993742593';

    $i = 0;
	foreach ($feed->get_items() as $item) {

        // Uses my stripBlogId() function to parse the blog's ID for the
        // number we want. See function above.
        $blogId = stripBlogId($item->get_id());
        
        if ($blogId == $rachel) {
            $imgPath = 'sidewalk.png';
        } elseif ($blogId == $travis) {
            $imgPath = 'go_ride_bikes.png';
        } else {
            $imgPath = 'rogue.png';
        }


        if ($i < 10) {

            echo '
            <div class="item">
                <img src="images/blogs/'.$imgPath.'"/>
                <h2><a href="'.$item->get_permalink().'">'.$item->get_title().'</a></h2>
                <p>'.shorten($item->get_description(), 200).'</p>
                <p><small>Posted on '.$item->get_date('j F Y | g:i a').'</small></p>
            </div>
            ';
        }
        
        $i++;
    }

    ?>
    
    
</div>
</div>
</div>