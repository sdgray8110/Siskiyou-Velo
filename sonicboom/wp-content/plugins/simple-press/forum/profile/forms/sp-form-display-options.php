<?php
/*
Simple:Press
Profile Disiplay Options Form
$LastChangedDate: 2012-05-30 07:35:17 -0700 (Wed, 30 May 2012) $
$Rev: 8608 $
*/

	if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

	# double check we have a user
	if (empty($userid)) return;
?>
	<script type="text/javascript">
	jQuery(document).ready(function() {
		/* ajax form and message */
		jQuery('#spProfileForm').ajaxForm({
			target: '#spProfileMessage',
			success: function() {
				jQuery('#spProfileMenu-edit-display-options').click();
				jQuery('#spProfileMessage').show();
				jQuery('#spProfileMessage').fadeOut(7000);
			}
		});
	})
	</script>
<?php
	$out = '';
	$out.= '<p>';
	$out.= sp_text('On this panel, you may set your Display Options preferences.');
	$out.= '</p>';
	$out.= '<hr>';

	# start the form
	$out.= '<div class="spProfileDisplayOptions">';

    $ahahURL = SFHOMEURL.'index.php?sp_ahah=profile-save&amp;sfnonce='.wp_create_nonce('forum-ahah')."&amp;form=$thisSlug&amp;userid=$userid";
	$out.= '<form action="'.$ahahURL.'" method="post" name="spProfileForm" id="spProfileForm" class="spProfileForm">';
	$out.= sp_create_nonce('forum-profile');

	$out = apply_filters('sph_ProfileFormTop', $out, $userid, $thisSlug);
	$out = apply_filters('sph_ProfileDisplayOptionsFormTop', $out, $userid);

	$tout = '';
	$tout.= '<div class="spColumnSection spProfileLeftCol">';
	$tout.= '<p class="spProfileLabel">'.sp_text('Select your Timezone').':</p>';
	$tout.= '</div>';
	$tout.= '<div class="spColumnSection spProfileSpacerCol"></div>';
	$tout.= '<div class="spColumnSection spProfileRightCol">';

	$tz = get_option('timezone_string');
	if (empty($tz) || substr($tz, 0, 3) == 'UTC') $tz = 'UTC';

	$tzUser = (!empty($spProfileUser->timezone_string)) ? $spProfileUser->timezone_string : $tz;
	if (substr($tzUser, 0, 3) == 'UTC') $tzUser = 'UTC';

	$tout.= '<p class="spProfileLabel"><select class="spControl" id="timezone" name="timezone">';
	$wptz = explode('<optgroup label=', wp_timezone_choice($tzUser));
    unset($wptz[count($wptz)-1]);
    $tout.= implode('<optgroup label=', $wptz);
	$tout.= '</select></p>';
	$tout.= '<p><small>'.sp_text('Server Timezone set to').': <b>'.$tz.'</b></small></p>';

	# timezone message
	date_default_timezone_set($tz);
	$now = localtime(time(), true);
	if ($now['tm_isdst']) {
		$tout.= '<p><small>'.sp_text('This timezone is currently in daylight savings time').'</small></p>';
	} else {
		$tout.= '<p><small>'.sp_text('This timezone is currently in standard time').'</small></p>';
	}
	$tout.= '<p><small>'.sp_text('Server Time is').': <b>'.date('Y-m-d G:i:s').'</b></small></p>';
	date_default_timezone_set($tzUser);
	$tout.= '<p><small>'.sp_text('Local Time is').': <b>'.date('Y-m-d G:i:s').'</b></small></p>';
	date_default_timezone_set('UTC');
	$tout.= '<p><small>'.sp_text('UTC Time is').': <b>'.date('Y-m-d G:i:s').'</b></small></p>';
	$tout.= '<p><small><a href="http://en.wikipedia.org/wiki/Time_zone">'.sp_text('Help and explanation of timezones').'</a></small></p>';
	$tout.= '</div>';
	$out.= apply_filters('sph_ProfileUserTimezone', $tout, $userid, $thisSlug);

	$sfcontrols = sp_get_option('sfcontrols');
    if (isset($sfcontrols['sfusersunread']) && $sfcontrols['sfusersunread']) {
        $tout = '';
		$tout.= '<div class="spColumnSection spProfileLeftCol">';
		$tout.= '<p class="spProfileLabel">'.sp_text('Max number of unread posts to display').' ('.sp_text('max allowed is').' '.$sfcontrols['sfmaxunreadposts'].')'.':</p>';
		$tout.= '</div>';
		$tout.= '<div class="spColumnSection spProfileSpacerCol"></div>';
		$tout.= '<div class="spColumnSection spProfileRightCol">';
        $number = (is_numeric($spProfileUser->unreadposts)) ? $spProfileUser->unreadposts : $sfcontrols['sfdefunreadposts'];
		$tout.= '<p class="spProfileLabel"><input class="spControl" type="text" name="unreadposts" id="unreadposts" value="'.$number.'" /></p>';
		$tout.= '</div>';
    	$out.= apply_filters('sph_ProfileUserUnread', $tout);
    }

	$out = apply_filters('sph_ProfileDisplayOptionsFormBottom', $out, $userid);
	$out = apply_filters('sph_ProfileFormBottom', $out, $userid, $thisSlug);

	$out.= '<div class="spProfileFormSubmit">';
	$out.= '<input type="submit" class="spSubmit" name="formsubmit" value="'.sp_text('Update Display Options').'" />';
	$out.= '</div>';
	$out.= '</form>';

	$out.= "</div>\n";

	$out = apply_filters('sph_ProfileDisplayOptionsForm', $out, $userid);
	echo $out;
?>