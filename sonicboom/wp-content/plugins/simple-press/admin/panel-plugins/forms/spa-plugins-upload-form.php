<?php
/*
Simple:Press
Admin plugins uploader
$LastChangedDate: 2012-04-13 16:04:15 -0700 (Fri, 13 Apr 2012) $
$Rev: 8322 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

function spa_plugins_upload_form() {
	spa_paint_options_init();
	spa_paint_open_tab(spa_text('Upload Plugin')." - ".spa_text('Upload a Simple:Press Plugin'));
	spa_paint_open_panel();
        spa_paint_open_fieldset(spa_text('Upload Plugin'), true, 'upload-plugin', false);
            echo '<p>'.spa_text('Upload a Simple:Press plugin in .zip format').'</p>';
            echo '<p>'.spa_text('If you have a plugin in a .zip format, you may upload it here').'</p>';
//            $ahahURL = SFHOMEURL.'index.php?sp_ahah=plugins-loader&amp;sfnonce='.wp_create_nonce('forum-ahah').'&amp;saveform=upload';
?>
        	<form method="post" enctype="multipart/form-data" action="<?php echo self_admin_url('update.php?action=upload-sp-plugin'); ?>" id="sfpluginuploadform" name="sfpluginuploadform">
                <?php echo sp_create_nonce('forum-plugin_upload'); ?>
        		<p><input type="file" id="pluginzip" name="pluginzip" /></p>
        		<p><input type="submit" class="button" id="saveit" name="saveit" value="<?php spa_etext('Upload Now') ?>" /></p>
        	</form>
<?php
		spa_paint_close_fieldset();
       
        do_action('sph_plugins_upload_panel');
	spa_paint_close_panel();
	spa_paint_close_tab();
}

?>