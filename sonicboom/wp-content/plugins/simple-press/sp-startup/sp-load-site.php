<?php
/*
Simple:Press
Desc:
$LastChangedDate: 2011-09-24 22:10:05 -0700 (Sat, 24 Sep 2011) $
$Rev: 7117 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

# ==========================================================================================
#
# 	SITE
#	This file loads the asdditional core SP support needed by the site (front end) for all
#	page loads - not just for the forum. It also exposes base api files that may be needed by
#	plugins, template tags etc., and creates items needed by the header for non forum use.
#
# ==========================================================================================

# ------------------------------------------------------------------------------------------
# Include core api files

# ------------------------------------------------------------------------------------------

# ------------------------------------------------------------------------------------------
# Set up core support WP Hooks

# Rewrite Rules

add_filter('page_rewrite_rules', 'sp_set_rewrite_rules');
# ------------------------------------------------------------------------------------------

# 404

add_action('template_redirect', 'sp_404');
# ------------------------------------------------------------------------------------------

# Load blog script support

add_action('wp_enqueue_scripts', 'sp_load_blog_script');
# ------------------------------------------------------------------------------------------

# Load blog header support

add_action('wp_head','sp_load_blog_support');
# ------------------------------------------------------------------------------------------

do_action('sph_site_startup');

?>