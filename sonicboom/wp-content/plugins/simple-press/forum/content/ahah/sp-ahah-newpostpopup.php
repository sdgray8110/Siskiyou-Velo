<?php
/*
Simple:Press
Users New Posts Popup
$LastChangedDate: 2012-03-30 10:19:35 -0700 (Fri, 30 Mar 2012) $
$Rev: 8248 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

sp_forum_api_support();

global $spThisUser, $spListView, $spThisListTopic;

if (!isset($_GET['action'])) die();

# Individual forum new post listing
if ($_GET['action'] == 'forum') {
	if (isset($_GET['id'])) {
		$fid = (int) $_GET['id'];
		$topics = array();
		for ($x=0; $x<count($spThisUser->newposts['forums']); $x++) {
			if ($spThisUser->newposts['forums'][$x] == $fid) $topics[] = $spThisUser->newposts['topics'][$x];
		}

		echo '<div id="spMainContainer">';
		$spListView = new spTopicList($topics);

		sp_load_template('spListView.php');
		echo '</div>';
	}
}

# All forums (users new post list)
if ($_GET['action'] == 'all') {
	echo '<div id="spMainContainer">';
	$spListView = new spTopicList($spThisUser->newposts['topics']);

	sp_load_template('spListView.php');
	echo '</div>';
}

die();
?>