<?php
/*
Simple:Press
Template Function Handler
$LastChangedDate: 2012-04-06 14:04:45 -0700 (Fri, 06 Apr 2012) $
$Rev: 8284 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');


# --------------------------------------------------------------------------------------
#
#	sp_ProfileEdit()
#	Display profile tabs and forms for current user
#	Scope:	site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ProfileEdit($tabSlug='profile', $menuSlug='') {
	# is this edit for current user of admin edit of user
	global $sfvars, $spThisUser;
	if (!empty($sfvars['member'])) {
		$dname = urldecode($sfvars['member']);
		$userid = spdb_table(SFUSERS, "user_login='$dname'", 'ID');
	} else {
		$userid = $spThisUser->ID;
	}

    if (empty($userid) || ($spThisUser->ID != $userid && !$spThisUser->admin)) {
		sp_notify(1, sp_text('Invalid profile request'));
		$out = sp_render_queued_notification();
		$out.= '<div class="spMessage">';
		$out.= apply_filters('sph_ProfileErrorMsg', sp_text('Sorry, an invalid profile request was detected. Do you need to log in?'));
		$out.= '</div>';
		echo $out;
		return;
    }
	sp_SetupUserProfileData($userid);

	# display the profile tabs
    do_action('sph_profile_edit_before');

	$tabs = sp_profile_get_tabs();
	if (!empty($tabs)) {
		echo '<ul id="spProfileTabs">';
		$first = true;
		$thisTab = '';
		foreach ($tabs as $tab) {
			# do we need an auth check?
			$authCheck = (empty($tab['auth'])) ? true : sp_get_auth($tab['auth'], '', $userid);

			# is this tab being displayed and does user have auth to see it?
			if ($authCheck && $tab['display']) {
				$class = ($first) ? "class='current'" : '';
				$thisTab = ($first) ? $tab : $thisTab;
				$first = false;
			    $ahahURL = SFHOMEURL.'index.php?sp_ahah=profile&amp;sfnonce='.wp_create_nonce('forum-ahah').'&amp;tab='.$tab['slug']."&amp;user=$userid&amp;rand=".rand();
                if (is_ssl()) $ahahURL = str_replace('http://', "https://", $ahahURL);
				echo "<li><a rel='nofollow' id='spProfileTab-".esc_attr($tab['slug'])."' $class href='$ahahURL'>".$tab['name'].'</a></li>';
			}
		}
		echo '</ul>';

        do_action('sph_profile_edit_after_tabs');

		# output the profile content area
		# dont need to fill as the js on page load will load default panel
		echo '<div id="spProfileContent">';
		echo '</div>';

		# message area
		echo '<div id="spProfileMessage"></div>';

        do_action('sph_profile_edit_after');

		# inline js to create profile tabs
		global $firstTab, $firstMenu;
		$firstTab = $tabSlug;
		$firstMenu = $menuSlug;

		# are we forcing password change on first login?
		if (isset($spThisUser->sp_change_pw) && $spThisUser->sp_change_pw) {
			$firstTab = 'profile';
			$firstMenu = 'account-settings';
		}

    	add_action('wp_footer', 'sp_ProfileEditFooter');
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ProfileShowHeader()
#	Display a users profile
#	Scope:	site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ProfileShowHeader($args='', $label='') {
	global $spThisUser, $spProfileUser;

	if (!sp_get_auth('view_profiles')) return;

	$defs = array('tagId'			=> 'spProfileShowHeader',
				  'tagClass'		=> 'spProfileShowHeader',
				  'editClass'		=> 'spProfileShowHeaderEdit',
				  'onlineStatus'	=> 1,
				  'statusClass'		=> 'spOnlineStatus',
				  'onlineIcon' 		=> 'sp_UserOnline.gif',
				  'offlineIcon'		=> 'sp_UserOffline.gif',
				  'echo'			=> 1,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ProfileShowHeader_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId			= esc_attr($tagId);
	$tagClass		= esc_attr($tagClass);
	$editClass		= esc_attr($editClass);
	$statusClass	= esc_attr($statusClass);
	$onlineStatus	= (int) $onlineStatus;
	$label 			= str_ireplace('%USER%', $spProfileUser->display_name, $label);
	$label			= sp_filter_title_display($label);
	$echo			= (int) $echo;

	# output the header
	$adminEdit = '';
	$out = "<div id='$tagId' class='$tagClass'>$label$adminEdit";
	if ($spThisUser->admin) {
		$out.= '<a href="'.sp_url('profile/'.urlencode($spProfileUser->user_login).'/edit').'">';
		$out.= " <span class='$editClass'>(".sp_text('Edit User Profile').')</span>';
		if ($onlineStatus) $out.= sp_OnlineStatus("tagClass=$statusClass&onlineIcon=$onlineIcon&offlineIcon=$offlineIcon&echo=0", $spProfileUser->ID, $spProfileUser);
		$out.= '</a>';
	}
	$out.= "</div>\n";

	$out = apply_filters('sph_ProfileShowHeader', $out, $spProfileUser, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ProfileShowDisplayName()
#	Display a users profile
#	Scope:	site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ProfileShowDisplayName($args='', $label='') {
	global $spProfileUser;

	if (!sp_get_auth('view_profiles')) return;

	$defs = array('tagClass'	=> 'spProfileShowDisplayName',
				  'leftClass'	=> 'spColumnSection spProfileLeftCol',
				  'middleClass'	=> 'spColumnSection spProfileSpacerCol',
				  'rightClass'	=> 'spColumnSection spProfileRightCol',
				  'echo'		=> 1,
				  'get'			=> 0,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ProfileShowDisplayName_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass		= esc_attr($tagClass);
	$leftClass		= esc_attr($leftClass);
	$middleClass	= esc_attr($middleClass);
	$rightClass		= esc_attr($rightClass);
	$label			= sp_filter_title_display($label);
	$echo			= (int) $echo;
	$get			= (int) $get;

	if ($get) return $spProfileUser->display_name;

	# output display name
	$out = '';
	$out.= "<div class='$leftClass'>";
	$out.= "<p class='$tagClass'>$label:</p>";
	$out.= '</div>';
	$out.= "<div class='$middleClass'></div>";
	$out.= "<div class='$rightClass'>";
	$out.= "<p class='$tagClass'>$spProfileUser->display_name</p>";
	$out.= "</div>\n";

	$out = apply_filters('sph_ProfileShowDisplayName', $out, $spProfileUser, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ProfileShowFirstName()
#	Display a users profile
#	Scope:	site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ProfileShowFirstName($args='', $label='') {
	global $spProfileUser;

	if (!sp_get_auth('view_profiles')) return;

	$defs = array('tagClass'	=> 'spProfileShowFirstName',
				  'leftClass'	=> 'spColumnSection spProfileLeftCol',
				  'middleClass'	=> 'spColumnSection spProfileSpacerCol',
				  'rightClass'	=> 'spColumnSection spProfileRightCol',
				  'showEmpty' 	=> 0,
				  'echo'		=> 1,
				  'get'			=> 0,
				  );

	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ProfileShowFirstName_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass		= esc_attr($tagClass);
	$leftClass		= esc_attr($leftClass);
	$middleClass	= esc_attr($middleClass);
	$rightClass		= esc_attr($rightClass);
	$showEmpty		= (int) $showEmpty;
	$label			= sp_filter_title_display($label);
	$echo			= (int) $echo;
	$get			= (int) $get;

	if ($get) return $spProfileUser->first_name;

	# output first name
	if (!empty($spProfileUser->first_name) || $showEmpty) {
		$out = '';
		$out.= "<div class='$leftClass'>";
		$out.= "<p class='$tagClass'>$label:</p>";
		$out.= '</div>';
		$out.= "<div class='$middleClass'></div>";
		$out.= "<div class='$rightClass'>";
		$name = (empty($spProfileUser->first_name)) ? '&nbsp;' : $spProfileUser->first_name;
		$out.= "<p class='$tagClass'>$name</p>";
		$out.= "</div>\n";

		$out = apply_filters('sph_ProfileShowFirstName', $out, $spProfileUser, $a);

		if ($echo) {
			echo $out;
		} else {
			return $out;
		}
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ProfileShowLastName()
#	Display a users profile
#	Scope:	site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ProfileShowLastName($args='', $label='') {
	global $spProfileUser;

	if (!sp_get_auth('view_profiles')) return;

	$defs = array('tagClass'	=> 'spProfileShowLastName',
				  'leftClass'	=> 'spColumnSection spProfileLeftCol',
				  'middleClass'	=> 'spColumnSection spProfileSpacerCol',
				  'rightClass'	=> 'spColumnSection spProfileRightCol',
				  'showEmpty' 	=> 0,
				  'echo'		=> 1,
				  'get'			=> 0,
				  );

	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ProfileShowLastName_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass		= esc_attr($tagClass);
	$leftClass		= esc_attr($leftClass);
	$middleClass	= esc_attr($middleClass);
	$rightClass		= esc_attr($rightClass);
	$showEmpty		= (int) $showEmpty;
	$label			= sp_filter_title_display($label);
	$echo			= (int) $echo;
	$get			= (int) $get;

	if ($get) return $spProfileUser->last_name;

	# output first name
	if (!empty($spProfileUser->last_name) || $showEmpty) {
		$out = '';
		$out.= "<div class='$leftClass'>";
		$out.= "<p class='$tagClass'>$label:</p>";
		$out.= '</div>';
		$out.= "<div class='$middleClass'></div>";
		$out.= "<div class='$rightClass'>";
		$name = (empty($spProfileUser->last_name)) ? '&nbsp;' : $spProfileUser->last_name;
		$out.= "<p class='$tagClass'>$name</p>";
		$out.= "</div>\n";
		$out = apply_filters('sph_ProfileShowLastName', $out, $spProfileUser, $a);

		if ($echo) {
			echo $out;
		} else {
			return $out;
		}
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ProfileShowWebsite()
#	Display a users website link
#	Scope:	site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ProfileShowWebsite($args='', $label='') {
	global $spProfileUser;

	if (!sp_get_auth('view_profiles')) return;

	$defs = array('tagClass'	=> 'spProfileShowWebsite',
				  'leftClass'	=> 'spColumnSection spProfileLeftCol',
				  'middleClass'	=> 'spColumnSection spProfileSpacerCol',
				  'rightClass'	=> 'spColumnSection spProfileRightCol',
				  'showEmpty' 	=> 0,
				  'echo'		=> 1,
				  'get'			=> 0,
				  );

	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ProfileShowWebsite_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass		= esc_attr($tagClass);
	$leftClass		= esc_attr($leftClass);
	$middleClass	= esc_attr($middleClass);
	$rightClass		= esc_attr($rightClass);
	$showEmpty		= (int) $showEmpty;
	$label			= sp_filter_title_display($label);
	$echo			= (int) $echo;
	$get			= (int) $get;

	if ($get) return $spProfileUser->user_url;

	# output first name
	if (!empty($spProfileUser->user_url) || $showEmpty) {
		$out = '';
		$out.= "<div class='$leftClass'>";
		$out.= "<p class='$tagClass'>$label:</p>";
		$out.= '</div>';
		$out.= "<div class='$middleClass'></div>";
		$out.= "<div class='$rightClass'>";
		if (empty($spProfileUser->user_url)) {
			$url = '&nbsp;';
		} else {
			$url = sp_filter_display_links($spProfileUser->user_url);
			$spFilters = sp_get_option('sffilters');
			if ($spFilters['sfnofollow']) $url = sp_filter_save_nofollow($url);
			if ($spFilters['sftarget']) $url = sp_filter_save_target($url);
		}
		$out.= "<p class='$tagClass'>$url</p>";
		$out.= "</div>\n";

		$out = apply_filters('sph_ProfileShowWebsite', $out, $spProfileUser, $a);

		if ($echo) {
			echo $out;
		} else {
			return $out;
		}
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ProfileShowLocation()
#	Display a users location
#	Scope:	site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ProfileShowLocation($args='', $label='') {
	global $spProfileUser;

	if (!sp_get_auth('view_profiles')) return;

	$defs = array('tagClass'	=> 'spProfileShowWebsite',
				  'leftClass'	=> 'spColumnSection spProfileLeftCol',
				  'middleClass'	=> 'spColumnSection spProfileSpacerCol',
				  'rightClass'	=> 'spColumnSection spProfileRightCol',
				  'showEmpty' 	=> 0,
				  'echo'		=> 1,
				  'get'			=> 0,
				  );

	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ProfileShowLocation_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass		= esc_attr($tagClass);
	$leftClass		= esc_attr($leftClass);
	$middleClass	= esc_attr($middleClass);
	$rightClass		= esc_attr($rightClass);
	$showEmpty		= (int) $showEmpty;
	$label			= sp_filter_title_display($label);
	$echo			= (int) $echo;
	$get			= (int) $get;

	if ($get) return $spProfileUser->location;

	# output first name
	if (!empty($spProfileUser->location) || $showEmpty) {
		$out = '';
		$out.= "<div class='$leftClass'>";
		$out.= "<p class='$tagClass'>$label:</p>";
		$out.= '</div>';
		$out.= "<div class='$middleClass'></div>";
		$out.= "<div class='$rightClass'>";
		$location = (empty($spProfileUser->location)) ? '&nbsp;' : $spProfileUser->location;
		$out.= "<p class='$tagClass'>$location</p>";
		$out.= "</div>\n";

		$out = apply_filters('sph_ProfileShowLocation', $out, $spProfileUser, $a);

		if ($echo) {
			echo $out;
		} else {
			return $out;
		}
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ProfileShowBio()
#	Display a users location
#	Scope:	site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ProfileShowBio($args='', $label='') {
	global $spProfileUser;

	if (!sp_get_auth('view_profiles')) return;

	$defs = array('tagClass'	=> 'spProfileShowBio',
				  'leftClass'	=> 'spColumnSection spProfileLeftCol',
				  'middleClass'	=> 'spColumnSection spProfileSpacerCol',
				  'rightClass'	=> 'spColumnSection spProfileRightCol',
				  'showEmpty' 	=> 0,
				  'echo'		=> 1,
				  'get'			=> 0,
				  );

	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ProfileShowBio_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass		= esc_attr($tagClass);
	$leftClass		= esc_attr($leftClass);
	$middleClass	= esc_attr($middleClass);
	$rightClass		= esc_attr($rightClass);
	$showEmpty		= (int) $showEmpty;
	$label			= sp_filter_title_display($label);
	$echo			= (int) $echo;
	$get			= (int) $get;

	if ($get) return $spProfileUser->description;

	# output first name
	if (!empty($spProfileUser->description) || $showEmpty) {
		$out = '';
		$out.= "<div class='$leftClass'>";
		$out.= "<p class='$tagClass'>$label:</p>";
		$out.= '</div>';
		$out.= "<div class='$middleClass'></div>";
		$out.= "<div class='$rightClass'>";
		$description = (empty($spProfileUser->description)) ? '&nbsp;' : $spProfileUser->description;
		$out.= "<p class='$tagClass'>$description</p>";
		$out.= "</div>\n";

		$out = apply_filters('sph_ProfileShowBio', $out, $spProfileUser, $a);

		if ($echo) {
			echo $out;
		} else {
			return $out;
		}
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ProfileShowAIM()
#	Display a users location
#	Scope:	site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ProfileShowAIM($args='', $label='') {
	global $spProfileUser;

	if (!sp_get_auth('view_profiles')) return;

	$defs = array('tagClass'	=> 'spProfileShowAIM',
				  'leftClass'	=> 'spColumnSection spProfileLeftCol',
				  'middleClass'	=> 'spColumnSection spProfileSpacerCol',
				  'rightClass'	=> 'spColumnSection spProfileRightCol',
				  'showEmpty' 	=> 0,
				  'echo'		=> 1,
				  'get'			=> 0,
				  );

	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ProfileShowAIM_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass		= esc_attr($tagClass);
	$leftClass		= esc_attr($leftClass);
	$middleClass	= esc_attr($middleClass);
	$rightClass		= esc_attr($rightClass);
	$showEmpty		= (int) $showEmpty;
	$label			= sp_filter_title_display($label);
	$echo			= (int) $echo;
	$get			= (int) $get;

	if ($get) return $spProfileUser->aim;

	# output first name
	if (!empty($spProfileUser->aim) || $showEmpty) {
		$out = '';
		$out.= "<div class='$leftClass'>";
		$out.= "<p class='$tagClass'>$label:</p>";
		$out.= '</div>';
		$out.= "<div class='$middleClass'></div>";
		$out.= "<div class='$rightClass'>";
		$aim = (empty($spProfileUser->aim)) ? '&nbsp;' : $spProfileUser->aim;
		$out.= "<p class='$tagClass'>$aim</p>";
		$out.= "</div>\n";

		$out = apply_filters('sph_ProfileShowAIM', $out, $spProfileUser, $a);

		if ($echo) {
			echo $out;
		} else {
			return $out;
		}
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ProfileShowYIM()
#	Display a users location
#	Scope:	site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ProfileShowYIM($args='', $label='') {
	global $spProfileUser;

	if (!sp_get_auth('view_profiles')) return;

	$defs = array('tagClass'	=> 'spProfileShowYIM',
				  'leftClass'	=> 'spColumnSection spProfileLeftCol',
				  'middleClass'	=> 'spColumnSection spProfileSpacerCol',
				  'rightClass'	=> 'spColumnSection spProfileRightCol',
				  'showEmpty' 	=> 0,
				  'echo'		=> 1,
				  'get'			=> 0,
				  );

	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ProfileShowAIM_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass		= esc_attr($tagClass);
	$leftClass		= esc_attr($leftClass);
	$middleClass	= esc_attr($middleClass);
	$rightClass		= esc_attr($rightClass);
	$showEmpty		= (int) $showEmpty;
	$label			= sp_filter_title_display($label);
	$echo			= (int) $echo;
	$get			= (int) $get;

	if ($get) return $spProfileUser->yim;

	# output first name
	if (!empty($spProfileUser->yim) || $showEmpty) {
		$out = '';
		$out.= "<div class='$leftClass'>";
		$out.= "<p class='$tagClass'>$label:</p>";
		$out.= '</div>';
		$out.= "<div class='$middleClass'></div>";
		$out.= "<div class='$rightClass'>";
		$yim = (empty($spProfileUser->yim)) ? '&nbsp;' : $spProfileUser->yim;
		$out.= "<p class='$tagClass'>$yim</p>";
		$out.= "</div>\n";

		$out = apply_filters('sph_ProfileShowYIM', $out, $spProfileUser, $a);

		if ($echo) {
			echo $out;
		} else {
			return $out;
		}
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ProfileShowYIM()
#	Display a users location
#	Scope:	site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ProfileShowICQ($args='', $label='') {
	global $spProfileUser;

	if (!sp_get_auth('view_profiles')) return;

	$defs = array('tagClass'	=> 'spProfileShowICQ',
				  'leftClass'	=> 'spColumnSection spProfileLeftCol',
				  'middleClass'	=> 'spColumnSection spProfileSpacerCol',
				  'rightClass'	=> 'spColumnSection spProfileRightCol',
				  'showEmpty' 	=> 0,
				  'echo'		=> 1,
				  'get'			=> 0,
				  );

	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ProfileShowICQ_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass		= esc_attr($tagClass);
	$leftClass		= esc_attr($leftClass);
	$middleClass	= esc_attr($middleClass);
	$rightClass		= esc_attr($rightClass);
	$showEmpty		= (int) $showEmpty;
	$label			= sp_filter_title_display($label);
	$echo			= (int) $echo;
	$get			= (int) $get;

	if ($get) return $spProfileUser->icq;

	# output first name
	if (!empty($spProfileUser->icq) || $showEmpty) {
		$out = '';
		$out.= "<div class='$leftClass'>";
		$out.= "<p class='$tagClass'>$label:</p>";
		$out.= '</div>';
		$out.= "<div class='$middleClass'></div>";
		$out.= "<div class='$rightClass'>";
		$icq = (empty($spProfileUser->icq)) ? '&nbsp;' : $spProfileUser->icq;
		$out.= "<p class='$tagClass'>$icq</p>";
		$out.= "</div>\n";

		$out = apply_filters('sph_ProfileShowICQ', $out, $spProfileUser, $a);

		if ($echo) {
			echo $out;
		} else {
			return $out;
		}
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ProfileShowGoogleTalk()
#	Display a users location
#	Scope:	site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ProfileShowGoogleTalk($args='', $label='') {
	global $spProfileUser;

	if (!sp_get_auth('view_profiles')) return;

	$defs = array('tagClass'	=> 'spProfileShowGoogleTalk',
				  'leftClass'	=> 'spColumnSection spProfileLeftCol',
				  'middleClass'	=> 'spColumnSection spProfileSpacerCol',
				  'rightClass'	=> 'spColumnSection spProfileRightCol',
				  'showEmpty' 	=> 0,
				  'echo'		=> 1,
				  'get'			=> 0,
				  );

	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ProfileShowGoogleTalk_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass		= esc_attr($tagClass);
	$leftClass		= esc_attr($leftClass);
	$middleClass	= esc_attr($middleClass);
	$rightClass		= esc_attr($rightClass);
	$showEmpty		= (int) $showEmpty;
	$label			= sp_filter_title_display($label);
	$echo			= (int) $echo;
	$get			= (int) $get;

	if ($get) return $spProfileUser->jabber;

	# output first name
	if (!empty($spProfileUser->jabber) || $showEmpty) {
		$out = '';
		$out.= "<div class='$leftClass'>";
		$out.= "<p class='$tagClass'>$label:</p>";
		$out.= '</div>';
		$out.= "<div class='$middleClass'></div>";
		$out.= "<div class='$rightClass'>";
		$jabber = (empty($spProfileUser->jabber)) ? '&nbsp;' : $spProfileUser->jabber;
		$out.= "<p class='$tagClass'>$jabber</p>";
		$out.= "</div>\n";

		$out = apply_filters('sph_ProfileShowGoogleTalk', $out, $spProfileUser, $a);

		if ($echo) {
			echo $out;
		} else {
			return $out;
		}
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ProfileShowMSN()
#	Display a users location
#	Scope:	site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ProfileShowMSN($args='', $label='') {
	global $spProfileUser;

	if (!sp_get_auth('view_profiles')) return;

	$defs = array('tagClass'	=> 'spProfileShowMSN',
				  'leftClass'	=> 'spColumnSection spProfileLeftCol',
				  'middleClass'	=> 'spColumnSection spProfileSpacerCol',
				  'rightClass'	=> 'spColumnSection spProfileRightCol',
				  'showEmpty' 	=> 0,
				  'echo'		=> 1,
				  'get'			=> 0,
				  );

	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ProfileShowMSN_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass		= esc_attr($tagClass);
	$leftClass		= esc_attr($leftClass);
	$middleClass	= esc_attr($middleClass);
	$rightClass		= esc_attr($rightClass);
	$showEmpty		= (int) $showEmpty;
	$label			= sp_filter_title_display($label);
	$echo			= (int) $echo;
	$get			= (int) $get;

	if ($get) return $spProfileUser->msn;

	# output first name
	if (!empty($spProfileUser->msn) || $showEmpty) {
		$out = '';
		$out.= "<div class='$leftClass'>";
		$out.= "<p class='$tagClass'>$label:</p>";
		$out.= '</div>';
		$out.= "<div class='$middleClass'></div>";
		$out.= "<div class='$rightClass'>";
		$msn = (empty($spProfileUser->msn)) ? '&nbsp;' : $spProfileUser->msn;
		$out.= "<p class='$tagClass'>$msn</p>";
		$out.= "</div>\n";

		$out = apply_filters('sph_ProfileShowMSN', $out, $spProfileUser, $a);

		if ($echo) {
			echo $out;
		} else {
			return $out;
		}
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ProfileShowMySpace()
#	Display a users location
#	Scope:	site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ProfileShowMySpace($args='', $label='') {
	global $spProfileUser;

	if (!sp_get_auth('view_profiles')) return;

	$defs = array('tagClass'	=> 'spProfileShowMySpace',
				  'leftClass'	=> 'spColumnSection spProfileLeftCol',
				  'middleClass'	=> 'spColumnSection spProfileSpacerCol',
				  'rightClass'	=> 'spColumnSection spProfileRightCol',
				  'showEmpty' 	=> 0,
				  'echo'		=> 1,
				  'get'			=> 0,
				  );

	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ProfileShowMySpace_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass		= esc_attr($tagClass);
	$leftClass		= esc_attr($leftClass);
	$middleClass	= esc_attr($middleClass);
	$rightClass		= esc_attr($rightClass);
	$showEmpty		= (int) $showEmpty;
	$label			= sp_filter_title_display($label);
	$echo			= (int) $echo;
	$get			= (int) $get;

	if ($get) return $spProfileUser->myspace;

	# output first name
	if (!empty($spProfileUser->myspace) || $showEmpty) {
		$out = '';
		$out.= "<div class='$leftClass'>";
		$out.= "<p class='$tagClass'>$label:</p>";
		$out.= '</div>';
		$out.= "<div class='$middleClass'></div>";
		$out.= "<div class='$rightClass'>";
		$myspace = (empty($spProfileUser->myspace)) ? '&nbsp;' : $spProfileUser->myspace;
		$out.= "<p class='$tagClass'>$myspace</p>";
		$out.= "</div>\n";

		$out = apply_filters('sph_ProfileShowMySpace', $out, $spProfileUser, $a);

		if ($echo) {
			echo $out;
		} else {
			return $out;
		}
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ProfileShowSkype()
#	Display a users location
#	Scope:	site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ProfileShowSkype($args='', $label='') {
	global $spProfileUser;

	if (!sp_get_auth('view_profiles')) return;

	$defs = array('tagClass'	=> 'spProfileShowSkype',
				  'leftClass'	=> 'spColumnSection spProfileLeftCol',
				  'middleClass'	=> 'spColumnSection spProfileSpacerCol',
				  'rightClass'	=> 'spColumnSection spProfileRightCol',
				  'showEmpty' 	=> 0,
				  'echo'		=> 1,
				  'get'			=> 0,
				  );

	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ProfileShowSkype_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass		= esc_attr($tagClass);
	$leftClass		= esc_attr($leftClass);
	$middleClass	= esc_attr($middleClass);
	$rightClass		= esc_attr($rightClass);
	$showEmpty		= (int) $showEmpty;
	$label			= sp_filter_title_display($label);
	$echo			= (int) $echo;
	$get			= (int) $get;

	if ($get) return $spProfileUser->skype;

	# output first name
	if (!empty($spProfileUser->skype) || $showEmpty) {
		$out = '';
		$out.= "<div class='$leftClass'>";
		$out.= "<p class='$tagClass'>$label:</p>";
		$out.= '</div>';
		$out.= "<div class='$middleClass'></div>";
		$out.= "<div class='$rightClass'>";
		$skype = (empty($spProfileUser->skype)) ? '&nbsp;' : $spProfileUser->skype;
		$out.= "<p class='$tagClass'>$skype</p>";
		$out.= "</div>\n";

		$out = apply_filters('sph_ProfileShowSkype', $out, $spProfileUser, $a);

		if ($echo) {
			echo $out;
		} else {
			return $out;
		}
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ProfileShowFacebook()
#	Display a users location
#	Scope:	site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ProfileShowFacebook($args='', $label='') {
	global $spProfileUser;

	if (!sp_get_auth('view_profiles')) return;

	$defs = array('tagClass'	=> 'spProfileShowFacebook',
				  'leftClass'	=> 'spColumnSection spProfileLeftCol',
				  'middleClass'	=> 'spColumnSection spProfileSpacerCol',
				  'rightClass'	=> 'spColumnSection spProfileRightCol',
				  'showEmpty' 	=> 0,
				  'echo'		=> 1,
				  'get'			=> 0,
				  );

	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ProfileShowFacebook_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass		= esc_attr($tagClass);
	$leftClass		= esc_attr($leftClass);
	$middleClass	= esc_attr($middleClass);
	$rightClass		= esc_attr($rightClass);
	$showEmpty		= (int) $showEmpty;
	$label			= sp_filter_title_display($label);
	$echo			= (int) $echo;
	$get			= (int) $get;

	if ($get) return $spProfileUser->facebook;

	# output first name
	if (!empty($spProfileUser->facebook) || $showEmpty) {
		$out = '';
		$out.= "<div class='$leftClass'>";
		$out.= "<p class='$tagClass'>$label:</p>";
		$out.= '</div>';
		$out.= "<div class='$middleClass'></div>";
		$out.= "<div class='$rightClass'>";
		$facebook = (empty($spProfileUser->facebook)) ? '&nbsp;' : $spProfileUser->facebook;
		$out.= "<p class='$tagClass'>$facebook</p>";
		$out.= "</div>\n";

		$out = apply_filters('sph_ProfileShowFacebook', $out, $spProfileUser, $a);

		if ($echo) {
			echo $out;
		} else {
			return $out;
		}
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ProfileShowTwitter()
#	Display a users location
#	Scope:	site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ProfileShowTwitter($args='', $label='') {
	global $spProfileUser;

	if (!sp_get_auth('view_profiles')) return;

	$defs = array('tagClass'	=> 'spProfileShowTwitter',
				  'leftClass'	=> 'spColumnSection spProfileLeftCol',
				  'middleClass'	=> 'spColumnSection spProfileSpacerCol',
				  'rightClass'	=> 'spColumnSection spProfileRightCol',
				  'showEmpty' 	=> 0,
				  'echo'		=> 1,
				  'get'			=> 0,
				  );

	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ProfileShowTwitter_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass		= esc_attr($tagClass);
	$leftClass		= esc_attr($leftClass);
	$middleClass	= esc_attr($middleClass);
	$rightClass		= esc_attr($rightClass);
	$showEmpty		= (int) $showEmpty;
	$label			= sp_filter_title_display($label);
	$echo			= (int) $echo;
	$get			= (int) $get;

	if ($get) return $spProfileUser->twitter;

	# output first name
	if (!empty($spProfileUser->twitter) || $showEmpty) {
		$out = '';
		$out.= "<div class='$leftClass'>";
		$out.= "<p class='$tagClass'>$label:</p>";
		$out.= '</div>';
		$out.= "<div class='$middleClass'></div>";
		$out.= "<div class='$rightClass'>";
		$twitter = (empty($spProfileUser->twitter)) ? '&nbsp;' : $spProfileUser->twitter;
		$out.= "<p class='$tagClass'>$twitter</p>";
		$out.= "</div>\n";

		$out = apply_filters('sph_ProfileShowTwitter', $out, $spProfileUser, $a);

		if ($echo) {
			echo $out;
		} else {
			return $out;
		}
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ProfileShowLinkedIn()
#	Display a users location
#	Scope:	site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ProfileShowLinkedIn($args='', $label='') {
	global $spProfileUser;

	if (!sp_get_auth('view_profiles')) return;

	$defs = array('tagClass'	=> 'spProfileShowLinkedIn',
				  'leftClass'	=> 'spColumnSection spProfileLeftCol',
				  'middleClass'	=> 'spColumnSection spProfileSpacerCol',
				  'rightClass'	=> 'spColumnSection spProfileRightCol',
				  'showEmpty' 	=> 0,
				  'echo'		=> 1,
				  'get'			=> 0,
				  );

	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ProfileShowLinkedIn_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass		= esc_attr($tagClass);
	$leftClass		= esc_attr($leftClass);
	$middleClass	= esc_attr($middleClass);
	$rightClass		= esc_attr($rightClass);
	$showEmpty		= (int) $showEmpty;
	$label			= sp_filter_title_display($label);
	$echo			= (int) $echo;
	$get			= (int) $get;

	if ($get) return $spProfileUser->linkedin;

	# output first name
	if (!empty($spProfileUser->linkedin) || $showEmpty) {
		$out = '';
		$out.= "<div class='$leftClass'>";
		$out.= "<p class='$tagClass'>$label:</p>";
		$out.= '</div>';
		$out.= "<div class='$middleClass'></div>";
		$out.= "<div class='$rightClass'>";
		$linkedin = (empty($spProfileUser->linkedin)) ? '&nbsp;' : $spProfileUser->linkedin;
		$out.= "<p class='$tagClass'>$linkedin</p>";
		$out.= "</div>\n";

		$out = apply_filters('sph_ProfileShowLinkedIn', $out, $spProfileUser, $a);

		if ($echo) {
			echo $out;
		} else {
			return $out;
		}
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ProfileShowYoutube()
#	Display a users youtube account
#	Scope:	site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ProfileShowYouTube($args='', $label='') {
	global $spProfileUser;

	if (!sp_get_auth('view_profiles')) return;

	$defs = array('tagClass'	=> 'spProfileShowYouTube',
				  'leftClass'	=> 'spColumnSection spProfileLeftCol',
				  'middleClass'	=> 'spColumnSection spProfileSpacerCol',
				  'rightClass'	=> 'spColumnSection spProfileRightCol',
				  'showEmpty' 	=> 0,
				  'echo'		=> 1,
				  'get'			=> 0,
				  );

	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ProfileShowYouTube_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass		= esc_attr($tagClass);
	$leftClass		= esc_attr($leftClass);
	$middleClass	= esc_attr($middleClass);
	$rightClass		= esc_attr($rightClass);
	$showEmpty		= (int) $showEmpty;
	$label			= sp_filter_title_display($label);
	$echo			= (int) $echo;
	$get			= (int) $get;

	if ($get) return $spProfileUser->youtube;

	# output first name
	if (!empty($spProfileUser->youtube) || $showEmpty) {
		$out = '';
		$out.= "<div class='$leftClass'>";
		$out.= "<p class='$tagClass'>$label:</p>";
		$out.= '</div>';
		$out.= "<div class='$middleClass'></div>";
		$out.= "<div class='$rightClass'>";
		$youtube = (empty($spProfileUser->youtube)) ? '&nbsp;' : $spProfileUser->youtube;
		$out.= "<p class='$tagClass'>$youtube</p>";
		$out.= "</div>\n";

		$out = apply_filters('sph_ProfileShowYouTube', $out, $spProfileUser, $a);

		if ($echo) {
			echo $out;
		} else {
			return $out;
		}
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ProfileShowMemberSince()
#	Display a users location
#	Scope:	site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ProfileShowMemberSince($args='', $label='') {
	global $spProfileUser;

	if (!sp_get_auth('view_profiles')) return;

	$defs = array('tagClass'	=> 'spProfileShowMemberSince',
				  'leftClass'	=> 'spColumnSection spProfileLeftCol',
				  'middleClass'	=> 'spColumnSection spProfileSpacerCol',
				  'rightClass'	=> 'spColumnSection spProfileRightCol',
				  'echo'		=> 1,
				  'get'			=> 0,
				  );

	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ProfileShowMemberSince_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass		= esc_attr($tagClass);
	$leftClass		= esc_attr($leftClass);
	$middleClass	= esc_attr($middleClass);
	$rightClass		= esc_attr($rightClass);
	$label			= sp_filter_title_display($label);
	$echo			= (int) $echo;
	$get			= (int) $get;

	if ($get) return $spProfileUser->user_registered;

	# output first name
	$out = '';
	$out.= "<div class='$leftClass'>";
	$out.= "<p class='$tagClass'>$label:</p>";
	$out.= '</div>';
	$out.= "<div class='$middleClass'></div>";
	$out.= "<div class='$rightClass'>";
	$out.= "<p class='$tagClass'>".sp_date('d', $spProfileUser->user_registered).'</p>';
	$out.= "</div>\n";

	$out = apply_filters('sph_ProfileShowMemberSince', $out, $spProfileUser, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ProfileShowLastVisit()
#	Display a users location
#	Scope:	site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ProfileShowLastVisit($args='', $label='') {
	global $spProfileUser;

	if (!sp_get_auth('view_profiles')) return;

	$defs = array('tagClass'	=> 'spProfileShowLastVisit',
				  'leftClass'	=> 'spColumnSection spProfileLeftCol',
				  'middleClass'	=> 'spColumnSection spProfileSpacerCol',
				  'rightClass'	=> 'spColumnSection spProfileRightCol',
				  'echo'		=> 1,
				  'get'			=> 0,
				  );

	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ProfileShowLastVisit_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass		= esc_attr($tagClass);
	$leftClass		= esc_attr($leftClass);
	$middleClass	= esc_attr($middleClass);
	$rightClass		= esc_attr($rightClass);
	$label			= sp_filter_title_display($label);
	$echo			= (int) $echo;
	$get			= (int) $get;

	if ($get) return $spProfileUser->lastvisit;

	# output first name
	$out = '';
	$out.= "<div class='$leftClass'>";
	$out.= "<p class='$tagClass'>$label:</p>";
	$out.= '</div>';
	$out.= "<div class='$middleClass'></div>";
	$out.= "<div class='$rightClass'>";
	$out.= "<p class='$tagClass'>".sp_date('d', $spProfileUser->lastvisit).' '.sp_date('t', $spProfileUser->lastvisit).'</p>';
	$out.= "</div>\n";

	$out = apply_filters('sph_ProfileShowLastVisit', $out, $spProfileUser, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ProfileShowUserPosts()
#	Display a users location
#	Scope:	site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ProfileShowUserPosts($args='', $label='') {
	global $spProfileUser;

	if (!sp_get_auth('view_profiles')) return;

	$defs = array('tagClass'	=> 'spProfileShowUserPosts',
				  'leftClass'	=> 'spColumnSection spProfileLeftCol',
				  'middleClass'	=> 'spColumnSection spProfileSpacerCol',
				  'rightClass'	=> 'spColumnSection spProfileRightCol',
				  'echo'		=> 1,
				  'get'			=> 0,
				  );

	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ProfileShowUserPosts_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass		= esc_attr($tagClass);
	$leftClass		= esc_attr($leftClass);
	$middleClass	= esc_attr($middleClass);
	$rightClass		= esc_attr($rightClass);
	$label			= sp_filter_title_display($label);
	$echo			= (int) $echo;
	$get			= (int) $get;

    $count = max($spProfileUser->posts, 0);

	if ($get) return $count;

	# output first name
	$out = '';
	$out.= "<div class='$leftClass'>";
	$out.= "<p class='$tagClass'>$label:</p>";
	$out.= '</div>';
	$out.= "<div class='$middleClass'></div>";
	$out.= "<div class='$rightClass'>";
	$out.= "<p class='$tagClass'>".$count.'</p>';
	$out.= "</div>\n";

	$out = apply_filters('sph_ProfileShowUserPosts', $out, $spProfileUser, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ProfileShowSearchPosts()
#	Display a users location
#	Scope:	site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ProfileShowSearchPosts($args='', $label='', $labelStarted='', $labelPosted='') {
	global $spProfileUser, $spThisUser;

	if (!sp_get_auth('view_profiles')) return;

	$defs = array('tagClass'	=> 'spProfileSearchPosts',
				  'leftClass'	=> 'spColumnSection spProfileLeftCol',
				  'middleClass'	=> 'spColumnSection spProfileSpacerCol',
				  'rightClass'	=> 'spColumnSection spProfileRightCol',
				  'linkClass'	=> 'spButton spLeft',
				  'echo'		=> 1,
				  );

	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ProfileShowSearchPosts_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass		= esc_attr($tagClass);
	$leftClass		= esc_attr($leftClass);
	$middleClass	= esc_attr($middleClass);
	$rightClass		= esc_attr($rightClass);
	$linkClass		= esc_attr($linkClass);
	$label			= sp_filter_title_display($label);
	$labelStarted	= sp_filter_title_display($labelStarted);
	$labelPosted	= sp_filter_title_display($labelPosted);
	$echo			= (int) $echo;

	# output first name
	$out = '';
	$out.= "<div class='$leftClass'>";
	$out.= "<p class='$tagClass'>$label:</p>";
	$out.= '</div>';
	$out.= "<div class='$middleClass'></div>";
	$out.= "<div class='$rightClass'>";
	$out.= '<form action="'.SFHOMEURL.'index.php?sp_ahah=search&amp;sfnonce='.wp_create_nonce('forum-ahah').'" method="post" id="searchposts" name="searchposts">';
	$out.= '<input type="hidden" class="sfhiddeninput" name="searchoption" id="searchoption" value="2" />';
	$out.= '<input type="hidden" class="sfhiddeninput" name="userid" id="userid" value="'.$spProfileUser->ID.'" />';
	if ($spProfileUser->ID == $spThisUser->ID) {
		$text1 = sp_text('List Topics You Have Posted To');
		$text2 = sp_text('List Topics You Started');
	} else {
		$text1 = sp_text('List Topics').'&#x0A;'.$spProfileUser->display_name.'&#x0A;'.sp_text('Has Posted To');
		$text2 = sp_text('List Topics').'&#x0A;'.$spProfileUser->display_name.'&#x0A;'.sp_text('User Started');
	}
	$out.= '<input type="submit" class="spSubmit" name="membersearch" value="'.$text1.'" />';
	$out.= '<input type="submit" class="spSubmit" name="memberstarted" value="'.$text2.'" />';
	$out.= '</form>';
	$out.= "</div>\n";

	$out = apply_filters('sph_ProfileShowSearchPosts', $out, $spProfileUser, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ProfileShowUserPhotos()
#	Display a users location
#	Scope:	site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ProfileShowUserPhotos($args='') {
	global $spProfileUser;

	if (!sp_get_auth('view_profiles')) return;

	$defs = array('tagClass'	=> 'spProfileShowUserPhotos',
				  'photoClass'	=> 'spProfileShowUserPhotos',
				  'imageClass'	=> 'spProfileShowUserPhotos',
				  'numCols'		=> 2,
				  'showEmpty' 	=> 0,
				  'echo'		=> 1,
				  'get'			=> 0,
				  );

	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ProfileShowUserPhotos_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass		= esc_attr($tagClass);
	$imageClass		= esc_attr($imageClass);
	$photoClass		= esc_attr($photoClass);
	$numCols		= (int) $numCols;
	$showEmpty		= (int) $showEmpty;
	$echo			= (int) $echo;
	$get			= (int) $get;

	if ($get) return $spProfileUser->photos;

	# output first name
	if (!empty($spProfileUser->photos) || $showEmpty) {
    	$sfprofile = sp_get_option('sfprofile');
		$out = '';
		$out.= "<table id='$tagId' class='$tagClass' width='100%'>";
		$col = 0;
		$width = 100 / $numCols;
		foreach ($spProfileUser->photos as $photo) {
			if ($col == 0) $out.= '<tr>';
			$out.= "<td class='$photoClass' width='$width%'>";
			if (!empty($photo)) {
                $img = getimagesize($photo);
                $width = ($img[0] > $sfprofile['photoswidth']) ? " width='{$sfprofile['photoswidth']}'" : '';
                $height = ($img[1] > $sfprofile['photosheight']) ? " height='{$sfprofile['photosheight']}'" : '';
                $out.= "<img class='$imageClass'$width$height src='$photo' />";
            }
			$out.= '</td>';
			$col++;
			if ($col == $numCols) {
				$out.= '</tr>';
				$col = 0;
			}
		}
		$out.= "</table>\n";

		$out = apply_filters('sph_ProfileShowUserPhotos', $out, $spProfileUser, $a);

		if ($echo) {
			echo $out;
		} else {
			return $out;
		}
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ProfileShowLink()
#	Display a users profile
#	Scope:	site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ProfileShowLink($args='', $label='') {
	global $spProfileUser;

	if (!sp_get_auth('view_profiles')) return;

	$defs = array('tagClass'	=> 'spProfileShowLink',
				  'echo'		=> 1,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ProfileShowLink_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass	= esc_attr($tagClass);
	$label 		= str_ireplace('%USER%', $spProfileUser->display_name, $label);
	$label		= sp_filter_title_display($label);
	$echo		= (int) $echo;

	# output the header
	$out = "<a rel='nofollow' class='$tagClass' href='".sp_url('profile/'.urlencode($spProfileUser->user_login))."'>$label</span></a>\n";

	$out = apply_filters('sph_ProfileShowLink', $out, $spProfileUser, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_ProfileShowEmail()
#	Display a users profile
#	Scope:	site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ProfileShowEmail($args='', $label='') {
	global $spProfileUser, $spThisUser;

	if (!sp_get_auth('view_profiles')) return;

	$defs = array('tagClass'	=> 'spProfileShowLink',
				  'leftClass'	=> 'spColumnSection spProfileLeftCol',
				  'middleClass'	=> 'spColumnSection spProfileSpacerCol',
				  'rightClass'	=> 'spColumnSection spProfileRightCol',
				  'adminOnly'	=> 1,
				  'echo'		=> 1,
				  'get'			=> 0,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_ProfileShowLink_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass		= esc_attr($tagClass);
	$leftClass		= esc_attr($leftClass);
	$middleClass	= esc_attr($middleClass);
	$rightClass		= esc_attr($rightClass);
	$adminOnly		= (int) $adminOnly;
	$label			= sp_filter_title_display($label);
	$echo			= (int) $echo;
	$get			= (int) $get;

	if ($get) return $spProfileUser->user_email;

	if ($spThisUser->admin || !$adminOnly) {
		$out = '';
		$out.= "<div class='$leftClass'>";
		$out.= "<p class='$tagClass'>$label:</p>";
		$out.= '</div>';
		$out.= "<div class='$middleClass'></div>";
		$out.= "<div class='$rightClass'>";
		$out.= "<p class='$tagClass'>$spProfileUser->user_email</p>";
		$out.= "</div>\n";

		$out = apply_filters('sph_ProfileShowEmail', $out, $spProfileUser, $a);

		if ($echo) {
			echo $out;
		} else {
			return $out;
		}
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_SetupUserProfileData()
#	sets up global array spProfileUser with user profile data
#	Scope:	site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_SetupUserProfileData($userid=0) {
	global $spProfileUser, $sfvars, $spThisUser;

	if (empty($userid)) {
		if (!empty($sfvars['member'])) {
			$userid = urldecode($sfvars['member']);
		} else {
			$userid = $spThisUser->ID;
		}
	}
	$spProfileUser = new spUser($userid);
    $spProfileUser = apply_filters('sph_profile_user_data', $spProfileUser);
}

# --------------------------------------------------------------------------------------
#
#	sp_ProfileEditFooter()
#	adds js to ProfileEdit view footer
#	Scope:	site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_ProfileEditFooter() {
	global $firstTab, $firstMenu, $SFMOBILE;
?>
	<script type="text/javascript">
	jQuery(document).ready(function() {
		/* set up the profile tabs */
	    jQuery("#spProfileTabs li a").click(function() {
	        jQuery("#spProfileContent").html("<div><img src='<?php echo SFCOMMONIMAGES; ?>working.gif' alt='Loading' /></div>");
	        jQuery("#spProfileTabs li a").removeClass("current");
	        jQuery(this).addClass("current");
	        jQuery.ajax({async: <?php if (empty($firstMenu)) echo 'true'; else echo 'false'; ?>, url: this.href, success: function(html) {
	            jQuery("#spProfileContent").html(html);

    			/* adjust height of profile content area based on the current content */
                spjSetProfileDataHeight();
	            }
	    	});
	    	return false;
	    });

		<?php if (!empty($firstMenu)) { ?>
			jQuery('#spProfileTab-<?php echo $firstTab; ?>').click();
		    jQuery("#spProfileMenu li a").unbind('click').click(function() {
		        jQuery("#spProfileContent").html("<div><img src='<?php echo SFCOMMONIMAGES; ?>working.gif' alt='Loading' /></div>");
		        jQuery.ajax({async: false, url: this.href, success: function(html) {
		            jQuery("#spProfileContent").html(html);
		            }
		    	});
		    	return false;
		    });

 	  		jQuery('#spProfileMenu-<?php echo $firstMenu; ?>').click();

			/* adjust height of profile content area based on the current content */
            spjSetProfileDataHeight();

			/* show any tooltips */
            <?php if (!$SFMOBILE) { ?>
                jQuery(function(jQuery){vtip();})
            <?php } ?>

			/* show any pretty checkboxes */
			jQuery("input[type=checkbox],input[type=radio]").prettyCheckboxes();

		    jQuery("#spProfileMenu li a").unbind('click').click(function() {
		        jQuery("#spProfileContent").html("<div><img src='<?php echo SFCOMMONIMAGES; ?>working.gif' alt='Loading' /></div>");
		        jQuery.ajax({async: true, url: this.href, success: function(html) {
		            jQuery("#spProfileContent").html(html);
		            }
		    	});
		    	return false;
		    });
		<?php } else if (!empty($firstTab)) { ?>
			jQuery('#spProfileTab-<?php echo $firstTab; ?>').click();
		<?php } else { ?>
			<?php $tabs = sp_profile_get_tabs(); ?>
			jQuery('#spProfileTab-<?php echo $tabs[0]['slug']; ?>').click();
		<?php } ?>
	})
	</script>
<?php
}

# --------------------------------------------------------------------------------------
#
#	sp_SetupSigEditor()
#	figures out what editor is to be used for profile signature editor in ProfileEdit
#	Scope:	site
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_SetupSigEditor($content='') {
	global $sfglobals;

	$out = '';
	$out.= do_action('sph_pre_editor_display', $sfglobals['editor']);
	$out.= apply_filters('sph_editor_textarea', $out, 'signature', $content, $sfglobals['editor'], '');
	$out.= do_action('sph_post_editor_display', $sfglobals['editor']);
	return $out;
}

?>