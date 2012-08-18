<ul>
<?php

function shorten($string, $length)
{
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



require_once('simplepie.inc');

$feed = new SimplePie(array(
  'http://feedproxy.google.com/BikePortland',
  'http://www.veloreview.com/obra3/',
  'http://blog.oregonlive.com/cycling_impact/atom.xml',
  'http://pipes.yahoo.com/pipes/pipe.run?_id=tNHVMaiz3RGkcbLP1JzWFw&_render=rss'/*,
  'http://pipes.yahoo.com/pipes/pipe.run?_id=994ddb8231e05f7e59bf48c35c53c528&_render=rss'*/));

        $feed->set_cache_location($_SERVER['DOCUMENT_ROOT'] . '/cache/');
		$feed->set_cache_duration(999999999); 
		$feed->set_timeout(-1);		
        $feed->enable_cache(true);
        $feed->init();

$feed->set_item_limit(3);
$feed->handle_content_type();

foreach ($feed->get_items() as $item)
{
	echo '<li><h3><a href="' . $item->get_permalink () . '">' . $item->get_title () . '</a></h3>';
	echo '<p>' . shorten($item->get_description(), 140) . '</p>';
	echo '<p class="from"><a href="' . $item->get_feed()->get_permalink() . '">' . $item->get_feed()->get_title() . '</a> | ' . $item->get_date(m . '/' . d . '/' . y . ' | ' . g . ':' . i . a) . '</p></li>';
}
?>
</ul>