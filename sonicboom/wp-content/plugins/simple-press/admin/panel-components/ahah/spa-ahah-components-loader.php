<?php
/*
Simple:Press Admin
Ahah form loader - Components
$LastChangedDate: 2012-04-11 12:09:25 -0700 (Wed, 11 Apr 2012) $
$Rev: 8317 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

spa_admin_ahah_support();

global $SPSTATUS;
if ($SPSTATUS != 'ok') {
	echo $SPSTATUS;
	die();
}

include_once(SF_PLUGIN_DIR.'/admin/panel-components/spa-components-display.php');
include_once(SF_PLUGIN_DIR.'/admin/panel-components/support/spa-components-prepare.php');
include_once(SF_PLUGIN_DIR.'/admin/panel-components/support/spa-components-save.php');
include_once(SF_PLUGIN_DIR.'/admin/library/spa-tab-support.php');

global $adminhelpfile;
$adminhelpfile = 'admin-components';
# --------------------------------------------------------------------

# ----------------------------------
# Check Whether User Can Manage Options
if (!sp_current_user_can('SPF Manage Components')) {
	if (!is_user_logged_in()) {
		spa_etext('Access denied - are you logged in?');
	} else {
		spa_etext('Access denied - you do not have permission');
	}
	die();
}

if (isset($_GET['loadform'])) {
	spa_render_components_container($_GET['loadform']);
	die();
}

if (isset($_GET['saveform'])) {
	switch($_GET['saveform']) {
		case 'smileys':
			echo spa_save_smileys_data();
			break;

		case 'login':
			echo spa_save_login_data();
			break;

		case 'seo':
			echo spa_save_seo_data();
			break;

		case 'forumranks':
			echo spa_save_forumranks_data();
			break;

		case 'specialranks':
			switch ($_GET['action']) {
				case 'newrank':
					echo spa_add_specialrank();
					break;
				case 'updaterank':
					echo spa_update_specialrank(sp_esc_int($_GET['id']));
					break;
				case 'addmember':
					echo spa_add_special_rank_member(sp_esc_int($_GET['id']));
					break;
				case 'delmember':
					echo spa_del_special_rank_member(sp_esc_int($_GET['id']));
					break;
			}
			break;

		case 'messages':
			echo spa_save_messages_data();
			break;

		case 'icons':
			echo spa_save_icons_data();
			break;

		case 'policies':
			echo spa_save_policies_data();
			break;
	}
	die();
}

die();

?>