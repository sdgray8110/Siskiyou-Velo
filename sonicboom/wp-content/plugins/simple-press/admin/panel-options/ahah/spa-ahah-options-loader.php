<?php
/*
Simple:Press Admin
Ahah form loader - Option
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

include_once(SF_PLUGIN_DIR.'/admin/panel-options/spa-options-display.php');
include_once(SF_PLUGIN_DIR.'/admin/panel-options/support/spa-options-prepare.php');
include_once(SF_PLUGIN_DIR.'/admin/panel-options/support/spa-options-save.php');
include_once(SF_PLUGIN_DIR.'/admin/library/spa-tab-support.php');

global $adminhelpfile;
$adminhelpfile = 'admin-options';
# --------------------------------------------------------------------

# ----------------------------------
# Check Whether User Can Manage Options
if (!sp_current_user_can('SPF Manage Options')) {
	if (!is_user_logged_in()) {
		spa_etext('Access denied - are you logged in?');
	} else {
		spa_etext('Access denied - you do not have permission');
	}
	die();
}

if (isset($_GET['loadform'])) {
	spa_render_options_container($_GET['loadform']);
	die();
}

if (isset($_GET['saveform'])) {
	switch ($_GET['saveform']) {
		case 'global':
		echo spa_save_global_data();
		break;

		case 'display':
		echo spa_save_display_data();
		break;

		case 'content':
		echo spa_save_content_data();
		break;

		case 'members':
		echo spa_save_members_data();
		break;

		case 'email':
		echo spa_save_email_data();
		break;
	}
	die();
}

die();

?>