<?php
/*
Simple:Press
Ahah call for View Member Profile
$LastChangedDate: 2012-03-30 10:19:35 -0700 (Fri, 30 Mar 2012) $
$Rev: 8248 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

sp_forum_api_support();
include_once(SF_PLUGIN_DIR.'/forum/content/sp-common-view-functions.php');

sp_load_editor(4);

# set up some globals for theme template files (spProfilePopup in this case) to use directly
global $spGroupView, $spThisGroup, $spForumView, $spThisForum, $spThisForumSubs,
$spThisTopic, $spThisPost, $spThisPostUser, $spNewPosts, $spThisUser,
$spProfileUser, $spMembersList, $spThisMemberGroup, $spThisMember,
$sfglobals, $sfvars;

$userid = sp_esc_int($_GET['user']);

do_action('sph_ProfileStart');

# is it a popup profile?
if (isset($_GET['action']) && $_GET['action'] == 'popup') {
	if (empty($userid)) {
		sp_notify(1, sp_text('Invalid profile request'));
		$out.= sp_render_queued_notification();
		$out.= '<div class="sfmessagestrip">';
		$out.= apply_filters('sph_ProfileErrorMsg', sp_text('Sorry, an invalid profile request was detected'));
		$out.= '</div>';
		return $out;
	}

	if (file_exists(SPTEMPLATES.'spProfilePopupShow.php')) {
		include_once (SF_PLUGIN_DIR.'/forum/content/sp-profile-view-functions.php');

		sp_SetupUserProfileData($userid);

		echo '<div id="spMainContainer">';
		include (SPTEMPLATES.'spProfilePopupShow.php');
		echo '</div>';
	} else {
		echo '<p>[spProfilePopupShow] '.sp_text('Template File not found or could not be opened.').'</p>';
	}
	die();
}

# check for tab press
if (isset($_GET['tab'])) {
	# profile edit, so only admin or logged in user can view
	if (empty($userid) || ($spThisUser->ID != $userid && !$spThisUser->admin)) {
		sp_notify(1, sp_text('Invalid profile request'));
		$out.= sp_render_queued_notification();
		$out.= '<div class="sfmessagestrip">';
		$out.= apply_filters('sph_ProfileErrorMsg', sp_text('Sorry, an invalid profile request was detected. Do you need to log in?'));
		$out.= '</div>';
		return $out;
	}

	# set up profile for requested user
	include_once (SF_PLUGIN_DIR.'/forum/content/sp-profile-view-functions.php');
	sp_SetupUserProfileData($userid);

	# get pressed tab and menu (if pressed)
	$thisTab = sp_esc_str($_GET['tab']);
	$thisMenu = (isset($_GET['menu'])) ? sp_esc_str($_GET['menu']) : '';

	# get all the tabs meta info
	$tabs = sp_profile_get_tabs();
	foreach ($tabs as $tab) {
		# find the pressed tab in the list of tabs
		if ($tab['slug'] == $thisTab) {
			# now output the menu and content
			$first = true;
			$thisForm = '';
			$thisName = '';
			$thisSlug = '';
			$out = '';
			if (!empty($tab['menus'])) {
				foreach ($tab['menus'] as $menu) {
					# do we need an auth check?
					$authCheck = (empty($menu['auth'])) ? true : sp_get_auth($menu['auth'], '', $userid);

					# is this menu being displayed and does user have auth to see it?
					if ($authCheck && $menu['display']) {
						$current = '';
						# if tab press, see if its the first
						if ($first && empty($thisMenu)) {
							$current = 'current';
							$thisName = $menu['name'];
							$thisForm = $menu['form'];
							$thisSlug = $menu['slug'];
							$first = false;
						} else if (!empty($thisMenu)) {
							# if this menu was pressed, make it the current form
							if ($menu['slug'] == $thisMenu) {
								$current = 'current';
								$thisName = $menu['name'];
								$thisForm = $menu['form'];
								$thisSlug = $menu['slug'];
								$thisMenu = ''; # menu press found so clear
								$first = false;
							}
						}

                        # special checking for displaying menus
                    	$spProfileOptions = sp_get_option('sfprofile');
                        $spAvatars = sp_get_option('sfavatars');
                        $noPhotos = ($menu['slug'] == 'edit-photos' && $spProfileOptions['photosmax'] < 1); # dont display edit photos if disabled
                        $noAvatars = ($menu['slug'] == 'edit-avatars' && !$spAvatars['sfshowavatars']); # dont display edit avatars if disabled
                        $hideMenu = ($noPhotos || $noAvatars);
                        $hideMenu = apply_filters('sph_ProfileMenuHide', $hideMenu, $tab, $menu, $userid);
                        if (!$hideMenu) {
    						# buffer the menu list while we find the current menu item
    					    $ahahURL = SFHOMEURL.'index.php?sp_ahah=profile&amp;sfnonce='.wp_create_nonce('forum-ahah')."&amp;tab=$thisTab&amp;menu=".$menu['slug'].'&amp;user='.$userid.'&amp;rand='.rand();
                            if (is_ssl()) $ahahURL = str_replace('http://', "https://", $ahahURL);
    						$out.= "<li class='spProfileMenuItem $current'><a rel='nofollow' href='$ahahURL' id='spProfileMenu-".esc_attr($menu['slug'])."'>".$menu['name'].'</a></li>';
                        }
					}
				}
			}

			# output the header area
			echo '<div id="spProfileHeader">';
			echo $thisName.' <small>('.sp_get_member_item($userid, 'display_name').')</small>';
			echo '</div>';

			# build the menus
			echo '<div id="spProfileMenu">';
			echo '<ul class="spProfileMenuGroup">';
			echo $out; # output buffered menu list
			echo '</ul>';
			echo '</div>';

			# build the form
			echo '<div id="spProfileData">';
			echo '<div id="spProfileFormPanel">';
			if (!empty($thisForm) && file_exists($thisForm)) {
				include_once ($thisForm);
			} else {
				echo sp_text('Profile form could not be found').' ['.$thisForm.']';
			}
			echo '</div>';
			echo '</div>';
		}
	}

	$msg = sp_text('Forum rules require you to change your password in order to view forum or save your profile');
	$msg = apply_filters('sph_change_pw_msg', $msg);
	$message = '<p class="spProfileFailure">'.$msg.'</p>';

	global $SFMOBILE;
?>
	<script type="text/javascript">
	jQuery(document).ready(function() {
		/* set up the profile tabs */
	    jQuery("#spProfileMenu li a").unbind('click').click(function() {
	        jQuery("#spProfileContent").html("<div><img src='<?php echo SFCOMMONIMAGES; ?>working.gif' alt='Loading' /></div>");
	        jQuery.ajax({async: true, url: this.href, success: function(html) {
	            jQuery("#spProfileContent").html(html);
          	  }
	    	});
	    	return false;
	    });

		/* adjust height of profile content area based on the current content */
        spjSetProfileDataHeight();

		/* show any tooltips */
        <?php if (!$SFMOBILE) { ?>
            jQuery(function(jQuery){vtip();})
        <?php } ?>

		/* show any pretty checkboxes */
        jQuery("#spProfileContent input[type=checkbox], #spProfileContent input[type=radio]").prettyCheckboxes();
		<?php if (isset($spThisUser->sp_change_pw) && $spThisUser->sp_change_pw) { ?>
		jQuery('#spProfileMessage').html('<?php echo $message; ?>').show();
		jQuery('#spProfileMessage').fadeOut(7000);
		<?php } ?>
	})
	</script>
<?php

	die();
}

if (isset($_GET['avatarremove']) && ($spThisUser->ID == $userid || $spThisUser->admin)) {
	# clear avatar db record
	$avatar = sp_get_member_item($userid, 'avatar');
	$avatar['uploaded'] = '';
	sp_update_member_item($userid, 'avatar', $avatar);
	echo '<strong>'.sp_text('Uploaded Avatar Removed').'</strong>';
	die();
}

if (isset($_GET['action']) && $_GET['action'] == 'avatarpool') {
	global $SPPATHS;

	# Open avatar pool folder and get cntents for matching
	$path = SF_STORE_DIR.'/'.$SPPATHS['avatar-pool'].'/';
	$dlist = @opendir($path);
	if (!$dlist) {
        echo '<strong>'.sp_text('The avatar pool folder does not exist').'</strong>';
        die();
	}

	# start the table display
	echo '<p align="center"'.sp_text('Avatar Pool').'</p>';
	echo '<p>';
	while (false !== ($file = readdir($dlist))) {
		if ($file != "." && $file != "..") {
			echo '<img class="spAvatarPool" src="'.esc_url(SFAVATARPOOLURL.'/'.$file).'" alt="" onclick="spjSelAvatar(\''.$file.'\', \''.esc_js("<p class=\'spCenter\'>" . sp_text('Avatar selected. Please save pool avatar') . "</p>").'\'); return jQuery(\'#dialog\').dialog(\'close\');" />&nbsp;&nbsp;';
		}
	}
	echo '</p>';
	closedir($dlist);
	die();
}

if (isset($_GET['poolremove']) && ($spThisUser->ID == $userid || $spThisUser->admin)) {
	$avatar = sp_get_member_item($userid, 'avatar');
	$avatar['pool'] = '';
	sp_update_member_item($userid, 'avatar', $avatar);
	echo '<strong>'.sp_text('Pool Avatar Removed').'</strong>';
	die();
}

die();
?>