<?php
/*
Simple:Press
Admin General Ahah file
$LastChangedDate: 2012-04-28 11:12:45 -0700 (Sat, 28 Apr 2012) $
$Rev: 8459 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

spa_admin_ahah_support();

if (isset($_GET['action']) && $_GET['action'] == 'news') {
	$news = sp_get_sfmeta('news', 'news');
	if (!empty($news)) {
		$news[0]['meta_value']['show'] = 0;
		sp_update_sfmeta('news', 'news', $news[0]['meta_value'], $news[0]['meta_id'], 0);
	}
}

die();

?>