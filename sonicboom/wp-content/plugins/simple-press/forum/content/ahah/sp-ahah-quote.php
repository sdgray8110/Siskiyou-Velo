<?php
/*
Simple:Press
Quote handing for posts
$LastChangedDate: 2012-03-30 10:19:35 -0700 (Fri, 30 Mar 2012) $
$Rev: 8248 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

sp_forum_api_support();
sp_load_editor(0,1);

$postid = sp_esc_int($_GET['post']);
$forumid = sp_esc_int($_GET['forumid']);
if (empty($forumid) || empty($postid)) die();

if (!sp_get_auth('reply_topics', $forumid)) {
	if (!is_user_logged_in()) {
		sp_etext('Access denied - are you logged in?');
	} else {
		sp_etext('Access denied - you do not have permission');
	}
	die();
}

$content = spdb_table(SFPOSTS, "post_id=$postid", 'post_content');
$content = sp_filter_content_edit($content, 'edit');
echo $content;

die();
?>