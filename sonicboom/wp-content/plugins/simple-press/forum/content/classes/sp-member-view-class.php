<?php
/*
Simple:Press
Members List Class
$LastChangedDate: 2012-04-19 22:43:21 -0700 (Thu, 19 Apr 2012) $
$Rev: 8395 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

# --------------------------------------------------------------------------------------
#	Version: 5.0
#
#	sp_has_member_groups()
#	sp_loop_member_groups()
#	sp_the_member_group()
#
#	Members List Group Loop functions from the MembersView template
#
# --------------------------------------------------------------------------------------

function sp_has_member_groups($groupBy='usergroup', $orderBy='id', $sortBy='asc', $number=15, $limitUG=false, $ugids='') {
	global $spMembersList;
	$spMembersList = new spMembersList($groupBy, $orderBy, $sortBy, $number, $limitUG, $ugids);
	return $spMembersList->sp_has_member_groups();
}

function sp_loop_member_groups() {
	global $spMembersList;
	return $spMembersList->sp_loop_member_groups();
}

function sp_the_member_group() {
	global $spMembersList, $spThisMemberGroup;
	$spThisMemberGroup = $spMembersList->sp_the_member_group();
}

# --------------------------------------------------------------------------------------
#
#	sp_has_members()
#	sp_loop_members()
#	sp_the_member()
#
#	Members List Member Loop functions from the MembersView template
#
# --------------------------------------------------------------------------------------

function sp_has_members() {
	global $spMembersList;
	return $spMembersList->sp_has_members();
}

function sp_loop_members() {
	global $spMembersList;
	return $spMembersList->sp_loop_members();
}

function sp_the_member() {
	global $spMembersList, $spThisMember;
	$spThisMember = $spMembersList->sp_the_member();
}

# ==========================================================================================
#
#	Members Listing Class
#
# ==========================================================================================

class spMembersList {
	# Status: 'data', 'no access', 'no data'
	var $membersListStatus = 'data';

	# True while the member loop is being rendered
	var $inMemberGroupsLoop = false;
	var $inMembersLoop = false;

	# Members List DB query result set
	var $pageData = array();
	var $pageMemberData = array();

	# Member single row object
	var $memberGroupData = '';
	var $memberData = '';

	# Internal counter
	var $currentMemberGroup = 0;
	var $currentMember = 0;

	# Count of member records
	var $memberGroupCount = 0;
	var $memberCount = 0;

	# Count of all member records
	var $totalMemberCount = 0;

	# The groupby clause - can be 'usergroup' or 'user'
	var $membersGroupBy = array();

	# The orderby clause - can be 'id' or 'alpha'
	var $membersOrderBy = '';

	# The sorting clause - can be 'asc' or 'desc'
	var $membersSortBy = '';

	# The limit clause - number of members to show on single page
	var $membersNumber = 15;

	# only valid if groupby='uergroup'
	# allows limiting usergroups displayed to current user memberships
	var $membersLimitUG = false;

	# only valid if groupby='uergroup'
	# allows limiting usergroups displayed to set of usergroup IDs
	var $membersWhere = '';

	# Run in class instantiation - populates data
	function __construct($groupBy='usergroup', $orderBy='id', $sortBy='asc', $number=15, $limitUG=false, $ugids='') {
		$this->membersGroupBy = sp_esc_str($groupBy);
		$this->membersOrderBy = sp_esc_str($orderBy);
		$this->membersSortBy = sp_esc_str($sortBy);
		$this->membersNumber = (int) $number;
		$this->membersLimitUG = ($groupBy == 'usergroup') ? sp_esc_str($limitUG) : false;
		$this->membersWhere = ($groupBy == 'usergroup' && !empty($ugids)) ? sp_esc_str($ugids) : '';

		$data = $this->sp_memberslist_query($this->membersGroupBy, $this->membersOrderBy, $this->membersSortBy, $this->membersNumber, $this->membersLimitUG, $this->membersWhere);
		$this->pageData = $data->records;
		$this->memberGroupCount = count($this->pageData);
		$this->totalMemberCount = $data->count;
		sp_display_inspector('mv_spMembersList', $this->pageData);
	}

	# Populate the members list result set
	function sp_has_member_groups() {
		# Check for no access to members list or no data
		if ($this->membersListStatus != 'data') return false;

		reset($this->pageData);

		if ($this->memberGroupCount) {
			$this->inMemberGroupsLoop = true;
			return true;
		} else {
			return false;
		}
	}

	# Loop control on Members List records
	function sp_loop_member_groups() {
		if ($this->currentMemberGroup > 0) do_action_ref_array('sph_after_memeber_group', array(&$this));
		$this->currentMemberGroup++;
		if ($this->currentMemberGroup <= $this->memberGroupCount) {
			do_action_ref_array('sph_before_member_group', array(&$this));
			return true;
		} else {
			$this->inMemberGroupsLoop = false;
			return false;
		}
	}

	# Sets array pointer and returns current Member data
	function sp_the_member_group() {
		$this->memberGroupData = current($this->pageData);
		sp_display_inspector('mv_spThisMemberGroup', $this->memberGroupData);
		next($this->pageData);
		return $this->memberGroupData;
	}

	# True if there are Member records
	function sp_has_members() {
		if ($this->memberGroupData->members) {
			$this->pageMemberData = $this->memberGroupData->members;
			$this->memberCount = count($this->pageMemberData);
			$this->inMembersLoop = true;
			return true;
		} else {
			return false;
		}
	}

	# Loop control on Member records
	function sp_loop_members() {
		if ($this->currentMember > 0) do_action_ref_array('sph_after_member', array(&$this));
		$this->currentMember++;
		if ($this->currentMember <= $this->memberCount) {
			do_action_ref_array('sph_before_member', array(&$this));
			return true;
		} else {
			$this->inMembersLoop = false;
			$this->currentMember = 0;
			$this->memberCount = 0;
			unset($this->pageMemberData);
			return false;
		}
	}

	# Sets array pointer and returns current Member data
	function sp_the_member() {
		$this->memberData = current($this->pageMemberData);
		sp_display_inspector('mv_spThisMember', $this->memberData);
		next($this->pageMemberData);
		return $this->memberData;
	}

	#	Builds the data structure for the Members List template
	function sp_memberslist_query($groupBy, $orderBy, $sortBy, $number, $limitUG, $ugids) {
		global $spThisUser, $sfvars;

		$page = (isset($_GET['page'])) ? sp_esc_int($_GET['page']) : $sfvars['page'];
		$search = (isset($_POST['msearch'])) ? sp_esc_str($_POST['msearch']) : '';
		$search = (isset($_GET['search'])) ? sp_esc_str($_GET['search']) : $search;

		if ($groupBy == 'usergroup' && !empty($ugids)) $ugids = explode(',', sp_esc_str($ugids));

		$data = '';
		$data->records = '';
		$data->count = 0;
		if ($spThisUser->admin || sp_get_auth('view_members_list')) {
		    # default to 'no data'
			$this->membersListStatus = 'no data';

			# are we limiting member lists to user group memberships?
			$where = 'posts > -2';
			if ($groupBy == 'usergroup' && !$spThisUser->admin) {
   				# if limiting to memberships, get usergroups current user has membership in
			    if ($limitUG) {
    				$ugs = sp_get_user_memberships($spThisUser->ID);
                } else {
                    $ugs = spdb_table(SFUSERGROUPS, '', '', '', '', ARRAY_A);
                }
   				if (empty($ugs)) return $data;

				# now build the where clause
				$ug_ids = '';
				foreach ($ugs as $ug) {
					if (empty($ugids) || in_array($ug['usergroup_id'], $ugids)) $ug_ids[] = $ug['usergroup_id'];
				}
				if (empty($ug_ids)) return $data;

				# create where clause based on user memberships
				$where.= ' AND ('.SFMEMBERSHIPS.'.usergroup_id IN ('.implode(',', $ug_ids).') OR '.SFMEMBERSHIPS.'.usergroup_id IS NULL)';
			}

			if ($search != '') $where.= ' AND '.SFMEMBERS.'.display_name LIKE "'.$search.'%"';

			# how many members per page?
			$startlimit = 0;
			if ($page != 1) $startlimit = ((($page - 1) * $number));
			$limit = $startlimit.', '.$number;

			$order = '';
			if ($groupBy == 'usergroup' && $orderBy == 'id') $order.= "usergroup_id $sortBy, ".SFMEMBERS.".display_name $sortBy";
			if ($groupBy == 'usergroup' && $orderBy == 'alpha') $order.= "usergroup_name $sortBy, ".SFMEMBERS.".display_name $sortBy";
			if ($groupBy == 'user' && $orderBy == 'id') $order.= SFMEMBERS.".user_id $sortBy";
			if ($groupBy == 'user' && $orderBy == 'alpha') $order.= SFMEMBERS.".display_name $sortBy";

			$join =	SFUSERS.' ON '.SFMEMBERS.'.user_id='.SFUSERS.'.ID ';
			if ($groupBy == 'usergroup') {
				$q = 'IF('.SFMEMBERS.'.admin=1, "0", IF('.SFMEMBERSHIPS.'.usergroup_id=NULL OR '.SFMEMBERSHIPS.'.usergroup_id=0, "99999999", '.SFMEMBERSHIPS.'.usergroup_id)) as usergroup_id,
					  IF('.SFMEMBERS.'.admin=1, "'.sp_text('Admins').'", IF('.SFMEMBERSHIPS.'.usergroup_id=NULL OR '.SFMEMBERSHIPS.'.usergroup_id=0, "'.sp_text('Unknown').'", '.SFUSERGROUPS.'.usergroup_name)) as usergroup_name,
					  IF('.SFMEMBERS.'.admin=1, "'.sp_text('Forum Administrators').'", IF('.SFMEMBERSHIPS.'.usergroup_id=NULL OR '.SFMEMBERSHIPS.'.usergroup_id=0, "'.sp_text('Unknown Memberships').'", '.SFUSERGROUPS.'.usergroup_desc)) as usergroup_desc,
					  '.SFMEMBERS.'.user_id, '.SFMEMBERS.'.display_name, admin, avatar, posts, lastvisit, user_registered, user_url, user_options';
				$join.= 'LEFT JOIN '.SFMEMBERSHIPS.' ON '.SFMEMBERSHIPS.'.user_id='.SFMEMBERS.'.user_id
						 LEFT JOIN '.SFUSERGROUPS.' ON '.SFUSERGROUPS.'.usergroup_id='.SFMEMBERSHIPS.'.usergroup_id';
			} else {
				$q = SFMEMBERS.'.user_id, '.SFMEMBERS.'.display_name, admin, avatar, posts, lastvisit, user_registered, user_url, user_options';
			}

			# retrieve members list records
			$spdb = new spdbComplex;
				$spdb->table		= SFMEMBERS;
				$spdb->fields		= $q;
				$spdb->found_rows	= true;
				$spdb->distinct		= true;
				$spdb->left_join	= $join;
				$spdb->where		= $where;
				$spdb->orderby		= $order;
				$spdb->limits		= $limit;
			$spdb = apply_filters('sph_members_list_query', $spdb);
			$records = $spdb->select();

			if ($records) {
				$m = array();
				$ugidx = -1;
				$midx = 0;

				$data->count = spdb_select('var', 'SELECT FOUND_ROWS()');
				foreach ($records as $r) {
					# for user list only, set up dummy usergroup
					if ($groupBy != 'usergroup') $ugidx = 0;

					# we have data
					$this->membersListStatus = 'data';

					# set up the usergroup outer data and member inner data
					if ($groupBy == 'usergroup' && ($ugidx == -1 || $m[$ugidx]->usergroup_id != $r->usergroup_id)) {
						$ugidx++;
						$midx = 0;
						$m[$ugidx]->usergroup_id	= $r->usergroup_id;
                        $name = (!empty($r->usergroup_name)) ? sp_filter_title_display($r->usergroup_name) : sp_text('No Memberships');
                        $desc = (!empty($r->usergroup_desc)) ? sp_filter_title_display($r->usergroup_desc) : sp_text('Members without any usergroup memberships');
						$m[$ugidx]->usergroup_name	= $name;
						$m[$ugidx]->usergroup_desc	= $desc;

						$m[$ugidx] = apply_filters('sph_members_list_records', $m[$ugidx], $r);
					}
					if (isset($r->user_id)) {
						$m[$ugidx]->members[$midx]->user_id			= $r->user_id;
						$m[$ugidx]->members[$midx]->display_name	= sp_filter_title_display($r->display_name);
						$m[$ugidx]->members[$midx]->posts			= $r->posts;
						$m[$ugidx]->members[$midx]->user_url		= $r->user_url;
						$m[$ugidx]->members[$midx]->admin			= $r->admin;
						$m[$ugidx]->members[$midx]->avatar			= unserialize($r->avatar);
						$m[$ugidx]->members[$midx]->user_options	= unserialize($r->user_options);
						$m[$ugidx]->members[$midx]->lastvisit		= sp_apply_timezone(sp_member_lastvisit_to_server_tz($r->lastvisit, $m[$ugidx]->members[$midx]->user_options), 'mysql');
						$m[$ugidx]->members[$midx]->user_registered	= sp_member_registration_to_server_tz($r->user_registered);

						$m[$ugidx]->members[$midx] = apply_filters('sph_members_list_records', $m[$ugidx]->members[$midx], $r);
						$midx++;
					}
				}
				$data->records = $m;
			}
		} else {
			$this->membersListStatus = 'no access';
		}

		return $data;
	}
}

?>