<?php
/*
Simple:Press
Admin Profiles
$LastChangedDate: 2011-07-17 14:08:22 -0700 (Sun, 17 Jul 2011) $
$Rev: 6704 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

# Check Whether User Can Manage Profiles
if (!sp_current_user_can('SPF Manage Profiles')) {
	if (!is_user_logged_in()) {
		spa_etext('Access denied - are you logged in?');
	} else {
		spa_etext('Access denied - you do not have permission');
	}
	die();
}

global $SPSTATUS;

include_once(SF_PLUGIN_DIR.'/admin/panel-profiles/spa-profiles-display.php');
include_once(SF_PLUGIN_DIR.'/admin/panel-profiles/support/spa-profiles-prepare.php');
include_once(SF_PLUGIN_DIR.'/admin/library/spa-tab-support.php');

if($SPSTATUS != 'ok') {
	include_once(SPLOADINSTALL);
	die();
}

global $adminhelpfile;
$adminhelpfile = 'admin-profiles';
# --------------------------------------------------------------------

if (isset($_GET['tab']) ? $tab=$_GET['tab'] : $tab='options');
spa_panel_header();
spa_render_profiles_panel($tab);
spa_panel_footer();

?>