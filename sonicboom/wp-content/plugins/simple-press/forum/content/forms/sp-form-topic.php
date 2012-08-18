<?php
/*
Simple:Press
New Topic Form Rendering
$LastChangedDate: 2012-05-19 09:17:26 -0700 (Sat, 19 May 2012) $
$Rev: 8540 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

function sp_render_add_topic_form($a) {
	global $sfvars, $sfglobals, $spThisForum, $spThisUser;

	extract($a, EXTR_SKIP);

	# Check for a failure package in case this is a redirect
	$f = sp_get_transient(1, true);
	if(isset($f['guestname']) ? $guestnameval = $f['guestname'] : $guestnameval = $spThisUser->guest_name);
	if(isset($f['guestemail']) ? $guestemailval = $f['guestemail'] : $guestemailval = $spThisUser->guest_email);
	if(isset($f['newtopicname']) ? $topicnameval = $f['newtopicname'] : $topicnameval = '');
	if(isset($f['postitem']) ? $postitemval = $f['postitem'] : $postitemval = '');
	if(isset($f['message']) ? $failmessage = $f['message'] : $failmessage = '');

	$out = '';

	# Grab above editor message if there is one
	$postmsg = sp_get_option('sfpostmsg');

	# Grab in-editor message if one
	$inEdMsg = sp_filter_text_display(sp_get_option('sfeditormsg'));

	if($hide ? $hide=' style="display:none;"' : $hide = '');
	$out.= '<div id="spPostForm"'.$hide.'>'."\n";

	$out.= "<form class='$tagClass' action='".SFHOMEURL."index.php?sp_ahah=post&amp;sfnonce=".wp_create_nonce('forum-ahah')."' method='post' id='addtopic' name='addtopic' onsubmit='return spjValidatePostForm(this, $spThisUser->guest, 1, \"".SPTHEMEICONSURL.'sp_Success.png'."\");'>\n";
	$out.= sp_create_nonce('forum-userform_addtopic');

	$out.= '<div class="spEditor">'."\n";
	$out = apply_filters('sph_topic_editor_top', $out, $spThisForum);

	$out.= "<fieldset class='$controlFieldset'>\n";
	$out.= "<legend>$labelHeading: ".$spThisForum->forum_name."</legend>\n";

	$out.= "<input type='hidden' name='action' value='topic' />\n";

	$out.= "<input type='hidden' name='forumid' value='$spThisForum->forum_id' />\n";
	$out.= "<input type='hidden' name='forumslug' value='$spThisForum->forum_slug' />\n";

	# let plugins add stuff at top of editor header
	$out.= '<div class="spEditorSection">';
	$out = apply_filters('sph_topic_editor_header_top', $out, $spThisForum, $a);

	if (!empty($postmsg['sfpostmsgtopic'])) $out.= '<div class="spEditorMessage">'.sp_filter_text_display($postmsg['sfpostmsgtext']).'</div>'."\n";

	if ($spThisUser->guest) {
		$out.= '<div class="spEditorSectionLeft">'."\n";
		$out.= "<div class='spEditorTitle'>$labelGuestName:\n";
		$out.= "<input type='text' tabindex='100' class='$controlInput' name='guestname' value='$guestnameval' /></div>\n";
		$out.= '</div>'."\n";
		$sfguests = sp_get_option('sfguests');
		if ($sfguests['reqemail']) {
			$out.= '<div class="spEditorSectionRight">'."\n";
			$out.= "<div class='spEditorTitle'>$labelGuestEmail:\n";
			$out.= "<input type='text' tabindex='101' class='$controlInput' name='guestemail' value='$guestemailval' /></div>\n";
			$out.= '</div>'."\n";
		}
		$out.= '<div class="spClear"></div>'."\n";
	}

	if (!sp_get_auth('bypass_moderation', $spThisForum->forum_id)) {
		$out.= "<p class='spLabelSmall'>$labelModerateAll</p>\n";
	} elseif (!sp_get_auth('bypass_moderation_once', $spThisForum->forum_id)) {
		$out.= "<p class='spLabelSmall'>$labelModerateOnce</p>\n";
	}

	$out.= "<div class='spEditorTitle'>$labelTopicName: \n";
	$out.= "<input id='spTopicTitle' type='text' tabindex='102' class='$controlInput' maxlength='180' name='newtopicname' value='$topicnameval'/>\n";
    $out = apply_filters('sph_topic_editor_name', $out, $a);
	$out.= '</div>'."\n";

	# let plugins add stuff at bottom of editor header
	$out = apply_filters('sph_topic_editor_header_bottom', $out, $spThisForum, $a);
	$out.= '</div>'."\n";

	# do we have content? Or just add any inline message
	if(empty($postitemval)) $postitemval = $inEdMsg;

	# Display the selected editor
	$out.= '<div id="spEditorContent">'."\n";
	$out.= sp_setup_editor(103, $postitemval);
	$out.= '</div>'."\n";

    # define primary toolbar for plugins to add buttons - only display if something there
    $toolbar = apply_filters('sph_topic_editor_toolbar', '');
    if (!empty($toolbar)) {
    	$out.= '<div class="spEditorSection spEditorToolbar">';
        $out.= $toolbar;
    	$out.= '</div>'."\n";
    }
    
	# let plugins add stuff at top of editor footer
	$out = apply_filters('sph_topic_editor_footer_top', $out, $spThisForum, $a);

	# work out what we need to display
	$display = array();
	$display['smileys'] = false;
	$display['options'] = false;

	if ($sfglobals['sfsmileys']) $display['smileys'] = true;
	if (sp_get_auth('lock_topics', $spThisForum->forum_id) ||
	   sp_get_auth('pin_topics', $spThisForum->forum_id) ||
	   $spThisUser->admin ||
	   $spThisUser->moderator) {
	   $display['options'] = true;
	}
    $display = apply_filters('sph_topic_editor_display_options', $display);

	# Now start the displays
	if ($display['smileys'] || $display['options']) $out.= '<div class="spEditorSection">'."\n";

	# Smileys
	if ($display['smileys']) {
		$smileysBox = '';
		$smileysBox = apply_filters('sph_topic_smileys_display', $smileysBox, $spThisForum, $a);
		if ($display['options']) $smileysBox.= '<div class="spEditorSectionLeft">'."\n";
		$smileysBox.= "<div class='spEditorHeading'>$labelSmileys\n";
		$smileysBox = apply_filters('sph_topic_smileys_header_add', $smileysBox, $spThisForum, $a);
		$smileysBox.= '</div>';
		$smileysBox.= '<div class="spEditorSmileys">'."\n";
		$smileysBox.= sp_render_smileys();
		$smileysBox.= '</div>';
		$smileysBox = apply_filters('sph_topic_smileys_add', $smileysBox, $spThisForum, $a);
		if ($display['options']) $smileysBox.= '</div>'."\n";
		$out.= $smileysBox;
		unset($smileysBox);
	}

	# Options
	if ($display['options']) {
		$optionsBox = '';
		$optionsBox = apply_filters('sph_topic_options_display', $optionsBox, $spThisForum, $a);
		if ($display['smileys']) $optionsBox.= '<div class="spEditorSectionRight">';
		$optionsBox.= "<div class='spEditorHeading'>$labelOptions\n";
		$optionsBox = apply_filters('sph_topic_options_header_add', $optionsBox, $spThisForum, $a);
		$optionsBox.= '</div>';
		if (sp_get_auth('lock_topics', $spThisForum->forum_id)) {
			$optionsBox.= "<label class='spLabel spCheckbox' for='sftopiclock'>$labelOptionLock</label>\n";
			$optionsBox.= "<input type='checkbox' class='$controlInput' name='topiclock' id='sftopiclock' tabindex='110' />\n";
		}
		if (sp_get_auth('pin_topics', $spThisForum->forum_id)) {
			$optionsBox.= "<label class='spLabel spCheckbox' for='sftopicpin'>$labelOptionPin</label>\n";
			$optionsBox.= "<input type='checkbox' class='$controlInput' name='topicpin' id='sftopicpin' tabindex='111' />\n";
		}
		if ($spThisUser->admin) {
			$optionsBox.= "<label class='spLabel spCheckbox' for='sfeditTimestamp'>$labelOptionTime</label>\n";
			$optionsBox.= "<input type='checkbox' class='$controlInput' tabindex='112' id='sfeditTimestamp' name='editTimestamp' onchange='spjToggleLayer(\"spHiddenTimestamp\");'/>\n";
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

		$optionsBox = apply_filters('sph_topic_options_add', $optionsBox, $spThisForum, $a);
		if ($display['smileys']) $optionsBox.= '</div>'."\n";

		$out.= $optionsBox;
		unset($optionsBox);
	}
	if ($display['smileys'] || $display['options']) $out.= '</div>';

	# let plugins add stuff at top of editor footer
	$out = apply_filters('sph_topic_editor_footer_bottom', $out, $spThisForum, $a);

	# let plugins add stuff at end of editor submit top
	$out.= '<div class="spEditorSubmit">'."\n";
	$out = apply_filters('sph_topic_editor_submit_top', $out, $spThisForum, $a);

	# Start Spam Measures
	if (sp_get_auth('bypass_spam_control', $spThisForum->forum_id) ? $usemath = false : $usemath = true);
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
		$out.= "<input type='text' tabindex='105' class='$controlInput' size='20' name='$uKey1' id='$uKey1' value='' onkeyup='spjSetTopicButton(this, ".$spammath[0].", ".$spammath[1].", \"".esc_js($labelPostButtonReady)."\", \"".esc_js($labelPostButtonMath)."\")' />\n";
		$out.= "<input type='hidden' name='$uKey2' value='$spammath[2]' />\n";
		$out.= '</div>';
	}
	# End Spam Measures

	$buttontext = $labelPostButtonReady;
	if ($usemath) $buttontext = $labelPostButtonMath;
    $buttontext = apply_filters('sph_topic_editor_button_text', $buttontext, $a);
    $enabled = apply_filters('sph_topic_editor_button_enable', $enabled, $a);

	$out.= "<div id='spPostNotifications'>$failmessage</div>\n";

	$out.= "<div class='spEditorSubmitButton'>\n";
	$out.= "<input type='submit' $enabled tabindex='106' class='$controlSubmit' name='newtopic' id='sfsave' value='$buttontext' />\n";
	$out.= "<input type='button' tabindex='107' class='$controlSubmit' id='sfcancel' name='cancel' value='$labelPostCancel' onclick='jQuery(document).ready(function() { spjEdCancelEditor(); });' />\n";

	# let plugins add stuff to editor controls
	$out = apply_filters('sph_post_editor_controls', $out, $spThisTopic, $a);

	$out.= '</div>'."\n";

	# let plugins add stuff at end of editor submit bottom
	$out = apply_filters('sph_topic_editor_submit_bottom', $out, $spThisForum, $a);
	$out.= '</div>'."\n";

	$out.= '</fieldset>'."\n";

	$out = apply_filters('sph_topic_editor_bottom', $out, $spThisForum, $a);
	$out.= '</div>'."\n";

	$out.= '</form>'."\n";
	$out.= '</div>'."\n";

	# let plugins add stuff beneath the editor
	$out = apply_filters('sph_post_editor_beneath', $out, $spThisTopic, $a);

	return $out;
}
?>