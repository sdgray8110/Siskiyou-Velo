<?php
/*
Simple:Press
Admin Forums Global Permission Form
$LastChangedDate: 2011-07-18 06:05:28 -0700 (Mon, 18 Jul 2011) $
$Rev: 6712 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

# function to display the add global permission set form. It is hidden until user clicks the add global permission set link
function spa_forums_global_perm_form()
{
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#sfnewglobalpermission').ajaxForm({
		target: '#sfmsgspot',
		success: function() {
			jQuery('#sfreloadfb').click();
			jQuery('#sfmsgspot').fadeIn();
			jQuery('#sfmsgspot').fadeOut(6000);
		}
	});
});
</script>
<?php

	spa_paint_options_init();

    $ahahURL = SFHOMEURL.'index.php?sp_ahah=forums-loader&amp;sfnonce='.wp_create_nonce('forum-ahah').'&amp;saveform=globalperm';
?>
	<form action="<?php echo $ahahURL; ?>" method="post" id="sfnewglobalpermission" name="sfnewglobalpermission">
<?php
		echo sp_create_nonce('forum-adminform_globalpermissionnew');
		spa_paint_open_tab(spa_text('Forums').' - '.spa_text('Add Global Permission Set'));
			spa_paint_open_panel();
				spa_paint_open_fieldset(spa_text('Add a User Group Permission Set to All Forums'), 'true', 'add-a-user-group-permission-set-to-all-forums', false);
?>
					<table class="form-table">
						<tr>
							<td class="sflabel"><?php spa_display_usergroup_select(); ?></td>
						</tr><tr>
							<td class="sflabel"><?php spa_display_permission_select(); ?></td>
						</tr>
					</table>
					<p><?php spa_etext('Caution:  Any current permission sets for the selected usergroup for ANY forum may be overwritten') ?></p>
<?php
				spa_paint_close_fieldset(false);
			spa_paint_close_panel();
			do_action('sph_forums_global_perm_panel');
		spa_paint_close_tab();
?>
		<div class="sfform-submit-bar">
		<input type="submit" class="sfform-panel-button" id="saveit" name="saveit" value="<?php spa_etext('Add Global Permission'); ?>" />
		</div>
	</form>

	<div class="sfform-panel-spacer"></div>
<?php
}
?>