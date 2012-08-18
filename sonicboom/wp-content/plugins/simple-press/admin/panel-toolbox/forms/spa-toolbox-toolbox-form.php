<?php
/*
Simple:Press
Admin Toolbox Toolbox Form
$LastChangedDate: 2011-09-08 22:10:36 -0700 (Thu, 08 Sep 2011) $
$Rev: 7028 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

function spa_toolbox_toolbox_form() {
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#sftoolboxform').ajaxForm({
		target: '#sfmsgspot',
		success: function() {
			jQuery('#sfmsgspot').fadeIn();
			jQuery('#sfmsgspot').fadeOut(6000);
		}
	});
});
</script>
<?php

	$sfoptions = spa_get_toolbox_data();

    $ahahURL = SFHOMEURL."index.php?sp_ahah=toolbox-loader&amp;sfnonce=".wp_create_nonce('forum-ahah')."&amp;saveform=toolbox";
?>
	<form action="<?php echo $ahahURL; ?>" method="post" id="sftoolboxform" name="sftoolbox">
	<?php echo sp_create_nonce('forum-adminform_toolbox'); ?>
<?php

	spa_paint_options_init();

#== TOOLBOX Tab ============================================================

	spa_paint_open_tab(spa_text('Toolbox')." - ".spa_text('Toolbox'));

		spa_paint_open_panel();
			spa_paint_open_fieldset(spa_text('Current Version/Build'), false, '');
            	echo '<tr valign="top">';
            	echo '<td colspan="2" class="sflabel">';
            	$version = spa_text('Version:').'&nbsp;<strong>'.sp_get_option('sfversion').'</strong>';
            	$build = spa_text('Build:  ').'&nbsp;<strong>'.sp_get_option('sfbuild').'</strong>';
            	echo $version.'&nbsp;&nbsp;&nbsp;&nbsp;'.$build;
            	echo '</td>';
                echo '</tr>';
			spa_paint_close_fieldset();
		spa_paint_close_panel();

		do_action('sph_toolbox_toolbox_left_panel');
		spa_paint_tab_right_cell();

		spa_paint_open_panel();
			spa_paint_open_fieldset(spa_text('Modify Build Number'), true, 'modify-build-number');
				echo '<tr><td colspan="2"><div class="sfoptionerror">'.spa_text('WARNING: This value should not be changed unless requested by the Simple:Press team in the support forum as it may cause the install/upgrade script to be re-run.').'</div></td></tr>';
				spa_paint_input(spa_text('Build number'), "sfbuild", sp_get_option('sfbuild'), false, false);
				spa_paint_checkbox(spa_text('Force upgrade to build number'), "sfforceupgrade", $sfoptions['sfforceupgrade']);
			spa_paint_close_fieldset();
		spa_paint_close_panel();

		do_action('sph_toolbox_toolbox_right_panel');
	spa_paint_close_tab();

?>
	<div class="sfform-submit-bar">
	<input type="submit" class="sfform-panel-button" id="saveit" name="saveit" value="<?php spa_etext('Update Toolbox'); ?>" />
	</div>
	</form>
<?php
}

?>