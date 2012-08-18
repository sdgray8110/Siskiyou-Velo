<?php
/*
Simple:Press
Install & Upgrade Support Routines
$LastChangedDate: 2012-04-28 11:12:45 -0700 (Sat, 28 Apr 2012) $
$Rev: 8459 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

# ==========================================
#
# GLOBAL UPDATE/INSTALL ROUTINES
#
# ==========================================

# Called to log updates.
function sp_log_event($release, $version, $build) {
	global $current_user;

	$now = current_time('mysql');
	$sql = "
		INSERT INTO ".SFLOG." (user_id, install_date, release_type, version, build)
		VALUES (
		".$current_user->ID.",
		'".$now."',
		'".$release."',
		'".$version."',
		".$build.");";
	spdb_query($sql);

	sp_update_option('sfversion', $version);
	sp_update_option('sfbuild', $build);
}

function sp_build_base_smileys() {
	$smileys = array(
	"Confused" => 	array (	0 => "sf-confused.gif",		1 => ":???:",   2 => 1, 3 => 0, 4 => 0 ),
	"Cool" =>		array (	0 => "sf-cool.gif",			1 => ":cool:",  2 => 1, 3 => 1, 4 => 0 ),
	"Cry" =>		array (	0 => "sf-cry.gif",			1 => ":cry:",   2 => 1, 3 => 2, 4 => 0 ),
	"Embarassed" =>	array (	0 => "sf-embarassed.gif",	1 => ":oops:",  2 => 1, 3 => 3, 4 => 0 ),
	"Frown" =>		array (	0 => "sf-frown.gif",		1 => ":frown:", 2 => 1, 3 => 4, 4 => 0 ),
	"Kiss" =>		array (	0 => "sf-kiss.gif",			1 => ":kiss:",  2 => 1, 3 => 5, 4 => 0 ),
	"Laugh" =>		array (	0 => "sf-laugh.gif",		1 => ":lol:",   2 => 1, 3 => 6, 4 => 0 ),
	"Smile" =>		array (	0 => "sf-smile.gif",		1 => ":smile:", 2 => 1, 3 => 7, 4 => 0 ),
	"Surprised" =>	array (	0 => "sf-surprised.gif",	1 => ":eek:",   2 => 1, 3 => 8, 4 => 0 ),
	"Wink" =>		array (	0 => "sf-wink.gif",			1 => ":wink:",  2 => 1, 3 => 9, 4 => 0 ),
	"Yell" =>		array (	0 => "sf-yell.gif",			1 => ":yell:",  2 => 1, 3 => 10, 4 => 0 )
	);

	sp_add_sfmeta('smileys', 'smileys', $smileys, 1);

	return;
}

function sp_create_usergroup_meta($members) {
	global $wp_roles;

	$roles = array_keys($wp_roles->role_names);
	if ($roles) {
		foreach ($roles as $role) {
			sp_add_sfmeta('default usergroup', $role, $members); # initally set each role to members usergroup
		}
	}
}

function sp_generate_member_feedkeys() {
    global $wpdb;

    $members = $wpdb->get_results("SELECT user_id FROM ".SFMEMBERS);
    foreach ($members as $member) {
        # generate a pseudo-random UUID according to RFC 4122
        $key = sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                    mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
                    mt_rand( 0, 0x0fff ) | 0x4000,
                    mt_rand( 0, 0x3fff ) | 0x8000,
                    mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ) );
        $wpdb->query("UPDATE ".SFMEMBERS." SET feedkey = '".$key."' WHERE user_id=".$member->user_id);
    }

    return;
}

function sp_install_members_table($subphase) {
	global $wpdb, $current_user;

	# get limits for installs
	if ($subphase != 0) $limit = " LIMIT 250 OFFSET ".(($subphase - 1) * 250);

	# select all users
	$sql = "SELECT ID FROM ".$wpdb->prefix."users".$limit;
	$members = $wpdb->get_results($sql);

	if ($members) {
		foreach($members as $member) {
			# Check ID exists and is not zero
			if(is_numeric($member->ID) && $member->ID > 0) {
                sp_create_member_data($member->ID);

                # for the admin installer, remove any usergroup membership added by create member function
				if ($current_user->ID == $member->ID) $wpdb->query("DELETE FROM ".$wpdb->prefix."sfmemberships WHERE user_id=".$member->ID);
			}
		}
	}
	return;
}

# ==================================
# MAY BE BOTH INSTALL AND UPGRADE
# ==================================

# 5.0 options update
function sp_new_options_update() {
	sp_delete_option('sfshowicon');

	$sfdisplay = sp_get_option('sfdisplay');
	unset($sfdisplay['breadcrumbs']);
    unset($sfdisplay['search']);
    unset($sfdisplay['quicklinks']);
    unset($sfdisplay['pagelinks']);
    unset($sfdisplay['firstlast']);
    unset($sfdisplay['unreadcount']);
    unset($sfdisplay['groups']['description']);
    unset($sfdisplay['groups']['showsubforums']);
    unset($sfdisplay['groups']['combinesubcount']);
    unset($sfdisplay['groups']['showallsubs']);
    unset($sfdisplay['forums']['description']);
    unset($sfdisplay['forums']['newposticon']);
    unset($sfdisplay['forums']['pagelinks']);
    unset($sfdisplay['forums']['newcount']);
    unset($sfdisplay['forums']['newposts']);
    unset($sfdisplay['forums']['newabove']);
    unset($sfdisplay['forums']['sortinforum']);
    unset($sfdisplay['forums']['topiccol']);
    unset($sfdisplay['forums']['postcol']);
    unset($sfdisplay['forums']['lastcol']);
    unset($sfdisplay['forums']['showtitle']);
    unset($sfdisplay['forums']['showtitletop']);
    unset($sfdisplay['forums']['pinned']);
	unset($sfdisplay['topics']['numpagelinks']);
	unset($sfdisplay['topics']['firstcol']);
	unset($sfdisplay['topics']['lastcol']);
	unset($sfdisplay['topics']['postcol']);
	unset($sfdisplay['topics']['viewcol']);
	unset($sfdisplay['topics']['pagelinks']);
	unset($sfdisplay['topics']['statusicons']);
	unset($sfdisplay['topics']['postrating']);
	unset($sfdisplay['topics']['topicstatus']);
	unset($sfdisplay['topics']['print']);
	unset($sfdisplay['topics']['showsubforums']);
	unset($sfdisplay['topics']['posttip']);
	unset($sfdisplay['posts']['numpagelinks']);
	unset($sfdisplay['posts']['userabove']);
	unset($sfdisplay['posts']['showedits']);
	unset($sfdisplay['posts']['showlastedit']);
	unset($sfdisplay['posts']['topicstatushead']);
	unset($sfdisplay['posts']['topicstatuschanger']);
	unset($sfdisplay['posts']['online']);
	unset($sfdisplay['posts']['time']);
	unset($sfdisplay['posts']['date']);
	unset($sfdisplay['posts']['usertype']);
	unset($sfdisplay['posts']['rankdisplay']);
	unset($sfdisplay['posts']['location']);
	unset($sfdisplay['posts']['postcount']);
	unset($sfdisplay['posts']['permalink']);
	unset($sfdisplay['posts']['print']);
	unset($sfdisplay['posts']['sffbconnect']);
	unset($sfdisplay['posts']['sfmyspace']);
	unset($sfdisplay['posts']['sflinkedin']);
	sp_update_option('sfdisplay', $sfdisplay);

	sp_delete_option('sftwitter');
	sp_delete_option('sfstyle');
	sp_delete_option('sffloatclear');

	sp_delete_option('sfcheck');

	$sfprofile = sp_get_option('sfprofile');
	unset($sfprofile['require']);
	unset($sfprofile['include']);
	unset($sfprofile['display']);
	unset($sfprofile['system']);
	unset($sfprofile['label']);
	unset($sfprofile['displayinforum']);
	unset($sfprofile['forminforum']);
	sp_update_option('sfprofile', $sfprofile);

	$sfuploads = sp_get_option('sfuploads');
	unset($sfuploads['privatefolder']);
	sp_update_option('sfuploads', $sfuploads);

	$sflogin = sp_get_option('sflogin');
	unset($sflogin['sfloginskin']);
	unset($sflogin['sfshowavatar']);
	unset($sflogin['sflostpassurl']);
	sp_update_option('sflogin', $sflogin);

	$sfmemberopts = sp_get_option('sfmemberopts');
	unset($sfmembersopt['sfshowmemberlist']);
	unset($sfmembersopt['sflimitmemberlist']);
	unset($sfmembersopt['sfviewperm']);
	sp_update_option('sfmemberopts', $sfmemberopts);

	$sfadminsettings = sp_get_option('sfadminsettings');
	unset($sfadminsettings['sftools']);
	sp_update_option('sfadminsettings', $sfadminsettings);

	sp_delete_option('sfuseannounce');
	sp_delete_option('sfannouncecount');
	sp_delete_option('sfannouncehead');
	sp_delete_option('sfannounceauto');
	sp_delete_option('sfannouncetime');
	sp_delete_option('sfannouncetext');
	sp_delete_option('sfannouncelist');
}

# 5.0 convert permissions to authorizations
function sp_convert_perms_to_auths() {
	# populate with existing permissions
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('view_forum', '".esc_sql(spa_text_noesc('Can view a forum'))."', 1, 0, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('view_forum_lists', '".esc_sql(spa_text_noesc('Can view a list of forums only'))."', 1, 0, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('view_forum_topic_lists', '".esc_sql(spa_text_noesc('Can view a list of forums and list of topics only'))."', 1, 0, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('view_admin_posts', '".esc_sql(spa_text_noesc('Can view posts by an administrator'))."', 1, 0, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('start_topics', '".esc_sql(spa_text_noesc('Can start new topics in a forum'))."', 1, 0, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('reply_topics', '".esc_sql(spa_text_noesc('Can reply to existing topics in a forum'))."', 1, 0, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('edit_own_topic_titles', '".esc_sql(spa_text_noesc('Can edit own topic titles'))."', 1, 0, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('edit_any_topic_titles', '".esc_sql(spa_text_noesc('Can edit any topic title'))."', 1, 0, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('pin_topics', '".esc_sql(spa_text_noesc('Can pin topics in a forum'))."', 1, 0, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('move_topics', '".esc_sql(spa_text_noesc('Can move topics from a forum'))."', 1, 0, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('move_posts', '".esc_sql(spa_text_noesc('Can move posts from a topic'))."', 1, 0, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('lock_topics', '".esc_sql(spa_text_noesc('Can lock topics in a forum'))."', 1, 0, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('delete_topics', '".esc_sql(spa_text_noesc('Can delete topics in forum'))."', 1, 0, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('edit_own_posts_forever', '".esc_sql(spa_text_noesc('Can edit own posts forever'))."', 1, 0, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('edit_own_posts_reply', '".esc_sql(spa_text_noesc('Can edit own posts until there has been a reply'))."', 1, 0, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('edit_any_post', '".esc_sql(spa_text_noesc('Can edit any post'))."', 1, 0, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('delete_own_posts', '".esc_sql(spa_text_noesc('Can delete own posts'))."', 1, 0, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('delete_any_post', '".esc_sql(spa_text_noesc('Can delete any post'))."', 1, 0, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('pin_posts', '".esc_sql(spa_text_noesc('Can pin posts within a topic'))."', 1, 0, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('reassign_posts', '".esc_sql(spa_text_noesc('Can reassign posts to a different user'))."', 1, 0, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('view_email', '".esc_sql(spa_text_noesc('Can view email addresses of members'))."', 1, 0, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('view_profiles', '".esc_sql(spa_text_noesc('Can view profiles of members'))."', 1, 0, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('view_members_list', '".esc_sql(spa_text_noesc('Can view the members lists'))."', 1, 0, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('report_posts', '".esc_sql(spa_text_noesc('Can report a post to administrators'))."', 1, 0, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('bypass_spam_control', '".esc_sql(spa_text_noesc('Can bypass the post spam control'))."', 1, 0, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('bypass_moderation', '".esc_sql(spa_text_noesc('Can bypass all post moderation'))."', 1, 0, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('bypass_moderation_once', '".esc_sql(spa_text_noesc('Can bypass first post moderation'))."', 1, 0, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('moderate_posts', '".esc_sql(spa_text_noesc('Can moderate pending posts'))."', 1, 0, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('use_spoilers', '".esc_sql(spa_text_noesc('Can use spoilers in posts'))."', 1, 0, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('view_links', '".esc_sql(spa_text_noesc('Can view links within posts'))."', 1, 0, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('upload_images', '".esc_sql(spa_text_noesc('Can upload images in posts'))."', 1, 1, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('upload_media', '".esc_sql(spa_text_noesc('Can upload media in posts'))."', 1, 1, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('upload_files', '".esc_sql(spa_text_noesc('Can upload other files in posts'))."', 1, 1, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('use_signatures', '".esc_sql(spa_text_noesc('Can attach a signature to posts'))."', 1, 1, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('upload_signatures', '".esc_sql(spa_text_noesc('Can upload signature images'))."', 1, 1, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('upload_avatars', '".esc_sql(spa_text_noesc('Can upload avatars'))."', 1, 1, 1)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('subscribe', '".esc_sql(spa_text_noesc('Can subscribe to topics within a forum'))."', 0, 1, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('watch', '".esc_sql(spa_text_noesc('Can watch topics within a forum'))."', 0, 1, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('change_topic_status', '".esc_sql(spa_text_noesc('Can change the status of a topic'))."', 1, 1, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('rate_posts', '".esc_sql(spa_text_noesc('Can rate a post'))."', 0, 1, 0)");
	spdb_query("INSERT INTO ".SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('use_pm', '".esc_sql(spa_text_noesc('Can use the private messaging system'))."', 0, 1, 1)");

	# add new column for user auths in sfmember
	spdb_query("ALTER TABLE ".SFMEMBERS." ADD (auths longtext)");

	# now we need to convert existing roles
	$roles = spdb_table(SFROLES);
	foreach ($roles as $role) {
		$actions = unserialize($role->role_actions);
		$new_actions = spa_convert_action_to_auth($actions);
		spdb_query("UPDATE ".SFROLES." SET role_actions='".serialize($new_actions)."' WHERE role_id=".$role->role_id);
	}
	spdb_query("ALTER TABLE ".SFROLES." CHANGE role_actions role_auths longtext");
}

#--------------------------------------------
# sp_move_storage_locations()
#
#
function sp_move_storage_locations() {
	$sfconfig = array();
	$sfconfig = sp_get_option('sfconfig');

	$targets = array('policies', 'custom-icons', 'ranks', 'avatars', 'avatar-pool');
	foreach($targets as $target) {
		if(file_exists(SF_STORE_DIR.'/'.$sfconfig[$target])) {
			if(@rename(SF_STORE_DIR.'/'.$sfconfig[$target], SF_STORE_DIR.'/sp-resources/'.$sfconfig[$target])) {
				$sfconfig[$target] = 'sp-resources/'.$sfconfig[$target];
			}
		}
	}

	sp_update_option('sfconfig', $sfconfig);
}

# 5.1 move ranks from sfmeta to new table and sfmembers
function sp_convert_ranks() {
	spdb_query('ALTER TABLE '.SFMEMBERS.' ADD (special_ranks text default NULL)');

    # convert special rank users to new column in sfmembers   
	$special_rankings = sp_get_sfmeta('special_rank');
	if ($special_rankings) {
		foreach ($special_rankings as $rank) {
            if (empty($rank['meta_value']['users'])) continue;
            $users = $rank['meta_value']['users'];
            foreach ($users as $user) {
                $memberData = sp_get_member_item($user, 'special_ranks');
                $memberData[] = $rank['meta_key'];
                sp_update_member_item($user, 'special_ranks', $memberData);
            }
            unset($rank['meta_value']['users']);
            sp_update_sfmeta('special_rank', $rank['meta_key'], $rank['meta_value'], $rank['meta_id'], 1);
        }
   }
}
?>