<?php
/*
Simple:Press Users Admin
Ahah form loader - Users
$LastChangedDate: 2011-07-17 14:08:22 -0700 (Sun, 17 Jul 2011) $
$Rev: 6704 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

spa_admin_ahah_support();

global $SPSTATUS;
if($SPSTATUS != 'ok') {
	echo $SPSTATUS;
	die();
}

include_once(SF_PLUGIN_DIR.'/admin/panel-users/spa-users-display.php');
include_once(SF_PLUGIN_DIR.'/admin/panel-users/support/spa-users-prepare.php');
include_once(SF_PLUGIN_DIR.'/admin/panel-users/support/spa-users-save.php');
include_once(SF_PLUGIN_DIR.'/admin/library/spa-tab-support.php');

global $adminhelpfile;
$adminhelpfile = 'admin-users';
# --------------------------------------------------------------------

# ----------------------------------
# Check Whether User Can Manage Users
if (!sp_current_user_can('SPF Manage Users')) {
	if (!is_user_logged_in()) {
		spa_etext('Access denied - are you logged in?');
	} else {
		spa_etext('Access denied - you do not have permission');
	}
	die();
}

if (isset($_GET['loadform'])) {
	spa_render_users_container($_GET['loadform']);
	die();
}

if (isset($_GET['saveform'])) {
}

die();

?>