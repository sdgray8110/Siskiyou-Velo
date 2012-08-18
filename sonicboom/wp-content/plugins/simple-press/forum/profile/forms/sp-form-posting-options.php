<?php
/*
Simple:Press
Profile Posting Options Form
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
				jQuery('#spProfileMenu-edit-posting-options').click();
				jQuery('#spProfileMessage').show();
				jQuery('#spProfileMessage').fadeOut(7000);
			}
		});
	})
	</script>
<?php
	$out = '';
	$out.= '<p>';
	$out.= sp_text('On this panel, you may set your Posting Options preferences.');
	$out.= '</p>';
	$out.= '<hr>';

	# start the form
	$out.= '<div class="spProfilePostingOptions">';

    $ahahURL = SFHOMEURL.'index.php?sp_ahah=profile-save&amp;sfnonce='.wp_create_nonce('forum-ahah')."&amp;form=$thisSlug&amp;userid=$userid";
	$out.= '<form action="'.$ahahURL.'" method="post" name="spProfileForm" id="spProfileForm" class="spProfileForm">';
	$out.= sp_create_nonce('forum-profile');

	$out = apply_filters('sph_ProfileFormTop', $out, $userid, $thisSlug);
	$out = apply_filters('sph_ProfilePostingOptionsFormTop', $out, $userid);

	# special section for editor selection at top
	$tout = '';
	$tout.= '<div class="spColumnSection spProfileLeftCol">';
	$tout.= '<p class="spProfileLabel">'.sp_text('Preferred Editor').':</p>';
	$tout.= '</div>';
	$tout.= '<div class="spColumnSection spProfileSpacerCol"></div>';
	$tout.= '<div class="spColumnSection spProfileRightCol">';
	$checked = ($spProfileUser->editor == PLAINTEXT) ? $checked = 'checked="checked" ' : '';
	$tout.= '<p class="spProfileLabel"><label for="sf-plaintext"><span class="spProfileRadioLabel">'.sp_text('Plain Textarea').'</span></label><input type="radio" '.$checked.'name="editor" id="sf-plaintext" value="'.PLAINTEXT.'"/></p>';
	$tout = apply_filters('sph_ProfilePostingOptionsFormEditors', $tout, $spProfileUser);
	$tout.= '</div>';
	$out.= apply_filters('sph_ProfileUserEditor', $tout, $userid, $thisSlug);

	$out = apply_filters('sph_ProfilePostingOptionsFormBottom', $out, $userid);
	$out = apply_filters('sph_ProfileFormBottom', $out, $userid, $thisSlug);

	$out.= '<div class="spProfileFormSubmit">';
	$out.= '<input type="submit" class="spSubmit" name="formsubmit" value="'.sp_text('Update Posting Options').'" />';
	$out.= '</div>';
	$out.= '</form>';

	$out.= "</div>\n";

	$out = apply_filters('sph_ProfilePostingOptionsForm', $out, $userid);
	echo $out;
?>