<?php
// File Paths
$siteJS = $rootContext . $site . '/docroot/js/';
$productionJS = $siteJS . 'production/';

// Site JS Files
$global = array('lib/jquery1.4.4.min.js', 'modules/mainNav.js', 'modules/jquery.sgModal.js', 'modules/facebookFeed.js','modules/resultsJSON.js','global.js');
$home = array('modules/fadeGallery.js', 'home.js');
$springthaw = array('modules/tabs.js', 'modules/ajaxTransition.js', 'modules/fadeGallery.js', 'modules/hashWatcher.js', 'springthaw.js');
$bikefest = array('modules/tabs.js', 'modules/ajaxTransition.js', 'modules/fadeGallery.js', 'modules/hashWatcher.js', 'bikefest.js');
$stxc = array('modules/tabs.js', 'modules/ajaxTransition.js', 'modules/fadeGallery.js', 'modules/hashWatcher.js', 'stxc.js');
$stagecoach = array('modules/tabs.js', 'modules/ajaxTransition.js', 'modules/fadeGallery.js', 'modules/hashWatcher.js', 'stagecoach.js');

// Production JS Files
$prodFiles = array('global.js', 'home.js', 'springthaw.js', 'shootout.js', 'stxc.js', 'stagecoach.js');
$files = array($global, $home, $springthaw, $bikefest, $stxc, $stagecoach);

?>