<?php
/*
Simple:Press
Upgrade Path Routines - Version 5.0
$LastChangedDate: 2012-06-08 08:34:19 -0700 (Fri, 08 Jun 2012) $
$Rev: 8676 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

global $current_user;

$InstallID = get_option('sfInstallID'); # use wp option table
wp_set_current_user($InstallID);

# use WP check here since SPF stuff may not be set up
if (!current_user_can('activate_plugins')) {
	spa_etext('Access denied - Only Users who can Activate Plugins may perform this upgrade');
	die();
}

require_once (dirname(__file__).'/sp-upgrade-support.php');
require_once (SF_PLUGIN_DIR.'/admin/library/spa-support.php');

if (!isset($_GET['start'])) die();

$checkval = $_GET['start'];
$build = intval($checkval);

# Start of Upgrade Routines - 5.0.0 ============================================================

# DATABASE SCHEMA CHANGES
$section = 6624;
if ($build < $section) {

	# Tables to be removed

	spdb_query("DROP TABLE IF EXISTS ".SF_PREFIX."sfsettings");
	spdb_query("DROP TABLE IF EXISTS ".SF_PREFIX."sfnotice");

	# New Tables

	# create error log table
	$sql = "
	CREATE TABLE IF NOT EXISTS ".SFERRORLOG." (
		id int(20) NOT NULL auto_increment,
		error_date datetime NOT NULL,
		error_type varchar(10) NOT NULL,
		error_text text,
		PRIMARY KEY (id)
	) ENGINE=MyISAM ".spdb_charset().";";
	spdb_query($sql);

	# create new table for auths
	$sql = "
		CREATE TABLE IF NOT EXISTS ".SFAUTHS." (
			auth_id bigint(20) NOT NULL auto_increment,
			auth_name varchar(50) NOT NULL,
			auth_desc text,
			active smallint(1) NOT NULL default '0',
			ignored smallint(1) NOT NULL default '0',
			enabling smallint(1) NOT NULL default '0',
			PRIMARY KEY	 (auth_id)
		) ENGINE=MyISAM ".spdb_charset().";";
	spdb_query($sql);

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 6637;
if ($build < $section) {

	# Other DB Schema changes

	# add new column for user memberships in sfmember
	spdb_query("ALTER TABLE ".SFMEMBERS." ADD (memberships longtext)");

	# add new column to sftrack for notifications
	spdb_query("ALTER TABLE ".SFTRACK." ADD (notification varchar(1024) default NULL)");

	# change post_content column to long text type
	spdb_query("ALTER TABLE ".SFPOSTS." CHANGE post_content post_content LONGTEXT;");

	# increase icons to length 50
	spdb_query("ALTER TABLE ".SFGROUPS." MODIFY group_icon varchar(50) default NULL");
	spdb_query("ALTER TABLE ".SFFORUMS." MODIFY forum_icon varchar(50) default NULL");

	# new option for user selectable usergroups
	spdb_query("ALTER TABLE ".SFUSERGROUPS." ADD (usergroup_join tinyint(4) unsigned NOT NULL default '0')");

	# add sfmeta autoload
	spdb_query("ALTER TABLE ".SFMETA." ADD (autoload tinyint(4) unsigned NOT NULL default '0')");

	# Remove pm flag from members
	spdb_query("ALTER TABLE ".SFMEMBERS." DROP pm");

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 6650;
if ($build < $section) {

	# Indexing and Column Def Changes

	# Primary Key ID consistency changes - Update fields to be bigint(20)
	spdb_query("ALTER TABLE ".SFERRORLOG." MODIFY id bigint(20) auto_increment;");
	spdb_query("ALTER TABLE ".SFLOG." MODIFY id bigint(20) auto_increment;");

	spdb_query("ALTER TABLE ".SFDEFPERMISSIONS." MODIFY permission_id bigint(20) auto_increment;");
	spdb_query("ALTER TABLE ".SFPERMISSIONS." MODIFY permission_id bigint(20) auto_increment;");
	spdb_query("ALTER TABLE ".SFMEMBERSHIPS." MODIFY membership_id bigint(20) auto_increment;");
	spdb_query("ALTER TABLE ".SFROLES." MODIFY role_id bigint(20) auto_increment;");
	spdb_query("ALTER TABLE ".SFUSERGROUPS." MODIFY usergroup_id bigint(20) auto_increment;");
	spdb_query("ALTER TABLE ".SFOPTIONS." MODIFY option_id bigint(20) auto_increment;");

	# Foreign Key ID consistency changes - Update fields to be bigint(20)
	spdb_query("ALTER TABLE ".SFDEFPERMISSIONS." MODIFY group_id bigint(20), MODIFY usergroup_id bigint(20), MODIFY permission_role bigint(20);");
	spdb_query("ALTER TABLE ".SFPERMISSIONS." MODIFY forum_id bigint(20), MODIFY usergroup_id bigint(20), MODIFY permission_role bigint(20);");
	spdb_query("ALTER TABLE ".SFMEMBERSHIPS." MODIFY user_id bigint(20), MODIFY usergroup_id bigint(20);");

	# Indexing on Foreign Keys
	spdb_query("ALTER TABLE ".SFDEFPERMISSIONS." ADD KEY group_idx (group_id), ADD key usergroup_idx (usergroup_id), ADD KEY perm_role_idx(permission_role);");
	spdb_query("ALTER TABLE ".SFLOG." ADD KEY user_idx (user_id);");
	spdb_query("ALTER TABLE ".SFPERMISSIONS." ADD KEY forum_idx (forum_id), ADD KEY usergroup_idx (usergroup_id), ADD KEY perm_role_idx(permission_role);");
	spdb_query("ALTER TABLE ".SFPOSTS." ADD KEY user_idx (user_id), ADD KEY comment_idx (comment_id);");
	spdb_query("ALTER TABLE ".SFFORUMS." ADD KEY post_idx (post_id);");
	spdb_query("ALTER TABLE ".SFTOPICS." ADD KEY user_idx (user_id), ADD KEY post_idx (post_id);");
	spdb_query("ALTER TABLE ".SFTRACK." ADD KEY forum_idx (forum_id), ADD KEY topic_idx (topic_id);");
	spdb_query("ALTER TABLE ".SFWAITING." ADD KEY forum_idx (forum_id), ADD KEY post_idx (post_id), ADD KEY user_idx (user_id);");

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

# End of DB Schema Changes

$section = 6663;
# drop old tablea and remove old optioon records
if ($build < $section) {
	# remve unwanted option records
	sp_delete_option('sfcbexclusions');

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 6689;
# move auto update stuff to sfmeta
if ($build < $section) {
	$autoup = array('spjUserUpdate', SFHOMEURL.'index.php?sp_ahah=autoupdate');
	sp_add_sfmeta('autoupdate', 'user', $autoup, 0);

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 6702;
# convert permissions to auths
if ($build < $section) {
	sp_convert_perms_to_auths();

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 6715;
if ($build < $section) {
	/* blog search stuff removed */
	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 6728;
# set up cron transient cleanup
if ($build < $section) {
	wp_schedule_event(time(), 'daily', 'sph_transient_cleanup_cron'); # new cron name

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 6741;
# add new breadcrumb option
if ($build < $section) {
	$sfdisplay = array();
	$sfdisplay = sp_get_option('sfdisplay');
	$sfdisplay['breadcrumbs']['showpage'] = true;
	sp_update_option('sfdisplay', $sfdisplay);

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 6754;
if ($build < $section) {
	sp_delete_option('sfeditor');

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 6767;
if ($build < $section) {
	$sfsupport = array();
	$sfsupport = sp_get_option('sfsupport');
	unset($sfsupport['sfusingtagstags']);
	unset($sfsupport['sfusingpagestags']);
	sp_update_option('sfsupport', $sfsupport);

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 6780;
if ($build < $section) {
	# config admin panel now gone
	sp_delete_option('sfsupport');

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 6793;
if ($build < $section) {
	# update smileys for in_use flag
	$smileys = sp_get_sfmeta('smileys', 'smileys');
	if ($smileys) {
		foreach ($smileys[0]['meta_value'] as $smiley => $something) {
			$smileys[0]['meta_value'][$smiley][2] = 1;
		}
		sp_update_sfmeta('smileys', 'smileys', $smileys[0]['meta_value'], $smileys[0]['meta_id'], true);
	}

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 6806;
if ($build < $section) {
	# new manage theme and plugin caps
	$admins = spdb_table(SFMEMBERS, 'admin = 1');
	if ($admins) {
	   foreach ($admins as $admin) {
            $user = new WP_User($admin->user_id);
            $user->add_cap('SPF Manage Themes');
            $user->add_cap('SPF Manage Plugins');
        }
    }
	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 6819;
if ($build < $section) {
	# move some stats to own options
	$spControls = sp_get_option('sfcontrols');
	sp_add_option('spMostOnline', $spControls['maxonline']);
	sp_add_option('spRecentMembers', $spControls['newuserlist']);

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 6832;
if ($build < $section) {
	# set up hourly stats generation
    wp_schedule_event(time(), 'hourly', 'sph_stats_cron'); # new cron name
    sp_cron_generate_stats();

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 6845;
if ($build < $section) {
	# new profile tabs
	spa_new_profile_setup();

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 6858;
if ($build < $section) {
	# set required items to autoload
	spdb_query("UPDATE ".SFMETA." SET autoload = 1 WHERE meta_type IN ('smileys', 'topic-status', 'customProfileFields', 'forum_rank', 'special_rank')");

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 6871;
if ($build < $section) {
	$sffilters = sp_get_option('sffilters');
	$sffilters['sfallowlinks'] = true;
	sp_update_option('sffilters', $sffilters);

	# update the options
	sp_new_options_update();

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 6884;
if ($build < $section) {
	# default theme
	$theme = array();
	$theme['theme'] = 'default';
	$theme['style'] = 'default.php';
	$theme['color'] = 'silver';
	sp_add_option('sp_current_theme', $theme);

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 6897;
if ($build < $section) {
	# Clean smiley options
	$sfsmileys = array();
	$sfsmileys = sp_get_option('sfsmileys');
	$setting = $sfsmileys['sfsmallow'];
	sp_update_option('sfsmileys', $setting);

	$sfrss = sp_get_option('sfrss');
	$sfrss['sfrsstopicname'] = false;
	sp_update_option('sfrss', $sfrss);

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

##### Before this is start of alpha #####
$section = 6910;
if ($build < $section) {
	$sfprofile = sp_get_option('sfprofile');
	if ($sfprofile['nameformat'] == 3) {
		$sfprofile['nameformat'] = false;
		$sfprofile['fixeddisplayformat'] = 4;
	} else if ($sfprofile['nameformat'] == 2) {
		$sfprofile['nameformat'] = false;
		$sfprofile['fixeddisplayformat'] = 1;
	} else {
		$sfprofile['nameformat'] = true;
		$sfprofile['fixeddisplayformat'] = 0;
	}

	$sfseo = sp_get_option('sfseo');
	$sfseo['sfseo_overwrite'] = false;
	$sfseo['sfseo_blogname'] = false;
	$sfseo['sfseo_pagename'] = false;
	sp_update_option('sfseo', $sfseo);

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 6923;
# create plugins, theme and language folder storage location (sp-resources)
if ($build < $section) {

	$sfconfig = array();
	$sfconfig = sp_get_option('sfconfig');

	# remove unrequired entries
	unset($sfconfig['hooks']);
	unset($sfconfig['help']);
	unset($sfconfig['styles']);
	unset($sfconfig['languages']);
	unset($sfconfig['pluggable']);
	unset($sfconfig['filters']);

	$perms = fileperms(SF_STORE_DIR);
	$owners = stat(SF_STORE_DIR);
	if($perms === false) $perms = 0755;
	$basepath.= 'sp-resources';
	if (!file_exists(SF_STORE_DIR.'/'.$basepath)) @mkdir(SF_STORE_DIR.'/'.$basepath, $perms);

	# hive off the basepath for later use - use wp options
	$spStorage = SF_STORE_DIR.'/'.$basepath;

	# Did it get created?
	$success = true;
	if (!file_exists(SF_STORE_DIR.'/'.$basepath)) $success = false;
	sp_add_option('spStorageInstall2', $success);

	# Is the ownership correct?
	$ownersgood = false;
	if($success) {
		$newowners = stat(SF_STORE_DIR.'/'.$basepath);
		if($newowners['uid']==$owners['uid'] && $newowners['gid']==$owners['gid']) {
			$ownersgood = true;
		} else {
			@chown(SF_STORE_DIR.'/'.$basepath, $owners['uid']);
			@chgrp(SF_STORE_DIR.'/'.$basepath, $owners['gid']);
			$newowners = stat(SF_STORE_DIR.'/'.$basepath);
			if($newowners['uid']==$owners['uid'] && $newowners['gid']==$owners['gid']) $ownersgood = true;
		}
	}
	sp_add_option('spOwnersInstall2', $ownersgood);

	$basepath .= '/';
	$sfconfig['plugins'] 		        = $basepath.'forum-plugins';
	$sfconfig['themes']			        = $basepath.'forum-themes';
	$sfconfig['language-sp']			= $basepath.'forum-language/simple-press';
	$sfconfig['language-sp-plugins']	= $basepath.'forum-language/sp-plugins';
	$sfconfig['language-sp-themes']		= $basepath.'forum-language/sp-themes';

	sp_update_option('sfconfig', $sfconfig);

	# Move and extract zip upgrade archive
	$successCopy2 = false;
	$successExtract2 = false;
	$zipfile = SF_PLUGIN_DIR.'/sp-startup/install/sp-resources-install-part2.zip';
	$extract_to = $spStorage;
	# Copy the zip file
	if (@copy($zipfile, $extract_to.'/sp-resources-install-part2.zip')) {
		$successCopy2 = true;
		# Now try and unzip it
		require_once(ABSPATH . 'wp-admin/includes/class-pclzip.php');
		$zipfile = $extract_to.'/sp-resources-install-part2.zip';
		$zipfile = str_replace('\\','/',$zipfile); # sanitize for Win32 installs
		$zipfile = preg_replace('|/+|','/', $zipfile); # remove any duplicate slash
		$extract_to = str_replace('\\','/',$extract_to); # sanitize for Win32 installs
		$extract_to = preg_replace('|/+|','/', $extract_to); # remove any duplicate slash
		$archive = new PclZip($zipfile);
		$archive->extract($extract_to);
		if ($archive->error_code == 0) {
			$successExtract2 = true;
			# Lets try and remove the zip as it seems to have worked
			@unlink($zipfile);
		} else {
			sp_add_option('ziperror', $archive->error_string);
		}
	}

    sp_add_option('spCopyZip2', $successCopy2);
    sp_add_option('spUnZip2', $successExtract2);

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 6936;
if ($build < $section) {
	# Move storage location folders
	if (sp_get_option('V5DoStorage') == true) {
		sp_move_storage_locations();
	}

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 6949;
if ($build < $section) {
	$sflogin = sp_get_option('sflogin');
	$sflogin['sfloginurl'] = sp_url();
	$sflogin['sflogouturl'] = sp_url();
	$sflogin['sfregisterurl'] = '';
	$sflogin['sfloginemailurl'] = site_url('wp-login.php?action=login', 'login');
	sp_update_option('sflogin', $sflogin);

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 6962;
if ($build < $section) {
	spdb_query("DELETE FROM ".SFMEMBERSHIPS." WHERE usergroup_id=0");

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

## after alpha start
$section = 7022;
if ($build < $section) {
	spdb_query("ALTER TABLE ".SFTRACK." ADD (pageview varchar(50) NOT NULL)");

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 7055;
if ($build < $section) {
    $auth = spdb_table(SFAUTHS, 'auth_name="view_admin_posts"', 'row');
    if ($auth) {
        $auth->auth_desc = esc_sql(spa_text('Can view posts by an administrator'));
    	spdb_query("UPDATE ".SFAUTHS." SET auth_desc='$auth->auth_desc' WHERE auth_id=$auth->auth_id");
    }

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 7181;
if ($build < $section) {
    $curTheme = sp_get_option('sp_current_theme');
	$theme = array();
    $theme['active'] = false;
	$theme['theme'] = $curTheme['theme'];
	$theme['style'] = $curTheme['style'];
	$theme['color'] = $curTheme['color'];
	sp_add_option('sp_mobile_theme', $theme);

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 7255;
if ($build < $section) {
    # ignore editing permissions for guests
    $auth = spdb_table(SFAUTHS, 'auth_name="edit_any_post"', 'row');
    if ($auth) spdb_query("UPDATE ".SFAUTHS." SET ignored=1 WHERE auth_id=$auth->auth_id");
    $auth = spdb_table(SFAUTHS, 'auth_name="edit_own_posts_forever"', 'row');
    if ($auth) spdb_query("UPDATE ".SFAUTHS." SET ignored=1 WHERE auth_id=$auth->auth_id");
    $auth = spdb_table(SFAUTHS, 'auth_name="edit_own_posts_reply"', 'row');
    if ($auth) spdb_query("UPDATE ".SFAUTHS." SET ignored=1 WHERE auth_id=$auth->auth_id");

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 7420;
if ($build < $section) {
	# new user notices table
	$sql = "
	CREATE TABLE IF NOT EXISTS ".SFNOTICES." (
	  notice_id bigint(20) NOT NULL auto_increment,
	  user_id bigint(20) default NULL,
	  guest_email varchar(50) default NULL,
	  post_id bigint(20) default NULL,
	  link varchar(255) default NULL,
	  link_text varchar(200) default NULL,
	  message varchar(255) NOT NULL default '',
	  expires int(4) default NULL,
	  PRIMARY KEY (notice_id)
		) ENGINE=MyISAM ".spdb_charset().";";
	spdb_query($sql);

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 7430;
if ($build < $section) {
	# creating new table columns for post moderation processing
	spdb_query("ALTER TABLE ".SFFORUMS." ADD (post_id_held bigint(20) default NULL)");
	spdb_query("ALTER TABLE ".SFFORUMS." ADD (post_count_held mediumint(8) default '0')");
	spdb_query("ALTER TABLE ".SFTOPICS." ADD (post_id_held bigint(20) default NULL)");
	spdb_query("ALTER TABLE ".SFTOPICS." ADD (post_count_held mediumint(8) default '0')");
	# pupulating with startup data

	spdb_query('UPDATE '.SFTOPICS.' SET post_id_held = post_id, post_count_held = post_count');
	spdb_query('UPDATE '.SFFORUMS.' SET post_id_held = post_id, post_count_held = post_count');

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 7500;
if ($build < $section) {
	# Set up unique key
	$uKey = substr(chr(rand(97, 122)).md5(time()), 0, 10);
	sp_add_option('spukey', $uKey);

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 7572;
if ($build < $section) {
	# Add post_date index
	spdb_query("ALTER TABLE ".SFPOSTS." ADD KEY post_date_idx (post_date)");

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 7579;
if ($build < $section) {
	# Add new column indexing
	spdb_query("ALTER TABLE ".SFMEMBERS." ADD KEY admin_idx (admin)");
	spdb_query("ALTER TABLE ".SFMEMBERS." ADD KEY moderator_idx (moderator)");
	spdb_query("ALTER TABLE ".SFMETA." ADD KEY meta_type_idx (meta_type)");

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 7750;
if ($build < $section) {
	# Add new column indexing
	spdb_query("ALTER TABLE ".SFMETA." ADD KEY autoload_idx (autoload)");

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 7826;
if ($build < $section) {
	# adjust log entries
	spdb_query("ALTER TABLE ".SFLOG." MODIFY release_type varchar(20)");
	spdb_query("ALTER TABLE ".SFLOG." MODIFY build int(6) NOT NULL");

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 8033;
if ($build < $section) {
	$sfcontrols = sp_get_option('sfcontrols');
	$sfcontrols['sfdefunreadposts'] = 50;
	$sfcontrols['sfusersunread'] = false;
	$sfcontrols['sfmaxunreadposts'] = 50;
	sp_update_option('sfcontrols', $sfcontrols);

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 8148;
if ($build < $section) {
	global $wp_rewrite;
	$wp_rewrite->flush_rules(); # flush rewrite rules to load newpost rule

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 8214;
if ($build < $section) {
	spdb_query("ALTER TABLE ".SFUSERGROUPS." ADD (usergroup_badge varchar(50) default NULL)");
    sp_reset_memberships();

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 8219;
if ($build < $section) {
	sp_add_option('sp_stats_interval', 3600);

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 8222;
if ($build < $section) {
	spdb_query("ALTER TABLE ".SFFORUMS." ADD (forum_icon_new varchar(50) default NULL)");

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 8239;
if ($build < $section) {
	$profile = sp_get_sfmeta('profile', array());
	$tabs = (!empty($profile)) ? $profile[0]['meta_value'] : '';
    if ($tabs) {
    	foreach ($tabs as $tindex => $tab) {
    		if ($tab['slug'] == 'profile') {
    			if ($tab['menus']) {
    				foreach ($tab['menus'] as $mindex => $menu) {
    					if ($menu['slug'] == 'edit-signature') {
    				        if (empty($tabs[$tindex]['menus'][$mindex]['auth'])) {
                                $tabs[$tindex]['menus'][$mindex]['auth'] = 'use_signatures';
                                break 2;
                            }
                        }
    				}
    			}
            }
    	}
        sp_update_sfmeta('profile', 'tabs', $tabs, $profile[0]['meta_id']);
    }

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 8240;
if($build < $section) {
	$sflogin = sp_get_option('sflogin');
	$sflogin['sptimeout'] = 20;
	sp_update_option('sflogin', $sflogin);

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 8284;
if($build < $section) {
	$sfprofile = sp_get_option('sfprofile');
    $sfprofile['photosheight'] = $sfprofile['photoswidth'];
	sp_update_option('sfprofile', $sfprofile);

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

# Start of Upgrade Routines - 5.1.0 ============================================================

$section = 8315;
if($build < $section) {
	# Add new index to sfposts
	spdb_query("ALTER TABLE ".SFPOSTS." ADD KEY guest_name_idx (guest_name);");
	# Remove mobile lost option
	sp_delete_option('sfmobile');

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 8375;
if($build < $section) {
	wp_schedule_event(time(), 'sp_news_interval', 'sph_news_cron');
	sp_add_sfmeta('news', 'news', array('id' => -999.999, 'show' => 0, 'news' => spa_text('Latest Simple Press News will be shown here')));

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 8402;
if($build < $section) {
    sp_cron_generate_stats();

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 8440;
if($build < $section) {
    sp_convert_ranks();

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 8493;
if($build < $section) {
	spdb_query("ALTER TABLE ".SFFORUMS." ADD (topic_icon varchar(50) default NULL)");
	spdb_query("ALTER TABLE ".SFFORUMS." ADD (topic_icon_new varchar(50) default NULL)");

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 8530;
if($build < $section) {
	sp_add_sfmeta('sort_order', 'forum', '', 1);
	sp_add_sfmeta('sort_order', 'topic', '', 1);

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 8552;
if($build < $section) {
	sp_add_option('poststamp', current_time('mysql'));
	sp_delete_option('sfzone');

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 8556;
if($build < $section) {
	$sfadminsettings = sp_get_option('sfadminsettings');
    $sfadminsettings['sfadminapprove'] = false;
    $sfadminsettings['sfmoderapprove'] = false;
	sp_update_option('sfadminsettings', $sfadminsettings);

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

# Start of Upgrade Routines - 5.1.1 ============================================================
$section = 8618;
if($build < $section) {
	# image size constraint
	$sfimage = sp_get_option('sfimage');
	$sfimage['constrain'] = true;
	$sfimage['forceclear'] = false;
	sp_update_option('sfimage', $sfimage);

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 8629;
if($build < $section) {
    sp_update_forum_moderators(); # build the list of moderators per forum

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 8655;
if($build < $section) {
	sp_add_option('account-name', '');
	sp_add_option('display-name', '');
	sp_add_option('guest-name', '');

	sp_update_option('sfbuild', $section);
	echo $section;
	die();
}

$section = 8656;
if($build < $section) {
	# create mysql search sfmeta row
	$s = array();
	$v = spdb_select('row', "SHOW VARIABLES LIKE 'ft_min_word_len'");
	(empty($v->Value) ? $s['min'] = 4 : $s['min'] = $v->Value);
	$v = spdb_select('row', "SHOW VARIABLES LIKE 'ft_max_word_len'");
	(empty($v->Value) ? $s['max'] = 84 : $s['max'] = $v->Value);
	sp_add_sfmeta('mysql', 'search', $s, true);
}

# let plugins know
do_action('sph_upgrade_done', $build);

# Finished Upgrades ===============================================================================
# EVERYTHING BELOW MUST BE AT THE END

sp_log_event(SPRELEASE, SPVERSION, SPBUILD);

echo SPBUILD;
delete_option('sfInstallID'); # use wp option table

die();
?>