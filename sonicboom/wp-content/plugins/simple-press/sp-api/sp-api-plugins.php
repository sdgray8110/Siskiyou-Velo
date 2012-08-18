<?php
/*
Simple:Press
Plugin API Routines
$LastChangedDate: 2012-03-26 10:30:25 -0700 (Mon, 26 Mar 2012) $
$Rev: 8234 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

# ==========================================================================================
#
# 	SITE - This file loads at core level - all page loads
#	SP Plugin Handling
#
# ==========================================================================================

/**
* This function returns an array of plugin files to be included in global scope
*/
# Version: 5.0
function sp_get_active_and_valid_plugins() {
	$plugins = array();
	$active_plugins = (array) sp_get_option('sp_active_plugins', array());

	if (empty($active_plugins)) return $plugins;
	foreach ($active_plugins as $plugin) {
		if (!validate_file($plugin)  # $plugin must validate as file
			&& '.php' == substr($plugin, -4)   # $plugin must end with '.php'
			&& file_exists(SFPLUGINDIR.$plugin )  # $plugin must exist
			)
		$plugins[] = SFPLUGINDIR.$plugin;
	}

	return $plugins;
}

/**
* Check the plugins directory and retrieve all plugin files with plugin data.
*
* Simple:Press only supports plugin files in the base plugins directory
* and in one directory above the plugins directory. The file it looks for
* has the plugin data and must be found in those two locations. It is
* recommended that do keep your plugin files in directories.
*
* The file with the plugin data is the file that will be included and therefore
* needs to have the main execution for the plugin. This does not mean
* everything must be contained in the file and it is recommended that the file
* be split for maintainability. Keep everything in one file for extreme
* optimization purposes.
*/
# Version: 5.0
function sp_get_plugins($plugin_folder = '') {
	$sf_plugins = array();
	$plugin_root = untrailingslashit(SFPLUGINDIR);
	if (!empty($plugin_folder)) $plugin_root.= $plugin_folder;

	# Files in root plugins directory
	$plugins_dir = @opendir($plugin_root);
	$plugin_files = array();
	if ($plugins_dir) {
		while (($file = readdir($plugins_dir)) !== false) {
			if (substr($file, 0, 1) == '.') continue;

			if (is_dir($plugin_root.'/'.$file)) {
				$plugins_subdir = @opendir($plugin_root.'/'.$file);
				if ($plugins_subdir) {
					while (($subfile = readdir($plugins_subdir)) !== false) {
						if (substr($subfile, 0, 1) == '.') continue;
						if (substr($subfile, -4) == '.php') $plugin_files[] = "$file/$subfile";
					}
				}
			} else {
				if (substr($file, -4) == '.php') $plugin_files[] = $file;
			}
		}
	} else {
		return $sf_plugins;
	}

	@closedir($plugins_dir);
	@closedir($plugins_subdir);

	if (empty($plugin_files)) return $sf_plugins;
	foreach ($plugin_files as $plugin_file) {
		if (!is_readable("$plugin_root/$plugin_file")) continue;
		$plugin_data = sp_get_plugin_data("$plugin_root/$plugin_file", false, false); # Do not apply markup/translate as it'll be cached.
		if (empty($plugin_data['Name'])) continue;
		$sf_plugins[plugin_basename($plugin_file)] = $plugin_data;
	}
	uasort($sf_plugins, create_function('$a, $b', 'return strnatcasecmp( $a["Name"], $b["Name"]);'));
	return $sf_plugins;
}

/**
* Parse the simple:press plugin contents to retrieve plugin's metadata.
*
* The metadata of the plugin's data searches for the following in the plugin's
* header. All plugin data must be on its own line. For plugin description, it
* must not have any newlines or only parts of the description will be displayed
* and the same goes for the plugin data. The below is formatted for printing.
*
* Plugin Name: Name of Plugin
* Plugin URI: Link to plugin information
* Description: Plugin Description
* Author: Plugin author's name
* Author URI: Link to the author's web site
* Version: Must be set in the plugin for WordPress 2.3+
* Text Domain: Optional. Unique identifier, should be same as the one used in
*		plugin_text_domain()
*
* Plugin data returned array contains the following:
*		'Name' - Name of the plugin, must be unique.
*		'PluginURI' - Plugin web site address.
*		'Version' - The plugin version number.
*		'Description' - Description of what the plugin does and/or notes
*		from the author.
*		'Author' - The author's name
*		'AuthorURI' - The authors web site address.
*		'TextDomain' - Plugin's text domain for localization.
*
* The first 8kB of the file will be pulled in and if the plugin data is not
* within that first 8kB, then the plugin author should correct their plugin
* and move the plugin data headers to the top.
*
* The plugin file is assumed to have permissions to allow for scripts to read
* the file. This is not checked however and the file is only opened for
* reading.
*/
# Version: 5.0
function sp_get_plugin_data($plugin_file, $markup = true, $translate = true) {
	$default_headers = array(
		'Name' => 'Simple:Press Plugin Title',
		'PluginURI' => 'Plugin URI',
		'Version' => 'Version',
		'Description' => 'Description',
		'Author' => 'Author',
		'AuthorURI' => 'Author URI',
	);
	$plugin_data = get_file_data($plugin_file, $default_headers, 'sp-plugin');

	$allowedtags = array(
		'a'       => array('href' => array(), 'title' => array()),
		'abbr'    => array('title' => array()),
		'acronym' => array('title' => array()),
		'code'    => array(),
		'em'      => array(),
		'strong'  => array(),
	);

	$plugin_data['Name']        = wp_kses($plugin_data['Name'],        $allowedtags);
	$plugin_data['Version']     = wp_kses($plugin_data['Version'],     $allowedtags);
	$plugin_data['Description'] = wp_kses($plugin_data['Description'], $allowedtags);
	$plugin_data['Author']      = wp_kses($plugin_data['Author'],      $allowedtags);

	return $plugin_data;
}

/**
* Attempts activation of plugin in a "sandbox" and redirects on success.
*
* A plugin that is already activated will not attempt to be activated again.
*/
# Version: 5.0
function sp_activate_sp_plugin($plugin) {
    $mess = '';
    $plugin  = sp_plugin_basename(trim($plugin));
   	$current = sp_get_option('sp_active_plugins', array());
    $valid = sp_validate_plugin($plugin);
    if (is_wp_error($valid)) return sp_text('An error occurred activating the plugin');

    if (!in_array($plugin, $current)) {
    	include(SFPLUGINDIR.$plugin);
    	do_action('sph_activate_sp_plugin', trim($plugin));
   		$current[] = $plugin;
   		sort($current);
   		sp_update_option('sp_active_plugins', $current);
    	do_action('sph_activate_'.trim($plugin));
    	do_action('sph_activated_sp_plugin', trim($plugin));

        $mess = sp_text('Plugin successfully activated');
    } else {
        $mess = sp_text('Plugin is already active');
    }
    return $mess;
}

/**
 * Deactivate a single plugin or multiple plugins.
 *
 * The deactivation hook is disabled by the plugin upgrader by using the $silent
 * parameter.
*/
# Version: 5.0
function sp_deactivate_sp_plugin($plugins, $silent = false) {
	$current = sp_get_option('sp_active_plugins', array());
	$do_blog = false;

	foreach ((array) $plugins as $plugin) {
		$plugin = sp_plugin_basename($plugin);
		if (!sp_is_plugin_active($plugin)) continue;
		if (!$silent) do_action('sph_deactivate_sp_plugin', trim($plugin));

		# Deactivate for this blog only
		$key = array_search($plugin, (array) $current);
		if (false !== $key) {
			$do_blog = true;
			array_splice($current, $key, 1);
		}

		# Used by Plugin updater to internally deactivate plugin, however, not to notify plugins of the fact to prevent plugin output.
		if (!$silent) {
			do_action('sph_deactivate_'.trim($plugin));
			do_action('sph_deactivated_sp_plugin', trim($plugin));
		}
	}
	if ($do_blog) sp_update_option('sp_active_plugins', $current);
}

/**
*  This function gets the basename of a plugin.
*  This method extracts the name of a plugin from its filename.
*/
# Version: 5.0
function sp_plugin_basename($file) {
	$file = str_replace('\\','/',$file); # sanitize for Win32 installs
	$file = preg_replace('|/+|','/', $file); # remove any duplicate slash
	$plugin_dir = str_replace('\\','/',SFPLUGINDIR); # sanitize for Win32 installs
	$plugin_dir = preg_replace('|/+|','/', $plugin_dir); # remove any duplicate slash
	$file = preg_replace('#^'.preg_quote($plugin_dir, '#').'/#','',$file); # get relative path from plugins dir
	$file = trim($file, '/');
	return $file;
}

/**
* Checks whether the plugin is active by checking the active plugins list.
*/
# Version: 5.0
function sp_is_plugin_active($plugin) {
	return in_array($plugin, (array) sp_get_option('sp_active_plugins', array()));
}

/**
* Validate active plugins
*
* Validate all active plugins, deactivates invalid plugins and
* returns an array of deactivated ones.
*/
# Version: 5.0
function sp_validate_active_plugins() {
	$plugins = sp_get_option('sp_active_plugins', array());

	# validate vartype: array
	if (!is_array($plugins)) {
		sp_update_option('sp_active_plugins', array());
		$plugins = array();
	}

	if (empty($plugins)) return;
	$invalid = array();

	# invalid plugins get deactivated
	foreach ($plugins as $plugin) {
		$result = sp_validate_plugin($plugin);
		if (is_wp_error($result)) {
			$invalid[$plugin] = $result;
			sp_deactivate_sp_plugin($plugin, true);
		}
	}
	return $invalid;
}

/**
* Validate the plugin path.
*
* Checks that the file exists and that it is a valid file.
*/
# Version: 5.0
function sp_validate_plugin($plugin) {
	if (validate_file($plugin)) return new WP_Error('plugin_invalid', sp_text('Invalid plugin path'));
	if (!file_exists(SFPLUGINDIR.$plugin)) return new WP_Error('plugin_not_found', sp_text('Plugin file does not exist'));
	$installed_plugins = sp_get_plugins();
	if (!isset($installed_plugins[$plugin])) return new WP_Error('no_plugin_header', sp_text('The plugin does not have a valid header'));
	return 0;
}

/**
 * Function to add a new forum admin panel
 *
 * admin panel array elements
 * 0 - panel name
 * 1 - spf capability to view
 * 2 - tool tip
 * 3 - icon
 * 4 - subpanels
*/
# Version: 5.0
function sp_add_admin_panel($name, $capability, $tooltop, $icon, $subpanels, $position='') {
	global $sfadminpanels, $sfactivepanels;

    # make sure the current user has capability to see this panel
    if (!sp_current_user_can($capability)) return false;

    # make sure the panel doesnt already exist
    if (array_key_exists($name, $sfadminpanels)) return false;

    # fix up the subpanels formids from user names
    $forms = array();
    foreach ($subpanels as $index => $subpanel) {
   	    $forms[$index] = array('plugin' => $subpanel['id'], 'admin' => $subpanel['admin'], 'save' => $subpanel['save'], 'form' => $subpanel['form']);
    }

	$num_panels = count($sfactivepanels);
	if (empty($position) || ($position < 0 || $position > $num_panels)) $position = $num_panels;

    # okay, lets add the new panel
	$panel_data = array($name, $capability, 'simple-press/admin/panel-plugins/spa-plugins.php', $tooltop, $icon, SFHOMEURL.'index.php?sp_ahah=plugins-loader&amp;sfnonce='.wp_create_nonce('forum-ahah'), $forms, false);
	array_splice($sfadminpanels, $position, 0, array($panel_data));

	# and update the active panels list
	$new = array_keys($sfactivepanels);
	array_splice($new, $position, 0, $name);
	$sfactivepanels = array_flip($new);

    return true;
}

/**
 * Function to add a new forum admin subpanels
*/
# Version: 5.0
function sp_add_admin_subpanel($panel, $subpanels) {
	global $sfadminpanels, $sfactivepanels;

    # make sure the panel exists
    if (!array_key_exists($panel, $sfactivepanels)) return false;

    # fix up the subpanels formids from user names
    $forms = $sfadminpanels[$sfactivepanels[$panel]][6];
    foreach ($subpanels as $index => $subpanel) {
        $forms[$index] = array('plugin' => $subpanel['id'], 'admin' => $subpanel['admin'], 'save' => $subpanel['save'], 'form' => $subpanel['form']);
    }

    # okay, lets add the new subpanel
    $sfadminpanels[$sfactivepanels[$panel]][6] = $forms;
    return true;
}

# Version: 5.0
function sp_plugins_dir() {
	return $this->find_folder(SFPLUGINDIR);
}

# ----------------------------------------------
# sp_find_css()
# Version: 5.0
# Checks in theme for css file - returns path
# ----------------------------------------------
function sp_find_css($path, $file) {
	# check for css in current theme first
	if (file_exists(SPTHEMEDIR.$file)) {
		return SPTHEMEURL.$file;
	} else {
		return $path.$file;
	}
}

# ----------------------------------------------
# sp_find_icon()
# Version: 5.0
# Checks in theme for icon file - returns path
# ----------------------------------------------
function sp_find_icon($path, $file) {
	# check for icon in current theme first
	if (file_exists(SPTHEMEICONSDIR.$file)) {
		return SPTHEMEICONSURL.$file;
	} else {
		return $path.$file;
	}
}

# ----------------------------------------------
# sp_find_template()
# Version: 5.0
# Checks in theme templates for plugin template
# returns path
# ----------------------------------------------
function sp_find_template($path, $file) {
	# check for icon in current theme first
	if (file_exists(SPTEMPLATES.$file)) {
		return SPTEMPLATES.$file;
	} else {
		return $path.$file;
	}
}

?>