<?php
/*
Simple:Press
Admin Panels
$LastChangedDate: 2012-06-02 08:38:13 -0700 (Sat, 02 Jun 2012) $
$Rev: 8637 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

# ==========================================================================================
#
# 	FORUM ADMIN
#	This file loads at Forum Admin
#
# ==========================================================================================

# ------------------------------------------------------------------
# spa_load_admin_css()
# Loads up the forum admin CSS
# ------------------------------------------------------------------
function spa_load_admin_css() {
	$spAdminStyleUrl = SFADMINCSS.'spa-admin.css';
	wp_register_style('spAdminStyle', $spAdminStyleUrl);
	wp_enqueue_style( 'spAdminStyle');
}

# ------------------------------------------------------------------
# spa_load_admin_scripts()
# Loads up the forum admin Javascript
# ------------------------------------------------------------------
function spa_load_admin_scripts() {
	global $APAGE, $SPSTATUS, $current_screen;

	$APAGE = spa_extract_admin_page();

	if ($SPSTATUS == 'ok') {
		if ($APAGE != 'notice') {
            do_action('sph_scripts_admin_start');

            $script = (defined('SP_SCRIPTS_DEBUG') && SP_SCRIPTS_DEBUG) ? SFAJSCRIPT.'ajaxupload/ajaxupload-dev.js' : SFAJSCRIPT.'ajaxupload/ajaxupload.js';
			wp_enqueue_script('sfajaxupload', $script, array('jquery'), false, false);

            # This script cannot be compressed
			wp_enqueue_script('jquery-tablednd', SFAJSCRIPT.'jquery.tablednd.js', array('jquery', 'jquery-ui-core', 'jquery-ui-widget'), false, false);

			# Load checkbox/radio button code if turned on
			if (SF_USE_PRETTY_CBOX) {
                $script = (defined('SP_SCRIPTS_DEBUG') && SP_SCRIPTS_DEBUG) ? SFCJSCRIPT.'checkboxes/prettyCheckboxes-dev.js' : SFCJSCRIPT.'checkboxes/prettyCheckboxes.js';
				wp_enqueue_script('jquery.checkboxes', $script, array('jquery'), false, false);
			}
		}

        $script = (defined('SP_SCRIPTS_DEBUG') && SP_SCRIPTS_DEBUG) ? SFAJSCRIPT.'spa-admin-dev.js' : SFAJSCRIPT.'spa-admin.js';
		wp_enqueue_script('sfadmin', $script, array('jquery', 'jquery-form', 'jquery-ui-accordion', 'jquery-ui-sortable'), false, false);

        $script = (defined('SP_SCRIPTS_DEBUG') && SP_SCRIPTS_DEBUG) ? SFCJSCRIPT.'sp-common-dev.js' : SFCJSCRIPT.'sp-common.js';
		wp_enqueue_script('spcommon', $script, array('jquery', 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-dialog'), false, false);

    	wp_enqueue_script('jquery-touch-punch', false, array('jquery', 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-mouse'), false, $footer);
        
        do_action('sph_scripts_admin_end');

		# Add help text to WP admin bar help 'slider'
        get_current_screen()->add_help_tab(array('id' => 'overview', 'title' => 'overview', 'callback' => 'spa_add_slider_help'));
	} else {
		# Install and Upgrade - use our own jQuery and ui.core
        $script = (defined('SP_SCRIPTS_DEBUG') && SP_SCRIPTS_DEBUG) ? SF_PLUGIN_URL.'/sp-startup/install/resources/jscript/sp-install-dev.js' : SF_PLUGIN_URL.'/sp-startup/install/resources/jscript/sp-install.js';
		wp_enqueue_script('sfjs', $script, array('jquery', 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-progressbar'), false, false);
	}
}

# ------------------------------------------------------------------
# spa_admin_header()
# Loads the forum page header
# ------------------------------------------------------------------
function spa_admin_header() {
	global $ISFORUMADMIN, $SPSTATUS, $spThisUser;

	if($SPSTATUS == 'ok') {
		do_action('sph_admin_head_start');

		if ($ISFORUMADMIN == false) return;

		# Compile admin color array
		$sfacolors = $spThisUser->colors;
		if (!isset($sfacolors)) $sfacolors = sp_get_option('sfacolours');

		if (get_bloginfo('text_direction') == 'rtl') {
		?>
		<link rel="stylesheet" type="text/css" href="<?php echo SFADMINCSS;?>spa-admin-rtl.css" />
		<?php } ?>
		<style type="text/css">
		.sfform-upload-button, .sfform-submit-button, .sfform-panel-button { background-color: <?php echo '#'.$sfacolors['submitbg']; ?> !important; color: <?php echo '#'.$sfacolors['submitbgt']; ?> !important; }
		.sfsidebutton, .sfsidebutton a, .ui-widget-header { background-color: <?php echo '#'.$sfacolors['bbarbg']; ?>; color: <?php echo '#'.$sfacolors['bbarbgt']; ?>; }
		.sfsideitem:hover { background-color: <?php echo '#'.$sfacolors['bbarbg']; ?>; color: #000000; }
		.sfform-panel-head { background: <?php echo '#'.$sfacolors['panelhead']; ?>; color: <?php echo '#'.$sfacolors['panelheadt']; ?>; }
		.sfform-panel { background: <?php echo '#'.$sfacolors['formbg']; ?>; color: <?php echo '#'.$sfacolors['formbgt']; ?>; }
		.form-table td.longmessage { color: <?php echo '#'.$sfacolors['formbgt']; ?>; }
		.sffieldset legend { background-color: <?php echo '#'.$sfacolors['bbarbg']; ?>; color: <?php echo '#'.$sfacolors['bbarbgt']; ?>; }
		.sfsubfieldset legend { background-color: <?php echo '#'.$sfacolors['panelsubbg']; ?>; color: <?php echo '#'.$sfacolors['formbgt']; ?>; }
		.subhead { color: <?php echo '#'.$sfacolors['formbgt']; ?> !important; }
		.form-table td { color: <?php echo '#'.$sfacolors['panelbgt']; ?>; }
		.sffieldset { color: <?php echo '#'.$sfacolors['panelbgt']; ?>; background-color: <?php echo '#'.$sfacolors['panelbg']; ?>; }
		.sublabel { color: <?php echo '#'.$sfacolors['panelbgt']; ?> !important; }
		.sfsubfieldset { color: <?php echo '#'.$sfacolors['panelsubbgt']; ?>; background-color: <?php echo '#'.$sfacolors['panelsubbg']; ?>; }
		.sfhelptext fieldset { background-color: <?php echo '#'.$sfacolors['panelsubbg']; ?>; color: <?php echo '#'.$sfacolors['panelsubbgt']; ?>; }
		.form-table th { background-color: <?php echo '#'.$sfacolors['formtabhead']; ?>; color: <?php echo '#'.$sfacolors['formtabheadt']; ?>; }
		.sfmaintable th, .sfsubtable th, .sfsubsubtable th { background: <?php echo '#'.$sfacolors['tabhead']; ?>; color: <?php echo '#'.$sfacolors['tabheadt']; ?>; }
		.sfmaintable { background-color: <?php echo '#'.$sfacolors['tabrowmain']; ?>; }
		.sfmaintable td { color: <?php echo '#'.$sfacolors['tabrowmaint']; ?>; }
		.sfsubtable, .sfsubsubtable,.sfhelptext .sfhelptag { background-color: <?php echo '#'.$sfacolors['tabrowsub']; ?>; }
		.sfsubtable td, .sfsubsubtable td, .sfhelptext .sfhelptag p { color: <?php echo '#'.$sfacolors['tabrowsubt']; ?>; }
		</style>

		<?php
		do_action('sph_admin_head_end');
	}
}


# ------------------------------------------------------------------
# spa_panel_header()
#
# Common admin header. Sets up main toolbar and content area.
#	$title:			admin panel title
#	$icon:			admin panel icon
# ------------------------------------------------------------------
function spa_panel_header() {
	global $APAGE, $spNews;

	echo '<!-- Common wrapper and header -->';
	echo '<div class="wrap nosubsub">';
	echo '<div class="mainicon icon-forums"></div>';
	echo '<h2>'.spa_text('Simple:Press Administration').'</h2>';
	echo '<div class="clearboth"></div>';

	echo '<table class="sfamenutable" width="100%"><tr><td width="60%">';

	echo '<div id="sfupdate"></div>';
	echo '</td>';
    $site = SFHOMEURL.'index.php?sp_ahah=acknowledge&amp;sfnonce='.wp_create_nonce('forum-ahah');
    $title = spa_text('About Simple:Press');
	echo '<td align="right"><a class="button button-highlight" target="_blank" href="http://codex.simple-press.com">'.spa_text('Simple:Press Codex').'</a>&nbsp;&nbsp;';
	echo '<a class="button button-highlight" href="javascript:void(null)" onclick="spjDialogAjax(this, \''.$site.'\', \''.$title.'\', 600, 0, \'center\');">'.$title.'</a>&nbsp;&nbsp;';
	echo '<a class="button button-highlight" href="'.esc_url(sp_get_option('sfpermalink')).'">'.spa_text('Go To Forum').'</a></td>';

	echo '</tr></table></div><div class="clearboth"></div>';

	# define container for th dialog box popup
	echo '<div id="dialogcontainer" style="display:none;"></div>';

	# display any warning messages
	echo spa_check_warnings();

	# News update widget
	$spNews = spa_check_for_news();
	if(!empty($spNews)) {
		add_action('in_admin_footer', 'spa_remove_news');
		echo '<div id="spa_dashboard_news" class="postbox" style="margin: 6px 20px 0 15px; padding: 20px;">';
		spa_dashboard_news();
		echo '</div>';
	}

	do_action('sph_admin_panel_header');
}

# ------------------------------------------------------------------
# spa_admin_footer()
# Loads the forum page footer
# ------------------------------------------------------------------
function spa_admin_footer() {
	global $ISFORUMADMIN;
	if ($ISFORUMADMIN) echo SFPLUGHOME.' | '.spa_text('Version').' '.SPVERSION.'<br />';
	do_action('sph_admin_footer');
}

# ------------------------------------------------------------------
# spa_admin_init_scripts()
# Initialises admin script routines
# ------------------------------------------------------------------
function spa_admin_init_scripts() {
	global $SPSTATUS, $APAGE, $sfactivepanels;

	if ($SPSTATUS == 'ok') {
		if ($APAGE != 'notice') {
		?>
			<script type="text/javascript">
				var jspf = jQuery.noConflict();
				jspf(document).ready(function() {
					<?php
					# Checkboxes/radio buttons
					if (SF_USE_PRETTY_CBOX && $APAGE != 'plugins') { ?>
						jspf("input[type=checkbox],input[type=radio]").prettyCheckboxes();
					<?php } ?>

					jspf("#sfadminmenu").accordion({
						autoHeight: false,
						collapsible: true,
						active: <?php echo $sfactivepanels[$APAGE]; ?>
					});

					jspf(function(jspf){vtip();})
	    		});
	<?php } ?>
	</script>
	<?php
	}
}

# ------------------------------------------------------------------
# spa_panel_footer()
#
# Common admin footer. Closes down content area and performs update
# check if option is turned on
# ------------------------------------------------------------------
function spa_panel_footer() {
}

function spa_render_sidemenu() {
	global $sfadminpanels, $spThisUser;

	$target = 'sfmaincontainer';
	$image = SFADMINIMAGES;
	$upgrade = admin_url('admin.php?page='.SPINSTALLPATH);

	if (isset($_GET['tab']) ? $formid = $_GET['tab'] : $formid = '');
	echo '<div id="sfsidepanel">'."\n";
		echo '<div id="sfadminmenu">'."\n";
            foreach ($sfadminpanels as $index => $panel) {
                if (sp_current_user_can($panel[1]) || ($panel[0] == 'Admins' && ($spThisUser->admin || $spThisUser->moderator))) {
    				echo '<div class="sfsidebutton" id="sfacc'.str_replace(' ', '', $panel[0]).'">'."\n";
   					echo '<img src="'.esc_attr($panel[4]).'" alt="" class="vtip" title="'.esc_attr($panel[3]).'" />'."\n";
                    echo '<a href="#">'.$panel[0].'</a>'."\n";
    				echo '</div>'."\n";
    				echo '<div class="sfmenublock">'."\n";

                	foreach ($panel[6] as $label => $data) {
                		foreach ($data as $formid => $reload) {
                            # ignore user plugin data for menu
                            if ($formid == 'admin' || $formid == 'save' || $formid == 'form') continue;
                			echo '<div class="sfsideitem">'."\n";
                			$id = '';
							if ($reload != '') {
								$id = ' id="'.esc_attr($reload).'"';
							} else {
								$id = ' id="acc'.esc_attr($formid).'"';
							}
                            $base = esc_js($panel[5]);
                            $admin = (!empty($data['admin']) ? $data['admin'] : '');
                            $save  = (!empty($data['save']) ? $data['save'] : '');
                            $form  = (!empty($data['form']) ? $data['form'] : '');
                			?>
                			<a<?php echo $id; ?> href="#" onclick="spjLoadForm('<?php echo $formid; ?>', '<?php echo $base; ?>', '<?php echo $target; ?>', '<?php echo $image; ?>', '', 'sfopen', '<?php echo $upgrade; ?>', '<?php echo esc_js($admin); ?>', '<?php echo esc_js($save); ?>', '<?php echo $form; ?>', '<?php echo $reload; ?>');"><?php echo $label; ?></a><?php echo "\n"; ?>
                			<?php
                		}
                		echo '</div>'."\n";
                	}
                    echo '</div>'."\n";
                }
            }
        echo '</div>'."\n";

	# Help link
	$site = SFHOMEURL.'index.php?sp_ahah=troubleshooting&amp;sfnonce='.wp_create_nonce('forum-ahah');
	echo '<br /><input type="button" class="button-primary" value="'.splice(spa_text('Simple:Press‌ Help and Troubleshooting'),2,0).'" onclick="spjTroubleshooting(\''.$site.'\', \''.$target.'\');" />'."\n";
	echo '</div>'."\n";
}

# ????
function spa_check_warnings() {
	$mess = '';

    # check if sp core, plugins or themes update available
    $update = false;
    $update_msg = '';
	$up = get_site_transient('update_plugins');
    if (!empty($up->response)) {
        foreach ($up->response as $plugin) {
            if ($plugin->slug == 'simple-press' ) {
                $msg = apply_filters('sph_core_update_notice', spa_text('There is a Simple:Press core update available.'));
                if (!empty($msg)) {
                    $update = true;
                    $update_msg.= $msg.'<br />';
                }
                break;
            }
        }
    }

	$up = get_site_transient('sp_update_plugins');
    if (!empty($up)) {
        $msg = apply_filters('sph_plugins_update_notice', spa_text('There is one or more Simple:Press plugin updates available'));
        if (!empty($msg)) {
            $update = true;
            $update_msg.= $msg.'<br />';
        }
    }

	$up = get_site_transient('sp_update_themes');
    if (!empty($up)) {
        $msg = apply_filters('sph_themes_update_notice', spa_text('There is one or more Simple:Press theme updates available'));
        if (!empty($msg)) {
            $update = true;
            $update_msg.= $msg.'<br />';
        }
    }

    if ($update) {
        if (is_main_site()) {
            $mess.= apply_filters('sph_updates_notice', spa_message($update_msg.'<a href="'.self_admin_url('update-core.php').'">'.spa_text('Click here to view any updates.').'</a>'));
        } else {
            $mess.= apply_filters('sph_updates_notice', spa_message(spa_text('There are some Simple:Press updates avaialable. You may want to notify the network site admin.')));
        }
    }
    
	# output warning if no SPF admins are defined
	if (sp_get_admins() == '') $mess.= spa_message(spa_text('Warning - There are no SPF admins defined!  All WP admins now have SP backend access'));

	# Check a theme is selected and available
	$t = sp_get_current_sp_theme();
	if (empty($t)) {
		$mess.= spa_message(spa_text('No theme has been selected and SP will be unable to display correctly. Please select a theme from the Themes panel'));
	} else {
		if (!file_exists(SPTHEMEDIR.$t['style']) || (!empty($t['color']) && !file_exists(SPTHEMEDIR.'overlays/'.$t['color'].'.php'))) {
			$mess.= spa_message(spa_text('Either the theme CSS file and/or color Overlay file from the selected theme is missing'));
		}
	}

	# check for missing default members user group
	$value = sp_get_sfmeta('default usergroup', 'sfmembers');
	$ugid = spdb_table(SFUSERGROUPS, "usergroup_id={$value[0]['meta_value']}", 'usergroup_id');
	if (empty($ugid)) $mess.= spa_message(spa_text('Warning - The default user group for new members is undefined!  Please visit the SP usergroups admin page, map users to usergroups tab and set the default user group'));

	# check for missing default guest user group
	$value = sp_get_sfmeta('default usergroup', 'sfguests');
	$ugid = spdb_table(SFUSERGROUPS, "usergroup_id={$value[0]['meta_value']}", 'usergroup_id');
	if (empty($ugid)) $mess.= spa_message(spa_text('Warning - The default user group for guests is undefined!  Please visit the SP usergroups admin page, map users to usergroups tab and set the default user group'));

	# check for unreachable forums because of permissions
	$done = 0;
	$usergroups = spdb_table(SFUSERGROUPS);
	if ($usergroups) {
		$has_members = false;
		foreach ($usergroups as $usergroup) {
			$members = spdb_table(SFMEMBERSHIPS, "usergroup_id=$usergroup->usergroup_id", 'row', '', '1');
			if ($members || $usergroup->usergroup_id == $value[0]['meta_value']) {
				$has_members = true;
				break;
			}
		}

		if (!$has_members) {
			$mess.= spa_message(spa_text('Warning - There are no usergroups that have members!  All forums may only be visible to SP admins'));
			$done = 1;
		}
	} else {
		$mess.= spa_message(spa_text('Warning - There are no usergroups defined!  All forums may only be visible to SP admins'));
		$done = 1;
	}

	$roles = sp_get_all_roles();
	if (!$roles) {
		$mess.= spa_message(spa_text('Warning - There are no permission sets defined!  All forums may only be visible to SP admins'));
		$done = 1;
	}

	# dont duplicate forum warnings if there are no user groups, no user groups with members or no permission sets
	if ($done) return;

	$forums = spdb_table(SFFORUMS);
	if ($forums) {
		foreach ($forums as $forum) {
			$has_members = false;
			$permissions = sp_get_forum_permissions($forum->forum_id);
			if ($permissions) {
				foreach ($permissions as $permission) {
					$members = spdb_table(SFMEMBERSHIPS, "usergroup_id= $permission->usergroup_id", 'row', '', '1');
					if ($members || $usergroup->usergroup_id == $ugid) {
						$has_members = true;
						break;
					}
				}
			}

			if (!$has_members) $mess.= spa_message(spa_text('Warning - There are no usergroups with members that have permissions on forum').': '.sp_filter_title_display($forum->forum_name).' '.spa_text('This forum may only be visible to SP admins'));
		}
	}

	# check if compatible with wp super cache
	if (function_exists('wp_cache_edit_rejected')) {
		global $spcache_rejected_uri;
		$slug = '/'.sp_get_option('sfslug').'/';
		if (isset($spcache_rejected_uri)) {
			$found = false;
			foreach ($spcache_rejected_uri as $value) {
				if ($value == $slug) {
					$found = true;
					break;
				}
			}

			if (!$found) {
				$string = spa_text('WP Super Cache is not properly configured to work with Simple:Press. Please visit your WP Super Cache settings page and in the accepted filenames & rejected URIs section for the pages not to be cached input field, add the following string');
				$string.= ':<br /><br />'.$slug.'<br /><br />';
				$string.= spa_text('Then, please clear your WP Super Cache cache to remove any cached Simple:Press pages');
				$mess.= spa_message($string);
			}
		}
	}
	if ($mess != '') return $mess;
}

# ------------------------------------------------------------------
# spa_message()
#
# Common success/failure post-save messaging
# ------------------------------------------------------------------
function spa_message($message) {
	return '<div class="message"><p><strong>'.$message.'</strong></p></div>';
}

# ------------------------------------------------------------------
# spa_extract_admin_page()
# Determines the forum admin panel being requested
# ------------------------------------------------------------------
function spa_extract_admin_page() {
	global $APAGE;

	if(strpos($_SERVER['QUERY_STRING'], 'spa-plugins.php') != 0) {
		$APAGE = '/spa-plugins.php';
	} else {
		$APAGE = strrchr($_SERVER['QUERY_STRING'] , '/');
	}
	if (strpos($APAGE, '/spa-', 0) === false) return 'none';
	$APAGE = substr($APAGE, 5, 26);
	$APAGE = reset(explode('.php', $APAGE));
	return $APAGE;
}

# ------------------------------------------------------------------
# spa_add_slider_help()
# Adds slider help to admin panels
# ------------------------------------------------------------------
function spa_add_slider_help() {
	global $ISFORUMADMIN;

	if (!$ISFORUMADMIN) return;
	$out = '';
	$out.= '<h5>'.spa_text('Simple:Press - Help Options').'</h5>';
	$out.= '<div class="metabox-prefs">';
	$out.= '<p>'.spa_text('For contextual help with Simple:Press, click on the Help buttons/links located on each administration panel').'<br />';
	$out.= spa_text('For Simple:Press information, troubleshooting, how-to, administration, our API and more, please visit our').' <a target="_blank" href="http://codex.simple-press.com">'.spa_text('Simple:Press Codex').'</a><br />';
	$out.= spa_text('If you cannot find your answer and need extra help, please visit our').' <a href="'.SFHOMESITE.'/support-forum">'.spa_text('Support Forum').'</a></p>';
	$out.= '</div>';
	echo $out;
}

?>