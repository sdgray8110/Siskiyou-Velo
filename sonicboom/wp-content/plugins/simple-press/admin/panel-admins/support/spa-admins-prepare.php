<?php
/*
Simple:Press
Admin Admins Update Global Options Support Functions
$LastChangedDate: 2012-05-20 13:29:45 -0700 (Sun, 20 May 2012) $
$Rev: 8556 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

function spa_get_admins_your_options_data() {
	global $spThisUser;

	$sfadminoptions = sp_get_member_item($spThisUser->ID, 'admin_options');
	if (!isset($sfadminoptions['colors'])) $sfadminoptions['colors'] = sp_get_option('sfacolours');

	return $sfadminoptions;
}

function spa_get_admins_global_options_data() {
	$sfadminsettings = sp_get_option('sfadminsettings');

	return $sfadminsettings;
}

function spa_get_admins_caps_data() {
	return sp_get_admins();
}

?>