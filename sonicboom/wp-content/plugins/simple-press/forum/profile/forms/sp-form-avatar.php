<?php
/*
Simple:Press
Profile Avatars Form
$LastChangedDate: 2012-04-20 17:51:56 -0700 (Fri, 20 Apr 2012) $
$Rev: 8399 $
*/

	if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

	# double check we have a user
	if (empty($userid)) return;
?>
	<script type="text/javascript">
	jQuery(document).ready(function() {
		/* ajax form and message */
		jQuery('#spProfileForm1').ajaxForm({
			target: '#spProfileMessage',
			success: function() {
				jQuery('#spProfileMenu-edit-avatar').click();
				jQuery('#spProfileMessage').show();
				jQuery('#spProfileMessage').fadeOut(7000);
			}
		});
		jQuery('#spProfileForm2').ajaxForm({
			target: '#spProfileMessage',
			success: function() {
				jQuery('#spProfileMenu-edit-avatar').click();
				jQuery('#spProfileMessage').show();
				jQuery('#spProfileMessage').fadeOut(7000);
			}
		});
		jQuery('#spProfileForm3').ajaxForm({
			target: '#spProfileMessage',
			success: function() {
				jQuery('#spProfileMenu-edit-avatar').click();
				jQuery('#spProfileMessage').show();
				jQuery('#spProfileMessage').fadeOut(7000);
			}
		});
	})
	</script>
<?php
	$out = '';
	$out.= '<p>';
	$out.= sp_text('On this panel, you may update your Avatar. Depending on Forum Admin settings, you may have multiple ways to select an Avatar.');
	$out.= '</p>';
	$out.= '<hr>';

	# start the form
	$out.= '<div class="spProfileAvatar">';

	$out = apply_filters('sph_ProfileFormTop', $out, $userid, $thisSlug);
	$out = apply_filters('sph_ProfileAvatarFormTop', $out, $userid);

	# display avatar priorities
	$out.= '<fieldset>';
	$out.= '<legend>'.sp_text('Current Displayed Avatar').'</legend>';
	$out.= '<div class="spColumnSection spProfileLeftHalf">';
	$list = array(
		0 => sp_text('From gravatar.com'),
		1 => sp_text('WordPress Avatar Setting'),
		2 => sp_text('Uploaded Avatar'),
		3 => sp_text('Forum Default Avatars'),
		4 => sp_text('Forum Avatar Pool'),
		5 => sp_text('Remote Avatar')
	);
	$out.= '<p>'.sp_text('This forum searches and selects a member avatar in the following priority sequence until one is found').':</p><br />';
	$out.= '<ol>';
	foreach ($spAvatars['sfavatarpriority'] as $priority) {
		$out.= '<li>'.$list[$priority].'</li>';
        if ($priority == 3) break; # done with priorities if we reach default avatars since others are inactive then
	}
	$out.= '</ol>';
	$out.= '</div>';

	$out.= '<div class="spColumnSection spProfileSpacerCol"></div>';

	# Avatar currently used by forum
	$out.= '<div class="spColumnSection spProfileRightHalf">';
	$out.= '<p class="spCenter">'.sp_text('Current Displayed Avatar').':<br /><br />'.sp_UserAvatar('tagClass=spCenter&context=user&echo=0', $spProfileUser).'</p>';

	$out = apply_filters('sph_ProfileAvatarDisplay', $out, $spProfileUser);

	$out.= '</div>';
	$out.= '</fieldset>';

	# message about avatar selection
	$out.= '<p><br />'.sp_text('You may update your avatar from the choices below.').'</p>';
	$out.= '<hr>';

    foreach ($spAvatars['sfavatarpriority'] as $priority) {
        switch ($priority) {
            case 0: # gravatar
                break;

            case 1: # wp avatar
        		$out.= '<fieldset><legend>'.sp_text('WordPress Avatar').'</legend>';
       			$out.= '<p>'.sp_text('Select your avatar').' <a href="'.admin_url('profile.php').'">'.sp_text('with your WordPress profile').'</a>.</p>';
        		$out.= '</fieldset>';
                break;

            case 2: # avatar uploading
            	if ($spAvatars['sfavataruploads'] && sp_get_auth('upload_avatars', '', $userid)) {
            		global $SPPATHS;

            		$out.= '<fieldset><legend>'.sp_text('Upload An Avatar').'</legend>';
            		$out.= '<div class="spColumnSection spProfileLeftHalf">';
            		if (is_writable(SF_STORE_DIR."/".$SPPATHS['avatars']."/")) {
            		    $ahahURL = SFHOMEURL.'index.php?sp_ahah=profile-save&amp;sfnonce='.wp_create_nonce('forum-ahah').'&amp;form=avatar-upload&amp;userid='.$userid;
            			$out.= '<form action="'.$ahahURL.'" method="post" name="spProfileForm1" id="spProfileForm1" class="spProfileForm" enctype="multipart/form-data">';
            			$out.= sp_create_nonce('forum-profile');
            			$out.= '<input id="spAvatarInput" class="spControl spCenter" type="file" name="avatar-upload">';
            			$out.= '<br /><br /><p class="spCenter">';
            			$out.= sp_text('Files accepted: GIF, PNG, JPG and JPEG').'<br />';
            			$out.= sp_text('Maximum width displayed').': '.$spAvatars['sfavatarsize'].' '.sp_text('pixels').'<br />';
            			$out.= sp_text('Maximum filesize').': '.$spAvatars['sfavatarfilesize'].' '.sp_text('bytes');
            			$out.= '</p>';
            			$out.= '<div class="spProfileFormSubmit">';
            			$out.= '<input type="submit" class="spSubmit" name="formsubmit1" value="'.sp_text('Upload Avatar').'" />';
            			$out.= '</div>';
            			$out.= '</form>';
            		} else {
            			$out.= '<div id="sf-upload-status">';
            			$out.= '<p class="sf-upload-status-fail">'.sp_text('Sorry, uploads disabled! Storage location does not exist or is not writable. Please contact a forum Admin.').'</p>';
            			$out.= '</div>';
            		}
            		$out.= '</div>';

            		$out.= '<div class="spColumnSection spProfileSpacerCol"></div>';

            		# display current uploaded avatar if there is one
            		$out.= '<div class="spColumnSection spProfileRightHalf">';
            		$out.= '<p class="spCenter">'.sp_text('Current Uploaded Avatar').':<br /><br /></p>';
            		if ($spProfileUser->avatar['uploaded']) {
                        $ahahURL = SFHOMEURL.'index.php?sp_ahah=profile&amp;sfnonce='.wp_create_nonce('forum-ahah')."&amp;user=$userid&amp;avatarremove=1";
            			$target = 'spAvatarUpload';
            			$spinner = SFCOMMONIMAGES.'working.gif';
            			$out.= '<div id="spAvatarUpload" class="spCenter"><img src="'.esc_url(SFAVATARURL.$spProfileUser->avatar['uploaded']).'" alt="" /><br /><br />';
            			$out.= '<p class="spCenter"><input type="button" class="spSubmit" id="spDeleteUploadedAvatar" value="'.sp_text('Remove Uploaded Avatar').'" onclick="spjRemoveAvatar(\''.$ahahURL.'\', \''.$target.'\', \''.$spinner.'\');" /></p>';
            			$out.= '</div>';
            		} else {
            			$out.= '<p class="spCenter">'.sp_text('No avatar currently uploaded').'<br /><br /></p>';
            		}
            		$out.= '</div>';
            		$out.= '</fieldset>';
            	}
                break;

        	case 3: #default
                break 2; # stop displaying avatar options since none can be used after this one

            case 4: # avatar pool
            	if ($spAvatars['sfavatarpool']) {
            		$out.= '<fieldset><legend>'.sp_text('Select Avatar From Pool').'</legend>';
            		$out.= '<div class="spColumnSection spProfileLeftHalf">';
                    $site = SFHOMEURL."index.php?sp_ahah=profile&amp;sfnonce=".wp_create_nonce('forum-ahah')."&amp;action=avatarpool&amp;user=".$userid;
                    $title = sp_text('Avatar Pool');
                    $position = 'center';
            		$out.= '<p class="spCenter">'.sp_text('Select the button below to browse the available avatars in the avatar pool.');
            		$out.= '<br /><br /><a rel="nofollow" id="spavpool" class="spButton" href="javascript:void(null)" onclick="spjDialogAjax(this, \''.$site.'\', \''.$title.'\', 500, 0, \''.$position.'\');">'.sp_text('Browse Avatar Pool').'</a></p>';
            	    $ahahURL = SFHOMEURL.'index.php?sp_ahah=profile-save&amp;sfnonce='.wp_create_nonce('forum-ahah').'&amp;form=avatar-pool&amp;userid='.$userid;
            		$out.= '<form action="'.$ahahURL.'" method="post" name="spProfileForm2" id="spProfileForm2" class="spProfileForm">';
            		$out.= sp_create_nonce('forum-profile');
            		$out.= '<p class="spProfileLabel spCenter"><input class="spControl" type="hidden" name="spPoolAvatar" id="spPoolAvatar" value="" /></p>';
            		$out.= '<div id="spPoolStatus"><p class="spCenter">'.sp_text('No pool avatar selected').'</p></div>';
            		$out.= '<div class="spProfileFormSubmit">';
            		$out.= '<input type="submit" class="spSubmit" name="formsubmit2" value="'.sp_text('Save Pool Avatar').'" />';
            		$out.= '</div>';
            		$out.= '</form>';
            		$out.= '</div>';

            		$out.= '<div class="spColumnSection spProfileSpacerCol"></div>';

            		# display current selected pool avatar if there is one
            		$out.= '<div class="spColumnSection spProfileRightHalf">';
            		$out.= '<p class="spCenter">'.sp_text('Current Pool Avatar').':<br /><br /></p>';
            		if ($spProfileUser->avatar['pool']) {
                        $ahahURL = SFHOMEURL.'index.php?sp_ahah=profile&amp;sfnonce='.wp_create_nonce('forum-ahah')."&amp;user=$userid&amp;poolremove=1";
            			$target = 'spAvatarPool';
            			$spinner = SFCOMMONIMAGES.'working.gif';
            			$out.= '<div id="spAvatarPool" class="spCenter"><img src="'.esc_url(SFAVATARPOOLURL.$spProfileUser->avatar['pool']).'" alt="" /><br /><br />';
            			$out.= '<p class="spCenter"><input type="button" class="spSubmit" id="spDeletePoolAvatar" value="'.sp_text('Remove Pool Avatar').'" onclick="spjRemovePool(\''.$ahahURL.'\', \''.$target.'\', \''.$spinner.'\');" /></p>';
            			$out.= '</div>';
            		} else {
            			$out.= '<p class="spCenter">'.sp_text('No pool avatar currently selected').'<br /><br /></p>';
            		}
            		$out.= '</div>';
            		$out.= '</fieldset>';
            	}
                break;

        	case 5: # remote avatar
            	if ($spAvatars['sfavatarremote']) {
            		$out.= '<fieldset><legend>'.sp_text('Select Remote Avatar').'</legend>';
            		$out.= '<div class="spColumnSection spProfileLeftHalf">';
            	    $ahahURL = SFHOMEURL.'index.php?sp_ahah=profile-save&amp;sfnonce='.wp_create_nonce('forum-ahah').'&amp;form=avatar-remote&amp;userid='.$userid;
            		$out.= '<form action="'.$ahahURL.'" method="post" name="spProfileForm3" id="spProfileForm3" class="spProfileForm">';
            		$out.= sp_create_nonce('forum-profile');
            		$out.= '<p class="spCenter">'.sp_text('Enter the URL for the remote avatar.');
            		$out.= '<p class="spProfileLabel spCenter"><input class="spControl" type="text" name="spAvatarRemote" id="spAvatarRemote" value="'.esc_url($spProfileUser->avatar['remote']).'" /></p>';
            		$out.= '<div class="spProfileFormSubmit">';
            		$out.= '<input type="submit" class="spSubmit" name="formsubmit3" value="'.sp_text('Save Remote Avatar').'" />';
            		$out.= '</div>';
            		$out.= '</form>';
            		$out.= '</div>';

            		$out.= '<div class="spColumnSection spProfileSpacerCol"></div>';

            		# display current selected remote avatar if there is one
            		$out.= '<div class="spColumnSection spProfileRightHalf">';
            		$out.= '<p class="spCenter">'.sp_text('Current Remote Avatar').':<br /><br /></p>';
            		if ($spProfileUser->avatar['remote']) {
            			$out.= '<div id="spRemoteAvatar" class="spCenter"><img src="'.esc_url($spProfileUser->avatar['remote']).'" alt="" /><br /><br /></div>';
            		} else {
            			$out.= '<p class="spCenter">'.sp_text('No remote avatar currently selected').'<br /><br /></p>';
            		}
            		$out.= '</div>';
            		$out.= '</fieldset>';
            	}
                break;
        }
    }

	$out = apply_filters('sph_ProfileAvatarFormBottom', $out, $userid);
	$out = apply_filters('sph_ProfileFormBottom', $out, $userid, $thisSlug);

	$out.= "</div>\n";

	$out = apply_filters('sph_ProfileAvatarForm', $out, $userid);
	echo $out;
?>