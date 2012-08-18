<?php
/*
Simple:Press
Profile Signature Form
$LastChangedDate: 2012-04-06 08:55:31 -0700 (Fri, 06 Apr 2012) $
$Rev: 8278 $
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
			beforeSubmit: function(a) {
				a = spjEdGetSignature(a);
			},
			success: function() {
				jQuery('#spProfileMenu-edit-signature').click();
				jQuery('#spProfileMessage').show();
				jQuery('#spProfileMessage').fadeOut(7000);
			}
		});
	})
	</script>
<?php
	$out = '';
	$out.= '<p>';
	$out.= sp_text('On this panel, you may edit your Signature.');
	$out.= '</p>';
	$out.= '<hr>';

	$out.= '<div class="spProfileSignature">';

	# Signature Set
	$out.= '<div class="spColumnSection spCenter">';
	$out.= '<p class="spTextLeft">'.sp_text('Setup Your Signature').':</p><br />';
	$value = sp_filter_content_edit($spProfileUser->signature);

    $ahahURL = SFHOMEURL.'index.php?sp_ahah=profile-save&amp;sfnonce='.wp_create_nonce('forum-ahah')."&amp;form=$thisSlug&amp;userid=$userid";
	$out.= '<form action="'.$ahahURL.'" method="post" name="spProfileForm" id="spProfileForm" class="spProfileForm">';
	$out.= sp_create_nonce('forum-profile');

	$out = apply_filters('sph_ProfileFormTop', $out, $userid, $thisSlug);
	$out = apply_filters('sph_ProfileSignatureFormTop', $out, $userid);

	$out.= sp_SetupSigEditor($value);
	$spSigImageSize = sp_get_option('sfsigimagesize');
	$sigWidth = sp_text('width - none specified').', ';
	$sigHeight = sp_text('height - none specified');
	if ($spSigImageSize['sfsigwidth'] > 0) $sigWidth = sp_text('width').' - '.$spSigImageSize['sfsigwidth'].', ';
	if ($spSigImageSize['sfsigheight'] > 0) $sigHeight = sp_text('height').' - '.$spSigImageSize['sfsigheight'];
	$out.= '<p class="spCenter">'.sp_text('Signature Image Size Limits (pixels)').': '.$sigWidth.$sigHeight.'</p>';
	$out.= '<p class="spCenter">'.sp_text('If you reset your signature, be sure to save it').'</p>';

	$out.= '<div class="spProfileFormSubmit">';
	# reset signature - plugins need to filter this input and provide their own with onclick to their js
	$tout = '<input type="button" class="spSubmit" name="reset" value="'.sp_text('Reset Signature').'" onclick="spjClearIt(\'signature\')" />';
	$out.= apply_filters('sph_ProfileSignatureReset', $tout);
	$out.= '<input type="submit" class="spSubmit" name="formsubmit" value="'.sp_text('Update Signature').'" />';
	$out.= '</div>';

	$out = apply_filters('sph_SignaturesFormBottom', $out, $userid);
	$out = apply_filters('sph_ProfileFormBottom', $out, $userid, $thisSlug);
	$out.= '</form>';
	$out.= '</div>';

	$out.= '<div class="spColumnSection spCenter">';
	$out.= '<p class="spTextLeft"><br />'.sp_text('Preview of Your Signature (update to see changes)').':</p><br />';
    $out.= sp_Signature('echo=0', $spProfileUser->signature);
	$out.= '</div>';

	$out.= '</div>'."\n";

	$out = apply_filters('sph_ProfileSignatureForm', $out, $userid);
	echo $out;

?>