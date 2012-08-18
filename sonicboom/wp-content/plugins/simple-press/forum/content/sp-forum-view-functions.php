<?php
/*
Simple:Press
TForum View Function Handler
$LastChangedDate: 2012-06-03 10:30:31 -0700 (Sun, 03 Jun 2012) $
$Rev: 8646 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');


# ======================================================================================
#
# FORUM VIEW
# Sub-Forum Head Functions
#
# ======================================================================================

# --------------------------------------------------------------------------------------
#
#	sp_SubForumHeaderDescription()
#	Display SubForum Description in Header
#	Scope:	SubForum Loop
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_SubForumHeaderDescription($args='', $label='') {
	$defs = array('tagId' 		=> 'spSubForumHeaderDescription',
				  'tagClass' 	=> 'spHeaderDescription',
				  'echo'		=> 1,
				  'get'			=> 0,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_SubForumHeaderDescription_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId		= esc_attr($tagId);
	$tagClass	= esc_attr($tagClass);
	$echo		= (int) $echo;
	$get		= (int) $get;

	$label = sp_filter_title_display($label);

	if ($get) return $label;

	$out = (empty($label)) ? '' : "<div id='$tagId' class='$tagClass'>$label</div>\n";
	$out = apply_filters('sph_SubForumHeaderDescription', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_SubForumIndexIcon()
#	Display Forum Icon
#	Scope:	Forum sub Loop
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_SubForumIndexIcon($args='') {
	global $spThisForum, $spThisSubForum;

	$defs = array('tagId' 		=> 'spSubForumIndexIcon%ID%',
				  'tagClass' 	=> 'spRowIcon',
				  'icon' 		=> 'sp_ForumIcon.png',
				  'iconUnread'	=> 'sp_ForumIcon.png',
				  'echo'		=> 1,
				  'get'			=> 0,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_SubForumIndexIcon_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId		= esc_attr($tagId);
	$tagClass	= esc_attr($tagClass);
	$echo		= (int) $echo;
	$get		= (int) $get;

	$tagId = str_ireplace('%ID%', $spThisSubForum->forum_id, $tagId);

	# Check if a custom icon
	if (!empty($spThisSubForum->forum_icon)) {
		$icon = SFCUSTOMURL.$spThisSubForum->forum_icon;
	} else {
		if ($spThisSubForum->unread) $icon = $iconUnread;
		$icon = SPTHEMEICONSURL.sanitize_file_name($icon);
	}

	if ($get) return $icon;

	$out = "<img id='$tagId' class='$tagClass' src='$icon' alt='' />\n";
	$out = apply_filters('sph_SubForumIndexIcon', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_SubForumIndexName()
#	Display Forum Name/Title in Header
#	Scope:	Forumn sub Loop
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_SubForumIndexName($args='', $toolTip='') {
	global $spThisForum, $spThisSubForum;
	$defs = array('tagId'    	=> 'spSubForumIndexName%ID%',
			      'tagClass' 	=> 'spRowName',
			      'truncate'	=> 0,
				  'echo'		=> 1,
				  'get'			=> 0,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_SubForumIndexName_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId		= esc_attr($tagId);
	$tagClass	= esc_attr($tagClass);
	$toolTip	= esc_attr($toolTip);
	$truncate	= (int) $truncate;
	$echo		= (int) $echo;
	$get		= (int) $get;

	$tagId = str_ireplace('%ID%', $spThisSubForum->forum_id, $tagId);
	$toolTip = str_ireplace('%NAME%', htmlspecialchars($spThisSubForum->forum_name, ENT_QUOTES), $toolTip);

	if ($get) return sp_truncate($spThisSubForum->forum_name, $truncate);

	$out = "<a href='$spThisSubForum->forum_permalink' id='$tagId' class='$tagClass vtip' title='$toolTip'>".sp_truncate($spThisSubForum->forum_name, $truncate)."</a>\n";
	$out = apply_filters('sph_SubForumIndexName', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_SubForumIndexDescription()
#	Display Forum Description in Header
#	Scope:	Forum sub Loop
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_SubForumIndexDescription($args='') {
	global $spThisForum, $spThisSubForum;
	$defs = array('tagId'    	=> 'spSubForumIndexDescription%ID%',
			      'tagClass' 	=> 'spRowDescription',
				  'echo'		=> 1,
				  'get'			=> 0,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ForumDescription_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId		= esc_attr($tagId);
	$tagClass	= esc_attr($tagClass);
	$echo		= (int) $echo;
	$get		= (int) $get;

	$tagId = str_ireplace('%ID%', $spThisSubForum->forum_id, $tagId);

	if ($get) return $spThisSubForum->forum_desc;

	$out = (empty($spThisSubForum->forum_desc)) ? '' : "<div id='$tagId' class='$tagClass'>$spThisSubForum->forum_desc</div>\n";
	$out = apply_filters('sph_SubForumIndexDescription', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_SubForumIndexPageLinks()
#	Display Forum 'in row' page links
#	Scope:	Forum sub Loop
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_SubForumIndexPageLinks($args='', $toolTip='') {
	global $spThisForum, $spThisSubForum, $sfglobals;

	$topics_per_page = $sfglobals['display']['topics']['perpage'];
	if ($topics_per_page >= $spThisSubForum->topic_count) return '';

	$defs = array('tagId'    		=> 'spSubForumIndexPageLinks%ID%',
				  'tagClass' 		=> 'spInRowPageLinks',
				  'icon'			=> 'sp_ArrowRightSmall.png',
				  'iconClass'		=> 'spIconSmall',
				  'pageLinkClass'	=> 'spInRowForumPageLink',
				  'showLinks'		=> 4,
				  'echo'			=> 1,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_SubForumIndexPageLinks_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId			= esc_attr($tagId);
	$tagClass		= esc_attr($tagClass);
	$icon			= sanitize_file_name($icon);
	$iconClass		= esc_attr($iconClass);
	$pageLinkClass	= esc_attr($pageLinkClass);
	$showLinks		= (int) $showLinks;
	$toolTip		= esc_attr($toolTip);
	$echo			= (int) $echo;

	$tagId = str_ireplace('%ID%', $spThisSubForum->forum_id, $tagId);

	$out = "<div id='$tagId' class='$tagClass'>\n";
	$total_pages=($spThisSubForum->topic_count / $topics_per_page);
	if (!is_int($total_pages)) $total_pages=intval($total_pages) + 1;
	($total_pages > $showLinks ? $max_count = $showLinks : $max_count = $total_pages);
	for ($x = 1; $x <= $max_count; $x++) {
		$out.= "<a class='$pageLinkClass vtip' href='".sp_build_url($spThisSubForum->forum_slug, '', $x, 0)."' title='".str_ireplace('%PAGE%', $x, $toolTip)."'>$x</a>\n";
	}
	if ($total_pages > $showLinks) {
		$out.= "<img src='".SPTHEMEICONSURL.$icon."' class='$iconClass' alt=''/>\n";
		$out.= "<a class='$pageLinkClass vtip' href='".sp_build_url($spThisSubForum->forum_slug, '', $total_pages, 0)."' title='".str_ireplace('%PAGE%', $total_pages, $toolTip)."'>$total_pages</a>\n";
	}
	$out.= "</div>\n";

	$out = apply_filters('sph_SubForumIndexPageLinks', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_SubForumIndexModerators()
#	Display Forum moderators
#	Scope:	Forum sub Loop
#	Version: 5.2
#
# --------------------------------------------------------------------------------------
function sp_SubForumIndexModerators($args='', $label='') {
	global $sfglobals, $spThisForum;
	$defs = array('tagId'    		=> 'spSubForumModerators%ID%',
				  'tagClass' 		=> 'spSubForumModeratorList',
				  'listClass'		=> 'spInRowLabel',
				  'labelClass'		=> 'spRowDescription',
                  'showEmpty'       => 0,
				  'echo'			=> 1,
				  'get'				=> 0,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_SubForumIndexModerators_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId			= esc_attr($tagId);
	$tagClass		= esc_attr($tagClass);
	$listClass		= esc_attr($listClass);
	$labelClass		= esc_attr($labelClass);
	$showEmpty		= (int) $showEmpty;
	$echo			= (int) $echo;
	$get			= (int) $get;

	$tagId = str_ireplace('%ID%', $spThisForum->forum_id, $tagId);

	$mods = $sfglobals['forum_moderators']['users'][$spThisForum->forum_id];
	if ($get) return $mods;

    # build mods list with name display
    if (!empty($mods)) {
        $modList = '';
        $first = true;
        foreach ($mods as $mod) {
            if (!$first) $modList.= ', ';
            $first = false;
            $modList.= sp_build_name_display($mod['user_id'], $mod['display_name'], true);
        }
    } else if ($showEmpty) {
        $modList = 'none';
    } else {
        return '';
    }

	$out = "<div id='$tagId' class='$tagClass'>\n";
	$out.= "<span class='$labelClass'>".sp_filter_title_display($label)."</span>\n";
	$out.= "<span class='$listClass'>$modList</span>\n";
	$out.= "</div>\n";
	$out = apply_filters('sph_SubForumIndexModerators', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_SubForumIndexLastPost()
#	Display Forum 'in row' link to the last post made to a topic in this forum
#	Scope:	Forum sub Loop
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_SubForumIndexLastPost($args='', $label='') {
	global $spThisForum, $spThisSubForum;

	if ($spThisSubForum->post_count == 0) return;

	$defs = array('tagId'    		=> 'spSubForumIndexLastPost%ID%',
				  'tagClass' 		=> 'spInRowPostLink',
				  'labelClass'		=> 'spInRowLabel',
				  'infoClass'		=> 'spInRowInfo',
				  'linkClass'		=> 'spInRowLastPostLink',
				  'includeSubs'		=> 1,
				  'tip'   			=> 1,
				  'nicedate'		=> 1,
				  'date'  			=> 0,
				  'time'  			=> 0,
				  'stackdate'		=> 0,
				  'user'  			=> 1,
				  'truncate'		=> 0,
				  'truncateUser'	=> 0,
				  'echo'			=> 1,
				  'get'				=> 0,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_SubForumIndexLastPost_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId			= esc_attr($tagId);
	$tagClass		= esc_attr($tagClass);
	$labelClass		= esc_attr($labelClass);
	$infoClass		= esc_attr($infoClass);
	$linkClass		= esc_attr($linkClass);
	$includeSubs	= (int) $includeSubs;
	$tip			= (int) $tip;
	$nicedate		= (int) $nicedate;
	$date			= (int) $date;
	$time			= (int) $time;
	$stackdate		= (int) $stackdate;
	$user			= (int) $user;
	$truncate		= (int) $truncate;
	$truncateUser	= (int) $truncateUser;
	$echo			= (int) $echo;
	$get			= (int) $get;

	if($includeSubs && $spThisSubForum->forum_id_sub == 0) $includeSubs = 0;

	$tagId = str_ireplace('%ID%', $spThisSubForum->forum_id, $tagId);
	$posttip = ($includeSubs ? $spThisSubForum->post_tip_sub : $spThisSubForum->post_tip);
	if ($tip && !empty($posttip)) {
		$title = "title='$posttip'";
		$linkClass.= ' vtip';
	} else {
		$title='';
	}

	($stackdate ? $dlb='<br />' : $dlb=' - ');

	$out = "<div id='$tagId' class='$tagClass'>\n";
	$out.= "<span class='$labelClass'>".sp_filter_title_display($label)." \n";

	# user
	$poster = ($includeSubs ? sp_build_name_display($spThisSubForum->user_id_sub, sp_truncate($spThisSubForum->display_name_sub, $truncateUser), true) : sp_build_name_display($spThisSubForum->user_id, sp_truncate($spThisSubForum->display_name, $truncateUser), true));
	if (empty($poster)) $poster = ($includeSubs ? sp_truncate($spThisSubForum->guest_name_sub, $truncateUser) : sp_truncate($spThisSubForum->guest_name, $truncateUser));
	if ($user) $out.= $poster;

	# other items
	$permalink = ($includeSubs ? $spThisSubForum->post_permalink_sub : $spThisSubForum->post_permalink);
	$topicname = ($includeSubs ? sp_truncate($spThisSubForum->topic_name_sub, $truncate) : sp_truncate($spThisSubForum->topic_name, $truncate));
	$postdate  = ($includeSubs ? $spThisSubForum->post_date_sub : $spThisSubForum->post_date);

	if ($get) {
		$getData = new stdClass();
		$getData->permalink = $permalink;
		$getData->topic_name = $topicname;
		$getData->post_date = $postdate;
		$getData->user = $poster;
		return $getData;
	}

	$out.= "</span>\n";

	# Link to post
	$out.= "<br /><span><a class='$linkClass' $title href='$permalink'>$topicname</a></span>\n";

	# date/time
	if ($nicedate) {
		$out.= "<br /><span class='$labelClass'>".sp_nicedate($postdate)."</span>\n";
	} else {
		if($date) {
			$out.= "<br /><span class='$labelClass'>".sp_date('d', $postdate);
			if($time) {
				$out.= $dlb.sp_date('t', $postdate);
			}
			$out.= "</span>\n";
		}
	}
	$out.= "</div>\n";
	$out = apply_filters('sph_SubForumIndexLastPost', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_SubForumIndexTopicCount()
#	Display Forum 'in row' total topic count
#	Scope:	Forum sub Loop
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_SubForumIndexTopicCount($args='', $label='') {
	global $spThisForum, $spThisSubForum;
	$defs = array('tagId'    		=> 'spSubForumIndexTopicCount%ID%',
				  'tagClass' 		=> 'spInRowCount',
				  'labelClass'		=> 'spInRowLabel',
				  'numberClass'		=> 'spInRowNumber',
				  'includeSubs'		=> 1,
				  'stack'			=> 0,
				  'echo'			=> 1,
				  'get'				=> 0,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_SubForumIndexTopicCount_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId			= esc_attr($tagId);
	$tagClass		= esc_attr($tagClass);
	$labelClass		= esc_attr($labelClass);
	$numberClass	= esc_attr($numberClass);
	$includeSubs	= (int) $includeSubs;
	$stack			= (int) $stack;
	$echo			= (int) $echo;
	$get			= (int) $get;

	if($includeSubs && $spThisSubForum->forum_id_sub == 0) $includeSubs = 0;

	$tagId = str_ireplace('%ID%', $spThisSubForum->forum_id, $tagId);
	($stack ? $att='<br />' : $att= ' ');

	$data = ($includeSubs ? $spThisSubForum->topic_count_sub : $spThisSubForum->topic_count);
	if ($get) return $data;

	$out = "<div id='$tagId' class='$tagClass'>\n";
	$out.= "<span class='$labelClass'>".sp_filter_title_display($label)."$att</span>\n";
	$out.= "<span class='$numberClass'>$data</span>\n";
	$out.= "</div>\n";
	$out = apply_filters('sph_SubForumIndexTopicCount', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_SubForumIndexPostCount()
#	Display Forum 'in row' total post count
#	Scope:	Forum sub Loop
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_SubForumIndexPostCount($args='', $label='') {
	global $spThisForum, $spThisSubForum;
	$defs = array('tagId'    		=> 'spSubForumIndexPostCount%ID%',
				  'tagClass' 		=> 'spInRowCount',
				  'labelClass'		=> 'spInRowLabel',
				  'numberClass'		=> 'spInRowNumber',
				  'includeSubs'		=> 1,
				  'stack'			=> 0,
				  'echo'			=> 1,
				  'get'				=> 0,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_SubForumIndexPostCount_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId			= esc_attr($tagId);
	$tagClass		= esc_attr($tagClass);
	$labelClass		= esc_attr($labelClass);
	$numberClass	= esc_attr($numberClass);
	$includeSubs	= (int) $includeSubs;
	$stack			= (int) $stack;
	$echo			= (int) $echo;
	$get			= (int) $get;

	if($includeSubs && $spThisSubForum->forum_id_sub == 0) $includeSubs = 0;

	$tagId = str_ireplace('%ID%', $spThisSubForum->forum_id, $tagId);
	($stack ? $att='<br />' : $att= ' ');

	$data = ($includeSubs ? $spThisSubForum->post_count_sub : $spThisSubForum->post_count);
	if ($get) return $data;

	$out = "<div id='$tagId' class='$tagClass'>\n";
	$out.= "<span class='$labelClass'>".sp_filter_title_display($label)."$att</span>\n";
	$out.= "<span class='$numberClass'>$data</span>\n";
	$out.= "</div>\n";
	$out = apply_filters('sph_SubForumIndexPostCount', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_SubForumIndexStatusIcons()
#	Display Forum Status (Locked/New Post/Blank)
#	Scope:	Forum sub Loop
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_SubForumIndexStatusIcons($args='', $toolTipLock='', $toolTipPost='', $toolTipAdd='') {
	global $spThisForum, $spThisSubForum,$sfglobals;

	$defs = array('tagId' 			=> 'spForumIndexStatus%ID%',
				  'tagClass' 		=> 'spStatusIcon',
				  'iconLock'		=> 'sp_ForumStatusLock.png',
				  'iconPost'		=> 'sp_ForumStatusPost.png',
				  'iconAdd'		    => 'sp_ForumStatusAdd.png',
				  'echo'			=> 1,
				  'get'				=> 0,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_SubForumIndexStatusIcons_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId			= esc_attr($tagId);
	$tagClass		= esc_attr($tagClass);
	$toolTipPost	= esc_attr($toolTipPost);
	$toolTipLock	= esc_attr($toolTipLock);
	$toolTipAdd	    = esc_attr($toolTipAdd);
	$echo			= (int) $echo;
	$get			= (int) $get;

	$tagId = str_ireplace('%ID%', $spThisForum->forum_id, $tagId);

	if ($get) return $spThisSubForum->forum_status;

	# Dislay if locked or new post
	$out = "<div id='$tagId' class='$tagClass'>\n";
	if ($spThisSubForum->forum_status == 1 || $sfglobals['lockdown'] == true) {
		$icon = SPTHEMEICONSURL.sanitize_file_name($iconLock);
		$out.= "<img class='vtip' src='$icon' title='$toolTipLock' alt='' />\n";
	}

	# New Post Popup
	if ($spThisSubForum->unread) {
		$icon = SPTHEMEICONSURL.sanitize_file_name($iconPost);
		$toolTipPost = str_ireplace('%COUNT%', $spThisSubForum->unread, $toolTipPost);

		$site = SFHOMEURL."index.php?sp_ahah=newpostpopup&amp;action=forum&amp;id=$spThisSubForum->forum_id&amp;sfnonce=".wp_create_nonce('forum-ahah');
		$linkId = 'spNewPostPopup'.$spThisSubForum->forum_id;
		$out.= "<a rel='nofollow' id='$linkId' href='javascript:void(null)' onclick='spjDialogAjax(this, \"$site\", \"$toolTipPost\", 600, 0, 0);'>";

		$out.= "<img class='vtip' src='$icon' title='$toolTipPost' alt='' /></a>\n";
	}

    # add new topic icon
	if (sp_get_auth('start_topics', $spThisSubForum->forum_id) && !$spThisSubForum->forum_status && !$sfglobals['lockdown']) {
		$url = sp_build_url($spThisSubForum->forum_slug, '', 1, 0).sp_add_get().'new=topic';
		$out.= "<a href='$url' class='vtip' title='$toolTipAdd'>\n";
		$icon = SPTHEMEICONSURL.sanitize_file_name($iconAdd);
		$out.= "<img src='$icon' alt='' />\n";
		$out.= "</a>\n";
	}
	$out.= "</div>\n";

	$out = apply_filters('sph_SubForumIndexStatusIcons', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ForumHeaderSubForums()
#	Display Sub Forums below parent
#	Scope:	Forum View
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ForumHeaderSubForums($args='', $label='', $toolTip='') {
	global $spThisForum, $spThisForumSubs, $spThisSubForum;

	if (empty($spThisForumSubs) || empty($spThisSubForum->children)) return;

	$defs = array('tagId'    		=> 'spForumHeaderSubForums%ID%',
				  'tagClass' 		=> 'spInRowSubForums',
				  'labelClass'		=> 'spInRowLabel',
				  'linkClass'		=> 'spInRowSubForumlink',
				  'icon'			=> 'sp_SubForumIcon.png',
				  'iconClass'		=> 'spIconSmall',
				  'topicCount'		=> 1,
				  'allNested'		=> 1,
				  'stack'			=> 0,
				  'truncate'		=> 0,
				  'echo'			=> 1,
				  'get'				=> 0
				 );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ForumHeaderSubForums_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId			= esc_attr($tagId);
	$tagClass		= esc_attr($tagClass);
	$labelClass		= esc_attr($labelClass);
	$linkClass		= esc_attr($linkClass);
	$icon			= sanitize_file_name($icon);
	$iconClass		= esc_attr($iconClass);
	$topicCount		= (int) $topicCount;
	$allNested		= (int) $allNested;
	$stack			= (int) $stack;
	$truncate		= (int) $truncate;
	$echo			= (int) $echo;
	$get			= (int) $get;
	$toolTip		= esc_attr($toolTip);

	$thisTagId = str_ireplace('%ID%', $spThisSubForum->forum_id, $tagId);
	if ($get) return $spThisForumSubs;

	$out = "<div id='$thisTagId' class='$tagClass'>\n";
	if($stack) {
		$out.= "<ul class='$labelClass'><li>".sp_filter_title_display($label)."<ul>";
	} else {
		$out.= "<span class='$labelClass'>".sp_filter_title_display($label)."</span>\n";
	}

	foreach ($spThisForumSubs as $sub) {
		if ($sub->parent == $spThisSubForum->forum_id) {
			$thisToolTip = str_ireplace('%NAME%', htmlspecialchars($sub->forum_name, ENT_QUOTES), $toolTip);
			if($stack) $out.= "<li>";
			if (!empty($icon)) $out.= "<img src='".SPTHEMEICONSURL.$icon."' class='$iconClass' alt=''/>\n";
			$thisTagId = str_ireplace('%ID%', $sub->forum_id, $tagId);
			$out.= "<a href='$sub->forum_permalink' id='$thisTagId' class='$linkClass vtip' title='$thisToolTip'>".sp_truncate($sub->forum_name, $truncate)."</a>\n";
			if ($topicCount) $out.= " ($sub->topic_count)\n";
			if($stack) $out.= "</li>";
		}
	}
	if($stack) $out.= "</ul></li></ul>";
	$out.= "</div>\n";
	$out = apply_filters('sph_ForumHeaderSubForums', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# ======================================================================================
#
# FORUM VIEW
# Forum Head Functions
#
# ======================================================================================

# --------------------------------------------------------------------------------------
#
#	sp_ForumHeaderIcon()
#	Display Forum Icon
#	Scope:	Forum View
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ForumHeaderIcon($args='') {
	global $spThisForum;
	$defs = array('tagId' 		=> 'spForumHeaderIcon',
				  'tagClass' 	=> 'spHeaderIcon',
				  'icon' 		=> 'sp_ForumIcon.png',
				  'echo'		=> 1,
				  'get'			=> 0
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ForumHeaderIcon_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId		= esc_attr($tagId);
	$tagClass	= esc_attr($tagClass);
	$echo		= (int) $echo;
	$get		= (int) $get;

	# Check if a custom icon
	if (!empty($spThisForum->forum_icon)) {
		$icon = SFCUSTOMURL.$spThisForum->forum_icon;
	} else {
		$icon = SPTHEMEICONSURL.sanitize_file_name($icon);
	}

	if ($get) return $icon;

	$out = "<img id='$tagId' class='$tagClass' src='$icon' alt='' />\n";
	$out = apply_filters('sph_ForumHeaderIcon', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ForumHeaderName()
#	Display Forum Name/Title in Header
#	Scope:	Forum View
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ForumHeaderName($args='') {
	global $spThisForum;
	$defs = array('tagId' 		=> 'spForumHeaderName',
				  'tagClass' 	=> 'spHeaderName',
				  'truncate'	=> 0,
				  'echo'		=> 1,
				  'get'			=> 0
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ForumHeaderName_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId		= esc_attr($tagId);
	$tagClass	= esc_attr($tagClass);
	$truncate	= (int) $truncate;
	$echo		= (int) $echo;
	$get		= (int) $get;

	if ($get) return $spThisForum->forum_name;

	$out = (empty($spThisForum->forum_name)) ? '' : "<div id='$tagId' class='$tagClass'>".sp_truncate($spThisForum->forum_name, $truncate)."</div>\n";
	$out = apply_filters('sph_ForumHeaderName', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ForumHeaderDescription()
#	Display Forum Description in Header
#	Scope:	Forum View
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ForumHeaderDescription($args='') {
	global $spThisForum;
	$defs = array('tagId' 		=> 'spForumHeaderDescription',
				  'tagClass' 	=> 'spHeaderDescription',
				  'echo'		=> 1,
				  'get'			=> 0
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ForumHeaderDescription_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId		= esc_attr($tagId);
	$tagClass	= esc_attr($tagClass);
	$echo		= (int) $echo;
	$get		= (int) $get;

	if ($get) return $spThisForum->forum_desc;

	$out = (empty($spThisForum->forum_desc)) ? '' : "<div id='$tagId' class='$tagClass'>$spThisForum->forum_desc</div>\n";
	$out = apply_filters('sph_ForumHeaderDescription', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ForumHeaderRSSButton()
#	Display Forum Level RSS Button
#	Scope:	Forum View
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ForumHeaderRSSButton($args='', $label='', $toolTip='') {
	global $spThisForum, $spThisUser;

    if (!sp_get_auth('view_forum', $spThisForum->forum_id) || $spThisForum->forum_rss_private) return;

	$defs = array('tagId' 		=> 'spForumHeaderRSSButton',
				  'tagClass' 	=> 'spButton',
				  'icon' 		=> 'sp_Feed.png',
				  'iconClass'	=> 'spIcon',
				  'echo'		=> 1,
				  'get'			=> 0
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ForumHeaderRSSButton_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId		= esc_attr($tagId);
	$tagClass	= esc_attr($tagClass);
	$icon		= sanitize_file_name($icon);
	$iconClass 	= esc_attr($iconClass);
	$toolTip	= esc_attr($toolTip);
	$echo		= (int) $echo;
	$get		= (int) $get;

	# Get or construct rss url
	if (empty($spThisForum->rss)) {
		$rssOpt = sp_get_option('sfrss');
		if ($rssOpt['sfrssfeedkey'] && isset($spThisUser->feedkey)) {
			$rssUrl = trailingslashit(sp_build_url($spThisForum->forum_slug, '', 0, 0, 0, 1)).user_trailingslashit($spThisUser->feedkey);
		} else {
			$rssUrl = sp_build_url($spThisForum->forum_slug, '', 0, 0, 0, 1);
		}
	} else {
		$rssUrl = $spThisForum->rss;
	}

	if ($get) return $rssUrl;

	$out = "<a class='$tagClass vtip' id='$tagId' title='$toolTip' rel='nofollow' href='$rssUrl'>";
	if (!empty($icon)) $out.= "<img class='$iconClass' src='".SPTHEMEICONSURL.$icon."' alt=''/>";
	if (!empty($label)) $out.= sp_filter_title_display($label);
	$out.= "</a>\n";
	$out = apply_filters('sph_ForumHeaderRSSButton', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ForumHeaderMessage()
#	Display Special Forum Message in Header
#	Scope:	Forum View
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ForumHeaderMessage($args='') {
	global $spThisForum;
	$defs = array('tagId' 		=> 'spForumHeaderMessage%ID%',
				  'tagClass' 	=> 'spHeaderMessage',
				  'echo'		=> 1,
				  'get'			=> 0
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ForumHeaderMessage_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId		= esc_attr($tagId);
	$tagClass	= esc_attr($tagClass);
	$echo		= (int) $echo;
	$get		= (int) $get;

	$tagId = str_ireplace('%ID%', $spThisForum->forum_id, $tagId);

	if ($get) return $spThisForum->forum_message;

	$out = (empty($spThisForum->forum_message)) ? '' : "<div id='$tagId' class='$tagClass'>$spThisForum->forum_message</div>\n";
	$out = apply_filters('sph_ForumHeaderMessage', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_NoForumMessage()
#	Display Message when no Forum can be displayed
#	Scope:	Forum View
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_NoForumMessage($args='', $deniedMessage='', $definedMessage='') {
	global $spForumView;
	$defs = array('tagId'		=> 'spNoForumMessage',
				  'tagClass'	=> 'spMessage',
				  'echo'		=> 1,
				  'get'			=> 0
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_NoForumMessage_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId		= esc_attr($tagId);
	$tagClass	= esc_attr($tagClass);
	$echo		= (int) $echo;
	$get		= (int) $get;

	# is Access denied
	if ($spForumView->forumViewStatus == 'no access') {
		$m = sp_filter_title_display($deniedMessage);
	} elseif ($spForumView->forumViewStatus == 'no data') {
		$m = sp_filter_title_display($definedMessage);
	} elseif ($spForumView->forumViewStatus == 'sneak peek') {
		$sflogin = sp_get_option('sflogin');
		if (!empty($sflogin['sfsneakredirect'])) {
            sp_redirect(apply_filters('sph_sneak_redirect', $sflogin['sfsneakredirect']));
		} else {
			$sneakpeek = sp_get_sfmeta('sneakpeek', 'message');
			$m = ($sneakpeek) ? sp_filter_text_display($sneakpeek[0]['meta_value']) : '';
		}
	} else {
		return;
	}

	if ($get) return $m;

	$out = "<div id='$tagId' class='$tagClass'>$m</div>\n";
	$out = apply_filters('sph_NoForumMessage', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# ======================================================================================
#
# FORUM VIEW
# Topic Loop Functions
#
# ======================================================================================

# --------------------------------------------------------------------------------------
#
#	sp_TopicIndexPageLinks()
#	Display page links for topic list
#	Scope:	Topic List Loop
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_TopicIndexPageLinks($args='', $label='', $toolTip='') {
	global $spThisForum, $sfglobals;

	$topics_per_page = $sfglobals['display']['topics']['perpage'];
	if (!$topics_per_page) $topics_per_page = 20;
	if ($topics_per_page >= $spThisForum->topic_count) return '';

	$defs = array('tagClass' 		=> 'spPageLinks',
				  'prevIcon'		=> 'sp_ArrowLeft.png',
				  'nextIcon'		=> 'sp_ArrowRight.png',
				  'iconClass'		=> 'spIcon',
				  'pageLinkClass'	=> 'spPageLinks',
				  'curPageClass'	=> 'spCurrent',
				  'showLinks'		=> 4,
				  'echo'			=> 1
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_TopicIndexPageLinks_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass		= esc_attr($tagClass);
	$prevIcon		= SPTHEMEICONSURL.sanitize_file_name($prevIcon);
	$nextIcon		= SPTHEMEICONSURL.sanitize_file_name($nextIcon);
	$iconClass		= esc_attr($iconClass);
	$pageLinkClass	= esc_attr($pageLinkClass);
	$curPageClass	= esc_attr($curPageClass);
	$showLinks		= (int) $showLinks;
	$label			= sp_filter_title_display($label);
	$toolTip		= esc_attr($toolTip);
	$echo			= (int) $echo;

	global $sfvars;
	$curToolTip = str_ireplace('%PAGE%', $sfvars['page'], $toolTip);

	$out = "<div class='$tagClass'>";
	$totalPages = ($spThisForum->topic_count / $topics_per_page);
	if (!is_int($totalPages)) $totalPages = (intval($totalPages) + 1);
	$out.= $label;
	$out.= sp_page_prev($sfvars['page'], $showLinks, $spThisForum->forum_permalink, $pageLinkClass, $iconClass, $prevIcon, $nextIcon, $toolTip, '');

	$url = $spThisForum->forum_permalink;
	if ($sfvars['page'] > 1) $url = user_trailingslashit(trailingslashit($url).'page-'.$sfvars['page']);
	$out.= "<a href='$url' class='$pageLinkClass $curPageClass vtip' title='$curToolTip'>".$sfvars['page'].'</a>';

	$out.= sp_page_next($sfvars['page'], $totalPages, $showLinks, $spThisForum->forum_permalink, $pageLinkClass, $iconClass, $prevIcon, $nextIcon, $toolTip, '');
	$out.= "</div>\n";
	$out = apply_filters('sph_TopicIndexPageLinks', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_TopicModeratorList()
#	Display the list of forum moderators
#	Scope:	Forum View
#	Version: 5.2
#
# --------------------------------------------------------------------------------------
function sp_TopicModeratorList($args='', $label='') {
	global $sfglobals, $spThisForum, $sfglobals, $spThisUser;

	global $sfglobals, $spThisForum;
	$defs = array('tagId'    		=> 'spForumModerators%ID%',
				  'tagClass' 		=> 'spForumModeratorList',
				  'listClass'		=> 'spForumModeratorList',
				  'labelClass'		=> 'spForumModeratorLabel',
                  'showEmpty'       => 0,
				  'echo'			=> 1,
				  'get'				=> 0,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_TopicModeratorList_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId			= esc_attr($tagId);
	$tagClass		= esc_attr($tagClass);
	$listClass		= esc_attr($listClass);
	$labelClass		= esc_attr($labelClass);
	$showEmpty		= (int) $showEmpty;
	$echo			= (int) $echo;
	$get			= (int) $get;

	$tagId = str_ireplace('%ID%', $spThisForum->forum_id, $tagId);

	$mods = $sfglobals['forum_moderators']['users'][$spThisForum->forum_id];
	if ($get) return $mods;

    # build mods list with name display
    if (!empty($mods)) {
        $modList = '';
        $first = true;
        foreach ($mods as $mod) {
            if (!$first) $modList.= ', ';
            $first = false;
            $modList.= sp_build_name_display($mod['user_id'], $mod['display_name'], true);
        }
    } else if ($showEmpty) {
        $modList = 'none';
    } else {
        return '';
    }

	$out = "<div id='$tagId' class='$tagClass'>\n";
	$out.= "<span class='$labelClass'>".sp_filter_title_display($label)."</span>\n";
	$out.= "<span class='$listClass'>$modList</span>\n";
	$out.= "</div>\n";

	$out = apply_filters('sph_TopicModeratorList', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_TopicNewButton()
#	Display The New Topic Button
#	Scope:	Forum View
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_TopicNewButton($args='', $label='', $toolTip='', $toolTipLock='') {
	global $spThisForum, $sfglobals, $spThisUser;

	if (!sp_get_auth('start_topics', $spThisForum->forum_id)) return;

	$defs = array('tagId' 		=> 'spTopicNewButton',
				  'tagClass' 	=> 'spButton',
				  'icon' 		=> 'sp_NewTopic.png',
				  'iconLock'	=> 'sp_ForumStatusLock.png',
				  'iconClass'	=> 'spIcon',
				  'echo'		=> 1
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_TopicNewButton_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId			= esc_attr($tagId);
	$tagClass		= esc_attr($tagClass);
	$icon			= sanitize_file_name($icon);
	$iconClass 		= esc_attr($iconClass);
	$toolTip		= esc_attr($toolTip);
	$toolTipLock	= esc_attr($toolTipLock);
	$echo			= (int) $echo;

	# is the forum locked?
	$out = '';
    $lock = false;
	if ($sfglobals['lockdown'] || $spThisForum->forum_status) {
		$iconLock = SPTHEMEICONSURL.sanitize_file_name($iconLock);
		$out.= "<img class='$tagClass $iconClass vtip' id='$tagId' src='$iconLock' title='$toolTipLock' alt='' />\n";
        if (!$spThisUser->admin) $lock = true;
	}
    if (!$lock && sp_get_auth('start_topics', $spThisForum->forum_id)) {
		$out.= "<a href='javascript:void(null)' class='$tagClass vtip' id='$tagId' title='$toolTip' onclick='jQuery(document).ready(function() { spjOpenEditor(\"spPostForm\", \"topic\"); });'>\n";
		if (!empty($icon)) $out.= "<img class='$iconClass' src='".SPTHEMEICONSURL.$icon."' alt=''/>\n";
		if (!empty($label)) $out.= sp_filter_title_display($label);
		$out.= "</a>\n";
	}

	$out = apply_filters('sph_TopicNewButton', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_TopicIndexIcon()
#	Display Topic Icon
#	Scope:	Topic Loop
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_TopicIndexIcon($args='') {
	global $spThisForum, $spThisTopic;
	$defs = array('tagId' 		=> 'spTopicIndexIcon%ID%',
				  'tagClass'	=> 'spRowIcon',
				  'icon' 		=> 'sp_TopicIcon.png',
				  'iconUnread'	=> 'sp_TopicIconPosts.png',
				  'echo'		=> 1,
				  'get'			=> 0
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_TopicIndexIcon_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId		= esc_attr($tagId);
	$tagClass	= esc_attr($tagClass);
	$echo		= (int) $echo;
	$get		= (int) $get;

	$tagId = str_ireplace('%ID%', $spThisTopic->topic_id, $tagId);

	$path = SPTHEMEICONSDIR;
	$url = SPTHEMEICONSURL;
	if ($spThisTopic->unread) {
		$tIcon = sanitize_file_name($iconUnread);
		if (!empty($spThisForum->topic_icon_new)) {
			$tIcon = sanitize_file_name($spThisForum->topic_icon_new);
			$path = SFCUSTOMDIR;
			$url = SFCUSTOMURL;
		}
	} else {
		$tIcon = sanitize_file_name($icon);
		if (!empty($spThisForum->topic_icon)) {
			$tIcon = sanitize_file_name($spThisForum->topic_icon);
			$path = SFCUSTOMDIR;
			$url = SFCUSTOMURL;
		}
	}
	if (!file_exists($path.$tIcon)) {
		$tIcon = SPTHEMEICONSURL.sanitize_file_name($icon);
	} else {
		$tIcon = $url.$tIcon;
	}

	if ($get) return $tIcon;

	$out = "<img id='$tagId' class='$tagClass' src='$tIcon' alt='' />\n";
	$out = apply_filters('sph_TopicIndexIcon', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_TopicIndexName()
#	Display Topic Name/Title
#	Scope:	Topic Loop
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_TopicIndexName($args='', $toolTip='') {
	global $spThisTopic;
	$defs = array('tagId'    	=> 'spTopicIndexName%ID%',
			      'tagClass' 	=> 'spRowName',
			      'truncate'	=> 0,
				  'echo'		=> 1,
				  'get'			=> 0
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_TopicIndexName_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId		= esc_attr($tagId);
	$tagClass	= esc_attr($tagClass);
	$truncate	= (int) $truncate;
	$toolTip	= esc_attr($toolTip);
	$echo		= (int) $echo;
	$get		= (int) $get;

	$tagId = str_ireplace('%ID%', $spThisTopic->topic_id, $tagId);
	$toolTip = str_ireplace('%NAME%', htmlspecialchars($spThisTopic->topic_name, ENT_QUOTES), $toolTip);

	if ($get) return sp_truncate($spThisTopic->topic_name, $truncate);

	$out = "<a href='$spThisTopic->topic_permalink' id='$tagId' class='$tagClass vtip' title='$toolTip'>".sp_truncate($spThisTopic->topic_name, $truncate)."</a>\n";
	$out = apply_filters('sph_TopicIndexName', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_TopicIndexPostPageLinks()
#	Display Topic 'in row' page links
#	Scope:	Topic sub Loop
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_TopicIndexPostPageLinks($args='', $toolTip='') {
	global $spThisForum, $spThisTopic, $sfglobals;

	$posts_per_page = $sfglobals['display']['posts']['perpage'];
	if ($posts_per_page >= $spThisTopic->post_count) return '';

	$defs = array('tagId'    		=> 'spTopicIndexPostPageLinks%ID%',
				  'tagClass' 		=> 'spInRowPageLinks',
				  'icon'			=> 'sp_ArrowRightSmall.png',
				  'iconClass'		=> 'spIconSmall',
				  'pageLinkClass'	=> 'spInRowForumPageLink',
				  'showLinks'		=> 4,
				  'echo'			=> 1
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_TopicIndexPostPageLinks_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId			= esc_attr($tagId);
	$tagClass		= esc_attr($tagClass);
	$icon			= sanitize_file_name($icon);
	$iconClass		= esc_attr($iconClass);
	$pageLinkClass	= esc_attr($pageLinkClass);
	$showLinks		= (int) $showLinks;
	$toolTip		= esc_attr($toolTip);
	$echo			= (int) $echo;

	$tagId = str_ireplace('%ID%', $spThisTopic->topic_id, $tagId);

	$out = "<div id='$tagId' class='$tagClass'>\n";

	$total_pages=($spThisTopic->post_count / $posts_per_page);
	if (!is_int($total_pages)) $total_pages=intval($total_pages) + 1;
	($total_pages > $showLinks ? $max_count = $showLinks : $max_count = $total_pages);
	for ($x = 1; $x <= $max_count; $x++) {
		$out.= "<a class='$pageLinkClass vtip' href='".sp_build_url($spThisForum->forum_slug, $spThisTopic->topic_slug, $x, 0)."' title='".str_ireplace('%PAGE%', $x,
                                                                                                                                                    $toolTip)."'>$x</a>\n";
	}
	if ($total_pages > $showLinks) {
		$out.= "<img src='".SPTHEMEICONSURL.$icon."' class='$iconClass' alt=''/>\n";
		$out.= "<a class='$pageLinkClass vtip' href='".sp_build_url($spThisForum->forum_slug, $spThisTopic->topic_slug, $total_pages, 0)."' title='".str_ireplace('%PAGE%',
                                                                                                                                                              $total_pages, $toolTip)."'>$total_pages</a>\n";
	}
	$out.= "</div>\n";

	$out = apply_filters('sph_TopicIndexPostPageLinks', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_TopicIndexPostCount()
#	Display Topic 'in row' total post count
#	Scope:	Topic Loop
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_TopicIndexPostCount($args='', $label='') {
	global $spThisTopic;
	$defs = array('tagId'    		=> 'spTopicIndexPostCount%ID%',
				  'tagClass' 		=> 'spInRowCount',
				  'labelClass'		=> 'spInRowLabel',
				  'numberClass'		=> 'spInRowNumber',
				  'stack'			=> 0,
				  'echo'			=> 1,
				  'get'				=> 0
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_TopicIndexPostCount_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId			= esc_attr($tagId);
	$tagClass		= esc_attr($tagClass);
	$labelClass		= esc_attr($labelClass);
	$numberClass	= esc_attr($numberClass);
	$stack			= (int) $stack;
	$echo			= (int) $echo;
	$get			= (int) $get;

	$tagId = str_ireplace('%ID%', $spThisTopic->topic_id, $tagId);
	($stack ? $att='<br />' : $att= ' ');

	if ($get) return $spThisTopic->post_count;

	$out = "<div id='$tagId' class='$tagClass'>\n";
	$out.= "<span class='$labelClass'>".sp_filter_title_display($label)."$att</span>\n";
	$out.= "<span class='$numberClass'>$spThisTopic->post_count</span>\n";
	$out.= "</div>\n";
	$out = apply_filters('sph_TopicIndexPostCount', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_TopicIndexViewCount()
#	Display Topic 'in row' total view count
#	Scope:	Topic Loop
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_TopicIndexViewCount($args='', $label='') {
	global $spThisTopic;
	$defs = array('tagId'    		=> 'spTopicIndexViewCount%ID%',
				  'tagClass' 		=> 'spInRowCount',
				  'labelClass'		=> 'spInRowLabel',
				  'numberClass'		=> 'spInRowNumber',
				  'stack'			=> 0,
				  'echo'			=> 1,
				  'get'				=> 0
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_TopicIndexViewCount_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId			= esc_attr($tagId);
	$tagClass		= esc_attr($tagClass);
	$labelClass		= esc_attr($labelClass);
	$numberClass	= esc_attr($numberClass);
	$stack			= (int) $stack;
	$echo			= (int) $echo;
	$get			= (int) $get;

	$tagId = str_ireplace('%ID%', $spThisTopic->topic_id, $tagId);
	($stack ? $att='<br />' : $att= ' ');

	if ($get) return $spThisTopic->topic_opened;

	$out = "<div id='$tagId' class='$tagClass'>\n";
	$out.= "<span class='$labelClass'>".sp_filter_title_display($label)."$att</span>\n";
	$out.= "<span class='$numberClass'>$spThisTopic->topic_opened</span>\n";
	$out.= "</div>\n";
	$out = apply_filters('sph_TopicIndexViewCount', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_TopicIndexStatusIcons()
#	Display Topic Status (Locked/Pinned/New Posts)
#	Scope:	Topic Loop
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_TopicIndexStatusIcons($args='', $toolTipLock='', $toolTipPin='', $toolTipPost='') {
	global $spThisTopic, $sfglobals, $spThisUser;

	$defs = array('tagId' 			=> 'spTopicIndexStatus%ID%',
				  'tagClass' 		=> 'spStatusIcon',
				  'iconLock'		=> 'sp_TopicStatusLock.png',
				  'iconPin'			=> 'sp_TopicStatusPin.png',
				  'iconPost'		=> 'sp_TopicStatusPost.png',
				  'echo'			=> 1
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_TopicIndexStatusIcons_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId			= esc_attr($tagId);
	$tagClass		= esc_attr($tagClass);
	$toolTipLock	= esc_attr($toolTipLock);
	$toolTipPin		= esc_attr($toolTipPin);
	$toolTipPost	= esc_attr($toolTipPost);
	$echo			= (int) $echo;

	$tagId = str_ireplace('%ID%', $spThisTopic->topic_id, $tagId);

	# Display if locked, pinned or new posts
	$out = "<div id='$tagId' class='$tagClass'>\n";
	if ($sfglobals['lockdown'] || $spThisTopic->topic_status) {
		$icon = SPTHEMEICONSURL.sanitize_file_name($iconLock);
		$out.= "<img class='vtip' src='$icon' title='$toolTipLock' alt='' />\n";
	}

	if ($spThisTopic->topic_pinned) {
		$icon = SPTHEMEICONSURL.sanitize_file_name($iconPin);
		$out.= "<img class='vtip' src='$icon' title='$toolTipPin' alt='' />\n";
	}

	if ($spThisTopic->unread) {
		$icon = SPTHEMEICONSURL.sanitize_file_name($iconPost);
		$out.= "<img class='vtip' src='$icon' title='$toolTipPost' alt='' />\n";
	}

	$out = apply_filters('sph_TopicIndexStatusIconsLast', $out);
	$out.= "</div>\n";

	$out = apply_filters('sph_TopicIndexStatusIcons', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_TopicIndexFirstPost()
#	Display Topic 'in row' link to the first post made to a topic in this forum
#	Scope:	Topic Loop
#	Version: 5.0
#
#	Changelog:
#	5.1	- 'ItemBreak' argument added
#
# --------------------------------------------------------------------------------------
function sp_TopicIndexFirstPost($args='', $label='') {
	global $spThisTopic;

	$defs = array('tagId'    		=> 'spTopicIndexFirstPost%ID%',
				  'tagClass' 		=> 'spInRowPostLink',
				  'labelClass'		=> 'spInRowLabel',
				  'infoClass'		=> 'spInRowInfo',
				  'linkClass'		=> 'spInRowLastPostLink',
				  'iconClass'		=> 'spIcon',
				  'icon'			=> 'sp_ArrowRight.png',
				  'tip'   			=> 1,
				  'nicedate'		=> 1,
				  'date'  			=> 0,
				  'time'  			=> 0,
				  'user'  			=> 1,
				  'stackuser'		=> 1,
				  'stackdate'		=> 0,
				  'truncateUser'	=> 0,
				  'itemBreak'		=> '<br />',
				  'echo'			=> 1,
				  'get'				=> 0
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_TopicIndexFirstPost_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId			= esc_attr($tagId);
	$tagClass		= esc_attr($tagClass);
	$labelClass		= esc_attr($labelClass);
	$infoClass		= esc_attr($infoClass);
	$linkClass		= esc_attr($linkClass);
	$iconClass		= esc_attr($iconClass);
	$tip			= (int) $tip;
	$nicedate		= (int) $nicedate;
	$date			= (int) $date;
	$time			= (int) $time;
	$user			= (int) $user;
	$stackuser		= (int) $stackuser;
	$stackdate		= (int) $stackdate;
	$truncateUser	= (int) $truncateUser;
	$icon			= sanitize_file_name($icon);
	$echo			= (int) $echo;
	$get			= (int) $get;

	$tagId = str_ireplace('%ID%', $spThisTopic->topic_id, $tagId);
	if ($tip && !empty($spThisTopic->first_post_tip)) {
		$title = "title='$spThisTopic->first_post_tip'";
		$linkClass.= ' vtip';
	} else {
		$title='';
	}

	($stackuser ? $ulb='<br />' : $ulb='&nbsp;');
	($stackdate ? $dlb='<br />' : $dlb=' - ');

	$out = "<div id='$tagId' class='$tagClass'>\n";
	$out.= "<span class='$labelClass'>".sp_filter_title_display($label)."\n";

	# Link to post
	$out.= "<a class='$linkClass' $title href='$spThisTopic->first_post_permalink'>\n";
	$out.= "<img src='".SPTHEMEICONSURL.$icon."' class='$iconClass' alt=''/>\n";
	$out.= "</a></span>\n";

	# user
	$poster = sp_build_name_display($spThisTopic->first_user_id, sp_truncate($spThisTopic->first_display_name, $truncateUser), true);
	if (empty($poster)) $poster = sp_truncate($spThisTopic->first_guest_name, $truncateUser);
	if ($user) $out.= "<span class='$labelClass'>$ulb$poster</span>$itemBreak";

	if ($get) {
		$getData = new stdClass();
		$getData->permalink = $spThisTopic->first_post_permalink;
		$getData->topic_name = $spThisTopic->topic_name;
		$getData->post_date = $spThisTopic->first_post_date;
		$getData->tooltip = $spThisTopic->first_post_tip;
		$getData->user = $poster;
		return $getData;
	}

	# date/time
	if ($nicedate) {
		$out.= "<span class='$labelClass'>".sp_nicedate($spThisTopic->first_post_date)."</span>\n";
	} else {
		if ($date) {
			$out.= "<span class='$labelClass'>".sp_date('d', $spThisTopic->first_post_date);
			if($time) {
				$out.= $dlb.sp_date('t', $spThisTopic->first_post_date);
			}
			$out.= "</span>\n";
		}
	}

	$out.= "</div>\n";
	$out = apply_filters('sph_TopicIndexFirstPost', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_TopicIndexLastPost()
#	Display Topic 'in row' link to the last post made to a topic in this forum
#	Scope:	Topic Loop
#	Version: 5.0
#
#	Changelog:
#	5.1	- 'ItemBreak' argument added
#
# --------------------------------------------------------------------------------------
function sp_TopicIndexLastPost($args='', $label='') {
	global $spThisTopic;

	$defs = array('tagId'    		=> 'spTopicIndexLastPost%ID%',
				  'tagClass' 		=> 'spInRowPostLink',
				  'labelClass'		=> 'spInRowLabel',
				  'infoClass'		=> 'spInRowInfo',
				  'linkClass'		=> 'spInRowLastPostLink',
				  'iconClass'		=> 'spIcon',
				  'icon'			=> 'sp_ArrowRight.png',
				  'tip'   			=> 1,
				  'nicedate'		=> 1,
				  'date'  			=> 0,
				  'time'  			=> 0,
				  'user'  			=> 1,
				  'stackuser'		=> 1,
				  'stackdate'		=> 0,
				  'truncateUser'	=> 0,
				  'itemBreak'		=> '<br />',
				  'echo'			=> 1,
				  'get'				=> 0
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_TopicIndexLastPost_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId			= esc_attr($tagId);
	$tagClass		= esc_attr($tagClass);
	$labelClass		= esc_attr($labelClass);
	$infoClass		= esc_attr($infoClass);
	$linkClass		= esc_attr($linkClass);
	$iconClass		= esc_attr($iconClass);
	$tip			= (int) $tip;
	$nicedate		= (int) $nicedate;
	$date			= (int) $date;
	$time			= (int) $time;
	$user			= (int) $user;
	$stackuser		= (int) $stackuser;
	$stackdate		= (int) $stackdate;
	$truncateUser	= (int) $truncateUser;
	$icon			= sanitize_file_name($icon);
	$echo			= (int) $echo;
	$get			= (int) $get;

	$tagId = str_ireplace('%ID%', $spThisTopic->topic_id, $tagId);
	if ($tip && !empty($spThisTopic->last_post_tip)) {
		$title = "title='$spThisTopic->last_post_tip'";
		$linkClass.= ' vtip';
	} else {
		$title='';
	}

	($stackuser ? $ulb='<br />' : $ulb='&nbsp;');
	($stackdate ? $dlb='<br />' : $dlb=' - ');

	$out = "<div id='$tagId' class='$tagClass'>\n";
	$out.= "<span class='$labelClass'>".sp_filter_title_display($label)."\n";

	# Link to post
	$out.= "<a class='$linkClass' $title href='$spThisTopic->last_post_permalink'>\n";
	$out.= "<img src='".SPTHEMEICONSURL.$icon."' class='$iconClass' alt=''/>\n";
	$out.= "</a></span>\n";

	# user
	$poster = sp_build_name_display($spThisTopic->last_user_id, sp_truncate($spThisTopic->last_display_name, $truncateUser), true);
	if (empty($poster)) $poster = sp_truncate($spThisTopic->last_guest_name, $truncateUser);
	if ($user) $out.= "<span class='$labelClass'>$ulb$poster</span>$itemBreak";

	if ($get) {
		$getData = new stdClass();
		$getData->permalink = $spThisTopic->first_post_permalink;
		$getData->topic_name = $spThisTopic->topic_name;
		$getData->post_date = $spThisTopic->last_post_date;
		$getData->tooltip = $spThisTopic->last_post_tip;
		$getData->user = $poster;
		return $getData;
	}

	# date/time
	if ($nicedate) {
		$out.= "<span class='$labelClass'>".sp_nicedate($spThisTopic->last_post_date)."</span>\n";
	} else {
		if ($date) {
			$out.= "<span class='$labelClass'>".sp_date('d', $spThisTopic->last_post_date);
			if($time) {
				$out.= $dlb.sp_date('t', $spThisTopic->last_post_date);
			}
			$out.= "</span>\n";
		}
	}
	$out.= "</div>\n";
	$out = apply_filters('sph_TopicIndexLastPost', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_NoTopicsInForumMessage()
#	Display Message when no Topics are found in a Forum
#	Scope:	Topic Loop
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_NoTopicsInForumMessage($args='', $definedMessage='') {
	global $spTopicView;
	$defs = array('tagId'		=> 'spNoTopicsInForumMessage',
				  'tagClass'	=> 'spMessage',
				  'echo'		=> 1,
				  'get'			=> 0
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_NoTopicsInForumMessage_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId		= esc_attr($tagId);
	$tagClass	= esc_attr($tagClass);
	$echo		= (int) $echo;
	$get		= (int) $get;

	if ($get) return $definedMessage;

	$out = "<div id='$tagId' class='$tagClass'>".sp_filter_title_display($definedMessage)."</div>\n";
	$out = apply_filters('sph_NoTopicsInForumMessage', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_TopicForumToolButton()
#	Display Topic Level Admin Tools Button
#	Scope:	Topic Loop
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_TopicForumToolButton($args='', $label='', $toolTip='') {
	global $spThisForum, $spThisTopic, $spThisUser, $sfglobals;

	if ($sfglobals['lockdown'] == true && $spThisUser->admin == false) return;

	$show = false;
	if ($spThisUser->admin || $spThisUser->moderator) {
		$show = true;
	} else {
		if (sp_get_auth('lock_topics', $spThisForum->forum_id) ||
			sp_get_auth('pin_topics', $spThisForum->forum_id) ||
			sp_get_auth('edit_any_topic_titles', $spThisForum->forum_id) ||
			sp_get_auth('delete_topics', $spThisForum->forum_id) ||
			sp_get_auth('move_topics', $spThisForum->forum_id) ||
			(sp_get_auth('edit_own_topic_titles', $spThisForum->forum_id) && $spThisTopic->first_user_id == $spThisUser->ID)) {
			$show = true;
		}
	}
	if (!$show) return;

	$defs = array('tagId' 			=> 'spForumToolButton%ID%',
				  'tagClass' 		=> 'spToolsButton',
				  'icon' 			=> 'sp_ForumTools.png',
				  'iconClass'		=> 'spIcon',
				  'hide'			=> 1,
				  'containerClass'	=> 'spForumTopicSection'
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_TopicForumToolButton_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId			= esc_attr($tagId);
	$tagClass		= esc_attr($tagClass);
	$icon			= sanitize_file_name($icon);
	$iconClass 		= esc_attr($iconClass);
	$containerClass	= esc_attr($containerClass);
	$hide			= (int) $hide;
	$toolTip		= esc_attr($toolTip);
	$label			= sp_filter_title_display($label);

	$tagId = str_ireplace('%ID%', $spThisTopic->topic_id, $tagId);

	$addStyle = '';
	if ($hide) $addStyle = " style='display:none;' ";

	$site = SFHOMEURL.'index.php?sp_ahah=admintoollinks&amp;sfnonce='.wp_create_nonce('forum-ahah')."&amp;action=topictools&amp;topic=$spThisTopic->topic_id&amp;forum=$spThisForum->forum_id&amp;page=$spThisForum->display_page";
	$out = "<a class='$tagClass vtip' id='$tagId' title='$toolTip' rel='nofollow' href='javascript:void(null)' $addStyle ";
	$title = esc_js(sp_text('Forum Tools'));
	$out.= "onclick='spjDialogAjax(this, \"$site\", \"$title\", 250, 0, 0);' >";

	if (!empty($icon)) $out.= "<img class='$iconClass' src='".SPTHEMEICONSURL.$icon."' alt=''/>";
	if (!empty($label)) $out.= $label;
	$out.= "</a>\n";
	$out = apply_filters('sph_TopicForumToolButton', $out, $a);

	echo $out;

	# Add script to hover admin buttons - just once
	if ($spThisForum->tools_flag && $hide) {
		?>
		<script type='text/javascript'>
		/* <![CDATA[ */
		var sptb = {
			toolclass : '.<?php echo($containerClass); ?>'
		};
		/* ]]> */
		</script>
		<?php
		add_action('wp_footer', 'spjs_AddTopicToolsHover');
		$spThisForum->tools_flag = false;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_TopicEditorWindow()
#	Placeholder for the new topic editor window
#	Scope:	Forum View
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_TopicEditorWindow($addTopicForm) {
	global $spThisForum, $sfglobals;

	if ((sp_get_auth('start_topics', $spThisForum->forum_id) == true) && ($spThisForum->forum_status == false) && ($sfglobals['lockdown'] == false)) {
		$out = '<a id="dataform"></a>'."\n";
		$out.= sp_add_topic($addTopicForm);
		echo $out;

		# inline js to open topic form if from the topic view (script below)
		if (isset($_GET['new']) && $_GET['new'] == 'topic') add_action('wp_footer', 'spjs_OpenTopicForm');
	}
}

# ======================================================================================
#
# INLINE SCRIPTS
#
# ======================================================================================

# --------------------------------------------------------------------------------------
# inline opens add topic window if called from topic view
# --------------------------------------------------------------------------------------
function spjs_OpenTopicForm() {
?>
<script type="text/javascript">
	jQuery(document).ready(function() {
		spjOpenEditor('spPostForm', 'topic');
	});
</script>
<?php
}

# --------------------------------------------------------------------------------------
# inline adds hover show event to admin tools button if hidden
# --------------------------------------------------------------------------------------
function spjs_AddTopicToolsHover() {
    global $SFMOBILE;

    # on mobile devices just show forum tools. otherwise, show on hover over row
    if ($SFMOBILE) {
?>
        <script type="text/javascript">
        	jQuery(document).ready(function() {
        		var p = jQuery(sptb.toolclass).position();
        		jQuery('.spToolsButton').css('left', p.left);
        		jQuery('.spToolsButton').show();
        	});
        </script>
<?php
    } else {
?>
        <script type="text/javascript">
        	jQuery(document).ready(function() {
        		jQuery(sptb.toolclass).hover(function() {
        			var p = jQuery(this).position();
        			jQuery('.spToolsButton', this).css('left', p.left);
        			jQuery('.spToolsButton', this).delay(800).slideDown('normal');
        				}, function() {
        			jQuery('.spToolsButton', this).stop(true, true).slideUp('normal');
        		});
        	});
        </script>
<?php
    }
}

?>