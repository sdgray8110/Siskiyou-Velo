<?php
/*
Simple:Press
Forum Tools Links
$LastChangedDate: 2012-05-18 15:20:11 -0700 (Fri, 18 May 2012) $
$Rev: 8531 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

sp_forum_api_support();

$fid = '';
if (isset($_GET['forum'])) $fid = sp_esc_int($_GET['forum']);

# get out of here if no action specified
if (empty($_GET['action'])) die();
$action = $_GET['action'];

if ($action == 'topictools') {
	# admins topic tool set
	$tid = sp_esc_int($_GET['topic']);
	$page = sp_esc_int($_GET['page']);
    if (empty($fid) || empty($tid)) die();
	$forum = spdb_table(SFFORUMS, "forum_id=$fid", 'row', '', '', ARRAY_A);
	$topic = spdb_table(SFTOPICS, "topic_id=$tid", 'row', '', '', ARRAY_A);
	echo sp_render_topic_tools($topic, $forum, $page);
	die();
}

if ($action == 'posttools') {
	# admins post tool set
	$postid = sp_esc_int($_GET['post']);
	$page = sp_esc_int($_GET['page']);
	$postnum = sp_esc_int($_GET['postnum']);
	$displayname = esc_html(urldecode($_GET['name']));
	$last = sp_esc_int($_GET['last']);
    if (empty($postid)) die();
	$post = spdb_table(SFPOSTS, "post_id=$postid", 'row', '', '', ARRAY_A);
	$forum = spdb_table(SFFORUMS, "forum_id=".$post['forum_id'], 'row', '', '', ARRAY_A);
	$topic = spdb_table(SFTOPICS, "topic_id=".$post['topic_id'], 'row', '', '', ARRAY_A);

	# establish email
	if ($post['user_id']==NULL || $post['user_id']==0) {
		$useremail = '';
		$guestemail = sp_filter_email_display($post['guest_email']);
	} else {
		$useremail = sp_filter_email_display(spdb_table(SFUSERS, 'ID='.$post['user_id'], 'user_email'));
		$guestemail = '';
	}
	echo sp_render_post_tools($post, $forum, $topic, $page, $postnum, $useremail, $guestemail, $displayname, $last);
	die();
}
die();

# Forum Tools - Topic View
function sp_render_post_tools($post, $forum, $topic, $page, $postnum, $useremail, $guestemail, $displayname, $last) {
	global $spThisUser;

    $out = '';

	$out.= '<div id="spMainContainer" class="spForumToolsPopup">';

	$out.= '<div class="spForumToolsHeader">';
	$out.= '<div class="spForumToolsHeaderTitle">'.sp_filter_title_display($topic['topic_name']).'</div>';
	$out.= '<div class="spForumToolsHeaderTitle">'.sp_text('Post').' #'.$postnum.'</div>';
	$out.= '</div>';

	if (($post['post_status'] != 0) && sp_get_auth('moderate_posts', $forum['forum_id'])) {
		$out.= '<div class="spForumToolsModerate">';
		$out.= '<img class="spIcon" src="'.SPTHEMEICONSURL.'sp_ToolsApprove.png" alt="" title="" />';
		$out.= '<a href="javascript:document.postapprove'.$post['post_id'].'.submit();">'.sp_text('Approve this post').'</a>';
		$out.= '<form action="'.sp_build_url($forum['forum_slug'], $topic['topic_slug'], $page, $post['post_id'], $post['post_index']).'" method="post" name="postapprove'.$post['post_id'].'">';
		$out.= '<input type="hidden" name="approvepost" value="'.$post['post_id'].'" />';
		$out.= '</form>';
		$out.= '</div>';
	}

	if (sp_get_auth('view_email', $forum['forum_id'])) {
		$email = (!empty($useremail)) ? $useremail : $guestemail;
		$content = '';
		if ($post['user_id']) {
			$content.= '<div>'.sp_text('User ID').': '.$post['user_id'].' - '.$displayname.'</div>';
		} else {
			$content.= '<div>'.sp_text('Guest').'</div>';
		}
		$content.= '<div>'.$email.'</div><div>'.$post['poster_ip'].'</div>';
		$out.= '<div class="spForumToolsEmail">';
		$out.= '<img class="spIcon" src="'.SPTHEMEICONSURL.'sp_ToolsEmail.png" alt="" title="" />';
        $title = sp_text("Users email and IP");
		$out.= '<a href="javascript:void(null)" onclick="spjDialogHtml(this, \''.$content.'\', \''.esc_js($title).'\', 300, 0, \'center\');">'.$title.'</a>';
		$out.= '</div>';
	}

	if (sp_get_auth('pin_posts', $forum['forum_id'])) {
		$pintext = ($post['post_pinned']) ? sp_text('Unpin this post') : sp_text('Pin this post');
		$out.= '<div class="spForumToolsPin">';
		$out.= '<img class="spIcon" src="'.SPTHEMEICONSURL.'sp_ToolsPin.png" alt="" title="" />';
		$out.= '<a href="javascript:document.postpin'.$post['post_id'].'.submit();">'.$pintext.'</a>';
		$out.= '<form action="'.sp_build_url($forum['forum_slug'], $topic['topic_slug'], $page, $post['post_id'], $post['post_index']).'" method="post" name="postpin'.$post['post_id'].'">';
		$out.= '<input type="hidden" name="pinpost" value="'.$post['post_id'].'" />';
		$out.= '<input type="hidden" name="pinpostaction" value="'.esc_attr($pintext).'" />';
		$out.= '</form>';
		$out.= '</div>';
	}

	if ($spThisUser->admin) {
		$out.= '<div class="spForumToolsOrder">';
		$out.= '<img class="spIcon" src="'.SPTHEMEICONSURL.'sp_ToolsSort.png" alt="" title="" />';
        $site = SFHOMEURL.'index.php?sp_ahah=admintools&amp;sfnonce='.wp_create_nonce('forum-ahah').'&amp;action=sort-topic&amp;topicid='.$topic['topic_id'];
		$out.= '<a href="javascript:void(null)" onclick="spjLoadTool(\''.$site.'\', \'spMainContainer\', \'\');">'.sp_text('Reverse sort this topic').'</a>';
		$out.= '</div>';
	}

	if (sp_get_auth('edit_any_post', $forum['forum_id']) ||
	   ($post['user_id'] == $spThisUser->ID && (sp_get_auth('edit_own_posts_forever', $forum['forum_id']) || (sp_get_auth('edit_own_posts_reply', $forum['forum_id']) && $last)))) {
		$out.= '<div class="spForumToolsEdit">';
		$out.= '<img class="spIcon" src="'.SPTHEMEICONSURL.'sp_ToolsEdit.png" alt="" title="" />';
		$out.= '<a href="javascript:document.admineditpost'.$post['post_id'].'.submit();">'.sp_text('Edit this post').'</a>';
		$out.= '<form action="'.sp_build_url($forum['forum_slug'], $topic['topic_slug'], $page, $post['post_id'], $post['post_index']).'" method="post" name="admineditpost'.$post['post_id'].'">';
		$out.= '<input type="hidden" name="postedit" value="'.$post['post_id'].'" />';
		$out.= '</form>';
		$out.= '</div>';
	}

	if (sp_get_auth('delete_any_post', $post['forum_id']) || sp_get_auth('delete_own_posts', $forum['forum_id']) && $spThisUser->ID == $post['user_id']) {
		$out.= '<div class="spForumToolsDelete">';
		$out.= '<img class="spIcon" src="'.SPTHEMEICONSURL.'sp_ToolsDelete.png" alt="" title="" />';
		$msg = esc_js(sp_text('Are you sure you want to delete this post?'));
		$out.= '<a href="javascript: if(confirm(\''.$msg.'\')) {document.postkill'.$post['post_id'].'.submit();}">'.sp_text('Delete this post').'</a>';
		$out.= '<form action="'.sp_build_url($forum['forum_slug'], $topic['topic_slug'], $page, 0).'" method="post" name="postkill'.$post['post_id'].'">';
		$out.= '<input type="hidden" name="killpost" value="'.$post['post_id'].'" />';
		$out.= '<input type="hidden" name="killposttopic" value="'.$post['topic_id'].'" />';
		$out.= '<input type="hidden" name="killpostforum" value="'.$post['forum_id'].'" />';
		$out.= '<input type="hidden" name="killpostposter" value="'.$post['user_id'].'" />';
		$out.= '</form>';
		$out.= '</div>';
	}
//==>
	if (sp_get_auth('move_posts', $post['forum_id'])) {
		$out.= '<div class="spForumToolsMove">';
		$out.= '<img class="spIcon" src="'.SPTHEMEICONSURL.'sp_ToolsMove.png" alt="" title="" />';
        $site = SFHOMEURL.'index.php?sp_ahah=admintools&amp;sfnonce='.wp_create_nonce('forum-ahah').'&amp;action=move-post&amp;id='.$post['topic_id'].'&amp;pid='.$post['post_id'].'&amp;pix='.$post['post_index'];
        $title = sp_text('Move this post');
		$out.= '<a rel="nofollow" href="javascript:void(null)" onclick="spjDialogAjax(this, \''.$site.'\', \''.esc_js($title).'\', 400, 0, \'center\');">'.$title.'</a>';
		$out.= '</div>';
	}
//==>
	if (sp_get_auth('reassign_posts', $post['forum_id'])) {
		$out.= '<div class="spForumToolsReassign">';
		$out.= '<img class="spIcon" src="'.SPTHEMEICONSURL.'sp_ToolsReassign.png" alt="" title="" />';
        $site = SFHOMEURL.'index.php?sp_ahah=admintools&amp;sfnonce='.wp_create_nonce('forum-ahah').'&amp;action=reassign&amp;id='.$post['topic_id'].'&amp;pid='.$post['post_id'].'&amp;uid='.$post['user_id'];
        $title = sp_text('Reassign This Post');
		$out.= '<a rel="nofollow" href="javascript:void(null)" onclick="spjDialogAjax(this, \''.$site.'\', \''.esc_js($title).'\', 400, 0, \'center\');">'.$title.'</a>';
		$out.= '</div>';
	}

	if ($spThisUser->admin || $spThisUser->moderator) {
		$out.= '<div class="spForumToolsProperties">';
		$out.= '<img class="spIcon" src="'.SPTHEMEICONSURL.'sp_ToolsProperties.png" alt="" title="" />';
        $site = SFHOMEURL.'index.php?sp_ahah=admintools&amp;sfnonce='.wp_create_nonce('forum-ahah').'&amp;action=properties&amp;forum='.$post['forum_id'].'&amp;topic='.$post['topic_id'].'&amp;post='.$post['post_id'];
        $title = sp_text('View properties');
		$out.= '<a rel="nofollow" href="javascript:void(null)" onclick="spjDialogAjax(this, \''.$site.'\', \''.esc_js($title).'\', 400, 0, \'center\');">'.$title.'</a>';
		$out.= '</div>';
	}

	$out = apply_filters('sph_add_post_tool', $out, $post, $forum, $topic, $page, $postnum, $useremail, $guestemail, $displayname);

	$out.= '</div>';
	$out = apply_filters('sph_post_tools', $out, $post, $forum, $topic, $page, $postnum, $useremail, $guestemail, $displayname);

	return $out;
}

# Forum Tools - Forum View
function sp_render_topic_tools($topic, $forum, $page) {
	global $spThisUser;

    $out = '';

	$topicname = urlencode(sp_filter_title_display($topic['topic_name']));

	$out.= '<div id="spMainContainer" class="spForumToolsPopup">';

	$out.= '<div class="spForumToolsHeader">';
	$out.= '<div class="spForumToolsHeaderTitle">'.sp_filter_title_display($topic['topic_name']).'</div>';
	$out.= '</div>';

	if (sp_get_auth('lock_topics', $forum['forum_id'])) {
		$out.= '<div class="spForumToolsLock">';
		$locktext = ($topic['topic_status']) ? sp_text('Unlock this topic') : sp_text('Lock this topic');
		$out.= '<img class="spIcon" src="'.SPTHEMEICONSURL.'sp_ToolsLock.png" alt="" title="" />';
		$out.= '<a href="javascript:document.topiclock'.$topic['topic_id'].'.submit();">'.$locktext.'</a>';
		$out.= '<form action="'.sp_build_url($forum['forum_slug'], '', $page, 0).'" method="post" name="topiclock'.$topic['topic_id'].'">';
		$out.= '<input type="hidden" name="locktopic" value="'.$topic['topic_id'].'" />';
		$out.= '<input type="hidden" name="locktopicaction" value="'.esc_attr($locktext).'" />';
		$out.= '</form>';
		$out.= '</div>';
	}

	if (sp_get_auth('pin_topics', $forum['forum_id'])) {
		$out.= '<div class="spForumToolsPin">';
		$pintext = ($topic['topic_pinned']) ? sp_text('Unpin this topic') : sp_text('Pin this topic');
		$out.= '<img class="spIcon" src="'.SPTHEMEICONSURL.'sp_ToolsPin.png" alt="" title="" />';
		$out.= '<a href="javascript:document.topicpin'.$topic['topic_id'].'.submit();">'.$pintext.'</a>';
		$out.= '<form action="'.sp_build_url($forum['forum_slug'], '', $page, 0).'" method="post" name="topicpin'.$topic['topic_id'].'">';
		$out.= '<input type="hidden" name="pintopic" value="'.$topic['topic_id'].'" />';
		$out.= '<input type="hidden" name="pintopicaction" value="'.esc_attr($pintext).'" />';
		$out.= '</form>';
		$out.= '</div>';
	}

	if ($spThisUser->admin) {
		$out.= '<div class="spForumToolsOrder">';
		$out.= '<img class="spIcon" src="'.SPTHEMEICONSURL.'sp_ToolsSort.png" alt="" title="" />';
        $site = SFHOMEURL.'index.php?sp_ahah=admintools&amp;sfnonce='.wp_create_nonce('forum-ahah').'&amp;action=sort-forum&amp;forumid='.$forum['forum_id'];
		$out.= '<a href="javascript:void(null)" onclick="spjLoadTool(\''.$site.'\', \'spMainContainer\', \'\');">'.sp_text('Reverse sort this forum').'</a>';
		$out.= '</div>';
	}

	if ((sp_get_auth('edit_own_topic_titles', $forum['forum_id']) && $topic['user_id'] == $spThisUser->ID) || sp_get_auth('edit_any_topic_titles', $forum['forum_id'])) {
		$out.= '<div class="spForumToolsEdit">';
		$out.= '<img class="spIcon" src="'.SPTHEMEICONSURL.'sp_ToolsEdit.png" alt="" title="" />';
        $site = SFHOMEURL.'index.php?sp_ahah=admintools&amp;sfnonce='.wp_create_nonce('forum-ahah').'&amp;action=edit-title&amp;topicid='.$topic['topic_id'].'&amp;forumid='.$forum['forum_id'].'&amp;userid='.$topic['user_id'];
        $title = sp_text('Edit topic title');
		$out.= '<a rel="nofollow" href="javascript:void(null)" onclick="spjDialogAjax(this, \''.$site.'\', \''.esc_js($title).'\', 400, 0, \'center\');">'.$title.'</a>';
		$out.= '</div>';
	}

	if (sp_get_auth('delete_topics', $forum['forum_id'])) {
		$out.= '<div class="spForumToolsDelete">';
		$out.= '<img class="spIcon" src="'.SPTHEMEICONSURL.'sp_ToolsDelete.png" alt="" title="" />';
		$msg = esc_js(sp_text('Are you sure you want to delete this topic?'));
		$out.= '<a href="javascript: if(confirm(\''.$msg.'\')) {document.topickill'.$topic['topic_id'].'.submit();}">'.sp_text('Delete this topic').'</a>';
		$out.= '<form action="'.sp_build_url($forum['forum_slug'], '', $page, 0).'" method="post" name="topickill'.$topic['topic_id'].'">';
		$out.= '<input type="hidden" name="killtopic" value="'.$topic['topic_id'].'" />';
		$out.= '<input type="hidden" name="killtopicforum" value="'.$forum['forum_id'].'" />';
		$out.= '</form>';
		$out.= '</div>';
	}

	if (sp_get_auth('move_topics', $forum['forum_id'])) {
		$out.= '<div class="spForumToolsMove">';
		$out.= '<img class="spIcon" src="'.SPTHEMEICONSURL.'sp_ToolsMove.png" alt="" title="" />';
        $site = SFHOMEURL.'index.php?sp_ahah=admintools&amp;sfnonce='.wp_create_nonce('forum-ahah').'&amp;action=move-topic&amp;topicid='.$topic['topic_id'].'&amp;forumid='.$forum['forum_id'].'&amp;topicname='.$topicname;
        $title = sp_text('Move this topic');
		$out.= '<a rel="nofollow" href="javascript:void(null)" onclick="spjDialogAjax(this, \''.$site.'\', \''.esc_js($title).'\', 400, 0, \'center\');">'.$title.'</a>';
		$out.= '</div>';
	}

	if ($spThisUser->admin || $spThisUser->moderator) {
		$out.= '<div class="spForumToolsProperties">';
		$out.= '<img class="spIcon" src="'.SPTHEMEICONSURL.'sp_ToolsProperties.png" alt="" title="" />';
        $title = sp_text('View properties');
        $site = SFHOMEURL.'index.php?sp_ahah=admintools&amp;sfnonce='.wp_create_nonce('forum-ahah').'&amp;action=properties&amp;group='.$forum['group_id'].'&amp;forum='.$forum['forum_id'].'&amp;topic='.$topic['topic_id'].'&amp;topicname='.$topicname;
		$out.= '<a rel="nofollow" href="javascript:void(null)" onclick="spjDialogAjax(this, \''.$site.'\', \''.esc_js($title).'\', 400, 0, \'center\');">'.$title.'</a>';
		$out.= '</div>';
	}

	$out = apply_filters('sph_add_topic_tool', $out, $topic, $forum, $page);

	$out.= '</div>';
	$out = apply_filters('sph_topic_tools', $out, $topic, $forum, $page);

	return $out;
}

die();
?>