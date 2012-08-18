<?php
/*
Simple:Press
Desc:
$LastChangedDate: 2012-03-26 10:30:25 -0700 (Mon, 26 Mar 2012) $
$Rev: 8234 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

# ==================================================================
#
# 	CORE: This file is loaded at CORE
#	Shared Form Component Routines
#
#
# ==================================================================

# Version: 5.0
function sp_create_nonce($action) {
	return '<input type="hidden" name="'.$action.'" value="'.wp_create_nonce($action).'" />'."\n";
}

# Version: 5.0
function sp_split_button_label($text, $pos=10) {
	$label = array();
	$label = explode(' ', $text);
	$label[$pos].= '&#x0A;';
	$text = implode(' ', $label);
	return str_replace('&#x0A; ', '&#x0A;', $text);
}

?>