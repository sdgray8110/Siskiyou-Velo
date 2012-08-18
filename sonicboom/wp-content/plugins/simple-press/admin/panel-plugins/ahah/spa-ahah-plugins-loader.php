<?php
/*
Simple:Press Admin
Ahah form loader - plugins
$LastChangedDate: 2011-07-17 14:08:22 -0700 (Sun, 17 Jul 2011) $
$Rev: 6704 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

spa_admin_ahah_support();

global $SPSTATUS;
if ($SPSTATUS != 'ok') {
	echo $SPSTATUS;
	die();
}

include_once(SF_PLUGIN_DIR.'/admin/panel-plugins/spa-plugins-display.php');
include_once(SF_PLUGIN_DIR.'/admin/panel-plugins/support/spa-plugins-prepare.php');
include_once(SF_PLUGIN_DIR.'/admin/panel-plugins/support/spa-plugins-save.php');
include_once(SF_PLUGIN_DIR.'/admin/library/spa-tab-support.php');

global $adminhelpfile;
$adminhelpfile = 'admin-plugins';
# --------------------------------------------------------------------

# ----------------------------------
# Check Whether User Can Manage Options
if (!sp_current_user_can('SPF Manage Plugins')) {
	if (!is_user_logged_in()) {
		spa_etext('Access denied - are you logged in?');
	} else {
		spa_etext('Access denied - you do not have permission');
	}
	die();
}

if (isset($_GET['loadform'])) {
	spa_render_plugins_container($_GET['loadform']);
	die();
}

if (isset($_GET['saveform'])) {
	if ($_GET['saveform'] == 'list') {
		echo spa_save_plugin_list_actions();
		die();
	}

	if ($_GET['saveform'] == 'activation') {
		echo spa_save_plugin_activation();
		die();
	}

	if ($_GET['saveform'] == 'plugin') {
		echo spa_save_plugin_userdata(sp_esc_str($_GET['func']));
		die();
	}
}

die();

?>