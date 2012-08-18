<?php
/*
Simple:Press
Global defs
$LastChangedDate: 2012-03-26 10:30:25 -0700 (Mon, 26 Mar 2012) $
$Rev: 8234 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

# ==================================================================
#
# 	CORE: This file is loaded at CORE
#	Sets up the $sfglobals array and the $SPCACHE
#
# ==================================================================

# ------------------------------------------------------
# sp_setup_globals()
#
# Version: 5.0
# some global system level defs used here and there
# NOTE: This array is initialized in sf-includes
# ------------------------------------------------------
function sp_setup_globals() {
	global $sfglobals, $current_user, $SPCACHE, $sfmeta;

	if ($SPCACHE['globals'] == true) return;

	# Main admin options
	$sfglobals['admin'] = sp_get_option('sfadminsettings');
	$sfglobals['lockdown'] = sp_get_option('sflockdown');

	# Load smiley options
	$sfglobals['sfsmileys'] = sp_get_option('sfsmileys');

	$sfglobals['editor'] = 0;

	# Display array
	$sfglobals['display'] = sp_get_option('sfdisplay');

	# Load up sfmeta
	$sfmeta = spdb_table(SFMETA, 'autoload=1');
	if ($sfmeta) {
		foreach ($sfmeta as $s) {
			$sfglobals[$s->meta_type][$s->meta_key] = maybe_unserialize($s->meta_value);
		}
	}

	# Pre-define a few others
	$sfglobals['canonicalurl']=false;

	$SPCACHE['globals'] = true;
}

# ------------------------------------------------------
# sp_filter_globals()
#
# Version: 5.0
# a special function that will allow plugins to filter
# the $sfglobals array with the $sfmeta data present
# NOTE: The global $sfmeta array is then unset
# ------------------------------------------------------
function sp_filter_globals() {
	global $sfglobals, $sfmeta;
	$sfglobals = apply_filters('sph_load_globals', $sfglobals, $sfmeta);
	unset($sfmeta);
}

# ------------------------------------------------------
# sf_build_auths_cache()
#
# Version: 5.0
# load auths table into cache
# ------------------------------------------------------
function sp_build_site_auths_cache() {
	global $sfglobals, $SPCACHE;

	if ($SPCACHE['site_auths'] == true) return;

    $auths = spdb_table(SFAUTHS);
    foreach ($auths as $auth) {
    	# is auth active?
    	if ($auth->active) {
	        # save auth name to auth id mapping for quick ref
	        $sfglobals['auths_map'][$auth->auth_name] = $auth->auth_id;

	        # save off all auth info
	        $sfglobals['auths'][$auth->auth_id] = $auth;
        }
    }

	$SPCACHE['site_auths'] = true;
}

# Version: 5.0
function sp_php_overrides() {
	global $is_IIS;

	# hack for some IIS installations
	if ($is_IIS && @ini_get('error_log') == '') @ini_set('error_log', 'syslog');

	# try to increase backtrack limit
	if ((int) @ini_get('pcre.backtrack_limit') < 10000000000) @ini_set('pcre.backtrack_limit', 10000000000);

	# try to increase php memory
	if (function_exists('memory_get_usage') && ((int) @ini_get('memory_limit') < abs(intval('64M')))) @ini_set('memory_limit', '64M');

	# try to increase cpu time
	if ((int) @ini_get('max_execution_time') < 120) @ini_set('max_execution_time', '120');
}

# ------------------------------------------------------
# sp_initialize_globals()
# Version: 5.0
#
# ------------------------------------------------------
function sp_initialize_globals() {
	global $SPCACHE, $SPSTATUS, $sfglobals;
	if ($SPCACHE['site_auths'] && $SPCACHE['ranks'] && $SPCACHE['globals']) return;

	if ($SPSTATUS == 'ok') {
		sp_build_site_auths_cache();
		sp_setup_globals();

        do_action('sph_globals_initialized');
    }
}
?>