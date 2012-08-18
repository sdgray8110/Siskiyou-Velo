<?php
/*
Simple:Press
Desc:
$LastChangedDate: 2012-06-03 13:15:43 -0700 (Sun, 03 Jun 2012) $
$Rev: 8650 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

# ==========================================================================================
#
# 	GLOBAL Database Module
# 	New Post Database Routines
#
#	sp_construct_users_newposts()
#	sp_update_users_newposts()
#	sp_remove_users_newposts()
#	sp_destroy_users_newposts()
#	sp_is_in_users_newposts()
#	sp_combined_new_posts_list()
#
# ==========================================================================================

# ------------------------------------------------------------------
# sp_construct_users_newposts()
#
# Constructs the new users personalized new/unread posts list when
# they first appear on the system and creates the timestamp for
# their creation
#
#	$nosave		if called from a tempate tag and this is true
#				do NOT save checktime
# ------------------------------------------------------------------
function sp_construct_users_newposts($nosave = false) {
	global $spThisUser;

	$newpostlist=array();
	$newpostlist['topics']=array();
	$newpostlist['forums']=array();

	$records=spdb_select('set', "SELECT DISTINCT topic_id, forum_id FROM ".SFPOSTS."
								WHERE post_status = 0 AND post_date > '".spdb_zone_mysql_checkdate($spThisUser->lastvisit)."'
								AND user_id != ".$spThisUser->ID." ORDER BY topic_id DESC LIMIT ".$spThisUser->unreadposts.";", ARRAY_A);

	if ($records) {
		foreach ($records as $r) {
			if (sp_get_auth('view_forum', $r['forum_id'])) {
				$newpostlist['topics'][]=$r['topic_id'];
				$newpostlist['forums'][]=$r['forum_id'];
			}
		}
	}

    $newpostlist = apply_filters('sph_new_post_list', $newpostlist);

	if (count($newpostlist['topics'])==0) {
		unset($newpostlist);
		$newpostlist = array();
		$newpostlist['topics'] = array();
		$newpostlist['forums'] = array();
	}

	sp_update_member_item($spThisUser->ID, 'newposts', $newpostlist);

	if ($nosave == false) sp_update_member_item($spThisUser->ID, 'checktime', 0);

	sp_set_server_timezone();
    $spThisUser->checktime = sp_apply_timezone(time(), 'mysql');

	$spThisUser->newpostlist = true;
	$spThisUser->newposts = $newpostlist;
}

# ------------------------------------------------------------------
# sp_update_users_newposts()
#
# Updates a users new-post-list on subsequent page loads
#	$newpostlist:		new-post-list
# ------------------------------------------------------------------
function sp_update_users_newposts() {
	global $spThisUser;

	# Check the users checktime against the last poist timestamp to see if we need to do this
	$userTime = spdb_zone_mysql_checkdate($spThisUser->checktime);
	$postTime = sp_get_option('poststamp');
	if(strtotime($userTime) > strtotime($postTime)) return;

	# so there must have been a new post since the last page load for this user
	$newpostlist = $spThisUser->newposts;
	if (empty($newpostlist)) {
		unset($newpostlist);
		$newpostlist = array();
		$newpostlist['topics'] = array();
		$newpostlist['forums'] = array();
	}
	$checktime = $spThisUser->checktime;
	$newpostlist['topics'] = array_reverse($newpostlist['topics']);
	$newpostlist['forums'] = array_reverse($newpostlist['forums']);

	# Use the current checktime for any new posts since users session began
	$records = spdb_select('set', "SELECT DISTINCT topic_id, forum_id FROM ".SFPOSTS."
								  WHERE post_status = 0 AND post_date > '".spdb_zone_mysql_checkdate($checktime)."' AND user_id != ".$spThisUser->ID."
								  ORDER BY topic_id DESC LIMIT ".$spThisUser->unreadposts.";", ARRAY_A);

	if ($records) {
		foreach ($records as $r) {
			if (sp_get_auth('view_forum', $r['forum_id']) && !in_array($r['topic_id'], $newpostlist['topics'])) {
				$newpostlist['topics'][] = $r['topic_id'];
				$newpostlist['forums'][] = $r['forum_id'];
			}
		}
	}

    $newpostlist = apply_filters('sph_new_post_list', $newpostlist);

	$newpostlist['topics'] = array_reverse($newpostlist['topics']);
	$newpostlist['forums'] = array_reverse($newpostlist['forums']);
	if (count($newpostlist['topics'])==0) {
		unset($newpostlist);
		$newpostlist=array();
		$newpostlist['topics'] = array();
		$newpostlist['forums'] = array();
	}
	sp_update_member_item($spThisUser->ID, 'newposts', $newpostlist);
	sp_update_member_item($spThisUser->ID, 'checktime', 0);

	sp_set_server_timezone();
	$spThisUser->checktime = sp_apply_timezone(time(), 'mysql');

	$spThisUser->newpostlist = true;
	$spThisUser->newposts = $newpostlist;
}

# ------------------------------------------------------------------
# sp_remove_users_newposts()
#
# Removes items from users new-post-list upon viewing them
#	$topicid:		the topic to remove from new-post-list
# ------------------------------------------------------------------
function sp_remove_users_newposts($topicid) {
	global $spThisUser;

	if ($spThisUser->member) {
		$newpostlist = $spThisUser->newposts;
		if ($newpostlist && !empty($newpostlist)) {
			if ((count($newpostlist['topics']) == 1) && ($newpostlist['topics'][0] == $topicid)) {
				sp_destroy_users_newposts($spThisUser->ID);
			} else {
				$remove = -1;
				for ($x=0; $x < count($newpostlist['topics']); $x++) {
					if ($newpostlist['topics'][$x] == $topicid) {
						$remove = $x;
						break;
					}
				}
				if ($remove != -1) {
					array_splice($newpostlist['topics'], $remove, 1);
					array_splice($newpostlist['forums'], $remove, 1);
					sp_update_member_item($spThisUser->ID, 'newposts', $newpostlist);
					$spThisUser->newposts = $newpostlist;
				}
			}
		}
	}
}

# ------------------------------------------------------------------
# sp_destroy_users_newposts()
#
# Destroy users new-post-list now they have departed
#	$userid:		Users ID
# ------------------------------------------------------------------
function sp_destroy_users_newposts($userid) {
	global $spThisUser;

	$newpostlist=array();
	$newpostlist['topics'] = array();
	$newpostlist['forums'] = array();
	sp_update_member_item($userid, 'newposts', $newpostlist);
}

# ------------------------------------------------------------------
# sp_is_in_users_newposts()
#
# Determines if topic is in current users new-post-list
#	$topicid:		the topic to look for
# ------------------------------------------------------------------
function sp_is_in_users_newposts($topicid) {
	global $spThisUser;

	$newpostlist = ($spThisUser->member) ? $spThisUser->newposts : '';
	$found = 0;
	if (!empty($newpostlist['topics']) && $newpostlist['topics']) {
		for ($x=0; $x < count($newpostlist['topics']); $x++) {
			if ($newpostlist['topics'][$x] == $topicid) $found = true;
		}
	}
	return $found;
}

# ------------------------------------------------------------------
# sp_mark_all_read()
#
# Marks current users posts as read
# ------------------------------------------------------------------
function sp_mark_all_read() {
	global $spThisUser;

	# just to be safe, make sure a member called
	if ($spThisUser->member) {
		sp_destroy_users_newposts($spThisUser->ID);
		sp_update_users_newposts();
	}
}

?>