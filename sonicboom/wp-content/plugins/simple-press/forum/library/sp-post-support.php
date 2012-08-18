<?php
/*
Simple:Press
Forum Topic/Post New Post SUpport routines
$LastChangedDate: 2012-04-13 19:00:49 -0700 (Fri, 13 Apr 2012) $
$Rev: 8330 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

# ==================================================================================
# NOTIFICATION EMAILS
# ==================================================================================
# Send emails to Admin (if needed) ---------------------------------
function sp_email_notifications($newpost) {
	global $sfglobals, $spThisUser, $sfvars;

	$groupname = urldecode(sp_get_group_name_from_forum($newpost['forumid']));
	$forumname = urldecode(spdb_table(SFFORUMS, "forum_slug='".$newpost['forumslug']."'", 'forum_name'));
	$topicname = urldecode(spdb_table(SFTOPICS, "topic_slug='".$newpost['topicslug']."'", 'topic_name'));

	$out = '';
	$email_status = array();

	$post_record = spdb_table(SFPOSTS, 'post_id='.$newpost['postid'], 'row');
	$eol = "\r\n";

	$admins_email = array();
	$admins = spdb_table(SFMEMBERS, 'admin = 1 OR moderator = 1');
	if ($admins) {
		foreach ($admins as $admin) {
			if ($admin->user_id != $newpost['userid']) {
				$admin_opts = unserialize($admin->admin_options);
				if ($admin_opts['sfnotify'] && sp_get_auth('moderate_posts', $newpost['forumid'], $admin->user_id)) {
					$email = spdb_table(SFUSERS, "ID = ".$admin->user_id, 'user_email');
					$admins_email[$admin->user_id] = $email;
				}
			}
		}
	}
    $admins_email = apply_filters('sph_admin_email_addresses', $admins_email);

	if (!empty($admins_email)) {
		# clean up the content for the plain text email
		$post_content = html_entity_decode($post_record->post_content, ENT_QUOTES);
		$post_content = sp_filter_content_display($post_content);
		$post_content = str_replace('&nbsp;', ' ', $post_content);

		# admin message
		$eol = "\n";
		$ip = spdb_table(SFPOSTS, 'post_id='.$newpost['postid'], 'poster_ip');

		# remove the html
		$post_content = strip_tags($post_content);

		# create message body
		$msg  = sp_text('New forum post on your site').': '.get_option('blogname').$eol.$eol;
		$msg .= sp_text('From').': '.$newpost['postername'].' ['.$newpost['posteremail'].']'.', '.sp_text('Poster IP').': '.$ip.$eol.$eol;
		$msg .= sp_text('Group').': '.$groupname.$eol;
		$msg .= sp_text('Forum').': '.$forumname.$eol;
		$msg .= sp_text('Topic').': '.$topicname;
		$msg .= ' ('.urldecode($newpost['url']).')'.$eol;
		$msg .= sp_text('Post').": ".$eol.$post_content.$eol.$eol;

		foreach($admins_email as $id=>$email) {

			$newmsg = apply_filters('sph_admin_email', $msg, $newpost, $id);
			sp_send_email($email, $newpost['email_prefix'].sp_text('Forum Post').': '.substr($topicname, 0, 30).'...'.' ['.get_option('blogname').']', $newmsg);

		}
		$out = '- '.sp_text('Notified: Administrators/Moderators');
	}

	$out = apply_filters('sph_new_post_notifications', $out, $newpost);

	return $out;
}

# Save to Admins Queue if needed ---------------------------------------------------
function sp_add_to_waiting($topicid, $forumid, $postid, $userid) {
	global $spThisUser;

	if ($spThisUser->admin || $spThisUser->moderator) return;

	$add = apply_filters('sph_add_to_waiting', false);
	if (!$add) return;

	if ($spThisUser->guest) $userid=0;

	# first is this topic already in waiting?
	$result = spdb_table(SFWAITING, "topic_id=$topicid", 'row');
	if ($result) {
		# add one to post count
		$pcount = ($result->post_count + 1);
		$sql = 'UPDATE '.SFWAITING.' SET ';
		$sql.= 'post_count='.$pcount." ".', user_id='.$userid.' ';
		$sql.= 'WHERE topic_id='.$topicid.';';
		spdb_query($sql);
	} else {
		# else a new record
		$pcount = 1;
		$sql =  "INSERT INTO ".SFWAITING." ";
		$sql.= "(topic_id, forum_id, post_id, user_id, post_count) ";
		$sql.= "VALUES (";
		$sql.= $topicid.", ";
		$sql.= $forumid.", ";
		$sql.= $postid.", ";
		$sql.= $userid.", ";
		$sql.= $pcount.");";
		spdb_query($sql);
	}
}

# = SPAM MATH CHECK ===========================
function sp_check_spammath($forumid) {
	# Spam Check
	$spamtest = array();
	$spamtest[0] = false;
	$usemath = true;
	if (sp_get_auth('bypass_spam_control', $forumid) == false) {
		$spamtest = sp_spamcheck();
	}
	return $spamtest;
}

# = COOKIE HANDLING ===========================
function sp_write_guest_cookie($guestname, $guestemail) {
	$cookiepath = '/';
	setcookie('guestname_'.COOKIEHASH, $guestname, time() + 30000000, $cookiepath, false);
	setcookie('guestemail_'.COOKIEHASH, $guestemail, time() + 30000000, $cookiepath, false);
	setcookie('sflast_'.COOKIEHASH, time(), time() + 30000000, $cookiepath, false);
}

?>