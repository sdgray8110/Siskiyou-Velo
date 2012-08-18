<?php
/*
Simple:Press
Remove a user notice in demand
$LastChangedDate: 2012-04-19 22:43:21 -0700 (Thu, 19 Apr 2012) $
$Rev: 8395 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

sp_forum_api_support();

if(isset($_GET['notice'])) {
	$id = (int) $_GET['notice'];
	if($id) {
		spdb_query('DELETE FROM '.SFNOTICES." WHERE notice_id=$id");
	}
}

die();
?>