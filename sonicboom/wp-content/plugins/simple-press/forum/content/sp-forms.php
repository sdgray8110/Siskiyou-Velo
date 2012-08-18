<?php
/*
Simple:Press
Form Rendering
$LastChangedDate: 2012-03-09 10:59:03 -0700 (Fri, 09 Mar 2012) $
$Rev: 8084 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

# --------------------------------------------------------------------------------------
#
# Top level form calls whcih then call the form painting functions
#
# --------------------------------------------------------------------------------------

function sp_inline_login_form($a) {
	include_once(SF_PLUGIN_DIR.'/forum/content/forms/sp-form-login.php');
	return sp_render_inline_login_form($a);
}

function sp_inline_search_form($args) {
	include_once(SF_PLUGIN_DIR.'/forum/content/forms/sp-form-search.php');
	return sp_render_inline_search_form($args);
}

function sp_add_topic($addTopicForm) {
	include_once(SF_PLUGIN_DIR.'/forum/content/forms/sp-form-topic.php');
	return sp_render_add_topic_form($addTopicForm);
}

function sp_add_post($addPostForm) {
	include_once(SF_PLUGIN_DIR.'/forum/content/forms/sp-form-post.php');
	return sp_render_add_post_form($addPostForm);
}

function sp_edit_post($editPostForm, $postid, $postcontent) {
	include_once(SF_PLUGIN_DIR.'/forum/content/forms/sp-form-post.php');
	return sp_render_edit_post_form($editPostForm, $postid, $postcontent);
}

function sp_forum_unavailable() {
	global $spThisUser;

	$out = '';
	$out.= '<div id="spMainContainer">';
	$out.= '<div class="spMessage">';
	$out.= '<p><img src="'.SPTHEMEICONSURL.'sp_Information.png" alt="" /> ';
	$out.= sp_text('Sorry, the forum is temporarily unavailable while it is being upgraded to a new version.').'</p>';
	if (sp_is_forum_admin($spThisUser->ID)) {
		$out.= '<a href="'.SFADMINFORUM.'">'.sp_text('Click here to perform the upgrade').'</a>';
	}
	$out.= '</div>';
	$out.= '</div>';
	$out = apply_filters('sph_forum_unavailable', $out);
	return $out;
}

function sp_setup_editor($tab, $content='') {
	global $sfglobals;

	$out = '';
	$out.= apply_filters('sph_pre_editor_display', '', $sfglobals['editor']);
	$out.= apply_filters('sph_editor_textarea', $out, 'postitem', $content, $sfglobals['editor'], $tab);
	$out.= apply_filters('sph_post_editor_display', '', $sfglobals['editor']);
	return $out;
}

function sp_render_smileys() {
	global $sfglobals;

	$out='';
	if ($sfglobals['sfsmileys']) {
		# load smiles from sfmeta
		if ($sfglobals['smileys']['smileys']) {
			foreach ($sfglobals['smileys']['smileys'] as $sname => $sinfo) {
				if ($sinfo[2]) {
					$out.= '<img class="spSmiley" src="'.esc_url(SFSMILEYS.$sinfo[0]).'" title="'.esc_attr($sname).'" alt="'.esc_attr($sname).'" ';
					$out.= 'onclick="spjEdInsertSmiley(\''.esc_js($sinfo[0]).'\', \''.esc_js($sname).'\', \''.SFSMILEYS.'\', \''.esc_js($sinfo[1]).'\');" />';
					if(isset($sinfo[4]) && $sinfo[4]==true) $out.='<br />';
				}
			}
		}
	}
	return $out;
}
?>