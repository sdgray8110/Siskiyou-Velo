<?php
/*
Simple:Press
Admin Forums
$LastChangedDate: 2011-07-17 14:08:22 -0700 (Sun, 17 Jul 2011) $
$Rev: 6704 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

# Check Whether User Can Manage Forums
global $SPSTATUS;
if (!sp_current_user_can('SPF Manage Forums')) {
	if (!is_user_logged_in()) {
		spa_etext('Access denied - are you logged in?');
	} else {
		spa_etext('Access denied - you do not have permission');
	}
	die();
}

include_once(SF_PLUGIN_DIR.'/admin/panel-forums/spa-forums-display.php');
include_once(SF_PLUGIN_DIR.'/admin/panel-forums/support/spa-forums-prepare.php');
include_once(SF_PLUGIN_DIR.'/admin/library/spa-tab-support.php');

if ($SPSTATUS != 'ok') {
    include_once(SPLOADINSTALL);
    die();
}

global $adminhelpfile;
$adminhelpfile = 'admin-forums';
# --------------------------------------------------------------------

if (isset($_GET['tab']) ? $tab=$_GET['tab'] : $tab='forums');
spa_panel_header();
spa_render_forums_panel($tab);
spa_panel_footer();
?>