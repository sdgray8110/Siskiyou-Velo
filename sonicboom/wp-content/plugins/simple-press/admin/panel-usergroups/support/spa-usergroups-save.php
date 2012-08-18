<?php
/*
Simple:Press
Admin User Groups Support Functions
$LastChangedDate: 2012-05-04 09:19:23 -0700 (Fri, 04 May 2012) $
$Rev: 8473 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

# function to create a new user group
function spa_save_usergroups_new_usergroup() {
    check_admin_referer('forum-adminform_usergroupnew', 'forum-adminform_usergroupnew');

    # if no usergroup name supplied use a default name
    if (empty($_POST['usergroup_name'])) {
        $usergroupname = spa_text('New User Group');
    } else {
        $usergroupname = sp_filter_title_save(trim($_POST['usergroup_name']));
    }

    $usergroupdesc = sp_filter_title_save(trim($_POST['usergroup_desc']));
    $usergroupbadge = sp_filter_filename_save(trim($_POST['usergroup_badge']));

    if (isset($_POST['usergroup_join'])) {
		$usergroupjoin = 1;
	} else {
		$usergroupjoin = 0;
	}

    if (isset($_POST['usergroup_is_moderator'])) {
		$usergroupismod = 1;
	} else {
		$usergroupismod = 0;
	}

    # create the usergroup
    $success = spa_create_usergroup_row($usergroupname, $usergroupdesc, $usergroupbadge, $usergroupjoin, $usergroupismod, true);

    sp_reset_memberships();

    if ($success == false) {
        $mess = spa_text('New User Group Creation Failed!');
    } else {
        $mess = spa_text('New User Group Created');
    }
    return $mess;
}

# function to update an existing user group
function spa_save_usergroups_edit_usergroup() {
    check_admin_referer('forum-adminform_usergroupedit', 'forum-adminform_usergroupedit');

    $usergroupdata = array();
    $usergroup_id = sp_esc_int($_POST['usergroup_id']);
    $usergroupdata['usergroup_name'] = sp_filter_title_save(trim($_POST['usergroup_name']));
    $usergroupdata['usergroup_desc'] = sp_filter_title_save(trim($_POST['usergroup_desc']));
    $usergroupdata['usergroup_badge'] = sp_filter_filename_save(trim($_POST['usergroup_badge']));
    if (isset($_POST['usergroup_join'])) { $usergroupdata['usergroup_join'] = 1; } else { $usergroupdata['usergroup_join'] = 0; }
    if (isset($_POST['usergroup_is_moderator'])) { $usergroupdata['usergroup_is_moderator'] = 1; } else { $usergroupdata['usergroup_is_moderator'] = 0; }

    # update the user group info
	$sql = "UPDATE ".SFUSERGROUPS." SET ";
	$sql.= 'usergroup_name="'.$usergroupdata['usergroup_name'].'", ';
	$sql.= 'usergroup_desc="'.$usergroupdata['usergroup_desc'].'", ';
	$sql.= 'usergroup_badge="'.$usergroupdata['usergroup_badge'].'", ';
	$sql.= 'usergroup_join="'.$usergroupdata['usergroup_join'].'", ';
	$sql.= 'usergroup_is_moderator="'.$usergroupdata['usergroup_is_moderator'].'" ';
	$sql.= 'WHERE usergroup_id="'.$usergroup_id.'";';
    $success = spdb_query($sql);

    sp_reset_memberships();

    if ($success == false) {
        $mess = spa_text('User Group Update Failed!');
    } else {
        $mess = spa_text('User Group Record Updated');
        do_action('sph_usergroup_new', $usergroup_id);
    }
    return $mess;
}

function spa_save_usergroups_delete_usergroup() {
    check_admin_referer('forum-adminform_usergroupdelete', 'forum-adminform_usergroupdelete');

    $usergroup_id = sp_esc_int($_POST['usergroup_id']);

    # dont allow updates to the default user groups
    $usergroup = spa_get_usergroups_row($usergroup_id);
    if ($usergroup->usergroup_locked) {
        $mess = spa_text('Sorry, the default User Groups cannot be deleted.');
        return $mess;
    }

    # remove all memberships for this user group
    spdb_query("DELETE FROM ".SFMEMBERSHIPS." WHERE usergroup_id=".$usergroup_id);

	# remove any permission sets using this user group
	$permissions = spdb_table(SFPERMISSIONS, "usergroup_id=$usergroup_id");
	if ($permissions) {
		foreach ($permissions as $permission) {
			spa_remove_permission_data($permission->permission_id);
		}
	}

	# remove any group default permissions using this user group
	spdb_query("DELETE FROM ".SFDEFPERMISSIONS." WHERE usergroup_id=".$usergroup_id);

    # remove the user group
   	spdb_query("DELETE FROM ".SFMEMBERSHIPS." WHERE usergroup_id=".$usergroup_id);
    $success = spdb_query("DELETE FROM ".SFUSERGROUPS." WHERE usergroup_id=".$usergroup_id);
    if ($success == false) {
        $mess = spa_text('User Group Delete Failed!');
    } else {
        $mess = spa_text('User Group Deleted');

        # reset auths and memberships for everyone
        sp_reset_memberships();
        sp_reset_auths();

        do_action('sph_usergroup_del', $usergroup_id);
    }

    return $mess;
}

function spa_save_usergroups_add_members() {
    check_admin_referer('forum-adminform_membernew', 'forum-adminform_membernew');

    # add the users to the user group membership
    $usergroup_id = sp_esc_int($_POST['usergroup_id']);
    if (isset($_POST['member_id'])) $user_id_list = array_unique($_POST['member_id']);

    if (!isset($user_id_list)) {
	    $mess = spa_text('No Data Changed!');
		return $mess;
	}

	for ($x=0; $x<count($user_id_list); $x++) {
		$user_id = sp_esc_int($user_id_list[$x]);
		$check = sp_check_membership($usergroup_id, $user_id);
		if (empty($check)) $success = sp_add_membership($usergroup_id, $user_id);
 	   	if ($success == false) {
	    	$mess = spa_text('Member Add Failed!');
	    	return $mess;
		}

        # reset auths and memberships for added user
        sp_reset_memberships($user_id);
        sp_reset_auths($user_id);

		# update mod flag
 	   	sp_update_member_moderator_flag($user_id);
	}

    do_action('sph_usergroup_add', $usergroup_id);

    $mess = spa_text('Member(s) Added to User Group');
    return $mess;
}

function spa_save_usergroups_delete_members() {
    check_admin_referer('forum-adminform_memberdel', 'forum-adminform_memberdel');

    $usergroup_id = sp_esc_int($_POST['usergroupid']);
    $new_usergroup_id = $_POST['usergroup_id'];
    if (isset($_POST['dmember_id'])) $user_id_list = array_unique($_POST['dmember_id']);

	# make sure not moving to same user group
	if (!isset($user_id_list) || $usergroup_id == $new_usergroup_id) {
	    $mess = spa_text('No Data Changed!');
		return $mess;
	}

	for ($x=0; $x<count($user_id_list); $x++) {
		$user_id = sp_esc_int($user_id_list[$x]);
		$success = spdb_query("DELETE FROM ".SFMEMBERSHIPS." WHERE user_id=".$user_id." AND usergroup_id=".$usergroup_id);

	    if ($new_usergroup_id != -1) {
			$check = sp_check_membership($new_usergroup_id, $user_id);
			if (empty($check)) $success = sp_add_membership($new_usergroup_id, $user_id);
	    }

        # reset auths and memberships for added user
        sp_reset_memberships($user_id);
        sp_reset_auths($user_id);

		# update mod flag
	    sp_update_member_moderator_flag($user_id);
	}

    if ($new_usergroup_id != -1) {
	    $mess = spa_text('Member(s) Moved');

        do_action('sph_usergroup_moved', $usergroup_id);
	} else {
	    $mess = spa_text('Member(s) Deleted From User Group');

        do_action('sph_usergroup_remove', $usergroup_id);
	}

    return $mess;
}

function spa_save_usergroups_map_settings() {
	global $wp_roles;

	check_admin_referer('forum-adminform_mapusers', 'forum-adminform_mapusers');

	# save default usergroups
	sp_add_sfmeta('default usergroup', 'sfguests', sp_esc_int($_POST['sfguestsgroup'])); # default usergroup for guests
	sp_add_sfmeta('default usergroup', 'sfmembers', sp_esc_int($_POST['sfdefgroup'])); # default usergroup for members

	# check for changes in wp role usergroup assignments
	if (isset($_POST['sfrole'])) {
		$roles = array_keys($wp_roles->role_names);
		foreach ($_POST['sfrole'] as $index => $role) {
			if ($_POST['sfoldrole'][$index] != $role) sp_add_sfmeta('default usergroup', $roles[$index], sp_esc_int($role));
		}
	}    

	$sfmemberopts = sp_get_option('sfmemberopts');
    $sfmemberopts['sfsinglemembership'] = isset($_POST['sfsinglemembership']);
	sp_update_option('sfmemberopts', $sfmemberopts);

	$mess = spa_text('User mapping settings saved');
    do_action('sph_option_map_settings_save');
	return $mess;
}

function spa_save_usergroups_map_users() {
	global $wp_roles;

	check_admin_referer('forum-adminform_mapusers', 'forum-adminform_mapusers');

    $where = ' WHERE admin=0';
    if ($_POST['ignoremods']) $where.= ' AND moderator=0';
	$users = spdb_select('col', 'SELECT user_id FROM '.SFMEMBERS.$where);
    if ($users) {
		$value = sp_get_sfmeta('default usergroup', 'sfmembers');
		$defaultUG = $value[0]['meta_value'];
        foreach ($users as $thisUser) {
            if ($_POST['mapoption'] == 2) spdb_query('DELETE FROM '.SFMEMBERSHIPS." WHERE user_id=$thisUser");
            $user = new WP_User($thisUser);
            if (!empty($user->roles ) && is_array($user->roles)) {
            	foreach ($user->roles as $role) {
            		$value = sp_get_sfmeta('default usergroup', $role);
            		if (!empty($value)) {
            			$ug = $value[0]['meta_value'];
            		} else {
            			$ug = $defaultUG;
            		}
                    sp_add_membership($ug, $thisUser);
                }
            }            
        }
        
        # clean up
        sp_reset_memberships();
        sp_reset_auths();
        
    	$mess = spa_text('Users mapped');
    } else {        
	   $mess = spa_text('No users to map that meet criteria/options');
    }
    
    do_action('sph_option_map_users_save');
	return $mess;
}

?>