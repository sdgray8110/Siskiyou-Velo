<?php
/*
Simple:Press
Admin Admins Current Admins Form
$LastChangedDate: 2011-10-29 20:05:34 -0700 (Sat, 29 Oct 2011) $
$Rev: 7264 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

function spa_admins_manage_admins_form() {
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#sfupdatecaps').ajaxForm({
		target: '#sfmsgspot',
		success: function() {
			jQuery('#sfreloadma').click();
			jQuery('#sfmsgspot').fadeIn();
			jQuery('#sfmsgspot').fadeOut(6000);
		}
	});
	jQuery('#sfaddadmins').ajaxForm({
		target: '#sfmsgspot',
		success: function() {
			jQuery('#sfreloadma').click();
			jQuery('#sfmsgspot').fadeIn();
			jQuery('#sfmsgspot').fadeOut(6000);
		}
	});
});
</script>
<?php
	global $spThisUser;

	$adminsexist = false;
	$adminrecords = spa_get_admins_caps_data();

	spa_paint_options_init();

	if ($adminrecords) {
		$adminsexist = true;

        $ahahURL = SFHOMEURL.'index.php?sp_ahah=admins-loader&amp;sfnonce='.wp_create_nonce('forum-ahah').'&amp;saveform=manageadmin';
		?>
		<form action="<?php echo $ahahURL; ?>" method="post" id="sfupdatecaps" name="sfupdatecaps">
		<?php echo sp_create_nonce('forum-adminform_sfupdatecaps'); ?>
		<?php

	spa_paint_open_tab(spa_text('Admins')." - ".spa_text('Manage Admins'));
		spa_paint_open_panel();
			spa_paint_open_fieldset(spa_text('Current Admins'), 'true', 'manage-admins', false);
				?>
				<table class="sfsubtable" cellpadding="0" cellspacing="0">
					<tr>
						<th align="center" width="70"><?php spa_etext('User ID'); ?></th>
						<th scope="col"><?php spa_etext('Admin Name') ?></th>
						<th align="center" width="10" scope="col"></th>
						<th align="center" width="600" scope="col"><?php spa_etext('Update Admin Capabilities') ?></th>
						<th align="center" width="150" scope="col"><?php spa_etext('Remove All Capabilities') ?></th>
					</tr>
					<?php
					foreach ($adminrecords as $admin) {
						$user = new WP_User($admin['id']);
						$manage_opts = $user->has_cap('SPF Manage Options') ? 1 : 0;
						$manage_forums = $user->has_cap('SPF Manage Forums') ? 1 : 0;
						$manage_ugs = $user->has_cap('SPF Manage User Groups') ? 1 : 0;
						$manage_perms = $user->has_cap('SPF Manage Permissions') ? 1 : 0;
						$manage_comps = $user->has_cap('SPF Manage Components') ? 1 : 0;
						$manage_users = $user->has_cap('SPF Manage Users') ? 1 : 0;
						$manage_profiles = $user->has_cap('SPF Manage Profiles') ? 1 : 0;
						$manage_admins = $user->has_cap('SPF Manage Admins') ? 1 : 0;
						$manage_tools = $user->has_cap('SPF Manage Toolbox') ? 1 : 0;
						$manage_plugins = $user->has_cap('SPF Manage Plugins') ? 1 : 0;
						$manage_themes = $user->has_cap('SPF Manage Themes') ? 1 : 0;
						?>
					<tr>
						<td align="center"><?php echo $admin['id']; ?></td>
						<td>
							<strong><?php echo sp_filter_name_display($admin['display_name']); ?></strong>
							<input type="hidden" name="uids[]" value="<?php echo $admin['id']; ?>" />
						</td>
						<td align="center"></td>
						<td align="center">
							<table width="100%" class="sfsubsubtable">
								<tr>
									<td>
										<?php spa_render_caps_checkbox(spa_text('Manage Options'), 'manage-opts['.$admin['id'].']', $manage_opts, $admin['id']); ?>
										<input type="hidden" name="old-opts[<?php echo $admin['id'] ?>]" value="<?php echo $manage_opts; ?>" />
									</td>
									<td>
										<?php spa_render_caps_checkbox(spa_text('Manage Forums'), 'manage-forums['.$admin['id'].']', $manage_forums, $admin['id']); ?>
										<input type="hidden" name="old-forums[<?php echo $admin['id'] ?>]" value="<?php echo $manage_forums; ?>" />
									</td>
									<td>
										<?php spa_render_caps_checkbox(spa_text('Manage User Groups'), 'manage-ugs['.$admin['id'].']', $manage_ugs, $admin['id']); ?>
										<input type="hidden" name="old-ugs[<?php echo $admin['id'] ?>]" value="<?php echo $manage_ugs; ?>" />
									</td>
								</tr>
								<tr>
									<td>
										<?php spa_render_caps_checkbox(spa_text('Manage Permissions'), 'manage-perms['.$admin['id'].']', $manage_perms, $admin['id']); ?>
										<input type="hidden" name="old-perms[<?php echo $admin['id'] ?>]" value="<?php echo $manage_perms; ?>" />
									</td>
									<td>
										<?php spa_render_caps_checkbox(spa_text('Manage Components'), 'manage-comps['.$admin['id'].']', $manage_comps, $admin['id']); ?>
										<input type="hidden" name="old-comps[<?php echo $admin['id'] ?>]" value="<?php echo $manage_comps; ?>" />
									</td>
									<td>
										<?php spa_render_caps_checkbox(spa_text('Manage Plugins'), 'manage-plugins['.$admin['id'].']', $manage_plugins, $admin['id']); ?>
										<input type="hidden" name="old-plugins[<?php echo $admin['id'] ?>]" value="<?php echo $manage_plugins; ?>" />
									</td>
								</tr>
								<tr>
									<td>
										<?php spa_render_caps_checkbox(spa_text('Manage Users'), 'manage-users['.$admin['id'].']', $manage_users, $admin['id']); ?>
										<input type="hidden" name="old-users[<?php echo $admin['id'] ?>]" value="<?php echo $manage_users; ?>" />
									</td>
									<td>
										<?php spa_render_caps_checkbox(spa_text('Manage Profiles'), 'manage-profiles['.$admin['id'].']', $manage_profiles, $admin['id']); ?>
										<input type="hidden" name="old-profiles[<?php echo $admin['id'] ?>]" value="<?php echo $manage_profiles; ?>" />
									</td>
									<td>
<?php
										if ($admin['id'] == $spThisUser->ID) {
											spa_etext('Manage Admins');
?>
											<input type="hidden" name="manage-admins[<?php echo $admin['id'] ?>]" value="<?php echo $manage_admins; ?>" />
											<img src="<?php echo SFADMINIMAGES.'sp_Locked.png'; ?>" alt="" style="vertical-align:middle;padding-left:45px;" />
<?php
										} else {
											spa_render_caps_checkbox(spa_text('Manage Admins'), 'manage-admins['.$admin['id'].']', $manage_admins, $admin['id']);
										}
?>
										<input type="hidden" name="old-admins[<?php echo $admin['id'] ?>]" value="<?php echo $manage_admins; ?>" />
									</td>
								</tr>
								<tr>
									<td>
										<?php spa_render_caps_checkbox(spa_text('Manage Toolbox'), 'manage-tools['.$admin['id'].']', $manage_tools, $admin['id']); ?>
										<input type="hidden" name="old-tools[<?php echo $admin['id'] ?>]" value="<?php echo $manage_tools; ?>" />
									</td>
									<td>
										<?php spa_render_caps_checkbox(spa_text('Manage Themes'), 'manage-themes['.$admin['id'].']', $manage_themes, $admin['id']); ?>
										<input type="hidden" name="old-themes[<?php echo $admin['id'] ?>]" value="<?php echo $manage_themes; ?>" />
									</td>
								</tr>
								<?php do_action('sph_admin_caps_list', $user); ?>
							</table>
						</td>
						<td align="center">
                            <?php if ($admin['id'] != $spThisUser->ID) spa_render_caps_checkbox(spa_text('Remove All'), 'remove-admin['.$admin['id'].']', '', $admin['id']); ?>
                        </td>
					</tr>
				<?php } ?>
				</table>

<?php
			spa_paint_close_fieldset(false);
		spa_paint_close_panel();
	spa_paint_close_tab();

?>
	<div class="sfform-submit-bar">
	<input type="submit" class="sfform-panel-button" id="savecaps" name="savecaps" value="<?php spa_etext('Update Admin Capabilities'); ?>" />
	</div>
	</form>

	<div class="sfform-panel-spacer"></div>
<?php
	}

    $ahahURL = SFHOMEURL.'index.php?sp_ahah=admins-loader&amp;sfnonce='.wp_create_nonce('forum-ahah').'&amp;saveform=addadmin';
?>
	<form action="<?php echo $ahahURL; ?>" method="post" id="sfaddadmins" name="sfaddadmins">
	<?php echo sp_create_nonce('forum-adminform_sfaddadmins'); ?>

<?php

	spa_paint_open_tab(spa_text('Manage Admins').' - '.spa_text('Add Admins'));

	spa_paint_open_panel();
	spa_paint_open_fieldset(spa_text('Add New Admins'), false, '', false);
?>
	<table align="center" class="forum-table" cellpadding="0" cellspacing="0">
		<tr>
			<th align="center" width="500"><?php spa_etext('Select New Admin Users'); ?></th>
			<th align="center" width="50" scope="col"></th>
			<th align="center" width="250" scope="col"><?php spa_etext('Select New Admin Capabilities') ?></th>
		</tr>
		<tr>
			<td align="center">
				<p align="center"><?php spa_etext("Select members to make Admins (use CONTROL for multiple users)") ?></p>
<?php
            	$from = esc_js(spa_text('Eligible Members'));
            	$to = esc_js(spa_text('Selected Members'));
                $action = 'addadmin';
            	include_once(SF_PLUGIN_DIR.'/admin/library/ahah/spa-ahah-multiselect.php');
?>
				<div class="clearboth"></div>
			</td>
			<td></td>
			<td>
				<table class="form-table">
					<tr><td class="sflabel"><?php spa_render_caps_checkbox(spa_text('Manage Options'), 'add-opts', 0); ?></td></tr>
					<tr><td class="sflabel"><?php spa_render_caps_checkbox(spa_text('Manage Forums'), 'add-forums', 0); ?></td></tr>
					<tr><td class="sflabel"><?php spa_render_caps_checkbox(spa_text('Manage User Groups'), 'add-ugs', 0); ?></td></tr>
					<tr><td class="sflabel"><?php spa_render_caps_checkbox(spa_text('Manage Permissions'), 'add-perms', 0); ?></td></tr>
					<tr><td class="sflabel"><?php spa_render_caps_checkbox(spa_text('Manage Components'), 'add-comps', 0); ?></td></tr>
					<tr><td class="sflabel"><?php spa_render_caps_checkbox(spa_text('Manage Users'), 'add-users', 0); ?></td></tr>
					<tr><td class="sflabel"><?php spa_render_caps_checkbox(spa_text('Manage Profiles'), 'add-profiles', 0); ?></td></tr>
					<tr><td class="sflabel"><?php spa_render_caps_checkbox(spa_text('Manage Admins'), 'add-admins', 0); ?></td></tr>
					<tr><td class="sflabel"><?php spa_render_caps_checkbox(spa_text('Manage Toolbox'), 'add-tools', 0); ?></td></tr>
					<tr><td class="sflabel"><?php spa_render_caps_checkbox(spa_text('Manage Plugins'), 'add-plugins', 0); ?></td></tr>
					<tr><td class="sflabel"><?php spa_render_caps_checkbox(spa_text('Manage Themes'), 'add-themes', 0); ?></td></tr>
					<?php do_action('sph_admin_caps_form', $user); ?>
				</table>
			</td>
		</tr>
	</table>

<?php
		spa_paint_close_fieldset(false);
		spa_paint_close_panel();
	spa_paint_open_panel();
	spa_paint_open_fieldset(spa_text('WP Admins But Not SPF Admins'), false, '', false);

?>

	<table align="center" class="sfmaintable" cellpadding="0" cellspacing="0" style="width:auto">
		<tr>
			<th align="center" width="30" scope="col"></th>
			<th align="center"><?php spa_etext('User ID'); ?></th>
			<th align="center" scope="col"><?php spa_etext('Admin Name') ?></th>
			<th align="center" width="30" scope="col"></th>
		</tr>
<?php
		$wp_admins = new SP_User_Search('', '', 'administrator');
		$is_users = false;
		for ($x=0; $x<count($wp_admins->results); $x++) {
			$username = spdb_table(SFMEMBERS, 'admin=0 AND user_id='.$wp_admins->results[$x], 'display_name');
			if ($username) {
				echo '<tr>';
				echo '<td></td>';
				echo '<td align="center">';
				echo $wp_admins->results[$x];
				echo '</td>';
				echo '<td>';
				echo esc_html($username);
				echo '</td>';
				echo '<td></td>';
				echo '</tr>';
				$is_users = true;
			}
		}
		if (!$is_users) {
			echo '<tr>';
			echo '<td></td>';
			echo '<td colspan="2">';
			spa_etext('No WP administrators that are not SPF admins were found');
			echo '</td>';
			echo '<td></td>';
			echo '</tr>';
		}
	?>
	</table>

<?php
		spa_paint_close_fieldset(false);
		spa_paint_close_panel();

		do_action('sph_admins_manage_panel');

	spa_paint_close_tab();
?>
	<div class="sfform-submit-bar">
	<input type="submit" class="sfform-panel-button" id="savenew" name="savenew" value="<?php spa_etext('Add New Admins'); ?>" />
	</div>
	</form>
<?php
}

function spa_render_caps_checkbox($label, $name, $value, $user=0) {
	$pos = strpos($name, '[');
	if ($pos) $thisid = substr($name, 0, $pos).$user; else $thisid = $name.$user;
	echo "<label for='sf-$thisid'>$label</label>";
	echo "<input type='checkbox' name='$name' id='sf-$thisid' ";
	if ($value) echo 'checked="checked" ';
	echo '/>';
}
?>