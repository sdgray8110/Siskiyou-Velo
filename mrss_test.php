<?php 
$date = date("M, d Y G:i:s").' +0000';

echo '<?xml version="1.0" encoding="utf-8"?>
<rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" xmlns:media="http://search.yahoo.com/mrss" version="2.0">
  <channel>
    <generator>Stream OS</generator>
    <pubDate>Thu, 10 Sep 2009 23:50:21 +0000</pubDate>
    <lastBuildDate>'.$date.'</lastBuildDate>
    <title>MF Media Center Test</title>
    <link>http://www.musiciansfriend.com</link>
    <description>Musicians Friend Videos</description>
    <itunes:block xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">yes</itunes:block>
    <itunes:explicit xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">yes</itunes:explicit>

';

include("includes/db_connect.php"); 

//Connect to database
$connection = mysql_connect("localhost","gray8110","8aU_V{^{,RJC");

//Debug
if (!$connection) {
	die ("Database connection failed: " . mysql_error());
}

//Select database
$db_select = mysql_select_db("gray8110_etna",$connection);

//Debug
if (!db_select) {
	die("Database selection failed: " . mysql_error());
}

$result = mysql_query("SELECT * FROM xml_rss ORDER BY ID ASC", $connection);

if (!result) {
   die("Database query failed: " . mysql_error());
}

while ($row = mysql_fetch_array($result)) {

$ID = $row["ID"];
$title = $row["title"];
$creator = $row["creator"];
$image = $row["image"];
$find = array("rtmpt://");
$replace = array ("http://");
$location = $row["location"];
$identifier = $row["identifier"];
$meta = $row["meta"];

echo '
	<item>
		<title>'.$title.'</title>
		<author>'.$creator.'</author>
		<description>'.$title.'</description>
		<pubDate>'.$date.'</pubDate>
		<enclosure url="'.$location.$identifier.'.flv" type="application/xml"/>
		<media:content xmlns:media="http://search.yahoo.com/mrss" expression="nonstop" bitrate="" duration="00:02:27" url="'.$location.$identifier.'.flv"/>
		<media:description xmlns:media="http://search.yahoo.com/mrss">'.$title.'</media:description>
		<media:thumbnail xmlns:media="http://search.yahoo.com/mrss" url="'.$image.'"/>
		<media:title xmlns:media="http://search.yahoo.com/mrss">Musicians Friend | '.$title.'</media:title>
	</item>
	';
}

?>
  </channel>
</rss>

