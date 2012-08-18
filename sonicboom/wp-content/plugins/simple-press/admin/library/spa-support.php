<?php
/*
Simple:Press
Admin Support Routines
$LastChangedDate: 2012-06-03 16:09:40 -0700 (Sun, 03 Jun 2012) $
$Rev: 8653 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

function spa_get_forums_in_group($groupid) {
	return spdb_table(SFFORUMS, "group_id=$groupid", '', 'forum_seq');
}

function spa_get_group_forums_by_parent($groupid, $parentid) {
	return spdb_table(SFFORUMS, "group_id=$groupid AND parent=$parentid", '', 'forum_seq');
}

function spa_get_forums_all() {
	return spdb_select('set',
		'SELECT forum_id, forum_name, '.SFGROUPS.'.group_id, group_name
		 FROM '.SFFORUMS.'
		 JOIN '.SFGROUPS.' ON '.SFFORUMS.'.group_id = '.SFGROUPS.'.group_id
		 ORDER BY group_seq, forum_seq');
}

function spa_create_group_select($groupid = 0) {
	$groups = spdb_table(SFGROUPS, '', '', "group_seq");
	$out='';
	$default='';

	$out.= '<option value="">'.spa_text('Select forum group:').'</option>';

	if ($groups) {
		foreach ($groups as $group) {
			if ($group->group_id == $groupid) {
				$default = 'selected="selected" ';
			} else {
				$default - null;
			}
			$out.='<option '.$default.'value="'.$group->group_id.'">'.sp_filter_title_display($group->group_name).'</option>'."\n";
			$default='';
		}
	}
	return $out;
}

# Creates a select list of forums in a group.
# If forum id is specified it is NOT included.
function spa_create_group_forum_select($groupid, $forumid = 0, $parent) {
	$forums = spa_get_forums_in_group($groupid);
	$out='';
	if ($forums) {
		foreach ($forums as $forum) {
			if ($forum->forum_id != $forumid) {
				$selected = '';
				if ($forum->forum_id == $parent) $selected = ' selected="selected"';
				$out.= '<option'.$selected.' value="'.$forum->forum_id.'">'.sp_filter_title_display($forum->forum_name).'</option>'."\n";
			}
		}
	}
	return $out;
}

function spa_update_check_option($key) {
	if (isset($_POST[$key])) {
		sp_update_option($key, true);
	} else {
		sp_update_option($key, false);
	}
}

function spa_get_usergroups_all($usergroupid=Null) {
	$where='';
	if (!is_null($usergroupid)) $where="usergroup_id=$usergroupid";
	return spdb_table(SFUSERGROUPS, $where);
}

function spa_get_usergroups_row($usergroup_id) {
	return spdb_table(SFUSERGROUPS, "usergroup_id=$usergroup_id", 'row');
}

function spa_create_usergroup_row($usergroupname, $usergroupdesc, $usergroupbadge, $usergroupjoin, $usergroupismod, $report_failure=false) {
	global $sfvars;

	# first check to see if user group name exists
	$exists = spdb_table(SFUSERGROUPS, "usergroup_name='$usergroupname'", 'usergroup_id');
	if ($exists) {
		if($report_failure == true) {
			return false;
		} else {
			return $exists;
		}
	}

	# go on and create the new user group
	$sql = 'INSERT INTO '.SFUSERGROUPS.' (usergroup_name, usergroup_desc, usergroup_badge, usergroup_join, usergroup_is_moderator) ';
	$sql.= "VALUES ('$usergroupname', '$usergroupdesc', '$usergroupbadge', '$usergroupjoin', '$usergroupismod')";

	if (spdb_query($sql)) {
		return $sfvars['insertid'];
	} else {
		return false;
	}
}


function spa_remove_permission_data($permission_id) {
	return spdb_query('DELETE FROM '.SFPERMISSIONS." WHERE permission_id=$permission_id");
}


function spa_create_role_row($role_name, $role_desc, $auths, $report_failure=false) {
	global $sfvars;

	# first check to see if rolename exists
	$exists = spdb_table(SFROLES, "role_name='$role_name'", 'role_id');
	if ($exists) {
		if ($report_failure == true) {
			return false;
		} else {
			return $exists;
		}
	}

	# go on and create the new role
	$sql = 'INSERT INTO '.SFROLES.' (role_name, role_desc, role_auths) ';
	$sql.= "VALUES ('$role_name', '$role_desc', '$auths')";

	if (spdb_query($sql)) {
		return $sfvars['insertid'];
	} else {
		return false;
	}
}

function spa_get_role_row($role_id) {
	return spdb_table(SFROLES, "role_id=$role_id", 'row');
}

function spa_get_defpermissions($group_id) {
	return spdb_select('set', '
		SELECT permission_id, '.SFUSERGROUPS.'.usergroup_id, permission_role, usergroup_name
		FROM '.SFDEFPERMISSIONS.'
		JOIN '.SFUSERGROUPS.' ON '.SFDEFPERMISSIONS.'.usergroup_id = '.SFUSERGROUPS.".usergroup_id
		WHERE group_id=$group_id");
}

function spa_get_defpermissions_role($group_id, $usergroup_id) {
	return spdb_table(SFDEFPERMISSIONS, "group_id=$group_id AND usergroup_id=$usergroup_id", 'permission_role');
}

function spa_display_usergroup_select($filter = false, $forum_id = 0) { ?>
	<?php $usergroups = spa_get_usergroups_all(); ?>
	<p><?php spa_etext('Select usergroup') ?>:&nbsp;&nbsp;
	<select style="width:145px" class='sfacontrol' name='usergroup_id'>
<?php
		$out = '<option value="-1">'.spa_text('Select usergroup').'</option>';
		if ($filter) $perms = sp_get_forum_permissions($forum_id);
		foreach ($usergroups as $usergroup) {
			$disabled = '';
			if ($filter ==1 and $perms) {
				foreach ($perms as $perm) {
					if ($perm->usergroup_id == $usergroup->usergroup_id) {
						$disabled = 'disabled="disabled" ';
						continue;
					}
				}
			}
			$out.= '<option '.$disabled.'value="'.$usergroup->usergroup_id.'">'.sp_filter_title_display($usergroup->usergroup_name).'</option>'."\n";
			$default='';
		}
		echo $out;
?>
	</select></p>
<?php
}

function spa_display_permission_select($cur_perm = 0) {
?>
	<?php $roles = sp_get_all_roles(); ?>
	<p><?php spa_etext('Select permission set') ?>:&nbsp;&nbsp;
	<select style="width:165px" class='sfacontrol' name='role'>
<?php
		$out = '';
		if ($cur_perm == 0) $out='<option value="-1">'.spa_text('Select permission set').'</option>';
		foreach($roles as $role)
		{
			$selected = '';
			if ($cur_perm == $role->role_id) $selected = 'selected = "selected" ';
			$out.='<option '.$selected.'value="'.$role->role_id.'">'.sp_filter_title_display($role->role_name).'</option>'."\n";
		}
		echo $out;
?>
	</select></p>
<?php
}

function spa_select_icon_dropdown($name, $label, $path, $cur) {
	# Open folder and get cntents for matching
	$dlist = @opendir($path);
	if (!$dlist) return;

	echo '<select name="'.$name.'" class="sfcontrol" style="vertical-align:middle;">';
	if ($cur != '') $label = spa_text('Remove');
	echo '<option value="">'.$label.'</option>';
	while (false !== ($file = readdir($dlist))) {
		if ($file != "." && $file != '..') {
			$selected = '';
			if ($file == $cur) $selected = ' selected="selected"';
			echo '<option'.$selected.' value="'.esc_attr($file).'">'.esc_html($file).'</option>';
		}
	}
	echo '</select>';
	closedir($dlist);
}

function spa_reset_permissions() {
	# Create default role data
    # NOTE that the auths do not use action names like this, but its pretty unreadable the way its stored
    # so use action names here for readability and maintainability. we will convert the actions to auths before storing
	$actions = array();
	$actions['Can view forum'] = 0;
	$actions['Can view forum lists only'] = 0;
	$actions['Can view forum and topic lists only'] = 0;
	$actions['Can view forum and topic lists only'] = 0;
	$actions['Can view admin posts'] = 0;
	$actions['Can start new topics'] = 0;
	$actions['Can reply to topics'] = 0;
	$actions['Can edit own topic titles'] = 0;
	$actions['Can edit any topic titles'] = 0;
	$actions['Can pin topics'] = 0;
	$actions['Can move topics'] = 0;
	$actions['Can move posts'] = 0;
	$actions['Can lock topics'] = 0;
	$actions['Can delete topics'] = 0;
	$actions['Can edit own posts forever'] = 0;
	$actions['Can edit own posts until reply'] = 0;
	$actions['Can edit any posts'] = 0;
	$actions['Can delete own posts'] = 0;
	$actions['Can delete any post'] = 0;
	$actions['Can pin posts'] = 0;
	$actions['Can reassign posts'] = 0;
	$actions['Can view users email addresses'] = 0;
	$actions['Can view members profiles'] = 0;
	$actions['Can view members lists'] = 0;
	$actions['Can sort most recent posts'] = 0;
	$actions['Can bypass spam control'] = 0;
	$actions['Can bypass post moderation'] = 0;
	$actions['Can bypass post moderation once'] = 0;
	$actions['Can use signatures'] = 0;
	$actions['Can upload avatars'] = 0;
	$actions['Can use spoilers'] = 0;
	$actions['Can view links'] = 0;
	$actions['Can moderate pending posts'] = 0;
	$role_name = 'No Access';
	$role_desc = 'Permission with no access to any Forum features';
    $new_actions = spa_convert_action_to_auth($actions);
	spa_create_role_row($role_name, $role_desc, serialize($new_actions));

	$actions = array();
	$actions['Can view forum'] = 1;
	$actions['Can view forum lists only'] = 0;
	$actions['Can view forum and topic lists only'] = 0;
	$actions['Can view admin posts'] = 1;
	$actions['Can start new topics'] = 0;
	$actions['Can reply to topics'] = 0;
	$actions['Can edit own topic titles'] = 0;
	$actions['Can edit any topic titles'] = 0;
	$actions['Can pin topics'] = 0;
	$actions['Can move topics'] = 0;
	$actions['Can move posts'] = 0;
	$actions['Can lock topics'] = 0;
	$actions['Can delete topics'] = 0;
	$actions['Can edit own posts forever'] = 0;
	$actions['Can edit own posts until reply'] = 0;
	$actions['Can edit any posts'] = 0;
	$actions['Can delete own posts'] = 0;
	$actions['Can delete any post'] = 0;
	$actions['Can pin posts'] = 0;
	$actions['Can reassign posts'] = 0;
	$actions['Can view users email addresses'] = 0;
	$actions['Can view members profiles'] = 0;
	$actions['Can view members lists'] = 0;
	$actions['Can sort most recent posts'] = 0;
	$actions['Can bypass spam control'] = 0;
	$actions['Can bypass post moderation'] = 0;
	$actions['Can bypass post moderation once'] = 0;
	$actions['Can use signatures'] = 0;
	$actions['Can upload avatars'] = 0;
	$actions['Can use spoilers'] = 1;
	$actions['Can view links'] = 1;
	$actions['Can moderate pending posts'] = 0;
	$role_name = 'Read Only Access';
	$role_desc = 'Permission with access to only view the Forum';
    $new_actions = spa_convert_action_to_auth($actions);
	spa_create_role_row($role_name, $role_desc, serialize($new_actions));

	$actions = array();
	$actions['Can view forum'] = 1;
	$actions['Can view forum lists only'] = 0;
	$actions['Can view forum and topic lists only'] = 0;
	$actions['Can view admin posts'] = 1;
	$actions['Can start new topics'] = 1;
	$actions['Can reply to topics'] = 1;
	$actions['Can edit own topic titles'] = 0;
	$actions['Can edit any topic titles'] = 0;
	$actions['Can pin topics'] = 0;
	$actions['Can move topics'] = 0;
	$actions['Can move posts'] = 0;
	$actions['Can lock topics'] = 0;
	$actions['Can delete topics'] = 0;
	$actions['Can edit own posts forever'] = 0;
	$actions['Can edit own posts until reply'] = 0;
	$actions['Can edit any posts'] = 0;
	$actions['Can delete own posts'] = 0;
	$actions['Can delete any post'] = 0;
	$actions['Can pin posts'] = 0;
	$actions['Can reassign posts'] = 0;
	$actions['Can view users email addresses'] = 0;
	$actions['Can view members profiles'] = 1;
	$actions['Can view members lists'] = 1;
	$actions['Can sort most recent posts'] = 0;
	$actions['Can bypass spam control'] = 0;
	$actions['Can bypass post moderation'] = 0;
	$actions['Can bypass post moderation once'] = 0;
	$actions['Can use signatures'] = 0;
	$actions['Can upload avatars'] = 1;
	$actions['Can use spoilers'] = 1;
	$actions['Can view links'] = 1;
	$actions['Can moderate pending posts'] = 0;
	$role_name = 'Limited Access';
	$role_desc = 'Permission with access to reply and start topics but with limited features';
    $new_actions = spa_convert_action_to_auth($actions);
	spa_create_role_row($role_name, $role_desc, serialize($new_actions));

	$actions = array();
	$actions['Can view forum'] = 1;
	$actions['Can view forum lists only'] = 0;
	$actions['Can view forum and topic lists only'] = 0;
	$actions['Can view admin posts'] = 1;
	$actions['Can start new topics'] = 1;
	$actions['Can reply to topics'] = 1;
	$actions['Can edit own topic titles'] = 0;
	$actions['Can edit any topic titles'] = 0;
	$actions['Can pin topics'] = 0;
	$actions['Can move topics'] = 0;
	$actions['Can move posts'] = 0;
	$actions['Can lock topics'] = 0;
	$actions['Can delete topics'] = 0;
	$actions['Can edit own posts forever'] = 0;
	$actions['Can edit own posts until reply'] = 1;
	$actions['Can edit any posts'] = 0;
	$actions['Can delete own posts'] = 0;
	$actions['Can delete any post'] = 0;
	$actions['Can pin posts'] = 0;
	$actions['Can reassign posts'] = 0;
	$actions['Can view users email addresses'] = 0;
	$actions['Can view members profiles'] = 1;
	$actions['Can view members lists'] = 1;
	$actions['Can sort most recent posts'] = 0;
	$actions['Can bypass spam control'] = 0;
	$actions['Can bypass post moderation'] = 1;
	$actions['Can bypass post moderation once'] = 1;
	$actions['Can use signatures'] = 1;
	$actions['Can upload avatars'] = 1;
	$actions['Can use spoilers'] = 1;
	$actions['Can view links'] = 1;
	$actions['Can moderate pending posts'] = 0;
	$role_name = 'Standard Access';
	$role_desc = 'Permission with access to reply and start topics with advanced features such as signatures';
    $new_actions = spa_convert_action_to_auth($actions);
	spa_create_role_row($role_name, $role_desc, serialize($new_actions));

	$actions = array();
	$actions['Can view forum'] = 1;
	$actions['Can view forum lists only'] = 0;
	$actions['Can view forum and topic lists only'] = 0;
	$actions['Can view admin posts'] = 1;
	$actions['Can start new topics'] = 1;
	$actions['Can reply to topics'] = 1;
	$actions['Can edit own topic titles'] = 1;
	$actions['Can edit any topic titles'] = 0;
	$actions['Can pin topics'] = 0;
	$actions['Can move topics'] = 0;
	$actions['Can move posts'] = 0;
	$actions['Can lock topics'] = 0;
	$actions['Can delete topics'] = 0;
	$actions['Can edit own posts forever'] = 1;
	$actions['Can edit own posts until reply'] = 1;
	$actions['Can edit any posts'] = 0;
	$actions['Can delete own posts'] = 0;
	$actions['Can delete any post'] = 0;
	$actions['Can pin posts'] = 0;
	$actions['Can reassign posts'] = 0;
	$actions['Can view users email addresses'] = 0;
	$actions['Can view members profiles'] = 1;
	$actions['Can view members lists'] = 1;
	$actions['Can sort most recent posts'] = 0;
	$actions['Can bypass spam control'] = 1;
	$actions['Can bypass post moderation'] = 1;
	$actions['Can bypass post moderation once'] = 1;
	$actions['Can use signatures'] = 1;
	$actions['Can upload avatars'] = 1;
	$actions['Can use spoilers'] = 1;
	$actions['Can view links'] = 1;
	$actions['Can moderate pending posts'] = 0;
	$role_name = 'Full Access';
	$role_desc = 'Permission with Standard Access features and spam control bypass';
    $new_actions = spa_convert_action_to_auth($actions);
	spa_create_role_row($role_name, $role_desc, serialize($new_actions));

	$actions = array();
	$actions['Can view forum'] = 1;
	$actions['Can view forum lists only'] = 0;
	$actions['Can view forum and topic lists only'] = 0;
	$actions['Can view admin posts'] = 1;
	$actions['Can start new topics'] = 1;
	$actions['Can reply to topics'] = 1;
	$actions['Can edit any topic titles'] = 1;
	$actions['Can edit own topic titles'] = 1;
	$actions['Can pin topics'] = 1;
	$actions['Can move topics'] = 1;
	$actions['Can move posts'] = 1;
	$actions['Can lock topics'] = 1;
	$actions['Can delete topics'] = 1;
	$actions['Can edit own posts forever'] = 1;
	$actions['Can edit own posts until reply'] = 1;
	$actions['Can edit any posts'] = 1;
	$actions['Can delete own posts'] = 0;
	$actions['Can delete any post'] = 1;
	$actions['Can pin posts'] = 1;
	$actions['Can reassign posts'] = 1;
	$actions['Can view users email addresses'] = 1;
	$actions['Can view members profiles'] = 1;
	$actions['Can view members lists'] = 1;
	$actions['Can sort most recent posts'] = 1;
	$actions['Can bypass spam control'] = 1;
	$actions['Can bypass post moderation'] = 1;
	$actions['Can bypass post moderation once'] = 1;
	$actions['Can use signatures'] = 1;
	$actions['Can upload avatars'] = 1;
	$actions['Can use spoilers'] = 1;
	$actions['Can view links'] = 1;
	$actions['Can moderate pending posts'] = 1;
	$role_name = 'Moderator Access';
	$role_desc = 'Permission with access to all Forum features';
    $new_actions = spa_convert_action_to_auth($actions);
	spa_create_role_row($role_name, $role_desc, serialize($new_actions));
}

function spa_convert_action_to_auth($actions) {
	$new_actions = array();
	foreach ($actions as $index => $action) {
		if ($index == 'Can view forum') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "view_forum"')] = (int) $action;
		if ($index == 'Can view forum lists only') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "view_forum_lists"')] = (int) $action;
		if ($index == 'Can view forum and topic lists only') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "view_forum_topic_lists"')] = (int) $action;
		if ($index == 'Can view admin posts') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "view_admin_posts"')] = (int) $action;
		if ($index == 'Can start new topics') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "start_topics"')] = (int) $action;
		if ($index == 'Can reply to topics') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "reply_topics"')] = (int) $action;
		if ($index == 'Can edit own topic titles') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "edit_own_topic_titles"')] = (int) $action;
		if ($index == 'Can edit any topic titles') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "edit_any_topic_titles"')] = (int) $action;
		if ($index == 'Can pin topics') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "pin_topics"')] = (int) $action;
		if ($index == 'Can move topics') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "move_topics"')] = (int) $action;
		if ($index == 'Can move posts') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "move_posts"')] = (int) $action;
		if ($index == 'Can lock topics') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "lock_topics"')] = (int) $action;
		if ($index == 'Can delete topics') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "delete_topics"')] = (int) $action;
		if ($index == 'Can edit own posts forever') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "edit_own_posts_forever"')] = (int) $action;
		if ($index == 'Can edit own posts until reply') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "edit_own_posts_reply"')] = (int) $action;
		if ($index == 'Can edit any posts') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "edit_any_post"')] = (int) $action;
		if ($index == 'Can delete own posts') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "delete_own_posts"')] = (int) $action;
		if ($index == 'Can delete any post') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "delete_any_post"')] = (int) $action;
		if ($index == 'Can pin posts') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "pin_posts"')] = (int) $action;
		if ($index == 'Can reassign posts') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "reassign_posts"')] = (int) $action;
		if ($index == 'Can view users email addresses') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "view_email"')] = (int) $action;
		if ($index == 'Can view members profiles') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "view_profiles"')] = (int) $action;
		if ($index == 'Can view members lists') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "view_members_list"')] = (int) $action;
		if ($index == 'Can report posts') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "report_posts"')] = (int) $action;
		if ($index == 'Can bypass spam control') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "bypass_spam_control"')] = (int) $action;
		if ($index == 'Can bypass post moderation') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "bypass_moderation"')] = (int) $action;
		if ($index == 'Can bypass post moderation once') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "bypass_moderation_once"')] = (int) $action;
		if ($index == 'Can moderate pending posts') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "moderate_posts"')] = (int) $action;
		if ($index == 'Can use spoilers') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "use_spoilers"')] = (int) $action;
		if ($index == 'Can view links') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "view_links"')] = (int) $action;
		if ($index == 'Can upload images') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "upload_images"')] = (int) $action;
		if ($index == 'Can upload media') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "upload_media"')] = (int) $action;
		if ($index == 'Can upload files') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "upload_files"')] = (int) $action;
		if ($index == 'Can use signatures') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "use_signatures"')] = (int) $action;
		if ($index == 'Can upload signatures') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "upload_signatures"')] = (int) $action;
		if ($index == 'Can upload avatars') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "upload_avatars"')] = (int) $action;
		if ($index == 'Can subscribe') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "subscribe"')] = (int) $action;
		if ($index == 'Can watch topics') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "watch"')] = (int) $action;
		if ($index == 'Can change topic status') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "change_topic_status"')] = (int) $action;
		if ($index == 'Can rate posts') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "rate_posts"')] = (int) $action;
		if ($index == 'Can use private messaging') $new_actions[spdb_select('var', 'SELECT auth_id FROM '.SFAUTHS.' where auth_name = "use_pm"')] = (int) $action;
	}
	return $new_actions;
}

# 5.0 set up stuff for new profile tabs
function spa_new_profile_setup() {
	# set up tabs and menus
    sp_profile_add_tab(spa_text('Profile'));
	sp_profile_add_menu(spa_text('Profile'), spa_text('Overview'), SF_PLUGIN_DIR.'/forum/profile/forms/sp-form-overview.php');
	sp_profile_add_menu(spa_text('Profile'), spa_text('Edit Profile'), SF_PLUGIN_DIR.'/forum/profile/forms/sp-form-profile.php');
	sp_profile_add_menu(spa_text('Profile'), spa_text('Edit Identities'), SF_PLUGIN_DIR.'/forum/profile/forms/sp-form-identities.php');
	sp_profile_add_menu(spa_text('Profile'), spa_text('Edit Avatar'), SF_PLUGIN_DIR.'/forum/profile/forms/sp-form-avatar.php');
	sp_profile_add_menu(spa_text('Profile'), spa_text('Edit Signature'), SF_PLUGIN_DIR.'/forum/profile/forms/sp-form-signature.php', 0, 1, 'use_signatures');
	sp_profile_add_menu(spa_text('Profile'), spa_text('Edit Photos'), SF_PLUGIN_DIR.'/forum/profile/forms/sp-form-photos.php');
	sp_profile_add_menu(spa_text('Profile'), spa_text('Account Settings'), SF_PLUGIN_DIR.'/forum/profile/forms/sp-form-account.php');

    sp_profile_add_tab(spa_text('Options'));
	sp_profile_add_menu(spa_text('Options'), spa_text('Edit Global Options'), SF_PLUGIN_DIR.'/forum/profile/forms/sp-form-global-options.php');
	sp_profile_add_menu(spa_text('Options'), spa_text('Edit Posting Options'), SF_PLUGIN_DIR.'/forum/profile/forms/sp-form-posting-options.php');
	sp_profile_add_menu(spa_text('Options'), spa_text('Edit Display Options'), SF_PLUGIN_DIR.'/forum/profile/forms/sp-form-display-options.php');

    sp_profile_add_tab(spa_text('Usergroups'));
	sp_profile_add_menu(spa_text('Usergroups'), spa_text('Show Memberships'), SF_PLUGIN_DIR.'/forum/profile/forms/sp-form-memberships.php');

    sp_profile_add_tab(spa_text('Permissions'));
	sp_profile_add_menu(spa_text('Permissions'), spa_text('Show Permissions'), SF_PLUGIN_DIR.'/forum/profile/forms/sp-form-permissions.php');

	# overview message
	$spProfile = sp_get_option('sfprofile');
	if (empty($spProfile['sfprofiletext'])) {
		$spProfile['sfprofiletext'] = 'Welcome to the User Profile Overview Panel. From here you can view and update your profile and options as well as view your Usergroup Memberships and Permissions.';
		sp_update_option('sfprofile', $spProfile);
	}
}

function spa_admins_restore_blue() {
	$sfacolors = array();
	$sfacolors['submitbg'] = '4a759a';
	$sfacolors['submitbgt'] = 'eaf2fa';
	$sfacolors['bbarbg'] = 'eff8ff';
	$sfacolors['bbarbgt'] = '1c6280';
	$sfacolors['formbg'] = 'ffffff';
	$sfacolors['formbgt'] = '333333';
	$sfacolors['panelhead'] = '5883a9';
	$sfacolors['panelheadt'] = 'ffffff';
	$sfacolors['panelbg'] = 'ffffff';
	$sfacolors['panelbgt'] = '000000';
	$sfacolors['tabhead'] = 'eff8ff';
	$sfacolors['tabheadt'] = '1c6280';
	$sfacolors['tabrowmain'] = 'ffffff';
	$sfacolors['tabrowmaint'] = '333333';
	$sfacolors['tabrowsub'] = 'ffffff';
	$sfacolors['tabrowsubt'] = '333333';
	$sfacolors['panelsubbg'] = 'eff8ff';
	$sfacolors['panelsubbgt'] = '333333';
	$sfacolors['formtabhead'] = 'eff8ff';
	$sfacolors['formtabheadt'] = '333333';
	return $sfacolors;
}

function spa_admins_restore_grey() {
	$sfacolors = array();
	$sfacolors['submitbg'] = '21759b';
	$sfacolors['submitbgt'] = 'eaf2fa';
	$sfacolors['bbarbg'] = 'f1f1f1';
	$sfacolors['bbarbgt'] = '21759b';
	$sfacolors['formbg'] = 'ffffff';
	$sfacolors['formbgt'] = '333333';
	$sfacolors['panelhead'] = 'dfdfdf';
	$sfacolors['panelheadt'] = '21759b';
	$sfacolors['panelbg'] = 'ffffff';
	$sfacolors['panelbgt'] = '333333';
	$sfacolors['tabhead'] = 'f1f1f1';
	$sfacolors['tabheadt'] = '333333';
	$sfacolors['tabrowmain'] = 'ffffff';
	$sfacolors['tabrowmaint'] = '333333';
	$sfacolors['tabrowsub'] = 'ffffff';
	$sfacolors['tabrowsubt'] = '333333';
	$sfacolors['panelsubbg'] = 'f1f1f1';
	$sfacolors['panelsubbgt'] = '333333';
	$sfacolors['formtabhead'] = 'f1f1f1';
	$sfacolors['formtabheadt'] = '333333';
	return $sfacolors;
}

/**
 * WordPress User Search class.
 *
 * @since unknown
 */
class SP_User_Search {
	var $results;
	var $search_term;
	var $page;
	var $role;
	var $raw_page;
	var $users_per_page = 50;
	var $first_user;
	var $last_user;
	var $query_limit;
	var $query_orderby;
	var $query_from;
	var $query_where;
	var $total_users_for_query = 0;
	var $too_many_total_users = false;
	var $search_errors;
	var $paging_text;

	function SP_User_Search ($search_term = '', $page = '', $role = '') {
		$this->search_term = $search_term;
		$this->raw_page = ( '' == $page ) ? false : (int) $page;
		$this->page = (int) ( '' == $page ) ? 1 : $page;
		$this->role = $role;

		$this->prepare_query();
		$this->query();
		$this->prepare_vars_for_template_usage();
		$this->do_paging();
	}

	function prepare_query() {
		global $wpdb;
		$this->first_user = ($this->page - 1) * $this->users_per_page;

		$this->query_limit = $wpdb->prepare(" LIMIT %d, %d", $this->first_user, $this->users_per_page);
		$this->query_orderby = ' ORDER BY display_name';

		$search_sql = '';
		if ($this->search_term) {
			$searches = array();
			$search_sql = 'AND (';
			foreach (array('user_login', 'display_name') as $col)
				$searches[] = $col." LIKE '%$this->search_term%'";
			$search_sql .= implode(' OR ', $searches);
			$search_sql .= ')';
		}

		$this->query_from = " FROM $wpdb->users";
		$this->query_where = " WHERE 1=1 $search_sql";

		if ($this->role) {
			$this->query_from .= " INNER JOIN $wpdb->usermeta ON $wpdb->users.ID = $wpdb->usermeta.user_id";
			$this->query_where .= $wpdb->prepare(" AND $wpdb->usermeta.meta_key = '{$wpdb->prefix}capabilities' AND $wpdb->usermeta.meta_value LIKE %s", '%'.$this->role.'%');
		} elseif (is_multisite()) {
			$level_key = $wpdb->prefix.'capabilities'; # wpmu site admins don't have user_levels
			$this->query_from .= ", $wpdb->usermeta";
			$this->query_where .= " AND $wpdb->users.ID = $wpdb->usermeta.user_id AND meta_key = '{$level_key}'";
		}

		do_action_ref_array('pre_user_search', array(&$this));
	}

	function query() {
		global $wpdb;

		$this->results = $wpdb->get_col("SELECT DISTINCT($wpdb->users.ID)".$this->query_from.$this->query_where.$this->query_orderby.$this->query_limit);

		if ($this->results)
			$this->total_users_for_query = $wpdb->get_var("SELECT COUNT(DISTINCT($wpdb->users.ID))".$this->query_from.$this->query_where); # no limit
		else
			$this->search_errors = new WP_Error('no_matching_users_found', spa_text('No matching users were found!'));
	}

	function prepare_vars_for_template_usage() {
		$this->search_term = stripslashes($this->search_term); # done with DB, from now on we want slashes gone
	}

	function do_paging() {
		if ($this->total_users_for_query > $this->users_per_page) { # have to page the results
			$args = array();
			if (!empty($this->search_term))
				$args['usersearch'] = urlencode($this->search_term);
			if (!empty($this->role))
				$args['role'] = urlencode($this->role);

			$this->paging_text = paginate_links( array(
				'total' => ceil($this->total_users_for_query / $this->users_per_page),
				'current' => $this->page,
				'base' => 'users.php?%_%',
				'format' => 'userspage=%#%',
				'add_args' => $args
			) );
			if ($this->paging_text) {
				$this->paging_text = sprintf( '<span class="displaying-num">'.spa_text( 'Displaying %1$s - %2$s of %3$s' ).'</span>%4$s',
					number_format_i18n(($this->page - 1) * $this->users_per_page + 1),
					number_format_i18n(min($this->page * $this->users_per_page, $this->total_users_for_query)),
					number_format_i18n($this->total_users_for_query),
					$this->paging_text
				);
			}
		}
	}

	function get_results() {
		return (array) $this->results;
	}

	function page_links() {
		echo $this->paging_text;
	}

	function results_are_paged() {
		if ( $this->paging_text )
			return true;
		return false;
	}

	function is_search() {
		if ( $this->search_term )
			return true;
		return false;
	}
}

?>