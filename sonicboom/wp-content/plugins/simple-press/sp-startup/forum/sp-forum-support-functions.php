<?php
/*
Simple:Press
Desc: $LastChangedDate: 2012-06-02 02:32:37 -0700 (Sat, 02 Jun 2012) $
$Rev: 8634 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

# ==========================================================================================
#
# 	FORUM PAGE
#	This file loads for forum page loads only
#
# ==========================================================================================

# ------------------------------------------------------------------
# sp_front_page_redirect()
#
# gets around the default canonical url behaviour when the
# forum is set to be the front page of the site - normally the
# ful url wold be discarded leaving just the home url.
# ------------------------------------------------------------------
function sp_front_page_redirect($redirect) {
	global $wp_query;

	if ($wp_query->is_page) {
		if (isset($wp_query->queried_object) && 'page' == get_option('show_on_front') && $wp_query->queried_object->ID == get_option('page_on_front')) {
			if (sp_get_option('sfpage') == get_option('page_on_front')) return false;
		}
	}
	return $redirect;
}

# ------------------------------------------------------------------
# sp_populate_query_vars()
#
# Populate the forum query variables from the URL
# ------------------------------------------------------------------
function sp_populate_query_vars() {
	global $sfvars, $sfglobals, $wp_rewrite;

	# load query vars
	if (isset($sfglobals['queryvarsdone']) && $sfglobals['queryvarsdone'] == true) return;
	$sfglobals['queryvarsdone'] = true;

	# initialize with some defaults
	$sfvars['error'] = false;
	$sfvars['forumid'] = 0;
	$sfvars['topicid'] = 0;
	$sfvars['displaymode'] = '';
	$sfvars['pageview'] = '';
	$sfvars['searchpage'] = 0;
	$sfvars['searchtype'] = 0;
	$sfvars['searchinclude'] = 0;
	$sfvars['newsearch'] = 0;

	# Special var for post content width if being used
	$sfvars['postwidth'] = 0;
	$sfpostwrap = array();
	$sfpostwrap = sp_get_option('sfpostwrap');
	if ($sfpostwrap['postwrap']) {
		$sfvars['postwidth'] = $sfpostwrap['postwidth'];

		# if not set then try and get from cookie
		if ($sfvars['postwidth'] == false) {
			if (isset($_COOKIE["p_width"]) && isset($_COOKIE["c_width"])) {
				$c_width = $_COOKIE["c_width"];
				$p_width = $_COOKIE["p_width"];
				if ($p_width > $c_width ? $w = ($c_width-80) : $w = $p_width);
				$sfpostwrap['postwidth'] = $w;
				sp_update_option('sfpostwrap', $sfpostwrap);
				$sfvars['postwidth'] = $w;
			}
		}
	}

	# user has permalinks
	if ($wp_rewrite->using_permalinks()) {
		# post V3 permalinks
		# using permalinks so get the values from the query vars
		$sfvars['forumslug'] = sp_esc_str(get_query_var('sf_forum'));
		if (empty($sfvars['forumslug']) && isset($_GET['search'])) $sfvars['forumslug'] = sp_esc_str($_GET['forum']);
		$sfvars['topicslug'] = sp_esc_str(get_query_var('sf_topic'));
		$sfvars['profile'] = sp_esc_str(get_query_var('sf_profile'));
		$sfvars['member'] = sp_esc_str(get_query_var('sf_member'));
		$sfvars['members'] = sp_esc_str(get_query_var('sf_members'));
		$sfvars['box'] = sp_esc_str(get_query_var('sf_box'));
		$sfvars['feed'] = sp_esc_str(get_query_var('sf_feed'));
		$sfvars['feedkey'] = sp_esc_str(get_query_var('sf_feedkey'));
		$sfvars['newposts'] = sp_esc_str(get_query_var('sf_newposts'));
		if (get_query_var('sf_page') != '') $sfvars['page'] = sp_esc_int(get_query_var('sf_page'));

		do_action('sph_get_query_vars');

		sp_populate_support_vars();
	} else {
		# Not using permalinks so we need to parse the query string from the url and do it ourselves
		$stuff = array();
		$stuff = explode('/', $_SERVER['QUERY_STRING']);

		# deal with non-standard cases first
		if (isset($_GET['search'])) {
			sp_build_search_vars($stuff);
		} else {
			sp_build_standard_vars($stuff);
		}

		sp_populate_support_vars();

		if (empty($sfvars['forumid'])) $sfvars['forumid'] = 0;
		if (empty($sfvars['topicid'])) $sfvars['topicid'] = 0;
		if (empty($sfvars['profile'])) $sfvars['profile'] = 0;
		if (empty($sfvars['member'])) $sfvars['member'] = 0;
		if (empty($sfvars['members'])) $sfvars['members'] = 0;
		if (empty($sfvars['box'])) $sfvars['box'] = 0;
		if (empty($sfvars['feed'])) $sfvars['feed'] = 0;
		if (empty($sfvars['feedkey'])) $sfvars['feedkey'] = 0;
		if (empty($sfvars['newposts'])) $sfvars['newposts'] = 0;

		do_action('sph_fill_query_vars');
	}
	sp_setup_page_type();

	# Now at this point sfvars should be set up
	# So from this point on we can direct messages at the user
	sp_track_online();
}

# ------------------------------------------------------------------
# sp_populate_support_vars()
#
# Query Variables support routine
# ------------------------------------------------------------------
function sp_populate_support_vars() {
	global $sfvars;

	# Populate the rest of sfvars
	if (empty($sfvars['page'])) $sfvars['page'] = 1;
	if (!empty($sfvars['forumslug']) && $sfvars['forumslug'] != 'all') {
		$record = spdb_table(SFFORUMS, "forum_slug='".$sfvars['forumslug']."'", 'row');
		if ($record) {
			$sfvars['forumid'] = $record->forum_id;
			if (empty($sfvars['forumid'])) $sfvars['forumid'] = 0;
			if ($record) $sfvars['forumname'] = $record->forum_name;

			# Is it a subforum?
			if (!empty($record->parent)) {
				$forumparent = $record->parent;
				while ($forumparent > 0) {
					$parent = spdb_table(SFFORUMS, "forum_id=$forumparent", 'row');
					$sfvars['parentforumid'][] = $forumparent;
					$sfvars['parentforumslug'][] = $parent->forum_slug;
					$sfvars['parentforumname'][] = $parent->forum_name;
					$forumparent = $parent->parent;
				}
			}
		} else {
			$header = apply_filters('sph_404', 404);
			status_header($header);
		}
	}

	if (!empty($sfvars['topicslug'])) {
		$record = spdb_table(SFTOPICS, "topic_slug='".$sfvars['topicslug']."'", 'row');
		if ($record) {
			$sfvars['topicid'] = $record->topic_id;
			if (empty($sfvars['topicid'])) $sfvars['topicid'] = 0;
			if($record) $sfvars['topicname'] = $record->topic_name;

			# verify forum slug matches forum slug based on topic and do canonical redirect if doesnt match (moved?)
			$forum = spdb_table(SFFORUMS, "forum_id='".$record->forum_id."'", 'row');
			if ($forum->forum_slug != $sfvars['forumslug']) {
				$url = sp_build_url($forum->forum_slug, $sfvars['topicslug'], $sfvars['page'], 0);
				wp_redirect(esc_url($url), 301);
			}
		} else {
			$header = apply_filters('sph_404', 404);
			status_header($header);
		}
	}

	# Add Search Vars
	if (isset($_GET['search'])) {
		if ($_GET['search'] != '') $sfvars['searchpage'] = intval($_GET['search']);
		$sfvars['searchpage'] = sp_esc_int($sfvars['searchpage']);

		if (isset($_GET['type']) ? $sfvars['searchtype'] = intval($_GET['type']) : $sfvars['searchtype'] = 1);
		$sfvars['searchtype'] = sp_esc_int($sfvars['searchtype']);
		if ($sfvars['searchtype'] == 0 || empty($sfvars['searchtype'])) $sfvars['searchtype'] =1;

		if (isset($_GET['include']) ? $sfvars['searchinclude'] = intval($_GET['include']) : $sfvars['searchinclude'] = 1);
		$sfvars['searchinclude'] = sp_esc_int($sfvars['searchinclude']);
		if ($sfvars['searchinclude'] == 0 || empty($sfvars['searchinclude'])) $sfvars['searchinclude'] =1;

		if (isset($_GET['value']) ? $sfvars['searchvalue'] = sp_filter_save_nohtml(urldecode($_GET['value'])) : $sfvars['searchvalue'] = '');

		$sfvars['searchvalue'] = sp_filter_table_prefix($sfvars['searchvalue']);

		if(isset($_GET['new']) ? $sfvars['newsearch'] = true : $sfvars['newsearch'] = false);

		if (empty($sfvars['searchvalue']) || $sfvars['searchvalue']=='') {
			$sfvars['searchpage'] = 0;
			$sfvars['searchtype'] = 0;
			$sfvars['searchinclude'] = 0;
			sp_notify(1, sp_text('Invalid search query'));
			wp_redirect(sp_url());
		}
	} else {
		$sfvars['searchpage'] = 0;
	}
	$sfvars['searchresults'] = 0;
}

# ------------------------------------------------------------------
# sp_build_search_vars()
#
# Query Variables support routine
# ------------------------------------------------------------------
function sp_build_search_vars($stuff) {
	global $sfvars;

	if (isset($_GET['forum'])) {
		# means searching all
		$sfvars['forumslug'] = sp_esc_str($_GET['forum']);
	} else {
		# searching single forum
		if (!empty($stuff[1])) $sfvars['forumslug'] = $stuff[1];

		# (2) topic
		if (!empty($stuff[2])) {
			$parts = explode('&', $stuff[2]);
			$sfvars['topicslug'] = $parts[0];
		}
	}
}

# ------------------------------------------------------------------
# sp_build_standard_vars()
#
# Query Variables support routine
# ------------------------------------------------------------------
function sp_build_standard_vars($stuff) {
	global $sfvars, $current_user;

	# special forum page check
	if (!empty($stuff[1])) {
		if ($stuff[1] == 'profile') {
    		if (empty($stuff[2])) {
                $sfvars['member'] = urlencode($current_user->user_login);
    			$sfvars['profile'] = 'edit';
    		} elseif (empty ($stuff[3])) {
    			$sfvars['member'] = sp_esc_str($stuff[2]);
    			$sfvars['profile'] = 'show';
    		} else {
    			$sfvars['member'] = sp_esc_str($stuff[2]);
    			$sfvars['profile'] = 'edit';
    		}
		} elseif ($stuff[1] == 'members') {
			$sfvars['members'] = 'list';
			if (isset($stuff[2]) && preg_match("/page-(\d+)/", $stuff[2], $matches)) $sfvars['page'] = intval($matches[1]);
        } else if ($stuff[1] == 'newposts') {
            $sfvars['newposts'] = 'all';
		} elseif ($stuff[1] == 'rss') {
			$sfvars['feed'] = 'all';
			$sfvars['feedkey'] = (isset($stuff[2])) ? $stuff[2] : '';
		} else {
            $sfvars['plugin-vars'] = false;
			do_action('sph_get_def_query_vars', $stuff);

            if (!$sfvars['plugin-vars']) {
    			# forum page
    			$substuff = explode('&', $stuff[1]);
    			$sfvars['forumslug'] = $substuff[0];

    			# topic, page or rss check
    			if (!empty($stuff[2])) {
    				$matches = array();
    				if ($stuff[2] == 'rss') {
    					# forum rss feed
    					$sfvars['feed'] = 'forum';
    					$sfvars['feedkey'] = $stuff[3];
    				} elseif (preg_match("/page-(\d+)/", $stuff[2], $matches)) {
    					# forum with page
    					$sfvars['page'] = intval($matches[1]);
    				} else {
    					# topic page
    					$substuff = explode('&', $stuff[2]);
    					$sfvars['topicslug'] = $substuff[0];

    					# page or rss check
    					if (isset($stuff[3]) && $stuff[3] == 'rss') {
    						# topic rss feed
    						$sfvars['feed'] = 'topic';
    						$sfvars['feedkey'] = $stuff[4];
    					} elseif (isset($stuff[3]) && preg_match("/page-(\d+)/", $stuff[3], $matches)) {
    						# topic with page
    						$sfvars['page'] = intval($matches[1]);
    					}
    				}
    			}
            }
		}
	}
}

function sp_setup_page_type() {
	global $sfvars, $sfglobals, $spThisUser, $SPCACHE, $SPSTATUS;

	if ($SPSTATUS != 'ok') return;

	if (isset($sfglobals['pagetypedone']) && $sfglobals['pagetypedone'] == true) return;
	$sfglobals['pagetypedone'] = true;

	# Maybe a profile edit or first time logged in?
	# If user has made no posts yet optionaly load the profile form
	$pageview = '';
	$goProfile = false;
	$sfprofile = sp_get_option('sfprofile');
	if ($spThisUser->member && $spThisUser->posts == -1) {
		sp_update_member_item($spThisUser->ID, 'posts', 0);
		$goProfile = $sfprofile['firstvisit'];
		if ($sfprofile['forcepw']) {
			add_user_meta($spThisUser->ID, 'sp_change_pw', true, true);
			$spThisUser->sp_change_pw = true;
		}
	}

	if ($spThisUser->member && ($goProfile || (isset($spThisUser->sp_change_pw) && $spThisUser->sp_change_pw))) {
        $sfvars['member'] = urlencode($spThisUser->login_name);
		$pageview = 'profileedit';
		$sfvars['forumslug'] = '';
		$sfvars['topicslug'] = '';
	}

	if ($pageview == '') {
		if (!empty($sfvars['feed'])) {
			$pageview = 'feed';
		} elseif (!empty($sfvars['forumslug'])) {
			$pageview = 'forum';
		} elseif (!empty($sfvars['profile'])) {
			if ($sfvars['profile'] == 'edit') $pageview = 'profileedit';
			if ($sfvars['profile'] == 'show') $pageview = 'profileshow';
    	} else if (!empty($sfvars['newposts'])) {
    		$pageview = 'newposts';
		} elseif (!empty($sfvars['members'])) {
			$pageview = 'members';
		} else {
			$pageview = 'group';
			# and if a single group id is passed load ot ointo sfvars
			if (isset($_GET['group'])) $sfvars['groupid'] = sp_esc_int($_GET['group']);

			# Check if single forum only is on
			if (isset($sfglobals['display']['forums']['singleforum']) && $sfglobals['display']['forums']['singleforum']) {
				$fid = sp_single_forum_user();
				if ($fid) {
					$cforum = spdb_table(SFFORUMS, "forum_id=$fid", 'row');
					$sfvars['forumid'] = $fid;
					$sfvars['forumslug'] = $cforum->forum_slug;
					$sfvars['forumname'] = $cforum->forum_name;
					$SPCACHE = '';
					$pageview = 'forum';
				}
			}
		}

		if (!empty($sfvars['topicslug'])) $pageview = 'topic';
		if (isset($_GET['search']) && !empty($sfvars['searchvalue'])) $pageview = 'search';
	}

    # profile via ssl if doing ssl logins
    if (($pageview == 'profileedit' || $pageview == 'profileshow') && force_ssl_login() && !is_ssl()) {
        if (0 === strpos($_SERVER['REQUEST_URI'], 'http')) {
            wp_redirect(preg_replace('|^http://|', 'https://', $_SERVER['REQUEST_URI']));
            exit();
        } else {
            wp_redirect('https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
            exit();
        }
    }

	$sfvars['pageview'] = apply_filters('sph_pageview', $pageview);
}

function sp_canonical_url() {
	global $sfvars;

	if ($sfvars['pageview'] == 'profileshow' || $sfvars['pageview'] == 'profileedit') {
		$url = sp_url('profile');
	} elseif ($sfvars['pageview'] == 'list') {
		$page = '';
		if ($sfvars['page'] > 0) $page = '/page-'.$sfvars['page'];
		$url = sp_url('members'.$page);
	} else {
		if (!empty($sfvars['topicslug'])) {
			$url = sp_build_url($sfvars['forumslug'], $sfvars['topicslug'], $sfvars['page'], 0);
		} else if (!empty($sfvars['forumslug'])) {
			$url = sp_build_url($sfvars['forumslug'], '', $sfvars['page'], 0);
		} else {
            $url = sp_url();
		}
	}
	return apply_filters('sph_canonical_url', $url);
}

#
# Create canonical URL for AIOSEO
# ------------------------------------------------------------------
function sp_aioseo_canonical_url($url) {
	global $sfvars, $sfglobals, $ISFORUM, $wp_query;

	if ($ISFORUM) {
		$url = sp_canonical_url();
	} else {
		# Do we need to change this from an SP perspective
		$wpPost = $wp_query->get_queried_object();
		$url = apply_filter('sph_aioseo_canonical_url', $url, $wpPost);
	}
	$sfglobals['canonicalurl'] = true;

	return $url;
}

#
# Create meta description for AIOSEO
# ------------------------------------------------------------------
function sp_aioseo_description($aioseo_descr) {
	global $sfglobals, $ISFORUM;

	if ($ISFORUM) {
		$sfglobals['metadescription'] = true;

		$description = sp_get_metadescription();
		if ($description != '') $aioseo_descr = $description;
	}
	return $aioseo_descr;
}

#
# Create meta keywords for AIOSEO
# ------------------------------------------------------------------
function sp_aioseo_keywords($aioseo_keywords) {
	global $sfglobals, $ISFORUM;

	if ($ISFORUM) {
		$sfglobals['metakeywords'] = true;
		$keywords = sp_get_metakeywords();
		if ($keywords != '') $aioseo_keywords = $keywords;
	}
	return $aioseo_keywords;
}

function sp_aioseo_homepage($title) {
	global $ISFORUM;

	if ($ISFORUM) {
		$sfseo = array();
		$sfseo = sp_get_option('sfseo');
		$title = sp_setup_title($title, ' '.$sfseo['sfseo_sep'].' ');
	}
	return $title;
}

function sp_get_metadescription() {
	global $sfvars;

	$description = '';

	# do we need a meta description
	$sfmetatags = sp_get_option('sfmetatags');
	switch ($sfmetatags['sfdescruse']) {
		case 1:  # no meta description
			break;

		case 2:  # use custom meta description on all forum pages
			$description = sp_filter_title_display($sfmetatags['sfdescr']);
			break;

		case 3:  # use custom meta description on group view and forum description on forum/topic pages
			if (($sfvars['pageview'] == 'forum' || $sfvars['pageview'] == 'topic') && !isset($_GET['search'])) {
				$forum = spdb_table(SFFORUMS, "forum_slug='".$sfvars['forumslug']."'", 'row');
				$description = sp_filter_title_display($forum->forum_desc);
			} else {
				$description = sp_filter_title_display($sfmetatags['sfdescr']);
			}
			break;

		case 4:  # use custom meta description on group view, forum description on forum pages and topic title on topic pages
		case 5:  # use custom meta description on group view, forum description on forum pages and first post excerpt on topic pages
			if ($sfvars['pageview'] == 'forum' && !isset($_GET['search'])) {
				$forum = spdb_table(SFFORUMS, "forum_slug='".$sfvars['forumslug']."'", 'row');
				if ($forum) $description = sp_filter_title_display($forum->forum_desc);
			} elseif ($sfvars['pageview'] == 'topic' && !isset($_GET['search'])) {
                if ($sfmetatags['sfdescruse'] == 4) {
    				$topic = spdb_table(SFTOPICS, "topic_slug='".$sfvars['topicslug']."'", 'row');
    				if ($topic) $description = sp_filter_title_display($topic->topic_name);
                } else {
                    $content = spdb_table(SFPOSTS, "topic_id={$sfvars['topicid']}", 'post_content', 'post_id ASC', 1);
                    $description = wp_html_excerpt($content, 120);
                }
			} else {  # must be group or other
				$description = sp_filter_title_display($sfmetatags['sfdescr']);
			}
			break;
	}
	return apply_filters('sph_meta_description', $description);
}

function sp_get_metakeywords() {
	global $sfvars;

	$keywords = '';
	$sfmetatags = sp_get_option('sfmetatags');
	if (isset($sfmetatags['sfusekeywords']) && $sfmetatags['sfusekeywords']) $keywords = stripslashes($sfmetatags['sfkeywords']);

	return apply_filters('sph_meta_keywords', $keywords);
}

# ------------------------------------------------------------------
# sp_setup_browser_title()
#
# Filter call
# Sets up the browser page title
#	$title		page title
# ------------------------------------------------------------------
function sp_setup_browser_title($title) {
	global $wp_query;

	$post = $wp_query->get_queried_object();
    if (isset($post->ID) && $post->ID == sp_get_option('sfpage')) {
		$sfseo = sp_get_option('sfseo');
		$title = sp_setup_title($title, ' '.$sfseo['sfseo_sep'].' ');
	}
	return apply_filters('sph_browser_title', $title);
}


# ------------------------------------------------------------------
# sp_title_hook()
#
# called by start of the wp loop action to output hook data before the page title
# ------------------------------------------------------------------
function sp_title_hook() {
	$out = '';
	$out = apply_filters('sph_before_page_title', $out);
}

# ------------------------------------------------------------------
# sp_setup_page_title()
#
# Filter Call
# Sets up the page title option
#	$title: Page title
# ------------------------------------------------------------------
function sp_setup_page_title($title) {
	global $sfglobals, $sfvars;

	if (trim($title) == trim(SFPAGETITLE)) {

		if (!empty($sfglobals['display']['pagetitle']['banner'])) return '';
		if ($sfglobals['display']['pagetitle']['notitle']) return '';

		$seo = array();
		$seo = sp_get_option('sfseo');
		$title = sp_setup_title($title, ' '.$seo['sfseo_sep'].' ');
		$sfvars['seotitle']=$title;
	}
	return $title;
}

# ------------------------------------------------------------------
# sp_setup_title()
#
# Support Routine
# Sets up the page title option
# ------------------------------------------------------------------
function sp_setup_title($title, $sep) {
	global $sfvars;

	$sfseo = sp_get_option('sfseo');

	if ($sfseo['sfseo_overwrite']) $title = '';

	if ($sfseo['sfseo_blogname']) $title = get_bloginfo('name').$sep.$title;

	if ($sfseo['sfseo_pagename']) $title = single_post_title('', false).$sep.$title;

    $page = (!empty($sfvars['page']) && $sfvars['page'] > 1) ? $sep.sp_text('Page').' '.$sfvars['page'] : '';
	$forumslug = (isset($sfvars['forumslug'])) ? $sfvars['forumslug'] : '';
	$topicslug = (isset($sfvars['topicslug'])) ? $sfvars['topicslug'] : '';

	if (!empty($forumslug) && $forumslug != 'all' && $sfseo['sfseo_forum'] && (!$sfseo['sfseo_noforum'] || $sfvars['pageview'] != 'topic')) {
		if (!empty($topicslug) && $sfseo['sfseo_topic']) {
			$title = $sfvars['forumname'].$sep.$title;
		} else {
			$title = $sfvars['forumname'].$page.$sep.$title;
		}
	}

	if (!empty($topicslug) && $sfseo['sfseo_topic']) $title = $sfvars['topicname'].$page.$sep.$title;

	if ($sfseo['sfseo_page']) {
		$profile = urlencode($sfvars['profile']);
		if (!empty($profile) && $profile == 'edit') $title = sp_text('Edit Member Profile').$sep.$title;
		if (!empty($profile) && $profile == 'show') $title =  sp_text('Member Profile').$sep.$title;

		$list = urlencode($sfvars['list']);
		if (!empty($list) && $list == 'members') $title =  sp_text('Member List').$sep.$title;
	}

	if (!empty($sfvars['searchpage']) && $sfvars['searchpage'] > 0) $title = sp_text('Search').$sep.$title;

	# no separators at end
	$title = trim($title, $sep);

	return apply_filters('sph_page_title', $title, $sep);
}

# ???
function sp_setup_meta_tags() {
	global $sfglobals;

	if (empty($sfglobals['metadescription'])) {
		$description = sp_get_metadescription();
		if ($description != '') {
			$description = str_replace('"', '', $description);
			echo '<meta name="description" content="'.$description.'" />'."\n";
		}
	}

	if (empty($sfglobals['metakeywords'])) {
		$keywords = sp_get_metakeywords();
		if ($keywords != '') {
			$keywords = str_replace('"', '', $keywords);
			echo '<meta name="keywords" content="'.$keywords.'" />'."\n";
		}
	}

	if (empty($sfglobals['canonicalurl'])) {
		# output the canonical url
		$url = sp_canonical_url();
		echo '<link rel="canonical" href="'.$url.'" />'."\n";
	}
}

# ------------------------------------------------------------------
# sp_clean_controls()
#
# Cleans up sfcontrols in options
# ------------------------------------------------------------------
function sp_clean_controls() {
	$controls = sp_get_option('sfcontrols');

	# clean 404 flag
	$controls['fourofour'] = false;

	sp_update_option('sfcontrols', $controls);
}

# ------------------------------------------------------------------
# sp_load_editor()
#
# Loads appropriate editor suport
#	$override: Default or user selected can be overridden
#	$supportOnly:	Load editor support but not the editor scripts
# ------------------------------------------------------------------
function sp_load_editor($override = 0, $supportOnly = 0) {
	global $sfvars, $sfglobals, $SFMOBILE;
	# load editor if required

	if ($override != 0) {
		$sfglobals['editor'] = $override;
	} elseif ($SFMOBILE || (!empty($sfvars['pageview']) && $sfvars['pageview'] == 'profileedit')) {
		# force plain text editor
		$sfglobals['editor'] = 4;
	}

    # load editor support
	do_action('sph_load_editor_support', $sfglobals['editor']);

    # only load editor itself on required pages and if not a support only call
    $editorPage = apply_filters('sph_editor_check', 'forum topic profileedit');
	if (!empty($sfvars['pageview']) && strpos($editorPage, $sfvars['pageview']) !== false && !$supportOnly) {
		do_action('sph_load_editor', $sfglobals['editor']);
	}
}

if (!function_exists('post_password_required')):
function post_password_required( $post = null ) {
	$post = get_post($post);
	if (empty($post->post_password)) return false;
    if (!isset($_COOKIE['wp-postpass_'.COOKIEHASH])) return true;
	if ($_COOKIE['wp-postpass_'.COOKIEHASH] != $post->post_password) return true;
	return false;
}
endif;

# ------------------------------------------------------------------
# sp_wp_list_pages()
#
# Filter Call
# Sorts bug in wp_list_pages by swapping out modified title
#	$ptext: Page titles html string
# ------------------------------------------------------------------
function sp_wp_list_pages($ptext) {
	global $sfvars, $sfglobals;

	if (!empty($sfvars['seotitle'])) {
		$seotitle = $sfvars['seotitle'];
		$ptext = str_replace($seotitle, SFPAGETITLE, $ptext);
		$seotitle = html_entity_decode($seotitle, ENT_QUOTES);
		$seotitle = htmlspecialchars($seotitle, ENT_QUOTES);
		$ptext = str_replace($seotitle, SFPAGETITLE, $ptext);
		$seotitle = sp_filter_title_save($seotitle);
		$ptext = str_replace($seotitle, SFPAGETITLE, $ptext);
		$ptext = str_replace(strtoupper($seotitle), SFPAGETITLE, $ptext);
	} else {
		if($sfglobals['display']['pagetitle']['banner'] || $sfglobals['display']['pagetitle']['notitle']) {
			$ptext = str_replace('></a>', '>'.SFPAGETITLE.'</a>', $ptext);
		}
	}
	return $ptext;
}

# = JAVASCRIPT CHECK ==========================
function sp_js_check() {
	return '<noscript><div class="sfregmessage">'.sp_text('This forum requires Javascript to be enabled for posting content').'</div></noscript>'."\n";
}

?>