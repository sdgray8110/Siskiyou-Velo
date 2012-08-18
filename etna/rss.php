<?php 
echo '<?xml version="1.0" encoding="ISO-8859-1"?>
<rss version="0.91">
	<channel>
		<title>Etna Brewing/DeSalvo Custom Cycles</title>
		<link>http://www.etna-desalvo.com</link>
		<description>Website and blog for the Etna Brewing/DeSalvo Custom Cycles cycling team</description>
		<language>en-us</language>
		<lastBuildDate>' . date("D, d M Y H:i:s") . ' -0800</lastBuildDate>
		<copyright>Copyright ' . date(Y) . ', Etna Brewing/DeSalvo Custom Cycles</copyright>
		<webMaster>info@etna-desalvo.com (Etna Brewing DeSalvo Custom Cycles Racing Team)</webMaster>
		
		<image>
			<title>Etna Brewing/DeSalvo Custom Cycles</title>
			<url>http://www.etna-desalvo.com/admin/images/logos.jpg</url>
			<link>http://www.etna-desalvo.com</link>
		</image>';

//Connect to database
$connection = mysql_connect("localhost","gray8110","8aU_V{^{,RJC");
if (!$connection) {
	die ("Database connection failed: " . mysql_error());
}

$db_select = mysql_select_db("gray8110_etna",$connection);

if (!db_select) {
	die("Database selection failed: " . mysql_error());
} 
?>

<?php
$result = mysql_query("SELECT * FROM posts WHERE draft != '1' ORDER BY ID DESC LIMIT 10", $connection);

if (!result) {
   die("Database query failed: " . mysql_error());
}

while ($row = mysql_fetch_array($result)) {

$username = $row["username"];
$userID = $row["userID"];
$title = $row["title"];
$post = $row["post"];
$timestamp = strtotime($row["timestamp"]);
$post_id = $row["ID"];
$date = date('D, d M Y H:i:s', $timestamp);
$search = array("<", ">", "&nbsp;", "(", ")", "&mdash;", "&ldquo;", "&rdquo;","&rsquo;","’");
$replace = array("&lt;", "&gt;", "", "-", "ldquo;", "rdquo;", "quot;", "quot;");
$description = str_replace($search, $replace, $post);
$title = str_replace($search, $replace, $title);

echo '
		<item>
			<title>' . $title . '</title>
			<link>http://www.etna-desalvo.com/full_entry.php?id=' . $post_id . '</link>
			<description><![CDATA['.$post.']]></description>
			<pubDate>' . $date . ' -0800</pubDate>
		</item>';

}
//Close connection
mysql_close($connection);

echo '
	</channel>
</rss>';

?>

