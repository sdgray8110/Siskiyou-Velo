<?php
/*
Simple:Press
profiles Specials
$LastChangedDate: 2011-07-17 14:08:22 -0700 (Sun, 17 Jul 2011) $
$Rev: 6704 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

spa_admin_ahah_support();

# Check Whether User Can Manage Profiles
if (!sp_current_user_can('SPF Manage Profiles')) {
	if (!is_user_logged_in()) {
		spa_etext('Access denied - are you logged in?');
	} else {
		spa_etext('Access denied - you do not have permission');
	}
	die();
}

global $SPPATHS;

$action = $_GET['action'];

if ($action == 'delavatar') {
	$file = $_GET['file'];
	$path = SF_STORE_DIR.'/'.$SPPATHS['avatar-pool'].'/'.$file;
	@unlink($path);
	echo '1';
}

if ($action == 'delete-tab') {
	$slug = sp_esc_str($_GET['slug']);
	sp_profile_delete_tab_by_slug($slug);
}

die();

?>