<?php
$date = date("D, d M Y G:i:s").' +0800';
$getFile = $_GET['file'];
$playlist = $_GET['listname'];
$file = explode('?', basename($getFile));
$file = str_replace('.xml', '.rss', $file[0]);


if ($getFile == '') {

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Musician&rsquo;s Friend - XML Playlist to MRSS Converter</title>

<style>
form {width:600px; font-family:Arial, Helvetica, sans-serif; margin:50px auto; padding:50px; background:#bfe2ff; border:4px solid #eee}
label {font-size:14px; display:block; font-weight:700; margin:7px 0 2px 0;}
input {width:100%; padding:4px; display:block; font-size:12px; margin:0;}
input.submit {width:auto; clear:both; margin:5px 0 0 0;}
</style>
</head>
<body>

<form method="get" action="xml_to_mrss.php">
<label for="file">Enter Path to Playlist:</label>
<input type="text" name="file" id="file" />
<label for="listname">Playlist name:</label>
<input type="text" name="listname" id="listname" />
<input class="submit" type="submit" value="Submit" />
</form>

';

}

else {
	
echo '

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Musician&rsquo;s Friend - '.$playlist.': Converted to MRSS</title>

<style>
form {width:900px; font-family:Arial, Helvetica, sans-serif; margin:50px auto; padding:50px; background:#bfe2ff; border:4px solid #eee}
label {font-size:14px; display:block; font-weight:700; margin:7px 0 2px 0;}
label small {font-size:10px; font-style:italic; cursor:pointer; float:right;}
label small:hover {text-decoration:underline;}
textarea {width:100%; height:800px; padding:4px; display:block; font-size:11px; margin:0;}
</style>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js" type="text/javascript"></script>


</head>
<body>
';

//////////////////////////////////////////////////////
// START FORM ** XML RENDERS INSIDE OF TEXT AREA ** //
//////////////////////////////////////////////////////

echo '
<form>
<label for="listname"><small>{Select Code}</small>'.$playlist.' MRSS Playlist</label>
<textarea name="xml_content" value="';

echo '<?xml version="1.0" encoding="utf-8"?>
<rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" xmlns:media="http://search.yahoo.com/mrss" version="2.0">
  <channel>
	<generator>Stream OS</generator>
	<pubDate>Thu, 10 Sep 2009 23:50:21 +0000</pubDate>
	<lastBuildDate>'.$date.'</lastBuildDate>
	<title>Musician\'s Friend | '.$playlist.'</title>
	<link>http://www.musiciansfriend.com</link>
	<description>Musician\'s Friend Media Center</description>
	<itunes:block xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">yes</itunes:block>
	<itunes:explicit xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">yes</itunes:explicit>
';

class Track {
    var $title;
    var $creator;
    var $image;
    var $location;
	var $identifier;
    
    function Track ($aa) 
    {
        foreach ($aa as $k=>$v)
            $this->$k = $aa[$k];
    }
}

function readDatabase($filename) 
{
    // read the XML database
    $data = implode("", file($filename));
    $parser = xml_parser_create();
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, $data, $values, $tags);
    xml_parser_free($parser);

    // loop through the structures
    foreach ($tags as $key=>$val) {
        if ($key == "track") {
            $molranges = $val;
            // each contiguous pair of array entries are the 
            // lower and upper range for each definition
            for ($i=0; $i < count($molranges); $i+=2) {
                $offset = $molranges[$i] + 1;
                $len = $molranges[$i + 1] - $offset;
                $tdb[] = parseMol(array_slice($values, $offset, $len));
            }
        } else {
            continue;
        }
    }
    return $tdb;
}

function parseMol($mvalues) 
{
    for ($i=0; $i < count($mvalues); $i++) {
        $mol[$mvalues[$i]["tag"]] = $mvalues[$i]["value"];
    }
    return new Track($mol);
}

$db = readDatabase($getFile);


//LOOP THROUGH ARRAY AND RENDER RSS
$num = count($db);

for($x=0;$x<$num;$x++){
	
$xmlTitle = $db[$x]->title;
$xmlCreator = $db[$x]->creator;
$xmlImage = $db[$x]->image;
$xmlLocation = $db[$x]->location;
$xmlIdentifier = $db[$x]->identifier;

echo '
	<item>
		<title>'.$xmlCreator.'</title>
		<author>Musician&rsquo;s Friend Media Center</author>
		<description>'.$xmlTitle.'</description>
		<pubDate>'.$date.'</pubDate>
		<enclosure url="'.$xmlLocation.$xmlIdentifier.'.flv" type="application/xml"/>
		<media:content xmlns:media="http://search.yahoo.com/mrss" expression="nonstop" bitrate="" duration="00:02:27" url="'.$xmlLocation.$xmlIdentifier.'.flv"/>
		<media:description xmlns:media="http://search.yahoo.com/mrss">'.$xmlCreator.' | '.$xmlTitle.'</media:description>
		<media:thumbnail xmlns:media="http://search.yahoo.com/mrss" url="'.$xmlImage.'"/>
		<media:title xmlns:media="http://search.yahoo.com/mrss">'.$xmlCreator.' | '.$xmlTitle.'</media:title>
	</item>
';

}

echo '
  </channel>
</rss>
';

echo '</textarea>
</form>

';

}
?>
</body>
</html>

<script type="text/javascript">
	$(document).ready(function() {
		$('textarea').focus(function() {
			$(this).select();
		});
		$('label small').click(function() {
			$('textarea').select();
		});
	});
</script>