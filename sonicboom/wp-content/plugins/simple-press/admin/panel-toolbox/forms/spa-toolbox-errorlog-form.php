<?php
/*
Simple:Press
Admin Toolbox Error Log Form
$LastChangedDate: 2011-09-04 17:43:47 -0700 (Sun, 04 Sep 2011) $
$Rev: 6968 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

function spa_toolbox_errorlog_form()
{
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#sfclearlog').ajaxForm({
		target: '#sfmsgspot',
		success: function() {
			jQuery('#sfreloadel').click();
			jQuery('#sfmsgspot').fadeIn();
			jQuery('#sfmsgspot').fadeOut(6000);
		}
	});
});
</script>
<?php

	$sflog = spa_get_errorlog_data();

	spa_paint_open_tab(spa_text('Toolbox')." - ".spa_text('Error Log'));

			if(!$sflog)
			{
				echo '<p>'.spa_text('There are no Error Log Entries').'</p>';
				return;
			}

			spa_paint_open_fieldset(spa_text('Error Log'), false, '', true);
				echo "<tr>";
				echo "<th>".spa_text('Date')."</th>";
				echo "<th>".spa_text('Type')."</th>";
				echo "<th>".spa_text('Description')."</th>";
				echo "</tr>";

				foreach ($sflog as $log)
				{
					echo "<tr>";
					echo "<td class='sferror'>".sp_date('d', $log['error_date']).'<br />'.sp_date('t', $log['error_date'])."</td>";
					echo "<td class='sferror'>".$log['error_type']."</td>";
					echo "<td class='sferror'>".$log['error_text']."</td>";
					echo "</tr>";
				}
			spa_paint_close_fieldset();
		spa_paint_close_panel();
		do_action('sph_toolbox_error_panel');
	spa_paint_close_tab();

    $ahahURL = SFHOMEURL."index.php?sp_ahah=toolbox-loader&amp;sfnonce=".wp_create_nonce('forum-ahah')."&amp;saveform=sfclearlog";
?>
	<form action="<?php echo $ahahURL; ?>" method="post" id="sfclearlog" name="sfclearlog">
	<?php echo sp_create_nonce('forum-adminform_clearlog'); ?>
	<div class="sfform-submit-bar">
	<input type="submit" class="sfform-panel-button" id="saveit" name="saveit" value="<?php spa_etext('Empty Error Log'); ?>" />
	</div>
	</form>

<?php
}
?>