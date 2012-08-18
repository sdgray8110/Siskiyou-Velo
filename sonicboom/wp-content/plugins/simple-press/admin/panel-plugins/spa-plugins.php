<?php
/*
Simple:Press
Admin Plugins
$LastChangedDate: 2012-04-23 21:13:06 -0700 (Mon, 23 Apr 2012) $
$Rev: 8447 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

# Check Whether User Can Manage Admins
global $SPSTATUS;

if (!sp_current_user_can('SPF Manage Plugins')) {
	if (!is_user_logged_in()) {
		spa_etext('Access denied - are you logged in?');
	} else {
		spa_etext('Access denied - you do not have permission');
	}
	die();
}

include_once(SF_PLUGIN_DIR.'/admin/panel-plugins/spa-plugins-display.php');
include_once(SF_PLUGIN_DIR.'/admin/panel-plugins/support/spa-plugins-prepare.php');
include_once(SF_PLUGIN_DIR.'/admin/panel-plugins/support/spa-plugins-save.php');
include_once(SF_PLUGIN_DIR.'/admin/library/spa-tab-support.php');
include_once(SPAPI.'sp-api-plugins.php');
include_once(SPAPI.'sp-api-themes.php');

if ($SPSTATUS != 'ok') {
    include_once(SPLOADINSTALL);
    die();
}

global $adminhelpfile;
$adminhelpfile = 'admin-plugins';
# --------------------------------------------------------------------

# was there a plugin action?
if (isset($_GET['action'])) spa_save_plugin_activation();

if (isset($_GET['tab']) ? $tab=$_GET['tab'] : $tab='list');
spa_panel_header();
spa_render_plugins_panel($tab);
spa_panel_footer();

if (isset($_GET['action'])) {
	$action = $_GET['action'];
	$title  = $_GET['title'];
	if ($action == 'activate')		$msg = $title.' '.spa_text('Plugin').' <strong>'.spa_text('Activated').'</strong>';
	if ($action == 'deactivate')	$msg = $title.' '.spa_text('Plugin').' <strong>'.spa_text('Deactivated').'</strong>';
	if ($action == 'uninstall')		$msg = $title.' '.spa_text('Plugin').' <strong>'.spa_text('Deactivated and Uninstalled').'</strong>';
	$msg = apply_filters('sph_plugin_message', $msg);

	?>
	<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery("#sfmsgspot").fadeIn("fast");
		jQuery("#sfmsgspot").html("<?php echo($msg); ?>");
		jQuery("#sfmsgspot").fadeOut(2000);
		window.location = '<?php echo(SFADMINPLUGINS); ?>';
	});
	</script>
	<?php
}

?>