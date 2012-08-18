<?php
/*
Plugin Name: Simple:Press
Version: 5.1.1
Plugin URI: http://simple-press.com
Description: Fully featured but simple page-based forum
Author: Andy Staines & Steve Klasen
Author URI: http://simple-press.com
WordPress Versions: 3.3 and above
For full acknowledgements click on the copyright/version strip at the bottom of forum pages
*/

# ==============================================================================================
# Copyright 2006/2012  Andy Staines & Steve Klasen
# Please read the 'License' supplied with this plugin
# (goto /admin/help/documentation)
# and abide by it's few simple requests.
# ==============================================================================================

# ==============================================================================================
# Turn on/off debug and error handling

define('SPSHOWDEBUG', 	true);
define('SPSHOWERRORS', 	true);
# ==============================================================================================

# ==============================================================================================
# version and system control constants

define('SPPLUGNAME',	'Simple:Press');
define('SPVERSION',		'5.1.1');
define('SPBUILD', 		 8675);
define('SPRELEASE', 	'Development');
define('SFPLUGHOME', 	'<a class="spLink" href="http://simple-press.com" target="_blank">Simple:Press</a>');
define('SFHOMESITE', 	'http://simple-press.com');
# ==============================================================================================

# ==============================================================================================
# Define startup constants

# IMPORTANT - SFHOMEURL is always slashed! check user_trailingslashit()) if using standalone (ie no args)
# IMPORTANT - This is NOT the same as what wp refers to as home url. This is actually URL to the WP files. Changing to be consistent ripples through everything.
$home = trailingslashit(site_url());
if (is_admin() && force_ssl_admin()) $home = str_replace('http://', "https://", $home);
define('SFHOMEURL', $home);

# IMPORTANT - SFSITEURL is always slashed! check user_trailingslashit()) if using standalone (ie no args)
# IMPORTANT - This is NOT the same as what wp refers to as site url. This is actually to the site home URL. Changing to be consistent ripples through everything.
$site = trailingslashit(home_url());
if (is_admin() && force_ssl_admin()) $site = str_replace('http://', "https://", $site);
define('SFSITEURL', $site);

define('SF_PLUGIN_DIR',	WP_PLUGIN_DIR.'/'.basename(dirname(__file__)));
define('SF_PLUGIN_URL', plugins_url().'/'.basename(dirname(__file__)));
define('SPBOOT', 		dirname(__file__).'/sp-startup/');
define('SPAPI', 		dirname(__file__).'/sp-api/');
define('SPINSTALLPATH',	'simple-press/sp-startup/sp-load-install.php');
# ==============================================================================================

# ==============================================================================================
# Define startup global variables

global $SPALLOPTIONS, $SPPATHS, $ISADMIN, $ISFORUMADMIN, $SPSTATUS, $APAGE, $ISFORUM, $SPCACHE,
	   $CONTENTLOADED, $SFMOBILE, $wpdb;
$SPALLOPTIONS 	= array();
$SPPATHS 		= array();
$ISADMIN 		= false;
$ISFORUMADMIN	= false;
$SPSTATUS 		= '';
$APAGE 			= '';
$ISFORUM 		= false;
$SPCACHE 		= array();
$CONTENTLOADED 	= false;
$SFMOBILE		= false;
# ==============================================================================================

# ==============================================================================================
# Initialise the cache array

$SPCACHE['globals'] 	= false;
$SPCACHE['ranks'] 		= false;
$SPCACHE['site_auths']	= false;
# ==============================================================================================

# ==============================================================================================
# Include minimum globally required startup files

include_once (SF_PLUGIN_DIR.'/sp-config.php');
include_once (SPBOOT.'sp-load-core.php');
include_once (SPBOOT.'sp-load-ahah.php');
# ==============================================================================================

# ==============================================================================================
# Load up admin boot files if an admin session

if ($ISADMIN == true) 		include_once (SPBOOT.'sp-load-core-admin.php');
if ($ISFORUMADMIN == true) 	include_once (SPBOOT.'sp-load-admin.php');
# ==============================================================================================

# ==============================================================================================
# Load up site boot files if a site session

if ($ISADMIN == false) 		include_once (SPBOOT.'sp-load-site.php');
# ==============================================================================================

# ==============================================================================================
# Finally wait to find out if this is a forum page being requested

if ($ISADMIN == false) 		add_action('wp', 'sp_is_forum_page');
# ==============================================================================================

# ==============================================================================================
# Load up forum page code if a forum page request

do_action('sph_control_startup');

function sp_is_forum_page() {
	global $ISFORUM, $wp_query;
	if ((is_page()) && ($wp_query->post->ID == sp_get_option('sfpage'))) {
		$ISFORUM = true;
		sp_load_current_user();
		include_once (SPBOOT.'sp-load-forum.php');
	}
}
# ==============================================================================================

?>