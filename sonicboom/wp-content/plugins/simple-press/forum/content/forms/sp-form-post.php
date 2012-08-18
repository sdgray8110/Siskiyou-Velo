<?php
/*
Simple:Press
Post Form Rendering
$LastChangedDate: 2012-05-19 06:50:07 -0700 (Sat, 19 May 2012) $
$Rev: 8534 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

function sp_render_add_post_form($a) {
	global $sfvars, $spThisUser, $spThisTopic, $sfglobals;

	extract($a, EXTR_SKIP);

	# Check for a failure package in case this is a redirect
	$f = sp_get_transient(1, true);
	if (isset($f['guestname']) ? $guestnameval = esc_attr(stripslashes($f['guestname'])) : $guestnameval = $spThisUser->guest_name);
	if (isset($f['guestemail']) ? $guestemailval = esc_attr(stripslashes($f['guestemail'])) : $guestemailval = $spThisUser->guest_email);
	if (isset($f['postitem']) ? $postitemval = stripslashes($f['postitem']) : $postitemval = '');
	if (isset($f['message']) ? $failmessage = stripslashes($f['message']) : $failmessage = '');

	$out = '';

	# Grab above editor message if there is one
	$postmsg = sp_get_option('sfpostmsg');

	if($hide ? $hide=' style="display:none;"' : $hide = '');
	$out.= '<div id="spPostForm"'.$hide.'>'."\n";

	$out.= "<form class='$tagClass' action='".SFHOMEURL."index.php?sp_ahah=post&amp;sfnonce=".wp_create_nonce('forum-ahah')."' method='post' id='addpost' name='addpost' onsubmit='return spjValidatePostForm(this, $spThisUser->guest, 0, \"".SPTHEMEICONSURL.'sp_Success.png'."\");'>\n";
	$out.= sp_create_nonce('forum-userform_addpost');

	$out.= '<div class="spEditor">'."\n";
	$out = apply_filters('sph_post_editor_top', $out, $spThisTopic, $a);

	$out.= "<fieldset class='$controlFieldset'>\n";
	$out.= "<legend>$labelHeading: ".$spThisTopic->topic_name."</legend>\n";

	$out.= "<input type='hidden' name='action' value='post' />\n";

	$out.= "<input type='hidden' name='forumid' value='$spThisTopic->forum_id' />\n";
	$out.= "<input type='hidden' name='forumslug' value='$spThisTopic->forum_slug' />\n";
	$out.= "<input type='hidden' name='topicid' value='$spThisTopic->topic_id' />\n";
	$out.= "<input type='hidden' name='topicslug' value='$spThisTopic->topic_slug' />\n";

	$close = false;
	if (!empty($postmsg['sfpostmsgpost']) || $spThisUser->guest || !sp_get_auth('bypass_moderation', $spThisTopic->forum_id) || !sp_get_auth('bypass_moderation_once', $spThisTopic->forum_id)) {
		$out.= '<div class="spEditorSection">';
		$close = true;
	}

	# let plugins add stuff at top of editor header
	$out = apply_filters('sph_post_editor_header_top', $out, $spThisTopic, $a);

	if (!empty($postmsg['sfpostmsgpost'])) {
		$out.= '<div class="spEditorMessage">'.sp_filter_text_display($postmsg['sfpostmsgtext']).'</div>'."\n";
	}

	if ($spThisUser->guest) {
		$out.= '<div class="spEditorSectionLeft">'."\n";
		$out.= "<div class='spEditorTitle'>$labelGuestName:\n";
		$out.= "<input type='text' tabindex='100' class='$controlInput' name='guestname' id='guestname' value='$guestnameval' /></div>\n";
		$out.= '</div>'."\n";
		$sfguests = sp_get_option('sfguests');
		if ($sfguests['reqemail']) {
			$out.= '<div class="spEditorSectionRight">'."\n";
			$out.= "<div class='spEditorTitle'>$labelGuestEmail:\n";
			$out.= "<input type='text' tabindex='101' class='$controlInput' name='guestemail' id='guestemail' value='$guestemailval' /></div>\n";
			$out.= '</div>'."\n";
		}
		$out.= '<div class="spClear"></div>'."\n";
	}

	if (!sp_get_auth('bypass_moderation', $spThisTopic->forum_id)) {
		$out.= "<p class='spLabelSmall'>$labelModerateAll</p>\n";
	} elseif (!sp_get_auth('bypass_moderation_once', $spThisTopic->forum_id)) {
		$out.= "<p class='spLabelSmall'>$labelModerateOnce</p>\n";
	}

	# let plugins add stuff at bottom of editor header
	$out = apply_filters('sph_post_editor_header_bottom', $out, $spThisTopic, $a);
	if ($close) $out.= '</div>'."\n";

	# Display the selected editor
	$out.= '<div id="spEditorContent">'."\n";
	$out.= sp_setup_editor(103, $postitemval);
	$out.= '</div>'."\n";

    # define primary toolbar for plugins to add buttons - only display if something there
    $toolbar = apply_filters('sph_post_editor_toolbar', '');
    if (!empty($toolbar)) {
    	$out.= '<div class="spEditorSection spEditorToolbar">';
        $out.= $toolbar;
    	$out.= '</div>'."\n";
    }

	# let plugins add stuff at top of editor footer
	$out = apply_filters('sph_post_editor_footer_top', $out, $spThisTopic, $a);

	# work out what we need to display
	$display = array();
	$display['smileys'] = false;
	$display['options'] = false;

	if ($sfglobals['sfsmileys']) $display['smileys'] = true;
	if (sp_get_auth('lock_topics', $spThisTopic->forum_id) ||
		   sp_get_auth('pin_posts', $spThisTopic->forum_id) ||
		   $spThisUser->admin ||
		   $spThisUser->moderator) {
		   $display['options'] = true;
	}
    $display = apply_filters('sph_post_editor_display_options', $display);

	if ($display['smileys'] || $display['options']) $out.= '<div class="spEditorSection">'."\n";

	# Smileys
	if ($display['smileys']) {
		$smileysBox = '';
		$smileysBox = apply_filters('sph_post_smileys_display', $smileysBox, $spThisTopic, $a);
		if ($display['options']) $smileysBox.= '<div class="spEditorSectionLeft">'."\n";
		$smileysBox.= "<div class='spEditorHeading'>$labelSmileys\n";
		$smileysBox = apply_filters('sph_post_smileys_header_add', $smileysBox, $spThisTopic, $a);
		$smileysBox.= '</div>';
		$smileysBox.= '<div class="spEditorSmileys">'."\n";
		$smileysBox.= sp_render_smileys();
		$smileysBox.= '</div>';
		$smileysBox = apply_filters('sph_post_smileys_add', $smileysBox, $spThisTopic, $a);
		if ($display['options']) $smileysBox.= '</div>'."\n";
		$out.= $smileysBox;
		unset($smileysBox);
	}

	# Options
	if ($display['options']) {
		$optionsBox = '';
		$optionsBox = apply_filters('sph_post_options_display', $optionsBox, $spThisTopic, $a);
		if ($display['smileys']) $optionsBox.= '<div class="spEditorSectionRight">';
		$optionsBox.= "<div class='spEditorHeading'>$labelOptions\n";
		$optionsBox = apply_filters('sph_post_options_header_add', $optionsBox, $spThisTopic, $a);
		$optionsBox.= '</div>';
		if (sp_get_auth('lock_topics', $spThisTopic->forum_id)) {
			$optionsBox.= "<label class='spLabel spCheckbox' for='sftopiclock'>$labelOptionLock</label>\n";
			$optionsBox.= "<input type='checkbox' class='$controlInput' name='topiclock' id='sftopiclock' tabindex='110' /><br />\n";
		}
		if (sp_get_auth('pin_topics', $spThisTopic->forum_id)) {
			$optionsBox.= "<label class='spLabel spCheckbox' for='sfpostpin'>$labelOptionPin</label>\n";
			$optionsBox.= "<input type='checkbox' class='$controlInput' name='postpin' id='sfpostpin' tabindex='111' /><br />\n";
		}
		if ($spThisUser->admin) {
			$optionsBox.= "<label class='spLabel spCheckbox' for='sfeditTimestamp'>$labelOptionTime</label>\n";
			$optionsBox.= "<input type='checkbox' class='$controlInput' tabindex='112' id='sfeditTimestamp' name='editTimestamp' onchange='spjToggleLayer(\"spHiddenTimestamp\");'/><br />\n";
		}

		if ($spThisUser->admin) {
			global $wp_locale, $month;
			$time_adj = time() + (get_option('gmt_offset') * 3600);
			$dd = gmdate( 'd', $time_adj );
			$mm = gmdate( 'm', $time_adj );
			$yy = gmdate( 'Y', $time_adj );
			$hh = gmdate( 'H', $time_adj );
			$mn = gmdate( 'i', $time_adj );
			$ss = gmdate( 's', $time_adj );

			$optionsBox.= '<div id="spHiddenTimestamp">'."\n";
			$optionsBox.= "<select class='$controlInput' tabindex='114' name='tsMonth' onchange='editTimestamp.checked=true'>\n";
			for ($i = 1; $i < 13; $i = $i +1) {
				$optionsBox.= "\t\t\t<option value=\"$i\"";
				if ($i == $mm ) $optionsBox.= " selected='selected'";
				if (class_exists('WP_Locale')) {
					$optionsBox.= '>'.$wp_locale->get_month($i).'</option>';
				} else {
					$optionsBox.= '>'.$month[$i].'</option>';
				}
			}
			$optionsBox.= '</select> ';
			$optionsBox.= "<input class='$controlInput' tabindex='115' type='text' id='tsDay' name='tsDay' value='$dd' size='2' maxlength='2'/> \n";
			$optionsBox.= "<input class='$controlInput' tabindex='116' type='text' id='tsYear' name='tsYear' value='$yy' size='4' maxlength='5'/> @\n";
			$optionsBox.= "<input class='$controlInput' tabindex='117' type='text' id='tsHour' name='tsHour' value='$hh' size='2' maxlength='2'/> :\n";
			$optionsBox.= "<input class='$controlInput' tabindex='118' type='text' id='tsMinute' name='tsMinute' value='$mn' size='2' maxlength='2'/> \n";
			$optionsBox.= "<input class='$controlInput' tabindex='119' type='hidden' id='tsSecond' name='tsSecond' value='$ss' /> \n";
			$optionsBox.= "</div>";
		}

		$optionsBox = apply_filters('sph_post_options_add', $optionsBox, $spThisTopic, $a);
		if ($display['smileys']) $optionsBox.= '</div>'."\n";

		$out.= $optionsBox;
		unset($optionsBox);
	}
	if ($display['smileys'] || $display['options']) $out.= '</div>';

	# let plugins add stuff at top of editor footer
	$out = apply_filters('sph_post_editor_footer_bottom', $out, $spThisTopic, $a);

	# let plugins add stuff at end of editor submit top
	$out.= '<div class="spEditorSubmit">'."\n";
	$out = apply_filters('sph_post_editor_submit_top', $out, $spThisTopic, $a);

	# Start Spam Measures
	if (sp_get_auth('bypass_spam_control', $spThisTopic->forum_id) ? $usemath = false : $usemath = true);
	$enabled = ' ';
	if ($usemath) {
		$enabled = 'disabled="disabled"';
		$out.= '<div class="spInlineSection">'."\n";
		$out.= 'Guest URL (required)<br />'."\n";
		$out.= "<input type='text' class='$controlInput' size='30' name='url' value='' />\n";
		$out.= '</div>';

		$spammath = sp_math_spam_build();
		$uKey = sp_get_option('spukey');
		$uKey1 = $uKey.'1';
		$uKey2 = $uKey.'2';
		$out.= "<div class='spEditorTitle'>$labelMath</div>\n";
		$out.= "<div class='spEditorSpam'>$labelMathSum:</div>\n";
		$out.= "<div class='spEditorSpam'>$spammath[0] + $spammath[1]</div>\n";
		$out.= "<div class='spEditorSpam'>\n";
		$out.= "<input type='text' tabindex='105' class='$controlInput' size='20' name='$uKey1' id='$uKey1' value='' onkeyup='spjSetPostButton(this, ".$spammath[0].", ".$spammath[1].", \"".esc_js($labelPostButtonReady)."\", \"".esc_js($labelPostButtonMath)."\")' />\n";
		$out.= "<input type='hidden' name='$uKey2' value='$spammath[2]' />\n";
		$out.= '</div>';
	}
	# End Spam Measures

	$buttontext = $labelPostButtonReady;
	if ($usemath) $buttontext = $labelPostButtonMath;
    $buttontext = apply_filters('sph_post_editor_button_text', $buttontext, $a);
    $enabled = apply_filters('sph_post_editor_button_enable', $enabled, $a);

	$out.= "<div id='spPostNotifications'>$failmessage</div>\n";

	$out.= "<div class='spEditorSubmitButton'>\n";
	$out.= "<input type='submit' $enabled tabindex='106' class='$controlSubmit' name='newpost' id='sfsave' value='$buttontext' />\n";
	$out.= "<input type='button' tabindex='107' class='$controlSubmit' id='sfcancel' name='cancel' value='$labelPostCancel' onclick='jQuery(document).ready(function() { spjEdCancelEditor(); });' />\n";

	# let plugins add stuff to editor controls
	$out = apply_filters('sph_post_editor_controls', $out, $spThisTopic, $a);

	$out.= '</div>'."\n";

	# let plugins add stuff at end of editor submit bottom
	$out = apply_filters('sph_post_editor_submit_bottom', $out, $spThisTopic, $a);
	$out.= '</div>'."\n";

	$out.= '</fieldset>'."\n";

	$out = apply_filters('sph_post_editor_bottom', $out, $spThisTopic, $a);
	$out.= '</div>'."\n";
	$out.= '</form>'."\n";
	$out.= '</div>'."\n";

	# let plugins add stuff beneath the editor
	$out = apply_filters('sph_post_editor_beneath', $out, $spThisTopic, $a);

	return $out;
}


function sp_render_edit_post_form($a, $postid, $postcontent) {
	global $sfvars, $spThisUser, $spThisTopic, $sfglobals;

	extract($a, EXTR_SKIP);

	$out = '';

	$out.= "<div id='spPostForm'>\n";

	$out.= "<form class='$tagClass' action='".sp_build_url($spThisTopic->forum_slug, $spThisTopic->topic_slug, $spThisTopic->display_page, $postid)."' method='post' name='editpostform'>\n";
	$out.= "<input type='hidden' name='pid' value='$postid' />\n";

	$out.= "<div class='spEditor'>\n";
	$out = apply_filters('sph_post_edit_top', $out, $postid, $a);

	$out.= "<fieldset class='$controlFieldset'>\n";
	$out.= "<legend>$labelHeading</legend>\n";

	# Display the selected editor
	$out.= '<div id="spEditorContent">'."\n";
	$out.= sp_setup_editor(1, str_replace('&', '&amp;', $postcontent));
	$out.= '</div>'."\n";

	# let plugins add stuff at top of editor footer
	$out = apply_filters('sph_post_edit_footer_top', $out, $postid, $a);

	if ($sfglobals['sfsmileys']) {
		$smileysBox = '';
		$smileysBox.= '<div class="spEditorSection">'."\n";
		$smileysBox = apply_filters('sph_post_smileys_display', $smileysBox, $postid);
		$smileysBox.= "<div class='spEditorHeading'>$labelSmileys\n";
		$smileysBox = apply_filters('sph_post_smileys_header_add', $smileysBox, $postid);
		$smileysBox.= '</div>';
		$smileysBox.= '<div class="spEditorSmileys">'."\n";
		$smileysBox.= sp_render_smileys();
		$smileysBox = apply_filters('sph_post_smileys_add', $smileysBox, $postid);
		$smileysBox.= '</div>'."\n";
		$smileysBox.= '</div>'."\n";
		$out.= $smileysBox;
		unset($smileysBox);
	}

	# let plugins add stuff at top of editor footer
	$out = apply_filters('sph_post_edit_footer_bottom', $out, $postid, $a);

	$out.= '<div class="spEditorSubmit">'."\n";
	$out = apply_filters('sph_post_edit_submit_top', $out, $postid, $a);

	$out.= "<div class='spEditorSubmitButton'>\n";
	$out = apply_filters('sph_post_edit_form_controls_above', $out, $postid, $spThisTopic);
	$out.= "<input type='submit' tabindex='106' class='$controlSubmit' name='editpost' id='sfsave' value='$labelPostButton' />\n";
	$out.= "<input type='submit' tabindex='107' class='$controlSubmit' id='sfcancel' name='cancel' value='$labelPostCancel' />\n";

	# let plugins add stuff to editor controls
	$out = apply_filters('sph_post_editor_controls', $out, $spThisTopic, $a);
	$out = apply_filters('sph_post_edit_form_controls_below', $out, $postid, $spThisTopic);
	$out.= '</div>'."\n";

	$out = apply_filters('sph_post_edit_submit_bottom', $out, $postid, $a);
	$out.= '</div>'."\n";
	$out.= '</fieldset>'."\n";

	$out = apply_filters('sph_post_edit_bottom', $out, $postid, $a);
	$out.= '</div>'."\n";
	$out.= '</form>'."\n";
	$out.= '</div>'."\n";

	# let plugins add stuff beneath the editor
	$out = apply_filters('sph_post_editor_beneath', $out, $spThisTopic, $a);

	return $out;
}

?>