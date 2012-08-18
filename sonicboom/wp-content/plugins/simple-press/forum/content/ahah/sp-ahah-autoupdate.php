<?php
/*
Simple:Press
Ahah call for Auto Update
$LastChangedDate: 2012-03-30 10:19:35 -0700 (Fri, 30 Mar 2012) $
$Rev: 8248 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

sp_forum_api_support();

global $spThisUser, $sfglobals;

# get out of here if no target specified
$target = $_GET['target'];
if (empty($target)) die();

# First do check to see if user is logged in
if ($target == 'checkuser') {
	$thisuser = sp_esc_int($_GET['thisuser']);
	if (empty($spThisUser->ID)) {
		if ($thisuser != 0 && $thisuser != '') {
			$out = '<div id="spMessage">';
			$out.= '<p>'.sp_text('Your Session has Expired');
			$out.= ' - <a href="'.site_url('wp-login.php', 'login_post').'">'.sp_text('Log back in').'</a></p>';
			$out.= '</div>';
			echo $out;
		}
	}
	die();
}

die();
?>