<?php
/*
Simple:Press
cache support
$LastChangedDate: 2012-03-26 10:30:25 -0700 (Mon, 26 Mar 2012) $
$Rev: 8234 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

# ==================================================================
#
# 	SITE: This file is loaded at SITE
#	SP Transient Handling Routines
#
# ==================================================================


# ------------------------------------------------------------------
# sp_add_cache_datarow()
#
# Version: 5.0
# caches simple single row selects on main tables
#	$table:			name of the table
#	$result:		the data row
# ------------------------------------------------------------------
function sp_add_cache_datarow($table, $result) {
	if($table==SFGROUPS || $table==SFFORUMS || $table==SFTOPICS || $table==SFPOSTS) {
		global $spCache;
		$spCache[$table][]=$result;
	}
}

# ------------------------------------------------------------------
# sp_get_cache_datarow()
#
# Version: 5.0
# looks for entry in the cache and retruns if found
# 	$table:			name of the table
#	$where:			the where clause of the select
#	$qtype:			'row' or 'var'
#	$varcol:		column to return if a 'var query
# ------------------------------------------------------------------
function sp_get_cache_datarow($table, $where, $qtype, $varcol) {
	if($table==SFGROUPS || $table==SFFORUMS || $table==SFTOPICS || $table==SFPOSTS) {
		global $spCache;
		$result = '';
		if(empty($spCache[$table])) return;
		# check the where clause is a standard equal and nothing complex
		if(substr_count($where, '=') == 1 && strpos($where, ' AND ') === false) {
			# explode the $where clause
			$p = explode('=', $where);
			$column = trim($p[0], "'");
			$column = trim($column, '"');
			$data = trim($p[1], "'");
			$data = trim($data, '"');

			foreach($spCache[$table] as $r) {
				if($r->$column == $data) {
					if($qtype == 'row') {
						$result = $r;
					} else {
						$result = $r->$varcol;
					}
					break;
				}
			}
		}
	}
	return $result;
}

# ------------------------------------------------------------------
# sp_set_transient_type()
#
# Version: 5.0
# Called by other transient functions to set up the data key and life
# NOTE: Add new transient types into the case statement
#	$type:			numeric transient type
#					1: Post content rejected
#					2: Search Topic ID List
#					3: URL to return user to
#					4: Bookmarked forum ID and Page ID
#					5: Reload id - in case of page refresh - no toggle
# ------------------------------------------------------------------
function sp_set_transient_type($type) {
	$thistype = array();
	$key = sp_get_ip();

	switch($type) {
		case 1:
			$thistype['datakey'] = $key.'post';
			$thistype['lifespan'] = 900;
			break;
		case 2:
			$thistype['datakey'] = $key.'search';
			$thistype['lifespan'] = 3600;
			break;
		case 3:
			$thistype['datakey'] = $key.'url';
			$thistype['lifespan'] = 3600;
			break;
		case 4:
			$thistype['datakey'] = $key.'bookmark';
			$thistype['lifespan'] = 3600;
			break;
		case 5:
			$thistype['datakey'] = $key.'reload';
			$thistype['lifespan'] = 900;
			break;
	}
	return $thistype;
}

# ------------------------------------------------------------------
# sp_add_transient()
#
# Version: 5.0
# Adds a transient of type passed storing data passed
#	$type:			see sp_set_transient_type()
#	$data:			the data to be stored
# ------------------------------------------------------------------
function sp_add_transient($type, $data) {
	$thistype = sp_set_transient_type($type);
	set_transient($thistype['datakey'], $data, $thistype['lifespan']);
}

# ------------------------------------------------------------------
# sp_get_transient()
#
# Version: 5.0
# returns data of type passed for current user
#	$type:			see sp_set_transient_type()
#	$kill:			if true - delete transient record
# ------------------------------------------------------------------
function sp_get_transient($type, $kill) {
	$thistype = sp_set_transient_type($type);
	if (false === ($value = get_transient($thistype['datakey']))) {
		 return '';
	} else {
		if ($kill) sp_delete_transient($type);
		return $value;
	}
}

# ------------------------------------------------------------------
# sp_delete_transient()
#
# Version: 5.0
# deletes data of type passed for current user
# NOTE: Transients need to be either removed via this function or
#		left to be removed by their lifespan setting
#	$type:			see sp_set_transient_type()
# ------------------------------------------------------------------
function sp_delete_transient($type) {
	$thistype = sp_set_transient_type($type);
	delete_transient($thistype['datakey']);
}
?>