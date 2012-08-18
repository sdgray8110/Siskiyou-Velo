<?php
/*
Simple:Press
Admin User Groups Support Functions
$LastChangedDate: 2012-05-04 09:19:23 -0700 (Fri, 04 May 2012) $
$Rev: 8473 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

function spa_get_mapping_data() {
	# get default usergroups
	$sfoptions = array();
	$value = sp_get_sfmeta('default usergroup', 'sfmembers');
	$sfoptions['sfdefgroup'] = $value[0]['meta_value'];
	$value = sp_get_sfmeta('default usergroup', 'sfguests');
	$sfoptions['sfguestsgroup'] = $value[0]['meta_value'];

	$sfmemberopts = sp_get_option('sfmemberopts');
	$sfoptions['sfsinglemembership'] = $sfmemberopts['sfsinglemembership'];

    return $sfoptions;    
}
?>