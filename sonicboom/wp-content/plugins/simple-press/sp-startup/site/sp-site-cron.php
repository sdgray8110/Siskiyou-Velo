<?php
/*
Simple:Press
Cron - global code
$LastChangedDate: 2012-06-03 09:59:36 -0700 (Sun, 03 Jun 2012) $
$Rev: 8645 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

# ==========================================================================================
#
# 	SITE - This file loads at core level - all page loads
#	SP Cron Functions
#
# ==========================================================================================

function sp_cron_schedule($schedules) {
    $schedules['sp_stats_interval'] = array('interval' => sp_get_option('sp_stats_interval'), 'display' => __('SP Stats Interval'));
    $schedules['sp_news_interval'] = array('interval' => (60*60*24*7), 'display' => __('SP News Check Interval')); # weekly
    return $schedules;
}

function sp_cron_remove_users() {
	require_once(ABSPATH . 'wp-admin/includes/user.php');

	# make sure auto removal is enabled
	$sfuser = sp_get_option('sfuserremoval');
	if ($sfuser['sfuserremove']) {
		# see if removing users with no posts
		if ($sfuser['sfusernoposts']) {
			$users = spdb_select('set', 'SELECT '.SFUSERS.'.ID FROM '.SFUSERS.'
										JOIN '.SFMEMBERS.' on '.SFUSERS.'.ID = '.SFMEMBERS.'.user_id
										LEFT JOIN '.SFWPPOSTS.' ON '.SFUSERS.'.ID = '.SFWPPOSTS.'.post_author
										WHERE user_registered < DATE_SUB(NOW(), INTERVAL '.$sfuser['sfuserperiod'].' DAY)
										AND post_author IS NULL
										AND posts < 1');

			if ($users) {
				foreach ($users as $user) {
					wp_delete_user($user->ID);
				}
			}
		}

		# see if removing inactive users
		if ($sfuser['sfuserinactive']) {
			$users = spdb_table(SFMEMBERS, 'lastvisit < DATE_SUB(NOW(), INTERVAL '.$sfuser['sfuserperiod'].' DAY)');
			if ($users) {
				foreach ($users as $user) {
					wp_delete_user($user->user_id);
				}
			}
		}
	} else {
		wp_clear_scheduled_hook('sph_cron_user');
	}

	do_action('sph_remove_users_cron');
}

function sp_cron_transient_cleanup() {
    include_once(SF_PLUGIN_DIR.'/forum/database/sp-db-management.php');
	sp_transient_cleanup();
	do_action('sph_transient_cleanup');
}

function sp_cron_generate_stats() {
	$counts = sp_get_stats_counts();
	sp_update_option('spForumStats', $counts);

	$stats = sp_get_membership_stats();
	sp_update_option('spMembershipStats', $stats);

	$spControls = sp_get_option('sfcontrols');
	$topPosters = sp_get_top_poster_stats((int) $spControls['showtopcount']);
	sp_update_option('spPosterStats', $topPosters);

	$mods = sp_get_moderator_stats();
	sp_update_option('spModStats', $mods);

	$admins = sp_get_admin_stats();
	sp_update_option('spAdminStats', $admins);

	do_action('sph_stats_cron_run');
}

function sp_cron_check_news() {
    $url = 'http://simple-press.com/downloads/simple-press/simple-press-news.xml';
	$response = wp_remote_get($url, array('timeout' => 5));
    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) != 200) return;
    $body = wp_remote_retrieve_body($response);
    if (!$body) return;
	$newNews = new SimpleXMLElement($body);
	if ($newNews) {
        $data = sp_get_sfmeta('news', 'news');
    	$curNews = (!empty($data)) ? $data[0]['meta_value'] : array('id' => -999.999, 'show' => 0, 'news' => '');
        if ($newNews->news->id != $curNews['id']) {
            $curNews['id'] = (string) $newNews->news->id;
            $curNews['show'] = 1;
            $curNews['news'] = (string) $newNews->news[0]->message;
    		sp_add_sfmeta('news', 'news', $curNews, 0);
        }        
	}
}
?>