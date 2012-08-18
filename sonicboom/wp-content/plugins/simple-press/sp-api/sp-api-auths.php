<?php
/*
Simple:Press
Auths Model forum rendering helper functions
$LastChangedDate: 2012-03-26 10:30:25 -0700 (Mon, 26 Mar 2012) $
$Rev: 8234 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

# ==================================================================
#
# 	CORE: This file is loaded at CORE
#	SP Authorisation and Permission Routines
#
# ==================================================================
#	Version: 5.0
function sp_get_auth($check, $id = 'global', $userid = '') {
    global $sfglobals, $spThisUser;

	if (empty($id)) $id = 'global';

	# check if for current user or specified user
	if (empty($userid)) {
		# retrieve the current user auth
        if (!isset($spThisUser->auths[$id])) {
            $auth = 0;
        } else {
            $auth = $spThisUser->auths[$id][$sfglobals['auths_map'][$check]];
        }
		# is this a guest and auth should be ignored?
		if (empty($spThisUser->ID) && $sfglobals['auths'][$sfglobals['auths_map'][$check]]->ignored) $auth = 0;
	} else {
		# see if we have a user object passed in with auths defined
		if (is_object($userid) && is_array($userid->auths)) {
			$user_auths = $userid->auths;
		} else  {
			#retrieve auth for specified user
			$user_auths = sp_get_member_item($userid, 'auths');
			if (empty($user_auths)) $user_auths = sp_rebuild_user_auths($userid);
		}
		$auth = $user_auths[$id][$sfglobals['auths_map'][$check]];
	}

	return ((int) $auth == 1);
}

#	Version: 5.0
function sp_reset_auths($userid = '') {
	# reset all the members auths
	$where = '';
	if (!empty($userid)) $where = ' WHERE user_id='.$userid;

	spdb_query('UPDATE '.SFMEMBERS." SET auths=''".$where);

	# reset guest auths if global update
	if (empty($userid)) sp_update_option('sf_guest_auths', '');
}

#	Version: 5.0
function sp_rebuild_user_auths($userid) {
	global $sfglobals;

	$user_auths = array();

	if (sp_is_forum_admin($userid)) {
		# forum admins get full auths
		$forums = spdb_table(SFFORUMS);
		if ($forums) {
			foreach ($forums as $forum) {
				foreach ($sfglobals['auths_map'] as $auth) {
					$user_auths[$forum->forum_id][$auth] = 1;
					$user_auths['global'][$auth] = 1;
				}
			}
		}
	} else {
		$memberships = sp_get_user_memberships($userid);
		if (empty($memberships)) {
			$value = sp_get_sfmeta('default usergroup', 'sfguests');
			$memberships[0]['usergroup_id'] = $value[0]['meta_value'];
		}

		# no memberships means no permissions
		if (empty($memberships)) return;

		# get the roles
		$roles_data = spdb_table(SFROLES, 0);
		foreach ($roles_data as $role) {
			$roles[$role->role_id] = unserialize($role->role_auths);
		}

		# now build auths for user
		foreach ($memberships as $membership) {
			# get the permissions for the membership
			$permissions = spdb_table(SFPERMISSIONS, 'usergroup_id='.$membership['usergroup_id']);
			if ($permissions) {
                $user_auths['global'] = array();
				foreach ($permissions as $permission) {
					if (!isset($user_auths[$permission->forum_id])) {
                        $user_auths[$permission->forum_id] = $roles[$permission->permission_role];
					} else {
                        foreach (array_keys($roles[$permission->permission_role]) as $auth_id) {
                            $user_auths[$permission->forum_id][$auth_id] |= $roles[$permission->permission_role][$auth_id];
					   }
					}
					foreach ($roles[$permission->permission_role] as $auth_id => $auth) {
                        if (empty($user_auths['global'][$auth_id])) {
                            $user_auths['global'][$auth_id] = $auth;
                        } else {
                            $user_auths['global'][$auth_id] |= $auth;
                        }
					}
				}
			}
		}
	}

	# now save the user auths
	if (!empty($user_auths)) {
		if (!empty($userid)) {
			sp_update_member_item($userid, 'auths', $user_auths);
		} else {
			sp_update_option('sf_guest_auths', $user_auths);
		}
	}

	return $user_auths;
}

#	Version: 5.0
function sp_is_forum_admin($userid) {
	$is_admin = 0;
	if ($userid) {
		if (is_multisite() && is_super_admin($userid)) {
			$is_admin = 1;
		} else {
			$is_admin = sp_get_member_item($userid, 'admin');
		}
	}
	return $is_admin;
}

#	Version: 5.0
function sp_is_forum_moderator($forum_id) {
	global $spThisUser, $sfglobals;

	if ($forum_id == '') return spdb_table(SFMEMBERS, "moderator=1 AND user_id=$spThisUser->ID", 'row');

	# get all the moderator groups for the specified forum - return false if no moderator groups
	$modgroups = spdb_table(SFUSERGROUPS, 'usergroup_is_moderator=1');
	if (empty($modgroups)) return 0;

	# if user is in multiple usergroups, cycle through all of them
	foreach ($spThisUser->memberships as $ugid) {
		# check each usergroup that user is a member of to see if the usergroup is a moderator group
		foreach ($modgroups as $modgroup) {
			# check if modgroup has permission for forum
			# if the user is in a moderator group and the group has forum permission return true
			if (empty($sfglobals['permissions'])) return false;
			foreach ($sfglobals['permissions'] as $perm) {
				if ($ugid['usergroup_id'] == $modgroup->usergroup_id && $perm->forum_id == $forum_id && $perm->usergroup_id == $ugid['usergroup_id']) {
					return true;
				}
			}
		}
	}

	# no matches, return false
	return false;
}

# returns false if current user can view multiple forums
# returns forum id if there is only one forum user can see
#	Version: 5.0
function sp_single_forum_user() {
	global $spThisUser, $sfglobals;
	$fid='';
	$cnt = 0;
	$auth = $sfglobals['auths_map']['view_forum'];
	if($spThisUser->auths) {
		foreach($spThisUser->auths as $key=>$set) {
			if(is_numeric($key)) {
				if($set[$auth]) {
					$fid = $key;
					$cnt++;
				}
			}
		}
	}
	If($cnt == 1) {
		return $fid;
	} else {
		return false;
	}
}

# ------------------------------------------------------------------
# sp_add_auth()
#
# Version: 5.0
# Allows plugins to create new auth
# new auth_id is available in $sfvars['insertid'] after success
#
# Version: 5.0
# Returns true if successful
# Returns false if failed and displays error if sql invalid
#
#	name:		name of new auth - meet title reqs
#	desc:		desc of new auth - no html and meet title reqs
#	active:		is the auth active
#	ignored:	is the auth ignored for guests
#	enabling:	in addition to the auth, is enabling of the feature reqd
# ------------------------------------------------------------------
function sp_add_auth($name, $desc, $active=1, $ignored=0, $enabling=0) {
	global $sfvars;

	$success = false;

    # make sure the auth doesnt already exist before we create it
	$auth = spdb_table(SFAUTHS, 'auth_name="'.$name.'"', 'auth_id');
	if (empty($auth)) {
		$name = sp_filter_title_save($name);
		$desc = sp_filter_title_save($desc);
		$sql = 'INSERT INTO '.SFAUTHS." (auth_name, auth_desc, active, ignored, enabling) VALUES ('$name', '$desc', $active, $ignored, $enabling)";
		$success = spdb_query($sql);

		# if successful, lets add it to the roles to keep things in sync
		if ($success) {
			$auth_id = $sfvars['insertid'];
			$roles = spdb_table(SFROLES);
			foreach ($roles as $role) {
				$actions = unserialize($role->role_auths);
				$actions[$auth_id] = 0;
				spdb_query('UPDATE '.SFROLES." SET role_auths='".serialize($actions)."' WHERE role_id=$role->role_id");
			}

			# reset auths if new auth added successfully
			sp_reset_auths();
		}
	}
	return $success;
}

# ------------------------------------------------------------------
# sp_delete_auth()
#
# Version: 5.0
# Allows plugins to delete an existing auth
#
# Returns true if successful
# Returns false if failed and displays error if sql invalid
#
#	$id_or_name:	id or name of auth to delete
# ------------------------------------------------------------------
function sp_delete_auth($id_or_name) {
	# if its not id, lets get the id for easy removal of auth from roles
	if (!is_numeric($id_or_name)) $id_or_name = spdb_table(SFAUTHS, 'auth_name="'.$id_or_name.'"', 'auth_id');

    # now lets delete the auth
   	$success = spdb_query('DELETE FROM '.SFAUTHS." WHERE auth_id=$id_or_name");

	# if successful, need to remove that auth from the roles
	if ($success) {
		$roles = spdb_table(SFROLES);
		foreach ($roles as $role) {
			$actions = unserialize($role->role_auths);
			unset($actions[$id_or_name]);
			spdb_query('UPDATE '.SFROLES." SET role_auths='".serialize($actions)."' WHERE role_id=$role->role_id");
		}

		# reset auths if auth was deleted
		sp_reset_auths();
	}
	return $success;
}

# ------------------------------------------------------------------
# sp_activate_auth()
#
# Version: 5.0
# Allows plugins to activate an auth that has already been created
# but may have been deactivated because the plugin was deactivate
#
# Returns true if successful
# Returns false if failed and displays error if sql invalid
#
#	name:		name of auth to activate
# ------------------------------------------------------------------
function sp_activate_auth($name) {
	$success = spdb_query('UPDATE '.SFAUTHS." SET active=1 WHERE auth_name='$name'");
	if ($success) sp_reset_auths();

	return $success;
}

# ------------------------------------------------------------------
# sp_deactivate_auth()
#
# Version: 5.0
# Allows plugins to deactivate an auth that has already been created
# and activated
#
# Returns true if successful
# Returns false if failed and displays error if sql invalid
#
#	name:		name of auth to deactivate
# ------------------------------------------------------------------
function sp_deactivate_auth($name) {
	$success = spdb_query('UPDATE '.SFAUTHS." SET active=0 WHERE auth_name='$name'");
	if ($success) sp_reset_auths();

	return $success;
}

# Version: 5.0
function sp_current_user_can($cap) {
	global $spThisUser;

    # is is multi site admin?
    $multisite_admin = is_multisite() && is_super_admin();

	# if there are no SPF admins defined, revert to allowing all WP admins so forum admin isn't locked out
	$allow_wp_admins = (sp_get_admins() == '' && get_user_meta($spThisUser->ID, SF_PREFIX.'user_level', true) == 10) ? true : false;

	if (current_user_can($cap) || $allow_wp_admins || $multisite_admin)
		return true;
	else
		return false;
}

# Version: 5.0
function sp_get_admins() {
	$administrators = array();

	# get all the administrators
	$admins = spdb_table(SFMEMBERS, 'admin=1');
	if (empty($admins)) return '';

	# cycle through all admins
	$count = 0;
	foreach ($admins as $admin) {
		$administrators[$count]['id'] = $admin->user_id;	 # add user to admin list
		$administrators[$count]['display_name'] = $admin->display_name;  # get display name for admin
		$count++;
	}
	return $administrators;
}

# Version: 5.0
function sp_get_moderators() {
	$moderators = array();

	# get all the moderator groups - return empty list if no moderator groups
	$mods = spdb_table(SFMEMBERS, 'moderator=1');
	if (empty($mods)) return '';

	# cycle through all moderators
	$count = 0;
	foreach ($mods as $mod) {
		$moderators[$count]['id'] = $mod->user_id;	 # add user to moderator list
		$moderators[$count]['display_name'] = $mod->display_name;  # get display name for moderator
		$count++;
	}
	return $moderators;
}

# Version: 5.0
function sp_get_all_roles() {
	return spdb_table(SFROLES, '', '', 'role_id');
}

# Version: 5.0
function sp_get_forum_permissions($forum_id) {
	return spdb_table(SFPERMISSIONS, "forum_id=$forum_id", '', 'permission_role');
}

?>