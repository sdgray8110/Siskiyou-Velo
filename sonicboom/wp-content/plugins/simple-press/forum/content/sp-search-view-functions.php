<?php
/*
Simple:Press
Search View Function Handler
$LastChangedDate: 2012-03-26 10:06:58 -0700 (Mon, 26 Mar 2012) $
$Rev: 8233 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

# ======================================================================================
#
# 	SEARCH VIEW
#	Version: 5.0
#
# ======================================================================================

function sp_Search($args='') {
	global $spSearchView;
	$defs = array('show' 	=> 30,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_Search_args', $a);
	extract($a, EXTR_SKIP);
	$show = (int) $show;

	$spSearchView = new spSearchView($show);
}

# --------------------------------------------------------------------------------------
#
#	sp_SearchHeaderName()
#	Search Heading text
#	Scope:	search view
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_SearchHeaderName($args='', $termLabel='', $postedLabel='', $startedLabel='') {
	global $spSearchView, $sfvars;
	$defs = array('tagId' 		=> 'spSearchHeaderName',
				  'tagClass' 	=> 'spMessage',
				  'echo'		=> 1,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_SearchHeaderName_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId		= esc_attr($tagId);
	$tagClass	= esc_attr($tagClass);
	$term		= "'".$spSearchView->searchTermRaw."'";
	$echo		= (int) $echo;

	if ($sfvars['searchtype'] < 4) {
		$label = str_replace('%TERM%', $term, $termLabel);
	} elseif($sfvars['searchtype']==4) {
		$label = str_replace('%NAME%', $term, $postedLabel);
	} elseif($sfvars['searchtype']==5) {
		$label = str_replace('%NAME%', $term, $startedLabel);
	}
	$label = apply_filters('sph_search_label', $label, $sfvars['searchtype'], $sfvars['searchinclude'], $term);

	$out = "<div id='$tagId' class='$tagClass'>$label ($spSearchView->searchCount)</div>\n";
	$out = apply_filters('sph_SearchHeaderName', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_SearchPageLinks()
#	Search view page links
#	Scope:	search view
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_SearchPageLinks($args='', $label='', $toolTip='') {
	global $spSearchView, $sfvars;

	$items_per_page = $spSearchView->searchShow;
	if(!$items_per_page) $items_per_page=30;
	if($items_per_page >= $spSearchView->searchCount) return '';

	$defs = array('tagClass' 		=> 'spPageLinks',
				  'prevIcon'		=> 'sp_ArrowLeft.png',
				  'nextIcon'		=> 'sp_ArrowRight.png',
				  'iconClass'		=> 'spIcon',
				  'pageLinkClass'	=> 'spPageLinks',
				  'curPageClass'	=> 'spCurrent',
				  'showLinks'		=> 4,
				  'echo'			=> 1,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_SearchPageLinks_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagClass		= esc_attr($tagClass);
	$prevIcon		= SPTHEMEICONSURL.sanitize_file_name($prevIcon);
	$nextIcon		= SPTHEMEICONSURL.sanitize_file_name($nextIcon);
	$iconClass		= esc_attr($iconClass);
	$pageLinkClass	= esc_attr($pageLinkClass);
	$curPageClass	= esc_attr($curPageClass);
	$showLinks		= (int) $showLinks;
	$label			= sp_filter_title_display($label);
	$toolTip		= esc_attr($toolTip);
	$echo			= (int) $echo;

	$curToolTip = str_ireplace('%PAGE%', $sfvars['searchpage'], $toolTip);

	$out = "<div class='$tagClass'>";
	$totalPages = ($spSearchView->searchCount / $items_per_page);
	if (!is_int($totalPages)) $totalPages = (intval($totalPages) + 1);
	$out.= $label;
	$out.= sp_page_prev($sfvars['searchpage'], $showLinks, $spSearchView->searchPermalink, $pageLinkClass, $iconClass, $prevIcon, $nextIcon, $toolTip, $sfvars['searchpage']);

	$url = $spSearchView->searchPermalink;
	if ($sfvars['searchpage'] > 1) $url = user_trailingslashit(trailingslashit($spSearchView->searchPermalink).'&amp;search='.$sfvars['searchpage']);
	$out.= "<a href='$url' class='$pageLinkClass $curPageClass vtip' title='$curToolTip'>".$sfvars['searchpage'].'</a>';

	$out.= sp_page_next($sfvars['searchpage'], $totalPages, $showLinks, $spSearchView->searchPermalink, $pageLinkClass, $iconClass, $prevIcon, $nextIcon, $toolTip, $sfvars['searchpage']);
	$out.= "</div>\n";
	$out = apply_filters('sph_SearchPageLinks', $out, $a);

	if ($echo) {
		echo $out;
	} else {
		return $out;
	}
}

# --------------------------------------------------------------------------------------
#
#	sp_SearchResults()
#	Search results - uses the ListView template and template functions for display
#	Scope:	search view
#	Version: 5.0
#
# --------------------------------------------------------------------------------------
function sp_SearchResults($args='') {
	global $spSearchView, $spThisUser, $spListView, $spThisListTopic;
	$defs = array('tagId'		=> 'spSearchList',
				  'tagClass'	=> 'spSearchSection',
				  'template'	=> 'spListView.php',
				  'get'			=> 0,
				  );
	$a = wp_parse_args($args, $defs);
	$a = apply_filters('sph_SearchResults_args', $a);
	extract($a, EXTR_SKIP);

	# sanitize before use
	$tagId		= esc_attr($tagId);
	$tagClass	= esc_attr($tagClass);

	if ($get) return $spSearchView->searchData;

	echo "<div id='$tagId' class='$tagClass'>\n";
	$spListView = new spTopicList($spSearchView->searchData, 0, false);
	sp_load_template($template);
	echo "</div>\n";
}

?>