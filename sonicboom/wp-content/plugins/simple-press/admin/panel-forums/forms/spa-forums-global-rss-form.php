<?php
/*
Simple:Press
Admin Forums Global RSS Settings Form
$LastChangedDate: 2011-09-09 12:28:24 -0700 (Fri, 09 Sep 2011) $
$Rev: 7034 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

# function to display the add global permission set form. It is hidden until user clicks the add global permission set link
function spa_forums_global_rss_form() {

?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#sfnewglobalrss').ajaxForm({
		target: '#sfmsgspot',
		success: function() {
			jQuery('#sfreloadfd').click();
			jQuery('#sfmsgspot').fadeIn();
			jQuery('#sfmsgspot').fadeOut(6000);
		}
	});
});
</script>
<?php

	spa_paint_options_init();

	$ahahURL = SFHOMEURL.'index.php?sp_ahah=forums-loader&amp;sfnonce='.wp_create_nonce('forum-ahah').'&amp;saveform=globalrss';
?>
	<form action="<?php echo $ahahURL; ?>" method="post" id="sfnewglobalrss" name="sfnewglobalrss">
<?php
		echo sp_create_nonce('forum-adminform_globalrss');
		spa_paint_open_tab(spa_text('Forums').' - '.spa_text('Global RSS Settings'));
			spa_paint_open_panel();
			spa_paint_open_fieldset(spa_text('Globally Enable/Disable RSS Feeds'), true, 'global-rss', true);
?>
				<tr>
					<td class="sflabel"><?php spa_etext('Replacement external RSS URL for all RSS') ?>:<br /><?php spa_etext('Default'); ?>: <strong><?php echo sp_build_url('', '', 0, 0, 0, 1); ?></strong></td>
					<td><input class="sfpostcontrol" type="text" name="sfallrssurl" size="45" value="<?php echo sp_get_option('sfallRSSurl'); ?>" /></td>
				</tr>
<?php
				echo '<tr><td colspan="2" class="sflabel">';
						$base = SFHOMEURL.'index.php?sp_ahah=forums-loader&amp;sfnonce='.wp_create_nonce('forum-ahah');
						$target = 'sfallrss';
						$image = SFADMINIMAGES;

						$rss_count = spdb_count(SFFORUMS, "forum_rss_private=0");
						spa_etext('Active forum RSS feeds').': '.$rss_count.'&nbsp;&nbsp;&nbsp;&nbsp;';
?>
						<input type="button" class="button button-highlighted" value="<?php echo spa_text('Disable All RSS Feeds'); ?>" onclick="spjLoadForm('globalrssset', '<?php echo $base; ?>', '<?php echo $target; ?>', '<?php echo $image; ?>', '1', '1');" />
						<input type="button" class="button button-highlighted" value="<?php echo spa_text('Enable All RSS Feeds'); ?>" onclick="spjLoadForm('globalrssset', '<?php echo $base; ?>', '<?php echo $target; ?>', '<?php echo $image; ?>', '0', '1');" />
<?php
				echo '</td></tr>';
				echo '<tr class="sfinline-form">  <!-- This row will hold ahah forms for the all rss -->';
				echo '<td colspan="2" style="height:0px">';
				echo '<div id="sfallrss">';
				echo '</div>';
				echo '</td>';
				echo '</tr>';
			spa_paint_close_fieldset();
			spa_paint_close_panel();
			do_action('sph_forums_global_rss_panel');
		spa_paint_close_tab();
?>
		<div class="sfform-submit-bar">
		<input type="submit" class="sfform-panel-button" id="saveit" name="saveit" value="<?php spa_etext('Update Global RSS Settings'); ?>" />
		</div>
	</form>

	<div class="sfform-panel-spacer"></div>
<?php
}
?>