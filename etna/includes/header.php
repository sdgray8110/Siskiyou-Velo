<?php

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="icon" type="image/x-icon" href="favicon.png" />
<title>Etna Brewing/DeSalvo Custom Cycles | '.$pageTitle;

// Get ID for Category Page
if ($getID) {
    echo ' | ' . $getID;
}

echo '</title>
<link href="2009.css" rel="stylesheet" type="text/css" />
<link type="text/css" href="../includes/thickbox.css" rel="stylesheet" />
<link rel="alternate" type="application/rss+xml" title="RSS" href="rss.php" />
</head>
</html>
';
?>