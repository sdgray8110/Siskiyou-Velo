<?php
/*
Simple:Press
Admin User Groups Delete Member Form
$LastChangedDate: 2011-10-29 20:05:34 -0700 (Sat, 29 Oct 2011) $
$Rev: 7264 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

function spa_usergroups_delete_members_form($usergroup_id)
{
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#sfmemberdel<?php echo $usergroup_id; ?>').ajaxForm({
		target: '#sfmsgspot',
		success: function() {
			jQuery('#sfreloadub').click();
			jQuery('#sfmsgspot').fadeIn();
			jQuery('#sfmsgspot').fadeOut(6000);
		}
	});
});
</script>
<?php
	spa_paint_options_init();

    $ahahURL = SFHOMEURL."index.php?sp_ahah=usergroups-loader&amp;sfnonce=".wp_create_nonce('forum-ahah')."&amp;saveform=delmembers";
?>
	<form action="<?php echo $ahahURL; ?>" method="post" id="sfmemberdel<?php echo $usergroup_id; ?>" name="sfmemberdel<?php echo $usergroup_id ?>">
<?php
		echo sp_create_nonce('forum-adminform_memberdel');
		spa_paint_open_tab(spa_text('User Groups')." - ".spa_text('Manage User Groups'));
			spa_paint_open_panel();
				spa_paint_open_fieldset(spa_text('Delete/Move Members'), 'true', 'move-delete-members', false);
?>
					<input type="hidden" name="usergroupid" value="<?php echo $usergroup_id; ?>" />
					<p><?php spa_etext("Select members to delete/move (use CONTROL for multiple users)") ?></p>
					<p><?php spa_etext("To move members, select a new usergroup") ?></p>
					<?php spa_display_usergroup_select() ?>
<?php
					$from = esc_js(spa_text('Current Members'));
					$to = esc_js(spa_text('Selected Members'));
                    $action = 'delug';
                	include_once(SF_PLUGIN_DIR.'/admin/library/ahah/spa-ahah-multiselect.php');
?>
					<div class="clearboth"></div>
<?php
				spa_paint_close_fieldset(false);
			spa_paint_close_panel();
			do_action('sph_usergroup_delete_member_panel');
		spa_paint_close_tab();
?>
		<div class="sfform-submit-bar">
		<input type="submit" class="sfform-panel-button" id="sfmemberdel<?php echo $usergroup_id; ?>" name="sfmemberdel<?php echo $usergroup_id; ?>" onclick="javascript:jQuery('#dmember_id<?php echo $usergroup_id; ?> option').each(function(i) {jQuery(this).attr('selected', 'selected');});" value="<?php spa_etext('Delete/Move Members'); ?>" />
		<input type="button" class="sfform-panel-button" onclick="javascript:jQuery('#members-<?php echo $usergroup_id; ?>').html('');" id="sfmemberdel<?php echo $usergroup_id; ?>" name="delmemberscancel<?php echo $usergroup_id; ?>" value="<?php spa_etext('Cancel'); ?>" />
		</div>
	</form>

	<div class="sfform-panel-spacer"></div>
<?php
}
?>