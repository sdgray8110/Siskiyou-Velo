<?php
/*
Simple:Press
List Topic Class
$LastChangedDate: 2012-05-05 19:28:28 -0700 (Sat, 05 May 2012) $
$Rev: 8496 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

# ==========================================================================================
#
#	Returns simplified but rich data based upon the Topic IDs passed in
#	Intended for simple listings
#
#	Version: 5.0
#
# ==========================================================================================

# --------------------------------------------------------------------------------------
#
#	sp_has_list()
#	sp_loop_list()
#	sp_the_list()
#
#	List (Topic Loop) functions for ListView data objects
#
#	Instantiate spListView	All arguments are optional but 1 of the first two are required
#
#	Pass:	$topicIds:	Pass an array of TOPIC ids to specifically use in the list
#			$count:		Optional count of how many rows to return (will also pad IDs)
#			$group:		Boolean: Group/Order the results into forums (Default true)
#			$forumIds:	Optional array of FORUM ids to filter the topic selection by
#
#	Returns a data object based upon the topic ids
#
#	IMPORTANT NOTES:
#
#	* If NO topic Ids are passed and a count of zero is passed - no data is returned.
#	* If topic Ids are passed with a count higher than the ids count then the object
#	will be padded to include the most recent topics updated as well as the ids passed in
#	* If forum Ids are passed in they will be used to filter the selection of topic Ids
#	to use in the returned data but will NOT verify that any topic Ids also passed in
#	belong within those forums.
#
# --------------------------------------------------------------------------------------

function sp_has_list() {
	global $list, $spListView;
	return $spListView->sp_has_list();
}

function sp_loop_list() {
	global $spListView;
	return $spListView->sp_loop_list();
}

function sp_the_list() {
	global $spListView, $spThisListTopic;
	$spThisListTopic = $spListView->sp_the_list();
}

# --------------------------------------------------------------------------------------


# ==========================================================================================
#
#	Topic List. Topic Listing Class
#
# ==========================================================================================

class spTopicList {
	# Forum View DB query result set
	var $listData = array();

	# Topic single row object
	var $topicData = '';

	# Internal counter
	var $currentTopic = 0;

	# Count of topic records
	var $listCount = 0;

	# Run in class instantiation - populates data
	function __construct($topicIds='', $count=0, $group=true, $forumIds='', $firsPost=0) {
		$this->listData = $this->sp_listview_query($topicIds, $count, $group, $forumIds, $firsPost);
		sp_display_inspector('tlv_spTopicListView', $this->listData);
	}

	# True if there are Topic records
	function sp_has_list() {
		if (!empty($this->listData)) {
			$this->listCount = count($this->listData);
			reset($this->listData);
			return true;
		} else {
			return false;
		}
	}

	# Loop control on Topic records
	function sp_loop_list() {
		if ($this->currentTopic > 0) do_action_ref_array('sph_after_list', array(&$this));
		$this->currentTopic++;
		if ($this->currentTopic <= $this->listCount) {
			do_action_ref_array('sph_before_list', array(&$this));
			return true;
		} else {
			$this->currentTopic = 0;
			$this->listCount = 0;
			unset($this->listData);
			return false;
		}
	}

	# Sets array pointer and returns current Topic data
	function sp_the_list() {
		$this->topicData = current($this->listData);
		sp_display_inspector('tlv_spThisListTopic', $this->topicData);
		next($this->listData);
		return $this->topicData;
	}

	# --------------------------------------------------------------------------------------
	#
	#	sp_listview_query()
	#	Builds the data structure for the Listview data object
	#
	# --------------------------------------------------------------------------------------
	function sp_listview_query($topicIds, $count, $group, $forumIds, $firstPost) {
		global $spThisUser, $sfglobals;

		# If no topic ids and no count then nothjing to do - return empty
		if (empty($topicIds) && $count == 0) return;

		# Do we have enough topic ids to satisfy count?
		if (empty($topicIds) || ($count != 0 && count($topicIds) < $count)) $topicIds = $this->sp_listview_populate_topicids($topicIds, $forumIds, $count);

		# Do we havwe too many topic ids?
		if ($topicIds && ($count != 0 && count($topicIds) > $count)) $topicIds = array_slice($topicIds, 0, $count, true);

		if (empty($topicIds)) return;

		# Construct the main WHERE clause and then main query
		$where = SFTOPICS.'.topic_id IN ('.implode(',', $topicIds).')' ;

		if ($group) {
			$orderby = 'group_seq, forum_seq, '.SFTOPICS.'.post_id DESC';
		} else {
			$orderby = SFTOPICS.'.post_id DESC';
		}

		$spdb = new spdbComplex;
			$spdb->table		= SFTOPICS;
			$spdb->fields		= SFTOPICS.'.forum_id, forum_name, forum_slug, '.SFTOPICS.'.topic_id, topic_name, topic_slug, topic_icon, topic_icon_new, '.SFTOPICS.'.post_count,
								'.SFTOPICS.'.post_id, post_status, post_index, '.spdb_zone_datetime('post_date').',
								guest_name, '.SFPOSTS.'.user_id, post_content, display_name';
			$spdb->join			= array(SFFORUMS.' ON '.SFFORUMS.'.forum_id = '.SFTOPICS.'.forum_id',
										SFGROUPS.' ON '.SFGROUPS.'.group_id = '.SFFORUMS.'.group_id',
										SFPOSTS.' ON '.SFPOSTS.'.post_id = '.SFTOPICS.'.post_id');
			$spdb->left_join	= array(SFMEMBERS.' ON '.SFMEMBERS.'.user_id = '.SFPOSTS.'.user_id');
			$spdb->where		= $where;
			$spdb->orderby		= $orderby;
		$spdb = apply_filters('sph_topic_list_query', $spdb);
		$records = $spdb->select();

		# add filters where required plus extra data
		if ($records) {
			# Now we can grab the supplementary post records where there may be new posts...
			if ($spThisUser->member) $new = $this->sp_listview_populate_newposts($topicIds);

			# Some values we need
			# How many topics to a page?
			$ppaged = $sfglobals['display']['posts']['perpage'];
			if (empty($ppaged) || $ppaged == 0) $ppaged = 20;
			# establish topic sort order
			$order = 'ASC'; # default
			if ($sfglobals['display']['posts']['sortdesc']) $order = 'DESC'; # global override
			# And the new array
			$list = array();
			$listPos = 1;

			foreach ($records as $r) {
				$show = true;
				# can the user see this forum?
				if (!sp_get_auth('view_forum', $r->forum_id) && !sp_get_auth('view_forum_topic_lists', $r->forum_id)) $show = false;
				# if in moderattion can this user approve posts?
				if ($r->post_status != 0 && !sp_get_auth('moderate_posts', $r->forum_id)) $show = false;

				if ($show) {
					$t = $r->topic_id;
					$list[$t]->forum_id 		= $r->forum_id;
					$list[$t]->forum_name 		= sp_filter_title_display($r->forum_name);
					$list[$t]->forum_permalink	= sp_build_url($r->forum_slug, '', 1, 0);
					$list[$t]->topic_id 		= $r->topic_id;
					$list[$t]->topic_name 		= sp_filter_title_display($r->topic_name);
					$list[$t]->topic_permalink	= sp_build_url($r->forum_slug, $r->topic_slug, 1, 0);
					$list[$t]->topic_icon		= sanitize_file_name($r->topic_icon);
					$list[$t]->topic_icon_new	= sanitize_file_name($r->topic_icon_new);
					$list[$t]->post_count 		= $r->post_count;
					$list[$t]->post_id 			= $r->post_id;
					$list[$t]->post_status 		= $r->post_status;
					$list[$t]->post_date 		= $r->post_date;
					$list[$t]->user_id	 		= $r->user_id;
					$list[$t]->guest_name	 	= sp_filter_name_display($r->guest_name);
					$list[$t]->display_name 	= sp_filter_name_display($r->display_name);
					if (sp_get_auth('view_forum', $r->forum_id) && (sp_get_auth('view_admin_posts', $r->forum_id) || !sp_is_forum_admin($r->user_id))) {
						$list[$t]->post_tip = ($r->post_status) ? sp_text('Post awaiting moderation') : sp_filter_tooltip_display($r->post_content, $r->post_status);
					} else {
						$list[$t]->post_tip = '';
					}
					$list[$t]->list_position	= $listPos;

					if (empty($r->display_name)) $list[$t]->display_name = $list[$t]->guest_name;

					# Lastly determine the page for the post permalink
					if ($order == 'ASC') {
						$page = $r->post_index / $ppaged;
						if (!is_int($page)) $page = intval($page+1);
					} else {
						$page = $r->post_count - $r->post_index;
						$page = $page / $ppaged;
						$page = intval($page+1);
					}
					$r->page = $page;
					$list[$t]->post_permalink = sp_build_url($r->forum_slug, $r->topic_slug, $r->page, $r->post_id, $r->post_index);

					# add in any new post details if they exist
					if (!empty($new) && array_key_exists($t, $new)) {
						$list[$t]->new_post_count 			= $new[$t]->new_post_count;
						$list[$t]->new_post_post_id			= $new[$t]->new_post_post_id;
						$list[$t]->new_post_post_index		= $new[$t]->new_post_post_index;
						$list[$t]->new_post_post_date		= $new[$t]->new_post_post_date;
						$list[$t]->new_post_user_id			= $new[$t]->new_post_user_id;
						$list[$t]->new_post_display_name	= $new[$t]->new_post_display_name;
						$list[$t]->new_post_guest_name		= $new[$t]->new_post_guest_name;
						$list[$t]->new_post_permalink 		= sp_build_url($r->forum_slug, $r->topic_slug, 0, $new[$t]->new_post_post_id, $new[$t]->new_post_post_index);
						if (empty($new[$t]->new_post_display_name)) $list[$t]->new_post_display_name = $new[$t]->new_post_guest_name;
					}
                    
                    # grab first post info if desired
                    if ($firstPost) {
                        $first = spdb_table(SFPOSTS, "topic_id=$r->topic_id AND post_index=1", 'row');
    					$list[$t]->first_post_permalink = sp_build_url($r->forum_slug, $r->topic_slug, 0, $first->post_id, 1);
    					$list[$t]->first_post_date 		= $first->post_date;
    					$list[$t]->first_user_id	 	= $first->user_id;
    					$list[$t]->first_guest_name	 	= sp_filter_name_display($first->guest_name);
    					$list[$t]->first_display_name 	= sp_filter_name_display($first->display_name);
    					if (sp_get_auth('view_forum', $r->forum_id) && (sp_get_auth('view_admin_posts', $r->forum_id) || !sp_is_forum_admin($first->user_id))) {
    						$list[$t]->first_post_tip = ($first->post_status) ? sp_text('Post awaiting moderation') : sp_filter_tooltip_display($first->post_content, $first->post_status);
    					} else {
    						$list[$t]->first_post_tip = '';
    					}
                    }
                    
					$list[$t] = apply_filters('sph_topic_list_record', $list[$t], $r);

					$listPos++;
				}
			}
			unset($records);
			unset($new);
		}
		return $list;
	}

	# --------------------------------------------------------------------------------------
	#
	#	sp_listview_populate_newposts()
	#	Adds first new posts data into the result
	#
	# --------------------------------------------------------------------------------------
	function sp_listview_populate_newposts($topicIds) {
		global $spThisUser;

		$newList = array();

		# First filter topics by those in the users new post list
		$newTopicIds = array();
		foreach ($topicIds as $topic) {
			if (sp_is_in_users_newposts($topic)) $newTopicIds[] = $topic;
		}
		if ($newTopicIds) {
			# construct the query - need to add in sfwaiting for admins
			$where = SFPOSTS.'.topic_id IN ('.implode(',', $newTopicIds).') AND (post_date > "'.spdb_zone_mysql_checkdate($spThisUser->lastvisit).'")';
			if ($spThisUser->admin || $spThisUser->moderator) {
				$wPosts = spdb_select('col', 'SELECT post_id FROM '.SFWAITING);
				if ($wPosts) $where.= ' OR ('.SFPOSTS.'.post_id IN ('.implode(",", $wPosts).'))';
			}

			$spdb = new spdbComplex;
				$spdb->table		= SFPOSTS;
				$spdb->fields		= SFPOSTS.'.topic_id, '.SFPOSTS.'.post_id, post_index, '.spdb_zone_datetime('post_date').',
									guest_name, '.SFPOSTS.'.user_id, display_name, post_count-post_index+1 AS new_post_count';
				$spdb->left_join	= array(SFMEMBERS.' ON '.SFMEMBERS.'.user_id = '.SFPOSTS.'.user_id');
				$spdb->join			= array(SFTOPICS.' ON '.SFPOSTS.'.topic_id = '.SFTOPICS.'.topic_id');
				$spdb->where		= $where;
				$spdb->orderby		= 'topic_id, post_id';
			$spdb = apply_filters('sph_listview_newposts_query', $spdb);
			$postrecords = $spdb->select();

			if ($postrecords) {
				$cTopic = 0;
				foreach ($postrecords as $p) {
					if ($p->topic_id != $cTopic) {
						$cTopic = $p->topic_id;
						$newList[$cTopic]->topic_id					= $cTopic;
						$newList[$cTopic]->new_post_count 			= $p->new_post_count;
						$newList[$cTopic]->new_post_post_id			= $p->post_id;
						$newList[$cTopic]->new_post_post_index		= $p->post_index;
						$newList[$cTopic]->new_post_post_date		= $p->post_date;
						$newList[$cTopic]->new_post_user_id			= $p->user_id;
						$newList[$cTopic]->new_post_display_name	= sp_filter_name_display($p->display_name);
						$newList[$cTopic]->new_post_guest_name		= sp_filter_name_display($p->guest_name);
					}
				}
			}
		}
		return $newList;
	}

	# --------------------------------------------------------------------------------------
	#
	#	sp_listview_populate_topicids()
	#	Populates the topic id list to satisfy required count
	#
	# --------------------------------------------------------------------------------------
	function sp_listview_populate_topicids($topicIds, $forumIds, $count) {
		global $sfglobals, $spThisUser;

		if (empty($topicIds)) $topicIds=array();
		$w = array();
		$needJoin -= false;

		# First check if being constrained bt passed in forum ids
		if (empty($forumIds)) {
			# No ids passed so first need to grab allowed forums and also for moderators whether they can moderate posts
			# So first grab the auth index numnbers fromn map
			foreach ($sfglobals['auths_map'] as $auth=>$idx) {
				if ($auth == 'view_forum' || $auth == 'view_forum_topic_lists') $view = $idx;
				if ($auth == 'moderate_posts') $mod = $idx;
			}

			# Now build an array of where clauses
			if (empty($spThisUser->auths)) return $topicIds;
			foreach ($spThisUser->auths as $id=>$auths) {
				if ($id != 'global') {
					if ($auths[$view] == 1) {
						$t = SFTOPICS.".forum_id=$id";
						if ($auths[$mod] == 0) {
							$t.= ' AND post_status=0';
							$needJoin = true;
						}
						$w[] = $t;
					}
				}
			}
		} else {
			# Yes - forum ids were passed to be used as a filter
			foreach ($forumIds as $f) {
				if (sp_get_auth('view_forum', $f)) {
					$t = SFTOPICS.".forum_id=$f";
					if (sp_get_auth('moderate_posts', $f)==false) {
						$t.= ' AND post_status=0';
						$needJoin = true;
					}
					$w[] = $t;
				}
			}
		}

		# Next construct the WHERE clause
		$where = '';
		for ($x=0; $x<count($w); $x++) {
			$where.= "($w[$x])";
			if ($x < count($w)-1) $where.= ' OR ';
		}

		# finally construct query and get base data
		$spdb = new spdbComplex;
			$spdb->table	= SFTOPICS;
			$spdb->distinct	= true;
			if ($needJoin) {
				$spdb->join	= array(SFPOSTS.' ON '.SFTOPICS.'.post_id = '.SFPOSTS.'.post_id');
			}
			$spdb->fields	= SFTOPICS.'.topic_id';
			$spdb->where	= $where;
			$spdb->orderby	= SFTOPICS.'.post_id DESC';
			$spdb->limits	= $count;
		$r = $spdb->select('col', ARRAY_N);

		if ($r) {
			foreach ($r as $t) {
				if (!in_array($t, $topicIds)) $topicIds[] = $t;
			}
		}

		# Only process if there are topics defined
		if (empty($topicIds)) return '';

		unset($r);
		unset($w);

		# Now make sure we just have the required number
		$topicIds = array_slice($topicIds, 0, $count, true);

		return $topicIds;
	}
}
?>