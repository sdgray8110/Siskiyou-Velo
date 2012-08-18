<?php
$rootContext = '/home/gray8110/public_html/';
$site = $_GET['site'];

include('functions/jsMin.php');
include('jsConcatenate/' . $site . '.php');

$count = count($prodFiles);

for ($i = 0; $i < $count; $i++) {
    $prodFile = $prodFiles[$i];
    $productionFile = $productionJS . $prodFile;
    unlink($productionFile);

    $newFile = fopen($productionFile, 'a');

    $fileArr = $files[$i];

    foreach ($fileArr as $file) {
        // get contents of a file into a string
        $file = $siteJS . $file;
        $readFile = fopen($file, "r");
        $contents = minify(fread($readFile, filesize($file)));
        fwrite($newFile, $contents);
        fclose($readFile);
    }

    fclose($newFile);
}

?>