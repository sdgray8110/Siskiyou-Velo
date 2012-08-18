<?php
/*
Simple:Press
Template Function Handler
$LastChangedDate: 2012-06-04 11:57:12 -0700 (Mon, 04 Jun 2012) $
$Rev: 8656 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

# --------------------------------------------------------------------------------------
#
#	sp_SectionStart()
#	Opens a new container section (div)
#	Scope:	Forum
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_SectionStart($args='', $sectionName='') {
	$defs = array('tagClass' 	=> 'spPlainSection',
				  'tagId'		=> '',
				  'echo'		=> 1,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_SectionStart_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass	= esc_attr($tagClass);
	$tagId		= esc_attr($tagId);
	$echo		= (int) $echo;

	# notifiy custom code before we start the section code
	do_action('sph_BeforeSectionStart', $sectionName, $a);
	do_action('sph_BeforeSectionStart_'.$sectionName, $a);

	# specific formatting based on 'defined' names
	$rowClass = '';
	$rowId = '';
	switch ($sectionName) {
		case 'group':
			global $spGroupView, $spThisGroup;
            if (isset($spGroupView)) $rowClass.= ($spGroupView->currentGroup % 2) ? ' spOdd' : ' spEven';
			if (isset($spThisGroup)) $rowId.= "group$spThisGroup->group_id";
			break;

		case 'forumlist':
			global $spThisGroup;
			if (isset($spThisGroup)) $rowId.= "forumlist$spThisGroup->group_id";
			break;

		case 'subforumlist':
			global $spThisForum;
            if (isset($spThisForum)) $rowId.= "subforumlist$spThisForum->forum_id";
			break;

		case 'topiclist':
			global $spThisForum;
            if (isset($spThisForum)) $rowId.= "topiclist$spThisForum->forum_id";
			break;

		case 'postlist':
			global $spThisTopic;
            if (isset($spThisTopic)) $rowId.= "postlist$spThisTopic->topic_id";
			break;

		case 'forum':
			global $spGroupView, $spThisForum;
            if (isset($spGroupView)) $rowClass.= ($spGroupView->currentForum % 2) ? ' spOdd' : ' spEven';
            if (isset($spThisForum)) {
    			if ($spThisForum->forum_status) $rowClass.= ' spLockedForum';
        		if ($spThisForum->unread) $rowClass.= ' spUnreadPosts';
    			$rowId.= "forum$spThisForum->forum_id";
            }
			break;

		case 'subForum':
			global $spForumView, $spThisSubForum;
   			if (isset($spForumView))  $rowClass.= ($spForumView->currentChild % 2) ? ' spOdd' : ' spEven';
            if (isset($spThisSubForum)) {
    			if ($spThisSubForum->forum_status) $rowClass.= ' spLockedForum';
        		if ($spThisSubForum->unread) $rowClass.= ' spUnreadPosts';
    			$rowId.= "subforum$spThisSubForum->forum_id";
            }
			break;

		case 'topic':
			global $spForumView, $spThisTopic;
            if (isset($spForumView)) $rowClass.= ($spForumView->currentTopic % 2) ? ' spOdd' : ' spEven';
            if (isset($spThisTopic)) {
    			if ($spThisTopic->topic_status) $rowClass.= ' spLockedTopic';
    			if ($spThisTopic->topic_pinned) $rowClass.= ' spPinnedTopic';
        		if ($spThisTopic->unread) $rowClass.= ' spUnreadPosts';
    			$rowId.= "topic$spThisTopic->topic_id";
            }
			break;

		case 'post':
			global $spTopicView, $spThisPost;
			if (isset($spTopicView)) $rowClass.= ($spTopicView->currentPost % 2) ? ' spOdd' : ' spEven';
            if (isset($spThisPost)) {
    			if ($spThisPost->post_pinned) $rowClass.= ' spPinnedPost';
        		if ($spThisPost->new_post) $rowClass.= ' spUnreadPosts';
    			$rowClass.= ' spType-'.$spThisPost->user->usertype;
    			if (!empty($spThisPost->user->rank)) $rowClass.= ' spRank-'.sp_create_slug($spThisPost->user->rank[0]['name'], 'rank', false);
    			if (!empty($spThisPost->user->special_rank)) {
    				foreach ($spThisPost->user->special_rank as $rank) {
    					$rowClass.= ' spSpecialRank-'.sp_create_slug($rank['name'], 'rank', false);
    				}
    			}
    			if (!empty($spThisPost->user->memberships)) {
    				foreach ($spThisPost->user->memberships as $membership) {
    					$rowClass.= ' spUsergroup-'.sp_create_slug($membership['usergroup_name'], 'usergroup', false);
    				}
    			}
    			$rowId.= "post$spThisPost->post_id";
            }
			break;

		case 'list':
			global $spListView, $spThisListTopic;
            if (isset($spListView)) $rowClass.= ($spListView->currentTopic % 2) ? ' spOdd' : ' spEven';
			if (isset($spThisListTopic)) $rowId.= "listtopic$spThisListTopic->topic_id";
			break;

		case 'usergroup':
			global $spMembersList;
			if (isset($spMembersList)) $rowClass.= ($spMembersList->currentMemberGroup % 2) ? ' spOdd' : ' spEven';
			break;

		case 'member':
			global $spMembersList;
			if (isset($spMembersList)) $rowClass.= ($spMembersList->currentMember % 2) ? ' spOdd' : ' spEven';
			break;

		case 'memberGroup':
			global $spThisMemberGroup;
			if (isset($spThisMemberGroup)) $rowClass.= ' spUsergroup-'.sp_create_slug($spThisMemberGroup->usergroup_name, 'usergroup', false);
			break;

		default:
			if (!empty($tagId)) $rowId.= $tagId;
			break;
	}

	# allow filtering of the row class
	$rowClass = apply_filters('sph_SectionStartRowClass', $rowClass, $sectionName, $a);
	$rowId = apply_filters('sph_SectionStartRowID', $rowId, $sectionName, $a);

	# output section starting div
    $class = '';
   	if (!empty($rowId)) $rowId = " id='$rowId'";
    if (!empty($tagClass) || !empty($rowClass)) $class = " class='$tagClass$rowClass'";
	$out = "<div$class$rowId>\n";

	$out = apply_filters('sph_SectionStart', $out, $sectionName, $a);

	if ($echo) {
		echo $out;

		# notifiy custom code that section has started
		# only valid if content is echoed out ($display=1)
		do_action('sph_AfterSectionStart', $sectionName, $a);
		do_action('sph_AfterSectionStart_'.$sectionName, $a);
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_SectionEnd()
#	Closes a previously started container section (div)
#	Scope:	Forum
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_SectionEnd($args='', $sectionName='') {
	$defs = array('tagClass' 	=> '',
				  'tagId'		=> '',
				  'echo'		=> 1,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_SectionEnd_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	if (!empty($tagId))    $tagId 	 = " id='".esc_attr($tagId)."'";
	if (!empty($tagClass)) $tagClass = " class='".esc_attr($tagClass)."'";
	$echo = (int) $echo;

	# notifiy custom code before we end the section code
	do_action('sph_BeforeSectionEnd', $sectionName, $a);
	do_action('sph_BeforeSectionEnd_'.$sectionName, $a);

    $out = '';
    if (!empty($tagClass) || !empty($tagId)) $out.= "<div$tagId$tagClass></div>\n";

    # close the secton begin
	$out.= "</div>\n";

	$out = apply_filters('sph_SectionEnd', $out, $sectionName, $a);

	if ($echo) {
		echo $out;

		# notifiy custom code that section has ended
		# only valid if content is echoed out ($show=1)
		do_action('sph_AfterSectionEnd', $sectionName, $a);
		do_action('sph_AfterSectionEnd_'.$sectionName, $a);
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ColumnStart()
#	Defines a new column (div) in all list views
#	Scope:	Forum
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ColumnStart($args='', $columnName='') {
	$defs = array('tagClass'	=> 'spColumnSection',
				  'tagId'		=> '',
				  'width'		=> 'auto',
				  'height'		=> '60px',
				  'echo'		=> 1,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ColumnStart_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass	= esc_attr($tagClass);
	if (!empty($tagId)) $tagId = " id='".esc_attr($tagId)."'";
	$width		= esc_attr($width);
	$height		= esc_attr($height);
	$echo		= (int) $echo;

	# notifiy custom code before we start the column code
	do_action('sph_BeforeColumnStart', $columnName, $a);
	do_action('sph_BeforeColumnStart_'.$columnName, $a);

	# specific formatting based on 'defined' names
	$colClass = '';
	switch ($columnName) {
		default:
			break;
	}

	# allow filtering of the column class
	$colClass = apply_filters('sph_ColumnStartColumnClass', $colClass, $columnName);

	$out = "<div class='$tagClass$colClass'$tagId style='width: $width; min-height: $height;'>\n";

	$out = apply_filters('sph_ColumnStart', $out, $columnName, $a);

	if ($echo) {
		echo $out;

		# notifiy custom code that column has ended
		# only valid if content is echoed out ($show=1)
		do_action('sph_AfterColumnStart', $columnName, $a);
		do_action('sph_AfterColumnStart_'.$columnName, $a);
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ColumnEnd()
#	Closes a previously started column (div)
#	Scope:	Forum
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ColumnEnd($args='', $columnName='') {
	$defs = array('tagClass' 	=> '',
				  'tagId'		=> '',
				  'echo'		=> 1,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ColumnEnd_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	if (!empty($tagClass)) $tagClass = " class='".esc_attr($tagClass)."'";
	if (!empty($tagId))    $tagId 	 = " id='".esc_attr($tagId)."'";
	$echo		= (int) $echo;

	# notifiy custom code before we end the column code
	do_action('sph_BeforeColumnEnd', $columnName, $a);
	do_action('sph_BeforeColumnEnd_'.$columnName, $a);

    $out = '';
    if (!empty($tagClass) || !empty($tagId)) $out.= "<div$tagId$tagClass></div>\n";

    # close the colmumn start
	$out.= "</div>\n";

	$out = apply_filters('sph_ColumnEnd', $out, $columnName, $a);

	if ($echo) {
		echo $out;

		# notifiy custom code that column has ended
		# only valid if content is echoed out ($show=1)
		do_action('sph_AfterColumnEnd', $columnName, $a);
		do_action('sph_AfterColumnEnd_'.$columnName, $a);
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_InsertBreak()
#	Defines a Break (CSS Clear)
#	Scope:	Forum
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_InsertBreak($args='')
{
	$defs = array('tagClass'	=> '',
				  'tagId'		=> '',
				  'direction'	=> 'both',
				  'echo'		=> 1,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_InsertBreak_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	if (!empty($tagId))    $tagId = " id='".esc_attr($tagId)."'";
	if (!empty($tagClass)) {
	   $tagClass = " class='".esc_attr($tagClass)."'";
    } else if (!empty($direction)) {
        $tagClass = " style='clear: $direction;'";
    } else {
        $tagClass = '';
    }
	$echo = (int) $echo;

	# notifiy custom code before we insert the break
	do_action('sph_BeforeInsertBreak', $a);

    $out = '';
    if (!empty($tagClass) || !empty($tagId)) $out.= "<div$tagId$tagClass></div>\n";

	$out = apply_filters('sph_InsertBreak', $out, $a);

	if ($echo) {
		echo $out;

		# notifiy custom code that break has been inserted
		# only valid if content is echoed out ($show=1)
		do_action('sph_AfterInsertBreak', $a);
	} else {
		return $out;
	}
}

# **************************************************************************************

# --------------------------------------------------------------------------------------
#
# Template top level function handler
#
# --------------------------------------------------------------------------------------

# --------------------------------------------------------------------------------------
#
#	sp_UserAvatar()
#	Display a users avatar
#	Scope:	Site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_UserAvatar($args='', $contextData='') {
	$defs = array('tagClass'	=> 'spAvatar',
				  'imgClass'	=> 'spAvatar',
				  'size'		=> '',
				  'link'		=> 'profile',
				  'context'		=> 'current',
				  'wp'			=> '',
				  'echo'		=> 1,
				  'get'			=> 0,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_Avatar_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass	= esc_attr($tagClass);
	$imgClass	= esc_attr($imgClass);
	$link		= esc_attr($link);
	$size 		= (int) $size;
	$echo		= (int) $echo;
	$get		= (int) $get;
	$wp 		= esc_attr($wp);

	# init some vars
	$checkWidth = false;

	# make sure we are displaying avatars
	$spAvatars = sp_get_option('sfavatars');
	if ($spAvatars['sfshowavatars'] == true) {
		$avatarData = '';
		$avatarData->object = false;
		$avatarData->userId = 0; # need user id OR email
		$avatarData->email = '';
		$avatarData->avatar = '';
		$avatarData->admin = '';

		# determine avatar size
		$avatarData->size = (!empty($size)) ? $size : $spAvatars['sfavatarsize'];

		# get the appropriate user id and email address
		switch ($context) {
			case 'current':
				# we want the avatar for the current user
				global $spThisUser;
				$avatarData->userId = $spThisUser->ID;
				$avatarData->email = (!empty($avatarData->userId)) ? $spThisUser->user_email : '';
				break;

			case 'user':
				# determine if we have user object, id or email address
				if (is_object($contextData)) {
					# sp user object passed in
					# can contain anything, but must contain id or email, avatar array and admin flag
					$avatarData->object = true;
					$avatarData->userId = $contextData->ID;
					$avatarData->email = $contextData->user_email;
					$avatarData->avatar = $contextData->avatar;
					$avatarData->admin = $contextData->admin;
				} else {
					if (is_numeric($contextData)) {
						# user id passed in
						$user = get_userdata((int) $contextData);
					} else {
						# email address passed in
						$user = get_user_by('email', sp_esc_str($contextData));
					}
					if ($user) {
						$avatarData->userId = $user->ID;
						$avatarData->email = $user->user_email;
					}
				}
				break;

			default:
				# allow themes/plugins to add new avatar user types
				$avatarData = apply_filters('sph_Avatar_'.$context, $avatarData, $a);
				break;
		}

		# loop through prorities until we find an avatar to use
		foreach ($spAvatars['sfavatarpriority'] as $priority) {
			switch ($priority) {
				case 0: # Gravatars
					if (function_exists('sp_get_gravatar_cache_url')) {
						$avatarData->url = sp_get_gravatar_cache_url(strtolower($avatarData->email), $avatarData->size);
						if(empty($avatarData->url)) {
							$gravatar = false;
						} else {
							$gravatar = true;
						}
					} else {
						$rating = $spAvatars['sfgmaxrating'];
						switch ($rating) {
							case 1:
								$grating = 'G';
								break;
							case 2:
								$grating = 'PG';
								break;
							case 3:
								$grating = 'R';
								break;
							case 4:
							default:
								$grating = 'X';
								break;
						}

						$avatarData->url = 'http://www.gravatar.com/avatar/'.md5(strtolower($avatarData->email))."?d=404&amp;size=$avatarData->size&amp;rating=$grating";

						# Is there an gravatar?
						$headers = wp_get_http_headers($avatarData->url);
						if (!is_array($headers)) {
							$gravatar = false;
						} elseif (isset($headers['content-disposition'])) {
							$gravatar = true;
						} else {
							$gravatar = false;
						}
					}

					# ignore gravatar blank images
					if ($gravatar == true) {
						break 2; # if actual gravatar image found, show it
					}
					break;

				case 1: # WP avatars
					# if wp avatars being used, handle slightly different since we get image tags
					$avatar = "<div class='$tagClass'>";
					if (!empty($wp)) {
						$avatar.= sp_build_avatar_display($avatarData->userId, $wp, $link);
					} else {
						if ($avatarData->userId) $avatarData->email = $avatarData->userId;
						$avatar.= sp_build_avatar_display($avatarData->userId, get_avatar($avatarData->email, $avatarData->size), $link);
					}
					$avatar.= '</div>';

					if ($get) return $avatarData;

					# for wp avatars, we need to display/return and bail
					if (empty($echo)) {
						return $avatar;
					} else {
						echo $avatar."\n";
						return;
					}

				case 2: # Uploaded avatars
					$userAvatar = ($avatarData->object) ? $avatarData->avatar : sp_get_member_item($avatarData->userId, 'avatar');
					if (!empty($userAvatar['uploaded'])) {
						$avfile = $userAvatar['uploaded'];
						$avatarData->url = SFAVATARURL.$avfile;
						if (file_exists(SFAVATARDIR.$avfile)) {
							$checkWidth = true;
							$avatarData->path = SFAVATARDIR.$avfile;
							break 2; # if uploaded avatar exists, show it
						}
					}
					break;

				case 3: # SPF default avatars
				default:
					$checkWidth = true;
					if (empty($avatarData->userId)) {
						$image = 'guestdefault.png';
					} else {
						if ($avatarData->object) {
							$image = ($avatarData->admin) ? 'admindefault.png' : 'userdefault.png';
						} else {
							$image = (sp_is_forum_admin($avatarData->userId)) ? 'admindefault.png' : 'userdefault.png';
						}
					}
					$avatarData->url = SFAVATARURL.$image;
					$avatarData->path = SFAVATARDIR.$image;
					break 2; # defaults, so show it

				case 4: # Pool avatars
					$userAvatar = ($avatarData->object) ? $avatarData->avatar : sp_get_member_item($avatarData->userId, 'avatar');
					if (!empty($userAvatar['pool'])) {
						$pavfile = $userAvatar['pool'];
						$avatarData->url = SFAVATARPOOLURL.$pavfile;
						if (file_exists(SFAVATARPOOLDIR.$pavfile)) {
							$checkWidth = true;
							$avatarData->path = SFAVATARPOOLDIR.$pavfile;
							break 2; # if pool avatar exists, show it
						}
					}
					break;

				case 5: # Remote avatars
					$userAvatar = ($avatarData->object) ? $avatarData->avatar : sp_get_member_item($avatarData->userId, 'avatar');
					if (!empty($userAvatar['remote'])) {
						$ravfile = $userAvatar['remote'];
						$avatarData->url = $ravfile;
						# see if file exists
                    	$response = wp_remote_get($avatarData->url);
                        if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) == 200) {
							$checkWidth = true;
							$avatarData->path = $avatarData->url;
							break 2; # if remote avatar exists, show it
						}
					}
					break;
			}
		}

		# if required, check the avatar width
		if ($checkWidth) {
			if (!empty($avatarData->url)) {
				global $gis_error;
				$gis_error = '';
				set_error_handler('sp_gis_error');
				$avdata = getimagesize(str_replace(' ', '%20', $avatarData->path));
				restore_error_handler();
				if ($gis_error == '') {
					if ($avdata[0] < $avatarData->size) $avatarData->size = $avdata[0];
				}
			}
		}

		# allow themes/plugins to filter the final avatar data
		$avatarData = apply_filters('sph_Avatar', $avatarData, $a);

		if ($get) return $avatarData;

		# now display the avatar
		$width = ($avatarData->size > 0) ? " width='$avatarData->size'" : "";
		$avatar = sp_build_avatar_display($avatarData->userId, "<img src='".esc_url($avatarData->url)."' class='$imgClass'$width alt='' />", $link);

		$avatar = "<div class='$tagClass'>$avatar</div>\n";

		if ($echo) {
			echo $avatar;
		} else {
			return $avatar;
		}
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_UserForumRank()
#	Display a users forum ranks
#	Scope:	Site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_UserForumRank($args='', $ranks) {
	$defs = array('tagClass'	=> 'spForumRank',
				  'titleClass'	=> 'spForumRank',
			 	  'badgeClass'	=> 'spForumRank',
				  'showTitle'	=> 1,
				  'showBadge'	=> 1,
				  'echo'		=> 1,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ForumRank_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass	= esc_attr($tagClass);
	$titleClass	= esc_attr($titleClass);
	$badgeClass	= esc_attr($badgeClass);
	$showTitle	= (int) $showTitle;
	$showBadge	= (int) $showBadge;
	$echo		= (int) $echo;

	if (!$showTitle && !$showBadge) return;

	# the forum rank and title based on specified options
	$out = '';
	if (!empty($ranks)) {
		foreach ($ranks as $rank) {
			if ($rank['badge'] && $showBadge)	$out.= "<img src='".$rank['badge']."' class='$badgeClass' title='".esc_attr($rank['name'])."' />";
			if ($showTitle) {
				$out.= "<p class='$titleClass'>".$rank['name'].'</p>';
			}
		}
	}
	$out = apply_filters('sph_ForumRank', $out, $ranks, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_UserSpecialRank()
#	Display a users special ranks
#	Scope:	Site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_UserSpecialRank($args='', $ranks) {
	$defs = array('tagClass'	=> 'spSpecialRank',
				  'titleClass'	=> 'spSpecialRank',
			 	  'badgeClass'	=> 'spSpecialRank',
				  'showTitle'	=> 1,
				  'showBadge'	=> 1,
				  'echo'		=> 1,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_SpecialRank_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass	= esc_attr($tagClass);
	$titleClass	= esc_attr($titleClass);
	$badgeClass	= esc_attr($badgeClass);
	$showTitle	= (int) $showTitle;
	$showBadge	= (int) $showBadge;
	$echo		= (int) $echo;

	if (!$showTitle && !$showBadge) return;

	# the forum rank and title based on specified options
	$out = '';
	if (!empty($ranks)) {
		foreach ($ranks as $rank) {
			if ($rank['badge'] && $showBadge) $out.= "<img src='".$rank['badge']."' class='$badgeClass' title='".esc_attr($rank['name'])."' />";
			if ($showTitle) {
				$out.= "<p class='$titleClass'>".$rank['name'].'</p>';
			}
		}
	}
	$out = apply_filters('sph_SpecialRank', $out, $ranks, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_LoggedInOutLabel()
#	Display current users logged in/out status message
#	Scope:	Site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_LoggedInOutLabel($args='', $inLabel='', $outLabel='', $outLabelMember='') {
	$defs = array('tagId'		=> 'spLoggedInOutLabel',
				  'tagClass'	=> 'spLabel',
				  'echo'		=> 1,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_LoggedInOutLabel_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId		= esc_attr($tagId);
	$tagClass	= esc_attr($tagClass);
	$echo		= (int) $echo;

	global $spThisUser;

	if (is_user_logged_in() == true) {
		$label = sp_filter_text_display(str_replace('%USERNAME%', $spThisUser->display_name, $inLabel));
	} elseif($spThisUser->offmember) {
		$label = sp_filter_text_display(str_replace('%USERNAME%', $spThisUser->offmember, $outLabelMember));
	} else {
		if (!empty($spThisUser->display_name)) $outLabel.=' ('.$spThisUser->display_name.')';
		$label = sp_filter_text_display($outLabel);
	}
	$out = "<div id='$tagId' class='$tagClass'>$label</div>\n";
	$out = apply_filters('sph_LoggedInOutLabel', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_LoginOutButton()
#	Display current users Login/Logout Button
#	Scope:	Forum
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_LogInOutButton($args='', $inLabel='', $outLabel='', $toolTip='') {
	global $sfglobals;

	$login = sp_get_option('sflogin');
	$defs = array('tagId'		=> 'spLogInOutButton',
				  'tagClass'	=> 'spButton',
				  'logInLink'	=> '',
				  'logOutLink'	=> wp_nonce_url(site_url('wp-login.php?action=logout', 'login'), 'log-out'),
				  'logInIcon'	=> 'sp_LogInOut.png',
				  'logOutIcon'	=> 'sp_LogInOut.png',
				  'iconClass'	=> 'spIcon',
				  'echo'		=> 1,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_LogInOutButton_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId		= esc_attr($tagId);
	$tagClass	= esc_attr($tagClass);
	$logInLink	= esc_url($logInLink);
	$logOutLink = esc_url($logOutLink);
	$logInIcon	= sanitize_file_name($logInIcon);
	$logOutIcon = sanitize_file_name($logOutIcon);
	$iconClass	= esc_attr($iconClass);
	$toolTip	= esc_attr($toolTip);
	$echo		= (int) $echo;

	$out = "<a class='$tagClass vtip' id='$tagId' title='$toolTip' ";

	if (is_user_logged_in() == true) {
		$out.= "href='$logOutLink'>";
		if (!empty($logOutIcon)) $out.= "<img class='$iconClass' src='".SPTHEMEICONSURL.$logOutIcon."' alt=''/>";
		if (!empty($outLabel)) $out.= sp_filter_title_display($outLabel);
	} else {
		if (!empty($logInLink)) {
			$out.= "href='$logInLink'>";
		} else {
			$out.= "href='javascript:void(0);' onclick='jQuery(document).ready(function() { spjToggleLayer(\"spLoginForm\"); });'>";
		}
		if (!empty($logInIcon)) $out.= "<img class='$iconClass' src='".SPTHEMEICONSURL.$logInIcon."' alt=''/>";
		if (!empty($inLabel)) $out.= sp_filter_title_display($inLabel);
	}
	$out.= "</a>\n";
	$out = apply_filters('sph_LogInOutButton', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_LoginForm()
#	Display inline drop down login form
#	Scope:	Forum
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_LoginForm($args='') {
	# no form if logged in
	if (is_user_logged_in() == true) return;

	$defs = array('tagId'			=> 'spLoginForm',
				  'tagClass'		=> 'spForm',
				  'controlFieldset'	=> 'spControl',
				  'controlInput'	=> 'spControl',
				  'controlSubmit'	=> 'spSubmit',
				  'controlIcon'		=> 'spIcon',
				  'controlLink'		=> 'spLink',
				  'iconName'		=> 'sp_LogInOut.png',
				  'labelUserName'	=> '',
				  'labelPassword'	=> '',
				  'labelRemember'	=> '',
				  'labelRegister'	=> '',
				  'labelLostPass'	=> '',
				  'labelSubmit'		=> '',
				  'showRegister'	=> 1,
				  'showLostPass'	=> 1,
				  'registerLink'    => site_url('wp-login.php?action=register', 'login'),
				  'passwordLink'	=> site_url('wp-login.php?action=lostpassword', 'login'),
				  'echo'			=> 1
				  );

	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_LoginForm_args', $a);

	# sanitize before use
	$a['tagId']				= esc_attr($a['tagId']);
	$a['tagClass']			= esc_attr($a['tagClass']);
	$a['controlFieldset']	= esc_attr($a['controlFieldset']);
	$a['controlInput']		= esc_attr($a['controlInput']);
	$a['controlSubmit']		= esc_attr($a['controlSubmit']);
	$a['controlIcon']		= esc_attr($a['controlIcon']);
	$a['controlLink']		= esc_attr($a['controlLink']);
	$a['iconName']			= sanitize_file_name($a['iconName']);
	$a['showRegister']		= (int) $a['showRegister'];
	$a['showLostPass']		= (int) $a['showLostPass'];
	$a['labelUserName']		= sp_filter_title_display($a['labelUserName']);
	$a['labelPassword']		= sp_filter_title_display($a['labelPassword']);
	$a['labelRemember']		= sp_filter_title_display($a['labelRemember']);
	$a['labelRegister']		= sp_filter_title_display($a['labelRegister']);
	$a['labelLostPass']		= sp_filter_title_display($a['labelLostPass']);
	$a['labelSubmit']		= sp_filter_title_display($a['labelSubmit']);
	$a['registerLink']		= esc_url($a['registerLink']);
	$a['passwordLink']		= esc_url($a['passwordLink']);
	$a['echo']				= (int) $a['echo'];

	$out = "<div id='".$a['tagId']."' class='".$a['tagClass']."'>\n";
	$out.= sp_inline_login_form($a);
	$out.= "</div>\n";
	$out = apply_filters('sph_LoginForm', $out, $a);

	if ($a['echo']) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_RegisterButton()
#	Display registration button link for guests
#	Scope:	Forum
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_RegisterButton($args='', $label='', $toolTip='') {
	global $sfglobals;

	# should we show the register button?
	if (is_user_logged_in() == true || get_option('users_can_register') == false || $sfglobals['lockdown'] == true) return;

	$sflogin = sp_get_option('sflogin');
	$defs = array('tagId'		=> 'spRegisterButton',
				  'tagClass'	=> 'spButton',
				  'link'		=> site_url('wp-login.php?action=register', 'login'),
				  'icon'		=> 'sp_Registration.png',
				  'iconClass'	=> 'spIcon',
				  'echo'		=> 1,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_RegisterButton_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId		= esc_attr($tagId);
	$tagClass	= esc_attr($tagClass);
	$link		= esc_url($link);
	$icon		= sanitize_file_name($icon);
	$iconClass 	= esc_attr($iconClass);
	$toolTip	= esc_attr($toolTip);
	$echo		= (int) $echo;

	$out = "<a class='$tagClass vtip' id='$tagId' title='$toolTip' href='$link'>";
	if (!empty($icon)) $out.= "<img class='$iconClass' src='".SPTHEMEICONSURL.$icon."' alt=''/>";
	if (!empty($label)) $out.= sp_filter_title_display($label);
	$out.= '</a>';
	$out = apply_filters('sph_RegisterButton', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ProfileButton()
#	Display profile button link for users
#	Scope:	Site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ProfileEditButton($args='', $label='', $toolTip='') {
	if (!is_user_logged_in()) return;
	global $spThisUser;

	$defs = array('tagId' 		=> 'spProfileEditButton',
				  'tagClass' 	=> 'spButton',
				  'link' 		=> sp_build_profile_formlink($spThisUser->ID),
				  'icon' 		=> 'sp_ProfileForm.png',
				  'iconClass'	=> 'spIcon',
				  'echo'		=> 1,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ProfileButton_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId		= esc_attr($tagId);
	$tagClass	= esc_attr($tagClass);
	$link		= esc_url($link);
	$icon		= sanitize_file_name($icon);
	$iconClass 	= esc_attr($iconClass);
	$toolTip	= esc_attr($toolTip);
	$echo		= (int) $echo;

	$out = "<a rel='nofollow' class='$tagClass vtip' id='$tagId' title='$toolTip' href='$link'>";
	if (!empty($icon)) $out.= "<img class='$iconClass' src='".SPTHEMEICONSURL.$icon."' alt=''/>";
	if (!empty($label)) $out.= sp_filter_title_display($label);
	$out.= '</a>';
	$out = apply_filters('sph_ProfileEditButton', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_MemberButton()
#	Display members list button link for users
#	Scope:	Site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_MemberButton($args='', $label='', $toolTip='') {
	global $sfvars;
	if (!sp_get_auth('view_members_list', $sfvars['forumid'])) return;

	$defs = array('tagId' 		=> 'spMemberButton',
				  'tagClass' 	=> 'spButton',
				  'link' 		=> SPMEMBERLIST,
				  'icon' 		=> 'sp_MemberList.png',
				  'iconClass'	=> 'spIcon',
				  'echo'		=> 1,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_MemberButton_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId		= esc_attr($tagId);
	$tagClass	= esc_attr($tagClass);
	$link		= esc_url($link);
	$icon		= sanitize_file_name($icon);
	$iconClass 	= esc_attr($iconClass);
	$toolTip	= esc_attr($toolTip);
	$echo		= (int) $echo;

	$out = "<a class='$tagClass vtip' id='$tagId' title='$toolTip' href='$link'>";
	if (!empty($icon)) $out.= "<img class='$iconClass' src='".SPTHEMEICONSURL.$icon."' alt=''/>";
	if (!empty($label)) $out.= sp_filter_title_display($label);
	$out.= '</a>';
	$out = apply_filters('sph_MemberButton', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_LastVisitLabel()
#	Display last visited user message
#	Scope:	Forum
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_LastVisitLabel($args='', $label='') {
	# should we show the last visit label?
	global $spThisUser;
	if (empty($spThisUser->lastvisit)) return;

	$defs = array('tagId'		=> 'spLastVisitLabel',
				  'tagClass'	=> 'spLabelSmall',
				  'echo'		=> 1,
				  'get'			=> 0,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_LastVisitLabel_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId		= esc_attr($tagId);
	$tagClass	= esc_attr($tagClass);
	$echo		= (int) $echo;
	$get		= (int) $get;

	if ($get) return $spThisUser->lastvisit;

	$label= sp_filter_title_display(str_replace('%LASTVISIT%', sp_date('d', $spThisUser->lastvisit), $label));

	$out = "<span id='$tagId' class='$tagClass'>$label</span>\n";
	$out = apply_filters('sph_LastVisitLabel', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_QuickLinksForum()
#	Display QuickLinks forum dropdown
#	Scope:	Site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_QuickLinksForum($args='', $label='') {
	$defs = array('tagId'		=> 'spQuickLinksForum',
				  'tagClass'	=> 'spControl',
				  'echo'		=> 1,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_QuickLinksForum_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId		= esc_attr($tagId);
	$tagClass	= esc_attr($tagClass);
	$echo		= (int) $echo;

	$out = "<div class='$tagClass' id='$tagId'>\n";
	$out.= sp_render_group_forum_select(true, true, true, true, sp_filter_title_display($label), 'spQuickLinksForumSelect')."\n";
	$out.= "</div>\n";
	$out = apply_filters('sph_QuickLinksForum', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_QuickLinksTopic()
#	Display QuickLinks new topics dropdown
#	Scope:	Site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_QuickLinksTopic($args='', $label='') {
	global $spThisUser;

	$defs = array('tagId'		=> 'spQuickLinksTopic',
				  'tagClass'	=> 'spControl',
				  'show'		=> 20,
				  'echo'		=> 1,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_QuickLinksTopic_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId		= esc_attr($tagId);
	$tagClass	= esc_attr($tagClass);
	$show   	= (int) $show;
	$echo		= (int) $echo;

    $out = '';
	if (!empty($spThisUser->newposts['topics'])) {
		$spList = new spTopicList(array_slice($spThisUser->newposts['topics'], 0, $show, true), $show, true);
	} else {
		$spList = new spTopicList('', $show, true);
	}
	if (!empty($spList->listData)) {
		$out = "<div class='$tagClass' id='$tagId'>\n";
		$out.= "<select id='spQuickLinksTopicSelect' onchange='javascript:spjChangeURL(this)'>\n";
		$out.= "<option>$label</option>\n";
		$thisForum = 0;
		$group = false;
		foreach ($spList->listData as $spPost) {
			if ($spPost->forum_id != $thisForum) {
				if ($group) $out.= '</optgroup>';
				$out.= "<optgroup class='spList' label='".sp_create_name_extract($spPost->forum_name)."'>\n";
				$thisForum = $spPost->forum_id;
				$group = true;
			}
			$class = '';
			$title = "title='".SPTHEMEICONSURL."sp_QLBalloonNone.png'";
			if($spPost->post_status != 0) {
				$class = 'spPostMod';
				$title = "title='".SPTHEMEICONSURL."sp_QLBalloonRed.png'";
			} elseif(sp_is_in_users_newposts($spPost->topic_id)) {
				$class = 'spPostNew';
				$title = "title='".SPTHEMEICONSURL."sp_QLBalloonBlue.png'";
			}
			$out.= "<option class='$class' $title value='$spPost->post_permalink'>".sp_create_name_extract($spPost->topic_name)."</option>\n";
		}
		$out.= "</optgroup>\n";
		$out.= "</select>\n";
		$out.= "</div>\n";
	}

	$out = apply_filters('sph_QuickLinksTopic', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_BreadCrumbs()
#	Display Breadcrumbs
#	Scope:	Forum
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_BreadCrumbs($args='', $homeLabel='') {
	$defs = array('tagId'			=> 'spBreadCrumbs',
				  'tagClass' 		=> 'spBreadCrumbs',
				  'spanClass'		=> 'spBreadCrumbs',
				  'linkClass'		=> 'spLink',
				  'homeLink'		=> user_trailingslashit(SFSITEURL),
				  'groupLink'		=> 0,
				  'tree'			=> 0,
				  'truncate'		=> 0,
				  'icon'			=> 'sp_ArrowRight.png',
				  'iconClass'		=> 'spIcon',
				  'echo'			=> 1,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_BreadCrumbs_args', $a);
	extract($a, EXTR_SKIP);

	global $sfvars, $post;

	# sanitize before use
	$tagId 		= esc_attr($tagId);
	$tagClass 	= esc_attr($tagClass);
	$spanClass	= esc_attr($spanClass);
	$linkClass	= esc_attr($linkClass);
	$homeLink 	= esc_url($homeLink);
	$groupLink 	= (int) $groupLink;
	$tree 		= (int) $tree;
	$truncate	= (int) $truncate;
	$icon 		= sanitize_file_name($icon);
	$iconClass	= esc_attr($iconClass);
	$echo		= (int) $echo;
	if (!empty($homeLabel)) $homeLabel = sp_filter_title_display($homeLabel);

	# init some vars
	$breadCrumbs = '';
	$treeCount = 0;
	$crumbEnd = ($tree) ? '<br />' : '';
	$crumbSpace = ($tree) ? "<span class='$spanClass'></span>" : '';
	if (!empty($icon)) $icon = "<img src='".SPTHEMEICONSURL.$icon."' class='$iconClass' alt=''/>";

	# main container for breadcrumbs
	$breadCrumbs.= "<div id='$tagId' class='$tagClass'>";

	# home link
	if (!empty($homeLink) && !empty($homeLabel) && !(get_option('page_on_front') == sp_get_option('sfpage') && get_option('show_on_front') == 'page')) {
		$breadCrumbs.= "<a class='$linkClass' href='$homeLink'>".$icon.$homeLabel."</a>";
		$treeCount++;
	}

	# wp page link for forum
	$breadCrumbs.= $crumbEnd.str_repeat($crumbSpace, $treeCount)."<a class='$linkClass' href='".sp_url()."'>$icon$post->post_title</a>";
	$treeCount++;

	if ($groupLink) {
		if (isset($_GET['group'])) {
			$groupId = sp_esc_int($_GET['group']);
			$group = spdb_table(SFGROUPS, "group_id=$groupId", "row");
		} elseif (isset($sfvars['forumslug'])) {
			$group = sp_get_group_record_from_slug($sfvars['forumslug']);
		}
		if ($group) {
			$breadCrumbs.= $crumbEnd.str_repeat($crumbSpace, $treeCount)."<a class='$linkClass' href='".sp_build_qurl('group='.$group->group_id)."'>".$icon.sp_truncate(sp_filter_title_display($group->group_name), $truncate).'</a>';
			$treeCount++;
		}
	}

	# parent forum links if current forum is a sub-forum
	if (isset($sfvars['parentforumid'])) {
		$forumNames = array_reverse($sfvars['parentforumname']);
		$forumSlugs = array_reverse($sfvars['parentforumslug']);
		for ($x=0; $x < count($forumNames); $x++) {
			$breadCrumbs.= $crumbEnd.str_repeat($crumbSpace, $treeCount)."<a class='$linkClass' href='".sp_build_url($forumSlugs[$x], '', 0, 0)."'>".$icon.sp_truncate(sp_filter_title_display($forumNames[$x]), $truncate).'</a>';
			$treeCount++;
		}
	}

	# forum link (parent or dhild forum)
	if (!empty($sfvars['forumslug']) && $sfvars['forumslug'] != 'all') {
		# if showing a topic then check the return page of forum in transient store
		$returnPage = (empty($sfvars['topicslug'])) ? 1 : sp_pop_topic_page($sfvars['forumid']);
		$breadCrumbs.= $crumbEnd.str_repeat($crumbSpace, $treeCount)."<a class='$linkClass' href='".sp_build_url($sfvars['forumslug'], '', $returnPage, 0)."'>".$icon.sp_truncate(sp_filter_title_display($sfvars['forumname']), $truncate).'</a>';
		$treeCount++;
	}

	# topic link
	if (!empty($sfvars['topicslug'])) {
		$breadCrumbs.= $crumbEnd.str_repeat($crumbSpace, $treeCount)."<a class='$linkClass' href='".sp_build_url($sfvars['forumslug'], $sfvars['topicslug'], $sfvars['page'], 0)."'>".$icon.sp_truncate(sp_filter_title_display($sfvars['topicname']), $truncate).'</a>';
	}

	# allow plugins/themes to filter the breadcrumbs
	$breadCrumbs = apply_filters('sph_BreadCrumbs', $breadCrumbs, $a, $crumbEnd, $crumbSpace, $treeCount);

	# close the breadcrumb container
	$breadCrumbs.= '</div>';

	if ($echo) {
		echo $breadCrumbs;
	} else {
		return $breadCrumbs;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_UserNotices()
#	Display user Notices
#	Scope:	Global
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_UserNotices($args='', $label='') {
	global $spThisUser;

	$defs = array('tagId'		=> 'spUserNotices',
				  'tagClass'	=> 'spMessage',
				  'textClass'	=> 'spNoticeText',
				  'linkClass'	=> 'spNoticeLink',
				  'echo'		=> 1,
				  'get'			=> 0,
				  );

	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_UserNotices_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId		= esc_attr($tagId);
	$tagClass	= esc_attr($tagClass);
	$textClass	= esc_attr($textClass);
	$linkClass	= esc_attr($linkClass);
	$echo		= (int) $echo;
	$get		= (int) $get;
	$m			= '';

	if(!empty($spThisUser->user_notices)) {
		foreach ($spThisUser->user_notices as $notice) {
			$site = SFHOMEURL.'index.php?sp_ahah=remove-notice&amp;notice='.$notice->notice_id.'&amp;sfnonce='.wp_create_nonce('forum-ahah');
			$nid = 'noticeid-'.$notice->notice_id;
			$m.= "<div id='$nid'>\n";
			$m.= "<p class='$textClass'>".sp_filter_title_display($notice->message)." ";
			if (!empty($notice->link_text)) $m.= "<a class='$linkClass' href='".esc_url($notice->link)."'>".sp_filter_title_display($notice->link_text)."</a>";
			if (!empty($label)) $m.= "&nbsp;&nbsp;<a class='spLabelSmall' href='javascript:void(null)' onclick='spjRemoveNotice(\"$site\", \"$nid\");'>".sp_filter_title_display($label)."</a>";
			$m.= "</p></div>\n";
		}
	}

	$m = apply_filters('sph_UserNotices_Custom', $m, $a);

	if ($get) return $m;

	if(!empty($m)) {
		$out = "<div id='$tagId' class='$tagClass'>".$m."</div>\n";
		$out = apply_filters('sph_UserNotices', $out, $a);

		if ($echo) {
			echo $out;
		} else {
			return $out;
		}
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_UnreadPostsInfo()
#	Display Unread Posts Info
#	Scope:	Forum
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_UnreadPostsInfo($args='', $label='', $unreadToolTip='', $markToolTip='') {
	global $spThisUser;
	if (!$spThisUser->member) return; # only valid for members

	$defs = array('tagId'		=> 'spUnreadPostsInfo',
				  'tagClass' 	=> 'spUnreadPostsInfo',
				  'unreadLinkId'=> 'spUnreadPostsLink',
				  'unreadIcon'	=> 'sp_UnRead.png',
				  'markIcon'	=> 'sp_markRead.png',
				  'spanClass'	=> 'spLabel',
				  'iconClass'	=> 'spIcon',
				  'popup'		=> 1,
				  'echo'		=> 1,
				  'get'			=> 0,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_UnreadPostsInfo_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId	 		= esc_attr($tagId);
	$tagClass 		= esc_attr($tagClass);
	$unreadLinkId 	= esc_attr($unreadLinkId);
	$unreadIcon		= sanitize_file_name($unreadIcon);
	$markIcon		= sanitize_file_name($markIcon);
	$spanClass	 	= esc_attr($spanClass);
	$iconClass 		= esc_attr($iconClass);
	$popup			= (int) $popup;
	$echo			= (int) $echo;
	$get			= (int) $get;
	if (!empty($unreadToolTip)) $unreadToolTip	= esc_attr($unreadToolTip);
	if (!empty($markToolTip)) 	$markToolTip	= esc_attr($markToolTip);

	# Mark all as read
	global $spThisUser;
	$unreads = (empty($spThisUser->newposts)) ? $unreads = 0 : count($spThisUser->newposts['topics']);
	$label = str_ireplace('%COUNT%', $unreads, $label);
	if (!empty($label)) $label = sp_filter_title_display($label);

	if ($get) return $unreads;

	$out = "<div id='$tagId' class='$tagClass'>";
	$out.= "<span class='$spanClass'>$label</span>";
	if ($unreads > 0) {
        if ($popup) {
            $site = SFHOMEURL.'index.php?sp_ahah=newpostpopup&amp;action=all&amp;sfnonce='.wp_create_nonce('forum-ahah');
            $out.= "<a rel='nofollow' id='$unreadLinkId' href='javascript:void(null)' onclick='spjDialogAjax(this, \"$site\", \"$unreadToolTip\", 600, 800, 0);'>";
        } else {
            $out.= "<a rel='nofollow' id='$unreadLinkId' href='".sp_url('newposts')."'>";
        }
		$out.= "<img class='$iconClass vtip' src='".SPTHEMEICONSURL."$unreadIcon' title='$unreadToolTip' alt='' /></a>\n";

		$out.= "<a href='".SFMARKREAD."'><img class='$iconClass vtip' src='".SPTHEMEICONSURL."$markIcon' alt='' title='$markToolTip' /></a>";
	}
	$out.= "</div>\n";
	$out = apply_filters('sph_UnreadPostsInfo', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_SearchForm()
#	Display Search Form Basic
#	Scope:	Forum
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_SearchForm($args='') {
	global $sfglobals;
	$defs = array(
		'tagId'			        => 'spSearchForm',
		'tagClass'			    => 'spSearchSection',
		'icon'	                => 'sp_Search.png',
		'iconClass'		        => 'spIcon',
		'inputClass'		    => 'spControl',
		'inputWidth'			=> 20,
		'submitId'		        => 'spSearchButton',
		'submitClass'		    => 'spButton',
		'advSearchLinkClass'	=> 'spLink',
		'advSearchLink'			=> '',
		'advSearchId'	    	=> 'spSearchFormAdvanced',
		'advSearchClass'		=> 'spSearchFormAdvanced',
		'searchIncludeDef'		=> 1,
        'submitLabel'           => '',
        'advancedLabel'         => '',
        'lastSearchLabel'		=> '',
        'toolTip'               => '',
        'labelLegend'           => '',
        'labelScope'            => '',
        'labelCurrent'          => '',
        'labelAll'              => '',
        'labelMatchAny'         => '',
        'labelMatchAll'         => '',
        'labelMatchPhrase'      => '',
        'labelOptions'          => '',
        'labelPostTitles'       => '',
        'labelPostsOnly'        => '',
        'labelTitlesOnly'       => '',
        'labelWildcards'        => '',
        'labelMatchAnyChars'    => '',
        'labelMatchOneChar'     => '',
        'labelMinLength'        => '',
        'labelMemberSearch'     => '',
        'labelTopicsPosted'     => '',
        'labelTopicsStarted'    => '',
    	'echo'				    => 1,
	);

	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_SearchForm_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId				= esc_attr($tagId);
	$tagClass			= esc_attr($tagClass);
	$icon				= sanitize_file_name($icon);
	$iconClass 			= esc_attr($iconClass);
	$inputClass			= esc_attr($inputClass);
	$inputWidth			= (int) $inputWidth;
	$submitId    		= esc_attr($submitId);
	$submitClass		= esc_attr($submitClass);
	$advSearchLinkClass	= esc_attr($advSearchLinkClass);
	$advSearchLink		= esc_url($advSearchLink);
	$advSearchId		= esc_attr($advSearchId);
	$advSearchClass		= esc_attr($advSearchClass);
	$searchIncludeDef	= (int) $searchIncludeDef;
	$echo				= (int) $echo;

	if (!empty($submitLabel)) 		$submitLabel 	= sp_filter_title_display($submitLabel);
	if (!empty($advancedLabel)) 	$advancedLabel 	= sp_filter_title_display($advancedLabel);
	if (!empty($lastSearchLabel))	$lastSearchLabel = sp_filter_title_display($lastSearchLabel);
	if (!empty($toolTip)) 			$toolTip		= esc_attr($toolTip);

	# render the search form and advanced link
	$out = "<form id='$tagId' action='".SFHOMEURL."index.php?sp_ahah=search&amp;sfnonce=".wp_create_nonce('forum-ahah')."' method='post' name='sfsearch'>";
	$out.= "<div class='$tagClass'>";
	$out.= "<input type='text' id='searchvalue' class='$inputClass' size='$inputWidth' name='searchvalue' value='' />";
	$out.= "<a rel='nofollow' id='$submitId' class='$submitClass vtip' title='$toolTip' onclick='jQuery(document).ready(function() { spjValidateSearch({$sfglobals['mysql']['search']['min']}); });'><img class='$iconClass' src='".SPTHEMEICONSURL.$icon."' alt=''/>$submitLabel</a>";

	$out.= sp_InsertBreak('echo=0');

	$out.= "<a class='$advSearchLinkClass' ";
	if (!empty($advSearchLink)) {
		$out.= "href='$advSearchLink'>";
	} else {
		$out.= "href='javascript:void(0);' onclick='jQuery(document).ready(function() { spjToggleLayer(\"$advSearchId\"); });'>";
	}
	$out.= "$advancedLabel</a>";

	# are the search results we can return to?
	if(!isset($_GET['search']) && !empty($lastSearchLabel)) {
		$r = sp_get_transient(2, false);
		if($r) {
			$p = $r[0]['page'];
			$url = $r[0]['url']."&amp;search=$p";
			$out.= " |<a class='$advSearchLinkClass' rel='nofollow' href='$url'>$lastSearchLabel</a>";
		}
	}

	$out.= "</div>\n";

	$out.= sp_InsertBreak('echo=0');
	$out.= "<div id='$advSearchId' class='$advSearchClass'>".sp_inline_search_form($a).'</div>';
	$out.= "</form>\n";

	# finish it up
	$out = apply_filters('sph_SearchForm', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_GoToTop()
#	Displays link to top of forum page
#	Scope:	Forum
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_GoToTop($args='', $label='', $toolTip='') {
	$defs = array('tagClass' 	=> 'spGoToTop',
				  'icon'		=> 'sp_ArrowUp.png',
				  'iconClass'	=> 'spGoToTop',
				  'echo'		=> 1,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_GoToTop_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass 	= esc_attr($tagClass);
	$iconClass	= esc_attr($iconClass);
	$icon		= sanitize_file_name($icon);
	$echo		= (int) $echo;
	if (!empty($label)) 	$label 		= sp_filter_title_display($label);
	if (!empty($toolTip)) 	$toolTip	= esc_attr($toolTip);

	# render the go to bottom link
	$out = "<div class='$tagClass'>";
	$out.= "<a class='$tagClass' href='#spForumTop'>";
	if (!empty($icon)) $out.= "<img class='$iconClass vtip' src='".SPTHEMEICONSURL.$icon."' alt='' title='$toolTip' />";
	$out.= "$label</a>";
	$out.= "</div>\n";

	$out = apply_filters('sph_GoToTop', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_GoToBottom()
#	Displays link to bottom of forum page
#	Scope:	Forum
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_GoToBottom($args='', $label='', $toolTip='') {
	$defs = array('tagClass' 	=> 'spGoToBottom',
				  'icon'		=> 'sp_ArrowDown.png',
				  'iconClass'	=> 'spGoToBottom',
				  'echo'		=> 1,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_GoToBottom_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass 	= esc_attr($tagClass);
	$iconClass 	= esc_attr($iconClass);
	$icon		= sanitize_file_name($icon);
	$echo		= (int) $echo;

	if (!empty($label)) 	$label 		= sp_filter_title_display($label);
	if (!empty($toolTip)) 	$toolTip	= esc_attr($toolTip);

	# render the go to bottom link
	$out = "<div class='$tagClass'>";
	$out.= "<a class='$tagClass' href='#spForumBottom'>";
	if (!empty($icon)) $out.= "<img class='$iconClass vtip' src='".SPTHEMEICONSURL.$icon."' alt='' title='$toolTip' />";
	$out.= "$label</a>";
	$out.= "</div>\n";

	$out = apply_filters('sph_GoToBottom', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_AllRSSButton()
#	Display All Forum RSS Button
#	Scope:	site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_AllRSSButton($args='', $label='', $toolTip='') {
    global $spThisUser;

    if (!sp_get_auth('view_forum')) return;

	$defs = array('tagId' 		=> 'spAllRSSButton',
				  'tagClass' 	=> 'spLink',
				  'icon' 		=> 'sp_Feed.png',
				  'iconClass'	=> 'spIcon',
				  'echo'		=> 1,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_AllRSSButton_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId		= esc_attr($tagId);
	$tagClass	= esc_attr($tagClass);
	$icon		= sanitize_file_name($icon);
	$iconClass 	= esc_attr($iconClass);
	$toolTip	= esc_attr($toolTip);
	$echo		= (int) $echo;

	# only display all rss feed if at least one forum has rss on
	$forums = spdb_table(SFFORUMS, 'forum_rss_private=0');
	if ($forums) {
		$rssUrl = sp_get_option('sfallRSSurl');
		if (empty($rssUrl)) {
			$rssopt = sp_get_option('sfrss');
			if ($rssopt['sfrssfeedkey'] && isset($spThisUser->feedkey)) {
				$rssUrl = trailingslashit(sp_build_url('', '', 0, 0, 0, 1)).user_trailingslashit($spThisUser->feedkey);
			} else {
				$rssUrl = sp_build_url('', '', 0, 0, 0, 1);
			}
		}
	} else {
	   return;
	}

	$out = "<a class='$tagClass vtip' id='$tagId' title='$toolTip' rel='nofollow' href='$rssUrl'>";
	if (!empty($icon)) $out.= "<img class='$iconClass' src='".SPTHEMEICONSURL.$icon."' alt=''/>";
	if (!empty($label)) $out.= sp_filter_title_display($label);
	$out.= "</a>\n";
	$out = apply_filters('sph_AllRSSButton', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ForumLockdown()
#	Display Message when complete Forum  is locked down
#	Scope:	Forum
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ForumLockdown($args='', $Message='') {
	global $sfglobals;
	if($sfglobals['lockdown'] == false) return;

	$defs = array('tagId'		=> 'spForumLockdown',
				  'tagClass'	=> 'spMessage',
				  'echo'		=> 1,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ForumLockdown_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId		= esc_attr($tagId);
	$tagClass	= esc_attr($tagClass);
	$echo		= (int) $echo;

	$out = "<div id='$tagId' class='$tagClass'>".sp_filter_title_display($Message)."</div>\n";
	$out = apply_filters('sph_ForumLockdown', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_RecentPostList()
#	Displasys the recent post list (as used on front page by default)
#	Scope:	Forum
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_RecentPostList($args='', $label='') {
	global $spThisUser, $spListView, $spThisListTopic;
	$defs = array('tagId'		=> 'spRecentPostList',
				  'tagClass'	=> 'spRecentPostSection',
				  'labelClass'	=> 'spMessage',
				  'template'	=> 'spListView.php',
				  'show'		=> 20,
				  'group'		=> 0,
                  'admins'      => 0,
                  'mods'        => 1,
                  'first'       => 0,
				  'get'			=> 0
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_RecentPostList_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId		= esc_attr($tagId);
	$tagClass	= esc_attr($tagClass);
	$labelClass	= esc_attr($labelClass);
	$template	= sanitize_file_name($template);
	$show		= (int) $show;
	$group		= (int) $group;
	$admins		= (int) $admins;
	$mods		= (int) $mods;
	$first		= (int) $first;
	$label		= sp_filter_title_display($label);
	$get		= (int) $get;

    if ((!$admins && $spThisUser->admin) || (!$mods && $spThisUser->moderator)) return;

	echo "<div id='$tagId' class='$tagClass'>\n";
	echo "<div class='$labelClass'>$label</div>\n";
	$topics = (!empty($spThisUser->newposts['topics'])) ? $spThisUser->newposts['topics'] : '';

	if ($get) return $topics;

	$spListView = new spTopicList($topics, $show, $group, '', $first);

	sp_load_template($template);
	echo '</div>';
}

# --------------------------------------------------------------------------------------
#
#	sp_Acknowledgements()
#	Display Forum acknowledgements popup and url links
#	Scope:	Site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_Acknowledgements($args='', $label='', $toolTip='', $siteToolTip='') {
	$defs = array('tagId'    	=> 'spAck',
				  'tagClass' 	=> 'spAck',
				  'icon'        => 'sp_Information.png',
				  'iconClass'	=> 'spIcon',
				  'linkClass'	=> 'spLink',
				  'echo'		=> 1,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_AcknowledgementsLink_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId	 	= esc_attr($tagId);
	$tagClass 	= esc_attr($tagClass);
	$icon		= sanitize_file_name($icon);
	$iconClass 	= esc_attr($iconClass);
	$echo		= (int) $echo;

	if (!empty($label)) 	   $label = sp_filter_title_display($label);
	if (!empty($toolTip))      $toolTip	= esc_attr($toolTip);
	if (!empty($siteToolTip))  $siteToolTip	= esc_attr($siteToolTip);

	# build acknowledgements url and render link to SP and popup
	$site = SFHOMEURL.'index.php?sp_ahah=acknowledge&amp;sfnonce='.wp_create_nonce('forum-ahah');
	$out = "<div id='$tagId' class='$tagClass'>";
    $out .= "&copy; <a class='spLink vtip' title='$siteToolTip' href='http://simple-press.com' target='_blank'>Simple:Press</a>";
	$out.= "<a rel='nofollow' class='$linkClass vtip' title='$toolTip' href='javascript:void(null)' onclick='spjDialogAjax(this, \"$site\", \"$toolTip\", 600, 0, \"center\");'>";
	if (!empty($icon)) $out.= "<img class='$iconClass' src='".SPTHEMEICONSURL.$icon."' alt='' />";
	$out.= "$label</a>";
    $out.= "</div>\n";
	$out = apply_filters('sph_AcknowledgementsLink', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ForumTimeZone()
#	Display the timezone of the forum
#	Scope:	Site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ForumTimeZone($args='', $label='') {
	$defs = array('tagClass'	=> 'spForumTimeZone',
				  'echo'		=> 1,
				  'get'			=> 0,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ForumTimeZone_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass	= esc_attr($tagClass);
	$echo		= (int) $echo;
	$get		= (int) $get;

	# render the forum timezone
	$tz = get_option('timezone_string');
	if (empty($tz)) $tz = 'UTC '.get_option('gmt_offset');

	if ($get) return $tz;

	$out = "<div class='$tagClass'>";
	if (!empty($label)) $out.= '<span>'.sp_filter_title_display($label).'</span>';
	$out.= $tz;
	$out.= '</div>';
	$out = apply_filters('sph_ForumTimeZone', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_UserTimeZone()
#	Display the timezone of the forum
#	Scope:	Site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_UserTimeZone($args='', $label='') {
    global $spThisUser;
    if ($spThisUser->guest) return;

	$defs = array('tagClass'	=> 'spUserTimeZone',
				  'echo'		=> 1,
				  'get'			=> 0,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_UserTimeZone_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass	= esc_attr($tagClass);
	$echo		= (int) $echo;
	$get		= (int) $get;

	# render the user timezone
	$tz = (!empty($spThisUser->timezone_string)) ? $spThisUser->timezone_string : get_option('timezone_string');

	if ($get) return $tz;

	$out = "<div class='$tagClass'>";
	if (!empty($label)) $out.= '<span>'.sp_filter_title_display($label).'</span>';
	$out.= $tz;
	$out.= '</div>';
	$out = apply_filters('sph_UserTimeZone', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_OnlineStats()
#	Display the online stats
#	Scope:	Site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_OnlineStats($args='', $mostLabel='', $currentLabel='', $browsingLabel='', $guestLabel='') {
	$defs = array('pMostClass'		=> 'spMostOnline',
				  'pCurrentClass'	=> 'spCurrentOnline',
				  'pBrowsingClass'	=> 'spCurrentBrowsing',
                  'link_names'      => 1,
                  'usersOnly'       => 0,
				  'echo'			=> 1,
				  'get'				=> 0,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_OnlineStats_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$pMostClass 	= esc_attr($pMostClass);
	$pCurrentClass 	= esc_attr($pCurrentClass);
	$pBrowsingClass	= esc_attr($pBrowsingClass);
	$link_names		= (int) $link_names;
	$echo			= (int) $echo;
	$get			= (int) $get;
	if (!empty($mostLabel)) 	$mostLabel 		= sp_filter_title_display($mostLabel);
	if (!empty($currentLabel)) 	$currentLabel 	= sp_filter_title_display($currentLabel);
	if (!empty($browsingLabel)) $browsingLabel	= sp_filter_title_display($browsingLabel);
	if (!empty($guestLabel)) 	$guestLabel 	= sp_filter_title_display($guestLabel);

    # grab most online stat and update if new most
    $max = sp_get_option('spMostOnline');
	$online = spdb_count(SFTRACK);
	if ($online > $max) {
		$max = $online;
		sp_update_option('spMostOnline', $max);
	}
	$members = sp_get_members_online();

	if ($get) {
		$getData = new stdClass();
		$getData->max = $max;
		$getData->members = $members;
		return $getData;
	}

	# render the max online stats
	$out = "<p class='$pMostClass'><span>$mostLabel</span>$max</p>";

	# render the current online stats
	$browse = '';
	$out.= "<p class='$pCurrentClass'><span>$currentLabel</span>";

	# members online
	if ($members) {
		global $spThisUser, $sfvars;

		$firstOnline = true;
		$firstBrowsing = true;
		$spMemberOpts = sp_get_option('sfmemberopts');
		foreach ($members as $user) {
			$userOpts = unserialize($user->user_options);
			if ($spThisUser->admin || !$spMemberOpts['sfhidestatus'] || !$userOpts['hidestatus']) {
				if (!$firstOnline) $out.= ', ';
				$out.= sp_build_name_display($user->trackuserid, sp_filter_name_display($user->display_name), $link_names);
				$firstOnline = false;

				# Set up the members browsing curent item list while here
				if (($sfvars['pageview'] == 'forum' && $user->forum_id == $sfvars['forumid']) ||
				    ($sfvars['pageview'] == 'topic' && $user->topic_id == $sfvars['topicid'])) {
					if (!$firstBrowsing) $browse.= ', ';
					$browse.= sp_build_name_display($user->trackuserid, sp_filter_name_display($user->display_name), $link_names);
					$firstBrowsing = false;
				}
			}
		}
	}

	# guests online
	if (!$usersOnly && $online && ($online > count($members))) {
		$guests = ($online - count($members));
		$out.= "<br />$guests $guestLabel";
	}
	$out.= '</p>';

	# Members and guests browsing
	$out.= "<p class='$pBrowsingClass'>";
	$guestBrowsing = sp_guests_browsing();
	if ($browse || $guestBrowsing) $out.= "<span>$browsingLabel</span>";
	if ($browse) $out.= $browse;
	if (!$usersOnly && $guestBrowsing != 0) $out.= "<br />$guestBrowsing $guestLabel";
	$out.= "</p>\n";

	# finish it up
	$out = apply_filters('sph_OnlineStats', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ForumStats()
#	Display the forum stats section
#	Scope:	Site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ForumStats($args='', $titleLabel='', $groupsLabel='', $forumsLabel='', $topicsLabel='', $postsLabel='') {
	$defs = array('pTitleClass'		=> 'spForumStatsTitle',
				  'pGroupsClass'	=> 'spGroupsStats',
				  'pForumsClass'	=> 'spForumsStats',
				  'pTopicsClass'	=> 'spTopicsStats',
				  'pPostsClass'		=> 'spPostsStats',
				  'echo'			=> 1,
				  'get'				=> 0,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ForumStats_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$pTitleClass 	= esc_attr($pTitleClass);
	$pGroupsClass 	= esc_attr($pGroupsClass);
	$pForumsClass 	= esc_attr($pForumsClass);
	$pTopicsClass 	= esc_attr($pTopicsClass);
	$pPostsClass 	= esc_attr($pPostsClass);
	$echo			= (int) $echo;
	$get			= (int) $get;
	if (!empty($titleLabel)) 	$titleLabel 	= sp_filter_title_display($titleLabel);
	if (!empty($groupsLabel))	$groupsLabel	= sp_filter_title_display($groupsLabel);
	if (!empty($forumsLabel)) 	$forumsLabel 	= sp_filter_title_display($forumsLabel);
	if (!empty($topicsLabel)) 	$topicsLabel 	= sp_filter_title_display($topicsLabel);
	if (!empty($postsLabel)) 	$postsLabel 	= sp_filter_title_display($postsLabel);

	# get stats for forum stats
	$counts = sp_get_option('spForumStats');

	if ($get) return $counts;

	# render the forum stats
	$out = "<p class='$pTitleClass'>$titleLabel</p>";
	$out.= "<p class='$pGroupsClass'>".$groupsLabel.$counts->groups.'</p>';
	$out.= "<p class='$pForumsClass'>".$forumsLabel.$counts->forums.'</p>';
	$out.= "<p class='$pTopicsClass'>".$topicsLabel.$counts->topics.'</p>';
	$out.= "<p class='$pPostsClass'>".$postsLabel.$counts->posts."</p>\n";

	# finish it up
	$out = apply_filters('sph_ForumStats', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_MembershipStats()
#	Display the membeship stats section
#	Scope:	Site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_MembershipStats($args='', $titleLabel='', $membersLabel='', $guestsLabel='', $modsLabel='', $adminsLabel='') {
	$defs = array('pTitleClass'		=> 'spMembershipStatsTitle',
				  'pMembersClass'	=> 'spMemberStats',
				  'pGuestsClass'	=> 'spGuestsStats',
				  'pModsClass'		=> 'spModsStats',
				  'pAdminsClass'	=> 'spAdminsStats',
				  'echo'			=> 1,
				  'get'				=> 0,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_MembershipStats_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$pTitleClass 	= esc_attr($pTitleClass);
	$pMembersClass 	= esc_attr($pMembersClass);
	$pGuestsClass 	= esc_attr($pGuestsClass);
	$pModsClass 	= esc_attr($pModsClass);
	$pAdminsClass 	= esc_attr($pAdminsClass);
	$echo			= (int) $echo;
	$get			= (int) $get;
	if (!empty($titleLabel)) $titleLabel = sp_filter_title_display($titleLabel);

	# get stats for membership stats
	$stats = sp_get_option('spMembershipStats');

	if ($get) return $stats;

	if (!empty($guestsLabel)) 	$guestsLabel 	= sp_filter_title_display(str_replace('%COUNT%', $stats['guests'], 	$guestsLabel));
	if (!empty($membersLabel)) 	$membersLabel 	= sp_filter_title_display(str_replace('%COUNT%', $stats['members'],	$membersLabel));
	if (!empty($modsLabel)) 	$modsLabel 		= sp_filter_title_display(str_replace('%COUNT%', $stats['mods'],    $modsLabel));
	if (!empty($adminsLabel)) 	$adminsLabel 	= sp_filter_title_display(str_replace('%COUNT%', $stats['admins'], 	$adminsLabel));

	# render the forum stats
	$out = "<p class='$pTitleClass'>$titleLabel</p>";
	$out.= "<p class='$pGuestsClass'>$guestsLabel</p>";
	$out.= "<p class='$pMembersClass'>$membersLabel</p>";
	$out.= "<p class='$pModsClass'>$modsLabel</p>";
	$out.= "<p class='$pAdminsClass'>$adminsLabel</p>\n";

	# finish it up
	$out = apply_filters('sph_MembershipStats', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_TopPostersStats()
#	Display the top poster stats section
#	Scope:	Site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_TopPostersStats($args='', $titleLabel='') {
	$defs = array('pTitleClass'		=> 'spTopPosterStatsTitle',
				  'pPosterClass'	=> 'spPosterStats',
                  'link_names'      => 1,
				  'echo'			=> 1,
				  'get'				=> 0,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_TopStats_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$pTitleClass 	= esc_attr($pTitleClass);
	$pPosterClass 	= esc_attr($pPosterClass);
	$link_names		= (int) $link_names;
	$echo			= (int) $echo;
	$get			= (int) $get;
	if (!empty($titleLabel)) $titleLabel = sp_filter_title_display($titleLabel);

	# get stats for top poster stats
	$topPosters = sp_get_option('spPosterStats');

	if ($get) return $topPosters;

	# render the forum stats
	$out = "<p class='$pTitleClass'>$titleLabel</p>";
	if ($topPosters) {
		foreach ($topPosters as $poster) {
			if ($poster->posts > 0)	$out.= "<p class='$pPosterClass'>".sp_build_name_display($poster->user_id, sp_filter_name_display($poster->display_name), $link_names).': '.$poster->posts.'</p>';
		}
	}

	# finish it up
	$out = apply_filters('sph_TopStats', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_NewMembers()
#	Display the latest new members
#	Scope:	Site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_NewMembers($args='', $titleLabel='') {
	$defs = array('tagClass'        => 'spNewMembers',
				  'pTitleClass'     => 'spNewMembersTitle',
				  'spanClass'       => 'spNewMembersList',
                  'link_names'      => 1,
				  'echo'		    => 1,
				  'get'		        => 0,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_NewMembers_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass 		= esc_attr($tagClass);
	$pTitleClass 	= esc_attr($pTitleClass);
	$spanClass 		= esc_attr($spanClass);
	$link_names		= (int) $link_names;
	$echo			= (int) $echo;
	$get			= (int) $get;
	if (!empty($titleLabel)) $titleLabel = sp_filter_title_display($titleLabel);

	# render the forum stats
	$out = "<div class='$tagClass'>";
	$out.= "<p class='$pTitleClass'><span class='$pTitleClass'>$titleLabel</span>";

	$newMemberList = sp_get_option('spRecentMembers');

	if ($get) return $newMemberList;

	if ($newMemberList) {
		$first = true;
		$out.= "<span class='$spanClass'>";
		foreach ($newMemberList as $member) {
			if (!$first) $out.= ', ';
			$out.= sp_build_name_display($member['id'], sp_filter_name_display($member['name']), $link_names);
			$first = false;
		}
		$out.='</span>';
	}
	$out.='</p>';

	# finish it up
	$out.= "</div>\n";
	$out = apply_filters('sph_NewMembers', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ModsList()
#	Display the list of moderators
#	Scope:	Site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ModsList($args='', $titleLabel='') {
	$defs = array('tagClass'	=> 'spModerators',
				  'pTitleClass'	=> 'spModeratorsTitle',
				  'spanClass'	=> 'spModeratorList',
                  'link_names'  => 1,
				  'echo'		=> 1,
				  'get'			=> 0,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ModsList_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass 		= esc_attr($tagClass);
	$pTitleClass 	= esc_attr($pTitleClass);
	$spanClass 		= esc_attr($spanClass);
	$link_names		= (int) $link_names;
	$echo			= (int) $echo;
	$get			= (int) $get;
	if (!empty($titleLabel)) $titleLabel = sp_filter_title_display($titleLabel);

	# get stats for moderator stats
	$mods = sp_get_option('spModStats');

	if ($get) return $mods;

	# render the moderators list
	$out = "<div class='$tagClass'>";
	$out.= "<p class='$pTitleClass'><span class='$pTitleClass'>$titleLabel</span>";
	if ($mods) {
		$first = true;
		$out.= "<span class='$spanClass'>";
		foreach ($mods as $mod) {
			if (!$first) $out.= ', ';
			$out.= sp_build_name_display($mod['user_id'], sp_filter_name_display($mod['display_name']), $link_names).' ('.$mod['posts'].')';
			$first = false;
		}
		$out.='</span>';
	}
	$out.='</p>';

	# finish it up
	$out.= "</div>\n";
	$out = apply_filters('sph_ModsList', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_AdminsList()
#	Display the list of administrators
#	Scope:	Site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_AdminsList($args='', $titleLabel='') {
	$defs = array('tagClass'	=> 'spAdministrators',
				  'pTitleClass'	=> 'spAdministratorsTitle',
				  'spanClass'	=> 'spAdministratorsList',
                  'link_names'  => 1,
				  'echo'		=> 1,
				  'get'			=> 0,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_AdminsList_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass 		= esc_attr($tagClass);
	$pTitleClass 	= esc_attr($pTitleClass);
	$spanClass 		= esc_attr($spanClass);
	$link_names		= (int) $link_names;
	$echo			= (int) $echo;
	$get			= (int) $get;
	if (!empty($titleLabel)) $titleLabel = sp_filter_title_display($titleLabel);

	# get stats for admin stats
	$admins = sp_get_option('spAdminStats');

	if ($get) return $admins;

	# render the admins list
	$out = "<div class='$tagClass'>";
	$out.= "<p class='$pTitleClass'><span class='$pTitleClass'>$titleLabel</span>";
	if ($admins) {
		$first = true;
		$out.= "<span class='$spanClass'>";
		foreach ($admins as $admin) {
			if (!$first) $out.= ', ';
			$out.= sp_build_name_display($admin['user_id'], sp_filter_name_display($admin['display_name']), $link_names).' ('.$admin['posts'].')';
			$first = false;
		}
		$out.='</span>';
	}
	$out.='</p>';

	# finish it up
	$out.= "</div>\n";
	$out = apply_filters('sph_AdminsList', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_Signature()
#	Display a specified signature
#	Scope:	Site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_Signature($args, $sig)
{
	$defs = array('tagClass'	=> 'spSignature',
				  'echo'		=> 1,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_Signature_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass	= esc_attr($tagClass);
	$echo		= (int) $echo;

	# force sig to have no follow in links and follow size limits
	$sig = sp_filter_save_nofollow($sig);
	$sig = sp_filter_signature_display($sig);

	# render the signature
	$out = "<div class='$tagClass'>";
	$out.= $sig;
	$out.= '</div>'."\n";

	$out = apply_filters('sph_Signature', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_OnlineStatus()
#	Display a users online status
#	Scope:	site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_OnlineStatus($args='', $user, $userProfile='') {
	global $spThisUser;
	$defs = array('tagClass'		=> 'spOnlineStatus',
				  'onlineIcon' 		=> 'sp_UserOnline.gif',
				  'offlineIcon'		=> 'sp_UserOffline.gif',
				  'echo'			=> 1,
				  'get'				=> 0,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_OnlineStatus_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass	= esc_attr($tagClass);
	$onlineIcon		= sanitize_file_name($onlineIcon);
	$offlineIcon	= sanitize_file_name($offlineIcon);
	$echo		= (int) $echo;
	$get		= (int) $get;

	# output display name
	$out = '';
	if (empty($userProfile)) $userProfile = new spUser($user);
	$spMemberOpts = sp_get_option('sfmemberopts');
	if (($spThisUser->admin || (!$spMemberOpts['sfhidestatus'] || !$userProfile->user_options['hidestatus'])) && sp_is_online($user)) {
		$icon = SPTHEMEICONSURL.sanitize_file_name($onlineIcon);
		if ($get) return true;
	} else {
		$icon = SPTHEMEICONSURL.sanitize_file_name($offlineIcon);
		if ($get) return false;
	}

	$out.= "<img class='$tagClass' src='$icon' />";

	$out = apply_filters('sph_OnlineStatus', $out, $user, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_page_prev()
#	sp_page_next()
#	sp_page_url()
#
#	Internally used page link procesing - can not be called directly
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_page_prev($curPage, $pnShow, $baseUrl, $linkClass, $iconClass, $prevIcon, $nextIcon, $toolTip, $search) {
	$start = max($curPage - $pnShow, 1);
	$end = $curPage - 1;
	$out = '' ;

	if ($start > 1) {
		$out.= sp_page_url(1, $baseUrl, 'none', $linkClass, $iconClass, $prevIcon, $nextIcon, $toolTip, $search);
		$out.= sp_page_url($curPage - 1, $baseUrl, 'prev', $linkClass, $iconClass, $prevIcon, $nextIcon, $toolTip, $search);
	}

	if ($end > 0) {
		for ($i = $start; $i <= $end; $i++) {
			$out.= sp_page_url($i, $baseUrl, 'none', $linkClass, $iconClass, $prevIcon, $nextIcon, $toolTip, $search);
		}
	} else {
		$end = 0;
	}
	return $out;
}

function sp_page_next($curPage, $totalPages, $pnShow, $baseUrl, $linkClass, $iconClass, $prevIcon, $nextIcon, $toolTip, $search) {
	$start = $curPage + 1;
	$end = min($curPage + $pnShow, $totalPages);
	$out = '';

	if ($start <= $totalPages) {
		for ($i = $start; $i <= $end; $i++) {
			$out.= sp_page_url($i, $baseUrl, 'none', $linkClass, $iconClass, $prevIcon, $nextIcon, $toolTip, $search);
		}
		if ($end < $totalPages) {
			$out.= sp_page_url($curPage + 1, $baseUrl, 'next', $linkClass, $iconClass, $prevIcon, $nextIcon, $toolTip, $search);
			$out.= sp_page_url($totalPages, $baseUrl, 'none', $linkClass, $iconClass, $prevIcon, $nextIcon, $toolTip, $search);
		}
	} else {
		$start = 0;
	}
	return $out;
}

function sp_page_url($thisPage, $baseUrl, $iconType, $linkClass, $iconClass, $prevIcon, $nextIcon, $toolTip, $search) {
	$toolTip = str_ireplace('%PAGE%', $thisPage, $toolTip);

	$out = "<a href='";
	if (is_int($search)) { # normal forum search puts page number in search query arg
		$out.= user_trailingslashit($baseUrl.'&amp;search='.$thisPage);
	} else {
		$url = ($thisPage > 1) ? trailingslashit($baseUrl).'page-'.$thisPage : $baseUrl;
		$url = user_trailingslashit($url);
		if (!empty($search)) { # members list search has the search arg in search query arg
			$param['search'] = $search;
			$url = add_query_arg($param, $url);
			$url = sp_filter_wp_ampersand($url);
		}
		$out.= $url;
	}

	Switch ($iconType) {
		case 'none':
			$out.= "' class='$linkClass vtip' title='$toolTip'>$thisPage</a>";
			break;
		case 'prev':
			$out.= "' class='$linkClass $iconClass'><img class='$iconClass vtip' src='$prevIcon' title='$toolTip' alt='' /></a>";
			break;
		case 'next':
			$out.= "' class='$linkClass $iconClass'><img class='$iconClass vtip' src='$nextIcon' title='$toolTip' alt='' /></a>";
			break;
	}
	return $out;
}

?>