<?php
/*
Simple:Press
Search View Class
$LastChangedDate: 2012-06-02 02:32:37 -0700 (Sat, 02 Jun 2012) $
$Rev: 8634 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

# ==========================================================================================
#	Version: 5.0
#
#	Search View Class
#	performs the sql work - passes off a topic list to List View
#
# ==========================================================================================

class spSearchView {
	# Search View DB query result set
	var $searchData = array();

	# Count of topic records
	var $searchCount = 0;

	# How many to show per page
	var $searchShow = 0;

	# Some search values from sfvars
	var $searchTerm = '';

	# the original, raw term
	var $searchTermRaw = '';

	# Permalink
	var $searchPermalink = '';

	# Forum where clause
	var $forumWhere = '';

	# limit
	var $limit = 0;

	# Run in class instantiation - populates data
	function __construct($count=0) {
		$this->searchPermalink = $this->sp_build_search_url();
		$this->searchData = $this->sp_searchview_control($count);
	}

	# --------------------------------------------------------------------------------------
	#
	#	sp_searchview_control()
	#	Builds the data structure for the Searchview data object
	#
	# --------------------------------------------------------------------------------------
	function sp_searchview_control($count) {
		global $sfvars;

		$searchType 	= $sfvars['searchtype'];
		$searchInclude 	= $sfvars['searchinclude'];

		# (LIMIT) how many topics per page?
		if (!$count) $count=30;
		$this->searchShow = $count;
		if ($sfvars['searchpage'] == 1) {
			$startlimit = 0;
		} else {
			$startlimit = ((($sfvars['searchpage']-1) * $count));
		}
		# For this page?
		$this->limit = $startlimit.', '.$count;

		# (WHERE) All or specific forum?
		if ($sfvars['forumslug'] == 'all') {
			# create forumIds list and where clause
			$forumIds = sp_user_visible_forums();
			if (empty($forumIds)) return;
			$this->forumWhere = 'forum_id IN ('.implode(',', $forumIds).') ';
		} else {
			# check we can see this forum and create where clause
			if (!sp_get_auth('view_forum', $sfvars['forum_id'])) return;
			$this->forumWhere = 'forum_id='.$sfvars['forumid'];
		}

		if (empty($sfvars['searchvalue'])) return '';
		if ($searchType == 4 || $searchType == 5) {
			$this->searchTermRaw = sp_get_member_item($sfvars['searchvalue'], 'display_name');
		} else {
			$this->searchTermRaw = $sfvars['searchvalue'];
		}
		$this->searchTerm = esc_sql(like_escape($this->sp_construct_search_term($sfvars['searchvalue'], $searchType, $searchInclude)));

		# if search type is 1,2 or 3 (i.e., normal data searches) and we are looking for page 1 then we need to run
		# the query. Note - if posts and titles then we need to run it twice!
		# If we are not loading page 1 however then we can grab the results from the cache.
		# For all other searchtypes - just rin the standard routine
		if ($searchType > 3) {
			$r = $this->sp_searchview_query($searchType, $searchInclude);
			return $r;
		}

		if ($sfvars['searchpage'] == 1 && $sfvars['newsearch']==true) {
			if ($searchInclude == 3) {
				$rPost  = array();
				$rTitle = array();
				$rPost 	= $this->sp_searchview_query($searchType, 1);
				$rTitle = $this->sp_searchview_query($searchType, 2);
				# merge the two
				$r = array_merge($rPost, $rTitle);
				unset($rPost);
				unset($rTitle);
			} else {
				$r = $this->sp_searchview_query($searchType, $searchInclude);
			}
			# Remove dupes and re-sort
			if ($r) {
				$r = array_unique($r);
				rsort($r, SORT_NUMERIC);
				# Now hive off into a transient
				$d = array();
				$d['url'] = $this->searchPermalink;
				$d['page'] = $sfvars['searchpage'];
				$t = array();
				$t[0]=$d;
				$t[1]=$r;

				sp_add_transient(2, $t);
			}
		} else {
			# Get the data from the cache if not page 1 for first time
			$r = sp_get_transient(2, false);
			if($r) {
				$d = $r[0];
				$r = $r[1];
				$d['url']=$this->searchPermalink;
				$d['page'] = $sfvars['searchpage'];
				$t = array();
				$t[0]=$d;
				$t[1]=$r;
				# update the transient with the new url
				sp_add_transient(2, $t);
			}
		}

		# Now work out which part of the $r array to return
		if ($r) {
			$sfvars['searchresults'] = count($r);
			$this->searchCount = $sfvars['searchresults'];

			return array_slice($r, $startlimit, $count);
		}
	}

	function sp_searchview_query($searchType, $searchInclude) {
		global $sfvars;

		$useLimit		= true;
		$useDistinct	= true;
		$userOrderBy	= true;

		# (WHERE) Post content search criteria
		if ($searchType==1 || $searchType==2 || $searchType==3) {
			$useLimit		= false;
			$useDistinct	= false;
			$userOrderBy	= false;

			# Standard forum search
			if ($searchInclude == 1) {
				# Include = 1 - posts
				$WHERE = "MATCH(post_content) AGAINST ('$this->searchTerm' IN BOOLEAN MODE) ";
				$TABLE = SFPOSTS;
			} elseif ($searchInclude == 2) {
				# Include = 2 - titles
				$WHERE = "MATCH(topic_name) AGAINST ('$this->searchTerm' IN BOOLEAN MODE) ";
				$TABLE = SFTOPICS;
			} else {
				# Plugns can set an alternate TABLE and MATCH statement based on the 'Include' parameter
				$TABLE = apply_filters('sph_search_type_table', SFTOPICS, $searchType, $searchInclude);
				$WHERE = apply_filters('sph_search_include_where', '', $this->searchTerm, $searchType, $searchInclude);
			}
		} elseif ($searchType==4) {
			# Member 'posted in'
			$WHERE = "user_id=$this->searchTerm";
			$TABLE = SFPOSTS;
		} elseif ($searchType==5) {
			# Member 'started'
			$WHERE = "user_id=$this->searchTerm AND post_index=1";
			$TABLE = SFPOSTS;
		} else {
			# Plugns can set an alternate TABLE and WHERE clause based on the 'Type' parameter
			$TABLE = apply_filters('sph_search_type_table', SFTOPICS, $searchType, $searchInclude);
			$WHERE = apply_filters('sph_search_type_where', '', $this->searchTerm, $searchType, $searchInclude);
		}

		# check if the WHERE clause is empty - probably comes from a legacy url
		if (empty($WHERE)) {
			sp_notify(1, sp_text('Unable to complete this search request'));
			return;
		}

		# Query
		$sfdb = new spdbComplex;
			$sfdb->table = $TABLE;
			$sfdb->fields = 'topic_id';
			if ($useDistinct) $sfdb->distinct = true;
			$sfdb->found_rows = true;
			$sfdb->where = $WHERE.' AND '.$TABLE.'.'.$this->forumWhere;
			if ($userOrderBy) $sfdb->orderby = 'topic_id DESC';
			if ($useLimit) $sfdb->limits = $this->limit;

			# Plugins can alter the final SQL
			$sfdb = apply_filters('sph_search_query', $sfdb, $this->searchTerm, $searchType, $searchInclude);
		$records = $sfdb->select('col');

		$sfvars['searchresults'] = spdb_select('var', 'SELECT FOUND_ROWS()');
		$this->searchCount = $sfvars['searchresults'];

		return $records;
	}

	function sp_construct_search_term($term, $type, $include) {
        $original = $term;

		# get the search terms(s) in format required
		$term = str_replace('%', ' ', $term);

		if ($type == 1) {
			$searchterm = $term;
		} elseif ($type == 2) {
			$term = str_replace(' ', ' +', $term);
			$searchterm.= '+'.$term;
		} elseif ($type == 3) {
			$searchterm = '"'.$term.'"';
		} elseif ($type == 4 || $type == 5) {
			$searchterm = (int) $term;
		} else {
			# Plugins can alter the search term
			$searchterm = apply_filters('sph_search_term_type', $term, $type, $include);
		}
		$searchterm = apply_filters('sph_search_term', $searchterm, $original, $type, $include);

		return $searchterm;
	}

	# ------------------------------------------------------------------
	# sp_build_search_url()
	#
	# Builds a forum search url with the query vars
	# ------------------------------------------------------------------
	function sp_build_search_url() {
		global $sfvars;

		$forumid = $_GET['forum'];
		return sp_build_qurl('forum='.$forumid, 'value='.$sfvars['searchvalue'], 'type='.$sfvars['searchtype'], 'include='.$sfvars['searchinclude']);
	}
}

?>