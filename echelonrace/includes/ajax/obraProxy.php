<?php
// Set your return content type
header('Content-type: text/html');

$getURL = $_GET['race_id'];

// Website url to open
$daurl = 'http://obra.org/events/'.$getURL.'/results';

// Get that website's content
$handle = fopen($daurl, "r");

// If there is something, read and return
if ($handle) {
    while (!feof($handle)) {
        $buffer = fgets($handle, 4096);
		$buffer = str_replace('href="/', 'href="http://obra.org/', $buffer);
		$buffer = str_replace('src="/', 'src="http://obra.org/', $buffer);
    }

    echo $buffer;
    fclose($handle);
}
?>