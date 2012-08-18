<?php
/*
Simple:Press
Special Admin Notice
$LastChangedDate: 2011-07-17 14:08:22 -0700 (Sun, 17 Jul 2011) $
$Rev: 6704 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

# ==========================================================================================
#
# 	FORUM ADMIN
#	This file loads at Forum Admin if needed
#
# ==========================================================================================

# Check Whether User is WP Admin
if (!current_user_can('administrator')) {
	if (!is_user_logged_in()) {
		spa_etext('Access denied - are you logged in?');
	} else {
		spa_etext('Access denied - you do not have permission');
	}
	die();
}

global $SPSTATUS;

include_once(SF_PLUGIN_DIR.'/admin/library/spa-tab-support.php');
include_once(SF_PLUGIN_DIR.'/admin/panel-admins/support/spa-admins-prepare.php');

if ($SPSTATUS != 'ok') {
	include_once(SPLOADINSTALL);
	die();
}

spa_panel_header();
spa_paint_options_init();
spa_paint_open_tab(spa_text('Special WP Admin Notice').' - '.spa_text('Special WP Admin Notice'));
	spa_paint_open_panel();
		spa_paint_open_fieldset(spa_text('Special WP Admin Notice'));
			echo '<tr><td colspan="3"><br /><p>';
			spa_etext('Please note that while you are a WP admin, you are not currently an SP admin. By default, WP admins are not SP admins');
            echo '<br />';
			spa_etext('Contact one of the SP Admins listed below to see if they want to grant you SP admin access on the SP manage admins panel');
			echo '</p>';

            # list all current SPF Admins
        	$adminrecords = spa_get_admins_caps_data();
           	if ($adminrecords) {
                echo '<p>';
                echo '<ul>';
				foreach ($adminrecords as $admin) {
				    echo '<li>'.sp_filter_name_display($admin['display_name']).'</li>';
                }
                echo '</ul>';
    			echo '</p><br />';
            }
   			echo '</td></tr>';
		spa_paint_close_fieldset();
	spa_paint_close_panel();
spa_paint_close_tab();
spa_panel_footer();

?>