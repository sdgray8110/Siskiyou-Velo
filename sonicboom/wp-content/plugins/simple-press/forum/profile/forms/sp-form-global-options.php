<?php
/*
Simple:Press
Profile Global Options Form
$LastChangedDate: 2012-04-14 10:51:23 -0700 (Sat, 14 Apr 2012) $
$Rev: 8342 $
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
				jQuery('#spProfileMenu-edit-global-options').click();
				jQuery('#spProfileMessage').show();
				jQuery('#spProfileMessage').fadeOut(7000);
			}
		});
	})
	</script>
<?php
	$out = '';
	$out.= '<p>';
	$out.= sp_text('On this panel, you may set your Global Options preferences.');
	$out.= '</p>';
	$out.= '<hr>';

	# start the form
	$out.= '<div class="spProfileGlobalOptions">';

	$spProfileOptions = sp_get_option('sfprofile');

    $ahahURL = SFHOMEURL.'index.php?sp_ahah=profile-save&amp;sfnonce='.wp_create_nonce('forum-ahah')."&amp;form=$thisSlug&amp;userid=$userid";
	$out.= '<form action="'.$ahahURL.'" method="post" name="spProfileForm" id="spProfileForm" class="spProfileForm">';
	$out.= sp_create_nonce('forum-profile');

	$out = apply_filters('sph_ProfileFormTop', $out, $userid, $thisSlug);
	$out = apply_filters('sph_ProfileGlobalOptionsFormTop', $out, $userid);

	$opts = sp_get_option('sfmemberopts');
    if ($opts['sfhidestatus']) {
    	$tout = '';
    	$tout.= '<div class="spColumnSection spProfileLeftCol">';
    	$tout.= '<p class="spProfileLabel">'.sp_text('Hide Online Status').':</p>';
    	$tout.= '</div>';
    	$tout.= '<div class="spColumnSection spProfileSpacerCol"></div>';
    	$tout.= '<div class="spColumnSection spProfileRightCol">';
    	$checked = ($spProfileUser->hidestatus) ? 'checked="checked" ' : '';
    	$tout.= '<p class="spProfileLabel"><label for="sf-hidestatus"></label><input type="checkbox" '.$checked.'name="hidestatus" id="sf-hidestatus" /></p>';
    	$tout.= '</div>';
    	$out.= apply_filters('sph_ProfileUserOnlineStatus', $tout, $userid, $thisSlug);
    }
    
	if ($spProfileOptions['nameformat']) {
		$tout = '';
		$tout.= '<div class="spColumnSection spProfileLeftCol">';
		$tout.= '<p class="spProfileLabel">'.sp_text('Sync Forum and WP Display Name').':</p>';
		$tout.= '</div>';
		$tout.= '<div class="spColumnSection spProfileSpacerCol"></div>';
		$tout.= '<div class="spColumnSection spProfileRightCol">';
		$checked = ($spProfileUser->namesync) ? $checked = 'checked="checked" ' : '';
		$tout.= '<p class="spProfileLabel"><label for="sf-namesync"></label><input type="checkbox" '.$checked.'name="namesync" id="sf-namesync" /></p>';
		$tout.= '</div>';
		$out.= apply_filters('sph_ProfileUserSyncName', $tout, $userid, $thisSlug);
	}

	$out = apply_filters('sph_ProfileGlobalOptionsFormBottom', $out, $userid);
	$out = apply_filters('sph_ProfileFormBottom', $out, $userid, $thisSlug);

	$out.= '<div class="spProfileFormSubmit">';
	$out.= '<input type="submit" class="spSubmit" name="formsubmit" value="'.sp_text('Update Global Options').'" />';
	$out.= '</div>';
	$out.= '</form>';

	$out.= "</div>\n";

	$out = apply_filters('sph_ProfileGlobalOptionsForm', $out, $userid);
	echo $out;
?>