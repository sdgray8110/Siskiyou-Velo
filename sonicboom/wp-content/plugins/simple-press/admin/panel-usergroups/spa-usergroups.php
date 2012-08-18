<?php
/*
Simple:Press
Admin User Groups
$LastChangedDate: 2011-07-17 14:08:22 -0700 (Sun, 17 Jul 2011) $
$Rev: 6704 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

# Check Whether User Can Manage User Groups
if (!sp_current_user_can('SPF Manage User Groups')) {
	if (!is_user_logged_in()) {
		spa_etext('Access denied - are you logged in?');
	} else {
		spa_etext('Access denied - you do not have permission');
	}
	die();
}

global $SPSTATUS;

include_once(SF_PLUGIN_DIR.'/admin/panel-usergroups/spa-usergroups-display.php');
include_once(SF_PLUGIN_DIR.'/admin/panel-usergroups/support/spa-usergroups-prepare.php');
include_once(SF_PLUGIN_DIR.'/admin/library/spa-tab-support.php');

if ($SPSTATUS != 'ok') {
	include_once(SPLOADINSTALL);
	die();
}

global $adminhelpfile;
$adminhelpfile = 'admin-usergroups';
# --------------------------------------------------------------------

if (isset($_GET['tab']) ? $tab=$_GET['tab'] : $tab='usergroups');
spa_panel_header();
spa_render_usergroups_panel($tab);
spa_panel_footer();

?>