<?php
/*
Simple:Press
Admin integration Update Global Options Support Functions
$LastChangedDate: 2012-05-05 11:57:55 -0700 (Sat, 05 May 2012) $
$Rev: 8491 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

function spa_get_integration_page_data() {
	$sfoptions = array();
	$sfoptions['sfslug'] = sp_get_option('sfslug');
	$sfoptions['sfpage'] = sp_get_option('sfpage');
	$sfoptions['sfpermalink'] = sp_get_option('sfpermalink');

	$sfoptions['sfuseob'] = sp_get_option('sfuseob');
	$sfoptions['sfwplistpages'] = sp_get_option('sfwplistpages');
	$sfoptions['sfscriptfoot'] = sp_get_option('sfscriptfoot');

	$sfoptions['sfinloop'] = sp_get_option('sfinloop');
	$sfoptions['sfmultiplecontent'] = sp_get_option('sfmultiplecontent');
	$sfoptions['sfwpheadbypass'] = sp_get_option('sfwpheadbypass');

	if (!empty($sfoptions['sfslug'])) {
		$pageslug = explode("/", $sfoptions['sfslug']);
		$thisslug = $pageslug[count($pageslug)-1];
		$pageid = spdb_table(SFWPPOSTS, "post_name='$thisslug'", 'ID');
		if (!$pageid) {
			$sfoptions['sfpage'] = 0;
			$sfoptions['sfslug'] = '';
		}
	} else {
		$sfoptions['sfpage'] = 0;
		$sfoptions['sfslug'] = '';
	}


	return $sfoptions;
}

function spa_get_storage_data() {
	$sfstorage = array();
	$sfstorage = sp_get_option('sfconfig');
	return $sfstorage;
}

?>