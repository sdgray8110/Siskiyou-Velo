<?php
/*
Simple:Press
Desc: $LastChangedDate: 2012-06-04 11:57:12 -0700 (Mon, 04 Jun 2012) $
$Rev: 8656 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

# ==========================================================================================
#
# 	FORUM PAGE
#	This file loads for forum pages only - Framework rendering functions
#
# ==========================================================================================

# ------------------------------------------------------------------
# sp_load_forum_scripts()
#
# Enqueue's necessary javascript and inline header script
# ------------------------------------------------------------------
function sp_load_forum_scripts() {
	global $sfvars;
	$footer = (sp_get_option('sfscriptfoot')) ? true : false;

	do_action('sph_scripts_start', $footer);

	wp_enqueue_script('jquery', false, array(), false, false);

	if (SF_USE_PRETTY_CBOX) {
        $script = (defined('SP_SCRIPTS_DEBUG') && SP_SCRIPTS_DEBUG) ? SFCJSCRIPT.'checkboxes/prettyCheckboxes-dev.js' : SFCJSCRIPT.'checkboxes/prettyCheckboxes.js';
		wp_enqueue_script('jquery.checkboxes', $script, array('jquery'), false, $footer);
	}

	if ($sfvars['pageview'] == 'topic') {
        # This script cannot be compressed
		wp_enqueue_script('sfprint', SFJSCRIPT.'print/jquery.jqprint.js', array('jquery'), false, $footer);
	}

	# Dialog boxes and other jQuery UI components
    $script = (defined('SP_SCRIPTS_DEBUG') && SP_SCRIPTS_DEBUG) ? SFJSCRIPT.'msdropdown/msdropdown-dev.js' : SFJSCRIPT.'msdropdown/msdropdown.js';
	wp_enqueue_script('jquery.ui.msdropdown', $script, array('jquery', 'jquery-ui-core', 'jquery-ui-widget'), false, $footer);

	if ($sfvars['pageview'] == 'profileshow' || $sfvars['pageview'] == 'profileedit') {
		wp_enqueue_script('jquery-form', false, array('jquery'), false, $footer);
	}

    $script = (defined('SP_SCRIPTS_DEBUG') && SP_SCRIPTS_DEBUG) ? SFJSCRIPT.'sp-forum-dev.js' : SFJSCRIPT.'sp-forum.js';
	wp_enqueue_script('spforum', $script, array('jquery'), false, $footer);

	$strings = array(
		'problem' 		=> sp_text('Unable to save'),
		'noguestname'	=> sp_text('No guest username entered'),
		'noguestemail'	=> sp_text('No guest email Entered'),
		'notopictitle'	=> sp_text('No topic title entered'),
		'nomath'		=> sp_text('Spam math unanswered'),
		'nocontent'		=> sp_text('No post content entered'),
		'rejected'		=> sp_text('This post is rejected because it contains embedded formatting, probably pasted in form MS Word or other WYSIWYG editor'),
		'iframe'		=> sp_text('This post contains an iframe which are disallowed'),
		'savingpost'	=> sp_text('Saving post'),
		'nosearch'		=> sp_text('No search text entered'),
		'allwordmin'	=> sp_text('Minimum number of characters that can be used for a search word is'),
		'somewordmin'	=> sp_text('Not all words can be used for the search as minimum word length is'),
		'wait'			=> sp_text('Please wait'),
	);
    wp_localize_script('spforum', 'sp_forum_vars', $strings);

    $script = (defined('SP_SCRIPTS_DEBUG') && SP_SCRIPTS_DEBUG) ? SFCJSCRIPT.'sp-common-dev.js' : SFCJSCRIPT.'sp-common.js';
	wp_enqueue_script('spcommon', $script, array('jquery', 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-dialog'), false, $footer);

	wp_enqueue_script('jquery-touch-punch', false, array('jquery', 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-mouse'), false, $footer);

	do_action('sph_scripts_end', $footer);
}

# ------------------------------------------------------------------
# sp_forum_header()
#
# Constructs the header for the forum - Javascript and CSS
# ------------------------------------------------------------------
function sp_forum_header() {
	global $wp_query, $sfglobals, $sfvars, $SPSTATUS, $SFMOBILE;

	do_action('sph_head_start');

	# The CSS is being set early in case we have to bow out quickly due to
	# the forum needing to be ugraded. This is to ensure that
	# this is the very FIRST thing to happen in the header (so we don't use enqueue styles for this)
    $curTheme = sp_get_current_sp_theme(); # get optional color variant to pass to stylesheet
	echo '<link rel="stylesheet" type="text/css" href="'.SPTHEMECSS.'?color='.esc_attr($curTheme['color']).'" />'."\n";

	# So - check if it needs to be upgraded...
	if ($SPSTATUS != 'ok') return sp_forum_unavailable();

	# If page is password protected, ensure it matches before starting
	if (!empty($wp_query->post->post_password)) {
		if ($_COOKIE['wp-postpass_'.COOKIEHASH] != $wp_query->post->post_password) return;
	}

	while ($x = has_filter('the_content', 'wpautop')) {
		remove_filter('the_content', 'wpautop', $x);
	}
	remove_filter('the_content', 'convert_smilies');

	# do meta stuff
	sp_setup_meta_tags();

	# load page specific css
	if ($sfvars['pageview'] == 'topic' || $sfvars['pageview'] == 'forum') {
		# if setting for post content width apply word-wrap
		if ($sfvars['postwidth'] > 0) {
			?>
			<style type="text/css">
			.spPostContent, .spPostContent p, .spPostContent pre, .spPostContent blockquote, .spPostContent table {
				max-width: <?php echo $sfvars['postwidth']; ?>px !important;
				text-wrap: normal;
				word-wrap: break-word; }
			<?php do_action('sph_textwrap_css'); ?>
			</style>
			<?php
		}
	}

	do_action('sph_head_end');
}

# ------------------------------------------------------------------
# sp_forum_footer()
#
# Constructs the footer for the forum - Javascript
# ------------------------------------------------------------------

function sp_forum_footer() {
	global $sfvars, $spThisUser, $SFMOBILE;

	do_action('sph_footer_start');

	# wait for page load and run JS inits
	?>
	<script type="text/javascript">
		var jspf = jQuery.noConflict();
		jspf(document).ready(function() {
			<?php
			# Quicklinks selects
			?>
			jspf("#spQuickLinksForumSelect, #spQuickLinksTopicSelect").msDropDown();
			jspf('#spQuickLinksForum').show();
			jspf('#spQuickLinksTopic').show();
			<?php
			# Checkboxes/radio buttons and tooltips
			if (SF_USE_PRETTY_CBOX) { ?>
				jspf("input[type=checkbox],input[type=radio]").prettyCheckboxes();
			<?php } ?>

            <?php if (!$SFMOBILE) { ?>
                jspf(function(jspf){vtip();})
            <?php } ?>

			<?php
			# Sets cookies with content and paragraph widths
			$docookie = true;
			$sfpostwrap = array();
			$sfpostwrap = sp_get_option('sfpostwrap');
			if ($sfpostwrap['postwrap'] == false) $docookie = false;
			if ($sfvars['postwidth'] > 0) $docookie = false;
			if ($sfvars['pageview'] != 'topic') $docookie = false;
			if ($spThisUser->admin == false) $docookie = false;

			if ($docookie) { ?>
				var c = jspf(".spPostContent").width();
				var p = jspf(".spPostContent p").width();
				if(c && p) {
					jspf.cookie('c_width', c, { path: '/' });
					jspf.cookie('p_width', p, { path: '/' });
				}
			<?php } ?>

			<?php
			# pre-load 'wait' image
			?>
				waitImage = new Image(32,32);
				waitImage.src = '<?php echo SPFIMAGES.'sp_Wait.png'; ?>';
			<?php

			# check if this is a redirect from a failed save
			if ($sfvars['pageview'] == 'topic' || $sfvars['pageview'] == 'forum') { ?>
				if(jspf('#spPostNotifications').html() != null) {
					if(jspf('#spPostNotifications').html() != '') {
						jspf('#spPostNotifications').show();
						spjOpenEditor('spPostForm', 'post');
					}
				}
			<?php }

			# turn on auto update of required
			$sfauto = array();
			$sfauto = sp_get_option('sfauto');
			if ($sfauto['sfautoupdate']) {
				$timer = ($sfauto['sfautotime'] * 1000);

				$autoup = sp_get_sfmeta('autoupdate');
				$arg = '';
				foreach ($autoup as $up) {
					$list = implode($up['meta_value'], ',');
					$list .= '&amp;sfnonce='.wp_create_nonce('forum-ahah');
					if ($arg != '') $arg.= '%';
					$arg.= $list;
				}
				?>
				spjAutoUpdate("<?php echo $arg; ?>", "<?php echo $timer; ?>");
			<?php } ?>
			<?php
			?>
		});
	</script>
	<?php

	do_action('sph_footer_end');
}

# ------------------------------------------------------------------
# sp_render_forum()
#
# Central Control of forum rendering
# Called by the_content filter
#	$content:	The page content
# ------------------------------------------------------------------
function sp_render_forum($content) {
	global $ISFORUM, $CONTENTLOADED, $sfvars, $sfglobals, $spThisUser, $SPSTATUS;

	# make sure we are at least in the html body before outputting any content
	if (!sp_get_option('sfwpheadbypass') && !did_action('wp_head')) return '';

	if ($ISFORUM && !post_password_required(get_post(sp_get_option('sfpage')))) {
       # Limit forum display to within the wp loop?
    	if (sp_get_option('sfinloop') && !in_the_loop()) return $content;

		# Has forum content already been loaded and are we limiting?
		if (!sp_get_option('sfmultiplecontent') && $CONTENTLOADED) return $content;
		$CONTENTLOADED = true;

		# check installed version is correct (needed even though the same call is in startup!)
		if ($SPSTATUS != 'ok') return sp_forum_unavailable();

		sp_clean_controls();

		sp_set_server_timezone();

#-----------------------------------------------

#-----------------------------------------------

		if (isset($_GET['mark-read'])) sp_mark_all_read();
		if (isset($_GET['mark'])) sp_remove_from_waiting(true, $sfvars['topicid'], 0);
		if (isset($_POST['editpost'])) sp_save_edited_post();
		if (isset($_POST['edittopic'])) sp_save_edited_topic();
		if (isset($_POST['locktopic'])) sp_lock_topic_toggle(sp_esc_int($_POST['locktopic']));
		if (isset($_POST['pintopic'])) sp_pin_topic_toggle($_POST['pintopic']);
		if (isset($_POST['makepostreassign'])) sp_reassign_post();
		if (isset($_POST['approvepost'])) sp_approve_post(false, $_POST['approvepost'], $sfvars['topicid']);
		if (isset($_POST['pinpost'])) sp_pin_post_toggle($_POST['pinpost']);
		if (isset($_POST['doqueue'])) sp_remove_waiting_queue();

		# delete a topic and redirect to forum page
		if (isset($_POST['killtopic'])) {
            sp_delete_topic(sp_esc_int($_POST['killtopic']));
           	$forumslug = spdb_table(SFFORUMS, 'forum_id='.sp_esc_int($_POST['killtopicforum']), 'forum_slug');
            $returnURL = sp_build_url($forumslug, '', 0);
            sp_redirect($returnURL);
        }

		# move a topic and redirect to that topic
		if (isset($_POST['maketopicmove'])) {
            sp_move_topic();
           	$forumslug = spdb_table(SFFORUMS, 'forum_id='.sp_esc_int(sp_esc_int($_POST['forumid'])), 'forum_slug');
           	$topicslug = spdb_table(SFTOPICS, 'topic_id='.sp_esc_int(sp_esc_int($_POST['currenttopicid'])), 'topic_slug');
            $returnURL = sp_build_url($forumslug, $topicslug, 0);
            sp_redirect($returnURL);
        }

		# move a post and redirect to the post
		if (isset($_POST['makepostmove1']) || isset($_POST['makepostmove2']) || isset($_POST['makepostmove3'])) {
            sp_move_post();
            if (isset($_POST['makepostmove1'])) {
	            $returnURL = sp_permalink_from_postid(sp_esc_int($_POST['postid']));
    	        sp_redirect($returnURL);
    	    }
        }
        if(isset($_POST['cancelpostmove'])) {
        	$meta = sp_get_sfmeta('post_move', 'post_move');
        	if($meta) {
        		$id = $meta[0]['meta_id'];
        		sp_delete_sfmeta($id);
        		unset($sfglobals['post_move']);
        	}
        }

		# delete a post and redirect to forum or topic if it still exists
		if (isset($_POST['killpost'])) {
            sp_delete_post(sp_esc_int($_POST['killpost']), sp_esc_int($_POST['killposttopic']), sp_esc_int($_POST['killpostforum']), true, sp_esc_int($_POST['killpostposter']));
           	$forumslug = spdb_table(SFFORUMS, 'forum_id='.sp_esc_int($_POST['killpostforum']), 'forum_slug');
           	$topicslug = spdb_table(SFTOPICS, 'topic_id='.sp_esc_int($_POST['killposttopic']), 'topic_slug');
            $returnURL = sp_build_url($forumslug, $topicslug, 0);
            sp_redirect($returnURL);
        }

		# rebuild the forum and post indexes
		if (isset($_POST['rebuildforum']) || isset($_POST['rebuildtopic'])) {
			sp_build_post_index(sp_esc_int($_POST['topicid']), true);
			sp_build_forum_index(sp_esc_int($_POST['forumid']), false);
		}

		# Set display mode if topic view (for editing posts)
		if ($sfvars['pageview'] == 'topic' && isset($_POST['postedit'])) {
			$sfvars['displaymode'] = 'edit';
			$sfvars['postedit'] = $_POST['postedit'];
		} else {
			$sfvars['displaymode'] = 'posts';
		}

#-----------------------------------------------

#-----------------------------------------------

		# let other plugins check for posted actions
		do_action('sph_setup_forum');

		# do we use output buffering?
		$ob = sp_get_option('sfuseob');
		if ($ob) ob_start();

		# set up some stuff before wp page content
		$content.= sp_display_banner();
   		$content = apply_filters('sph_before_wp_page_content', $content);

        # run any other wp filters on page content but exclude ours
		if (!$ob) {
            remove_filter('the_content', 'sp_render_forum', 1);
    		$content = apply_filters('the_content', $content);
            $content = wpautop($content);
            add_filter('the_content', 'sp_render_forum', 1);
        }

        # set up some stuff after wp page content
   		$content = apply_filters('sph_after_wp_page_content', $content);
		$content.= '<div id="dialogcontainer" style="display:none;"></div>';
		$content.= sp_js_check();

        # echo any wp page content
        echo $content;

        # now add our content
		do_action('sph_before_template_processing');
		sp_process_template();
		do_action('sph_after_template_processing');

		# Return if using output buffering
		if ($ob) {
			$forum = ob_get_contents();
			ob_end_clean();
			return $forum;
		}
	}

    # not returning any content since we output it already unless password needed
    if (post_password_required(get_post(sp_get_option('sfpage')))) return $content;
}

?>