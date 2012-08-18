<?php
// File Paths
$siteJS = $rootContext . $site . '/docroot/js/';
$productionJS = $siteJS . 'production/';

// Site JS Files
$global = array('lib/jquery1.4.4.min.js', 'modules/inlineLabel.js', 'global.js');
$home = array('modules/carousel.js','home.js');

// Production JS Files
$prodFiles = array('global.js', 'home.js');
$files = array($global, $home);

?>