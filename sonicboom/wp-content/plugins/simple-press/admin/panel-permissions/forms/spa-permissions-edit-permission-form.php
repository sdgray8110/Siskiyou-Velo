<?php
/*
Simple:Press
Admin Permissions Edit Permission Form
$LastChangedDate: 2012-01-27 09:11:58 -0700 (Fri, 27 Jan 2012) $
$Rev: 7796 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

# function to display the edit permission set form.  It is hidden until the edit permission set link is clicked
function spa_permissions_edit_permission_form($role_id)
{
    global $sfglobals;
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#sfroleedit<?php echo $role_id; ?>').ajaxForm({
		target: '#sfmsgspot',
		success: function() {
			jQuery('#sfreloadpb').click();
			jQuery('#sfmsgspot').fadeIn();
			jQuery('#sfmsgspot').fadeOut(6000);
		}
	});
	jQuery(function(jQuery){vtip();})
});
</script>
<?php
	# Get correct tooltips file
	$lang = WPLANG;
	if (empty($lang)) $lang = 'en';
	$ttpath = SPHELP.'admin/tooltips/admin-permissions-tips-'.$lang.'.php';
	if (file_exists($ttpath) == false) $ttpath = SPHELP.'admin/tooltips/admin-permissions-tips-en.php';
	if (file_exists($ttpath)) include_once($ttpath);

	$role = spa_get_role_row($role_id);

	spa_paint_options_init();
    $ahahURL = SFHOMEURL."index.php?sp_ahah=permissions-loader&amp;sfnonce=".wp_create_nonce('forum-ahah')."&amp;saveform=editperm";
?>
	<form action="<?php echo $ahahURL; ?>" method="post" id="sfroleedit<?php echo $role->role_id; ?>" name="sfroleedit<?php echo $role->role_id; ?>">
<?php
		echo sp_create_nonce('forum-adminform_roleedit');
		spa_paint_open_tab(spa_text('Permissions')." - ".spa_text('Manage Permissions'));
			spa_paint_open_panel();
				spa_paint_open_fieldset(spa_text('Edit Permission'), 'true', 'edit-master-permission-set', false);
?>
					<input type="hidden" name="role_id" value="<?php echo $role->role_id; ?>" />
					<table class="form-table">
						<tr>
							<td class="sflabel"><?php spa_etext("Permission Set Name") ?>:&nbsp;&nbsp;<br />
							<input type="text" class="sfpostcontrol" size="45" name="role_name" value="<?php echo sp_filter_title_display($role->role_name); ?>" /></td>
							<td class="sflabel"><?php spa_etext("Permission Set Description") ?>:&nbsp;&nbsp;<br/>
							<input type="text" class="sfpostcontrol" size="85" name="role_desc" value="<?php echo sp_filter_title_display($role->role_desc); ?>" /></td>
						</tr>
					</table>

					<br /><p><strong><?php spa_etext("Permission Set Actions") ?>:</strong></p>
					<?php
					echo '<p><img src="'.SFADMINIMAGES.'sp_GuestPerm.png" alt="" width="16" height="16" align="top" />';
					echo '<small>&nbsp;'.spa_text('Note: Action settings displaying this icon will be ignored for Guest Users').'</small>';
					echo '&nbsp;&nbsp;&nbsp;<img src="'.SFADMINIMAGES.'sp_GlobalPerm.png" alt="" width="16" height="16" align="top" />';
					echo '<small>&nbsp;'.spa_text('Note: Action settings displaying this icon require enabling to use').'</small></p>';
					?>

					<table class="outershell" width="100%" border="0" cellspacing="15">
					<tr>
<?php
                        sp_build_site_auths_cache();

						$role_auths = maybe_unserialize($role->role_auths);
						$items = count($sfglobals['auths_map']);
						$cols = 3;
						$rows  = ($items / $cols);
						$lastrow = $rows;
						$lastcol = $cols;
						$curcol = 0;
						if (!is_int($rows)) {
							$rows = (intval($rows) + 1);
							$lastrow = $rows - 1;
							$lastcol = ($items % $cols);
						}
						$thisrow = 0;

						foreach ($sfglobals['auths_map'] as $auth_name => $auth_id) {
							$button = 'b-'.$auth_id;
							$checked = '';
							if (isset($role_auths[$auth_id]) && $role_auths[$auth_id]) $checked = ' checked="checked"';
							if ($sfglobals['auths'][$auth_id]->ignored || $sfglobals['auths'][$auth_id]->enabling) {
								$span = '';
							} else {
								$span = ' colspan="2" ';
							}

							if ($thisrow == 0) {
								$curcol++;
?>
								<td width="33%" style="vertical-align:top">
								<table class="form-table table-cbox">
<?php
							}
?>
								<tr>
									<td class="sflabel">
									<label for="sfR<?php echo $role->role_id.$button; ?>" class="sflabel">
									<img align="middle" style="float: right; border: 0pt none ; margin: 2px 5px 5px 3px;" class="vtip" title="<?php echo $tooltips[$auth_name]; ?>" src="<?php echo SFADMINIMAGES; ?>sp_Information.png" alt="" />
									<?php spa_etext($sfglobals['auths'][$auth_id]->auth_desc); ?></label>
									<input type="checkbox" name="<?php echo $button; ?>" id="sfR<?php echo $role->role_id.$button; ?>"<?php echo $checked; ?>  />
									<?php if ($span == '') { ?>
									<td align="center">
									<?php } ?>
<?php
									if ($span == '') {
										if ($sfglobals["auths"][$auth_id]->enabling) {
											echo '<img src="'.SFADMINIMAGES.'sp_GlobalPerm.png" alt="" width="16" height="16" title="'.spa_text('Requires Enabling').'" />';
										}
										echo '<img src="'.SFADMINIMAGES.'sp_GuestPerm.png" alt="" width="16" height="16" title="'.spa_text('Ignored for Guests').'" />';
										echo '</td>';
									}
									?>
								</tr>
<?php
							$thisrow++;
							if (($curcol <= $lastcol && $thisrow == $rows) || ($curcol > $lastcol && $thisrow == $lastrow)) {
?>
								</table>
								</td>
<?php
								$thisrow=0;
							}
						} ?>
						</tr>
					</table>
<?php
				spa_paint_close_fieldset(false);
			spa_paint_close_panel();
			do_action('sph_perm_edit_perm_panel');
		spa_paint_close_tab();
?>
		<div class="sfform-submit-bar">
		<input type="submit" class="sfform-panel-button" id="sfpermedit<?php echo $role->role_id; ?>" name="sfpermedit<?php echo $role->role_id; ?>" value="<?php spa_etext('Update Permission'); ?>" />
		<input type="button" class="sfform-panel-button" onclick="javascript:jQuery('#perm-<?php echo $role->role_id; ?>').html('');" id="sfpermedit<?php echo $role->role_id; ?>" name="editpermcancel<?php echo $role->role_id; ?>" value="<?php spa_etext('Cancel'); ?>" />
		</div>
		</form>

	<div class="sfform-panel-spacer"></div>
<?php
}
?>