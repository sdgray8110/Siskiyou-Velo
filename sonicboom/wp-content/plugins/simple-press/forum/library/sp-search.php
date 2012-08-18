<?php
/*
Simple:Press
Forum Search url creation
$LastChangedDate: 2012-06-02 02:32:37 -0700 (Sat, 02 Jun 2012) $
$Rev: 8634 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

# ------------------------------------------------------------------------------------------
# 								POST variables			URL contruct		$sfvars
# ------------------------------------------------------------------------------------------
#
# Search (Standard)				-						search = 1			searchpage
#
# Search Value					searchvalue				value='???'			searchvalue
#
# Search Option:
#	search current forum		searchoption = 1		forum=forum_slug	forumslug
#	search all forums			searchoption = 2		forum=all			forumslug ('all')
#
# Type =
#	Match any word				searchtype = 1			type = 1			searchtype
#	Match all words				searchtype = 2			type = 2				"
#	Match phrase				searchtype = 3			type = 3				"
# ------------------------------------------------------------------------------------------
#	Member 'posted in'			searchtype = 4			type = 4				"
#	Member 'started'			searchtype = 5			type = 5				"
#
# Include =
#	Posts Only					encompass = 1			include = 1			searchinclude
#	Topic Titles only			encompass = 2			include = 2				"
#	Posts and Topic Titles		encompass = 3			include = 3				'
#
# ------------------------------------------------------------------------------------------
# NOTE FOR PLUGINS:
#	Each plugin must use a unique 'type' -
#	core SP and core SP plugins reserves 1 to 20
#		Plugin:	Topic Status:			uses type 10
#
# ------------------------------------------------------------------------------------------

$url = $_SERVER['HTTP_REFERER'];

$param = array();
$param['search'] = 1;
if ($_POST['searchoption'] == 2) {
	$param['forum'] = 'all';
} else {
	$param['forum'] = sp_esc_str($_POST['forumslug']);
}

if(!empty($_POST['searchvalue'])) {
	# standard search
	$searchvalue = trim(stripslashes($_POST['searchvalue']));
	$searchvalue = trim($searchvalue, '"');
	$searchvalue = trim($searchvalue, "'");
	$param['value'] = urlencode($searchvalue);
	$param['type'] = sp_esc_int($_POST['searchtype']);
	$param['include'] = sp_esc_int($_POST['encompass']);
	$param['new'] = 1;
} elseif(isset($_POST['memberstarted']) && !empty($_POST['memberstarted'])) {
	# member 'started' search
	$id = sp_esc_int($_POST['userid']);
	$param['value'] = $id;
	$param['type'] = 5;
} elseif(isset($_POST['membersearch']) && !empty($_POST['membersearch'])) {
	# member 'posted in' search
	$id = sp_esc_int($_POST['userid']);
	$param['value'] = $id;
	$param['type'] = 4;
} else {
	# Available for plugins to prepare search query vars
	$param = apply_filters('sph_prepare_search', $param);
}

$url = add_query_arg($param, sp_url());
wp_redirect($url);

die();
?>