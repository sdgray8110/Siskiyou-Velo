<?php
/*
Simple:Press
DESC:
$LastChangedDate: 2011-10-27 04:42:04 -0700 (Thu, 27 Oct 2011) $
$Rev: 7248 $
*/

# ==========================================================================================
#
# 	FORUM PAGE
#	This file loads for forum page loads only
#
# ==========================================================================================

global $SPSTATUS;

if (!defined('SPMEMBERLIST')) define('SPMEMBERLIST', sp_url('members'));
if (!defined('SFMARKREAD'))   define('SFMARKREAD',   sp_build_qurl('mark-read'));

# hack to get around wp_list_pages() bug
if ($SPSTATUS == 'ok') {
	# go for whole row so it gets cached.
	$t = spdb_table(SFWPPOSTS, "ID=".sp_get_option('sfpage'), 'row');
	if (!defined('SFPAGETITLE')) define('SFPAGETITLE', $t->post_title);
}

do_action('sph_forum_constants');

?>