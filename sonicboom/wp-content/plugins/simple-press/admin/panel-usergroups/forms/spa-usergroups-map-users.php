<?php
/*
Simple:Press
User groups map users form
$LastChangedDate: 2012-04-08 14:55:07 -0700 (Sun, 08 Apr 2012) $
$Rev: 8297 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

function spa_usergroups_map_users() {
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#sfmapsettingsform').ajaxForm({
		target: '#sfmsgspot',
		success: function() {
			jQuery('#sfreloadmu').click();
			jQuery('#sfmsgspot').fadeIn();
			jQuery('#sfmsgspot').fadeOut(6000);
		}
	});
	jQuery('#sfmapusersform').ajaxForm({
		target: '#sfmsgspot',
		success: function() {
			jQuery('#sfreloadmu').click();
			jQuery('#sfmsgspot').fadeIn();
			jQuery('#sfmsgspot').fadeOut(6000);
		}
	});
});
</script>
<?php
	global $wp_roles;

    $sfoptions = spa_get_mapping_data();
    
    $ahahURL = SFHOMEURL.'index.php?sp_ahah=usergroups-loader&amp;sfnonce='.wp_create_nonce('forum-ahah').'&amp;saveform=mapsettings';
?>
	<form action="<?php echo $ahahURL; ?>" method="post" id="sfmapsettingsform" name="sfmapsettingsform">
	<?php echo sp_create_nonce('forum-adminform_mapusers'); ?>
<?php

	spa_paint_options_init();
	spa_paint_open_tab(spa_text('User Groups').' - '.spa_text('User Mapping Settings'));

		spa_paint_open_panel();
			spa_paint_open_fieldset(spa_text('User Memberships'), true, 'user-memberships');
    			echo '<tr><td colspan="2"><br /><div class="sfoptionerror">';
    			spa_etext('Warning: Use caution when setting the single usergroup membership option below. It should primarily be used in conjunction with a membership plugin (such as Wishlist) where strict usergroup membership is required.  Please note that auto usergroup membership by WP role or by forum rank may conflict or overwrite any manual usergroup memberships (such as moderator) you may set if you have single usergroup membership set');
    			echo '</div><br />';
    			echo '</td></tr>';
				spa_paint_checkbox(spa_text('Users are limited to single usergroup membership'), 'sfsinglemembership', $sfoptions['sfsinglemembership']);
				echo '<tr><td colspan="2"><p class="subhead">'.spa_text('Default usergroup membership').':</p></td></tr>';
				spa_paint_select_start(spa_text('Default usergroup for guests'), 'sfguestsgroup', 'sfguestsgroup');
				echo spa_create_usergroup_select($sfoptions['sfguestsgroup']);
				spa_paint_select_end();

				spa_paint_select_start(spa_text('Default usergroup for new members'), 'sfdefgroup', 'sfdefgroup');
				echo spa_create_usergroup_select($sfoptions['sfdefgroup']);
				spa_paint_select_end();

				$roles = array_keys($wp_roles->role_names);
				if ($roles) {
					echo '<tr><td colspan="2"><p class="subhead">'.spa_text('Usergroup memberships based on WP role').':</p></td></tr>';
					$sfoptions['role'] = array();
					foreach ($roles as $index => $role) {
						$value = sp_get_sfmeta('default usergroup', $role);
						if ($value) {
							$group = $value[0]['meta_value'];
						} else {
							$group = $sfoptions['sfdefgroup'];
						}
						echo '<input type="hidden" class="sfhiddeninput" name="sfoldrole['.$index.']" value="'.$group.'" />';
						spa_paint_select_start(spa_text('Default usergroup for').' '.$role, "sfrole[$index]", 'sfguestsgroup');
						echo spa_create_usergroup_select($group);
						spa_paint_select_end();
					}
				}
			spa_paint_close_fieldset();
		spa_paint_close_panel();

		do_action('sph_usergroups_mapping_settings_panel');

	spa_paint_close_tab();
?>
	<div class="sfform-submit-bar">
	<input type="submit" class="sfform-panel-button" id="saveit" name="saveit" value="<?php spa_etext('Update Mapping Settings'); ?>" />
	</div>
	</form>

	<div class="sfform-panel-spacer"></div>
<?php
    $ahahURL = SFHOMEURL.'index.php?sp_ahah=usergroups-loader&amp;sfnonce='.wp_create_nonce('forum-ahah').'&amp;saveform=mapusers';
    $working = '<div class=\\\'sfoptionerror\\\'>'.spa_text('Working... Please be patient as this may take some time for large number of users. This page will reload when complete.').'</div>';
?>
	<form action="<?php echo $ahahURL; ?>" method="post" id="sfmapusersform" name="sfmapusersform" onsubmit="javascript:jQuery('#saveit2').attr('disabled', 'disabled'); jQuery('#save-msg').html('<?php echo $working; ?>');">
<?php
	echo sp_create_nonce('forum-adminform_mapusers');
	spa_paint_options_init();
	spa_paint_open_tab(spa_text('User Groups').' - '.spa_text('Map Users'));

		spa_paint_open_panel();
			spa_paint_open_fieldset(spa_text('Map Users'), true, 'map-users');
    			echo '<tr><td colspan="2"><br /><div class="sfoptionerror">';
    			spa_etext("Warning: Use caution when mapping users. This will adjust your user's memberships in User Groups. Choose the criteria and options carefully. The mapping cannot be undone except by remapping or manual process. Also, make sure you have saved your mapping settings above before mapping as they are two distinct actions.");
    			echo '</div><br />';
    			echo '</td></tr>';
				$values = array(spa_text('Add user membership based on WP role to existing memberships'),
                                spa_text('Replace all user memberships with a single membership based on WP role'));
				spa_paint_radiogroup(spa_text('Select mapping criteria'), 'mapoption', $values, 2, false, true);
				spa_paint_checkbox(spa_text('Ignore current SP Moderators when mapping'), 'ignoremods', true);
			spa_paint_close_fieldset();
		spa_paint_close_panel();

		do_action('sph_usergroups_map_users_panel');

	spa_paint_close_tab();
?>
	<div class="sfform-submit-bar">
	<input type="submit" class="sfform-panel-button" id="saveit2" name="saveit2" value="<?php spa_etext('Map Users'); ?>" /> <div id='save-msg'></div>
	</div>
	</form>
<?php
}

function spa_create_usergroup_select($sfdefgroup) {
    $out = '';

    $ugid = spdb_table(SFUSERGROUPS, "usergroup_id=$sfdefgroup", 'usergroup_id');
	if (empty($ugid)) $out.= '<option selected="selected" value="-1">INVALID</option>';

	$usergroups = spa_get_usergroups_all();
	$default='';
	foreach ($usergroups as $usergroup) {
		if ($usergroup->usergroup_id == $sfdefgroup) {
			$default = 'selected="selected" ';
		} else {
			$default = null;
		}
		$out.= '<option '.$default.'value="'.$usergroup->usergroup_id.'">'.sp_filter_title_display($usergroup->usergroup_name).'</option>';
		$default = '';
	}
	return $out;
}

?>