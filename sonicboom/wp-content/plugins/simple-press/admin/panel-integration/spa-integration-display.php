<?php
/*
Simple:Press
Admin integration Display Rendering
$LastChangedDate: 2011-10-29 16:45:05 -0700 (Sat, 29 Oct 2011) $
$Rev: 7262 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

function spa_render_integration_panel($formid) {
?>
	<div class="clearboth"></div>

	<div class="wrap sfatag">
		<?php
			spa_render_sidemenu();
		?>
		<div id='sfmsgspot'></div>
		<div id="sfmaincontainer">
			<?php spa_render_integration_container($formid); ?>
		</div>
			<div class="clearboth"></div>
	</div>
<?php
}

function spa_render_integration_container($formid) {
	switch($formid) {
		case 'page':
			include_once(SF_PLUGIN_DIR.'/admin/panel-integration/forms/spa-integration-page-form.php');
			spa_integration_page_form();
			break;

		case 'storage':
			include_once(SF_PLUGIN_DIR.'/admin/panel-integration/forms/spa-integration-storage-form.php');
			spa_integration_storage_form();
			break;

        # leave this for plugins to add to this panel
		case 'plugin':
			include_once(SF_PLUGIN_DIR.'/admin/panel-plugins/forms/spa-plugins-user-form.php');
            $admin = (isset($_GET['admin'])) ? sp_esc_str($_GET['admin']) : '';
            $save = (isset($_GET['save'])) ? sp_esc_str($_GET['save']) : '';
            $form = (isset($_GET['form'])) ? sp_esc_int($_GET['form']) : '';
            $reload = (isset($_GET['reload'])) ? sp_esc_str($_GET['reload']) : '';
			spa_plugins_user_form($admin, $save, $form, $reload);
			break;
	}
}
?>