<?php
/*
Simple:Press
Common AHAH
$LastChangedDate: 2012-04-28 11:12:45 -0700 (Sat, 28 Apr 2012) $
$Rev: 8459 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

spa_admin_ahah_support();

if (isset($_GET['show_msbox'])) {
	$msbox = $_GET['msbox'];                   	# name of multiselect box - used to determine which query to use
	$uid = sp_esc_int($_GET['uid']);         	# uniue identifier - should be a number
	$name = esc_attr($_GET['name']);			# name of select element on the form
	$from = esc_html($_GET['from']);			# text displayed above where selecting from
	$to = esc_html($_GET['to']);				# text displayed above where selecting to
	$num = sp_esc_int($_GET['num']);			# number of items to show per page in the from box
	echo spa_populate_msbox_list($msbox, $uid, $name, $from, $to, $num);
	die();
}

if (isset($_GET['page_msbox'])) {
	$msbox = $_GET['msbox'];
	$uid = sp_esc_int($_GET['uid']);
	$name = esc_attr($_GET['name']);
	$from = esc_html($_GET['from']);
	$num = sp_esc_int($_GET['num']);
	$offset = sp_esc_int($_GET['offset']);
	$max = sp_esc_int($_GET['max']);
	$filter = urldecode($_GET['filter']);

	if ($_GET['page_msbox'] == 'filter') $max = spa_get_query_max($msbox, $uid, $filter);

	echo spa_page_msbox_list($msbox, $uid, $name, $from, $num, $offset, $max, $filter);
	die();
}

# handle include of file but not via ahah
if (isset($action)) {
    if ($action == 'addug')
        echo spa_populate_msbox_list('usergroup_add', $usergroup_id, 'member_id', $from, $to, 100);
    elseif ($action == 'delug')
       echo spa_populate_msbox_list('usergroup_del', $usergroup_id, 'dmember_id', $from, $to, 100);
    elseif ($action == 'addru')
       echo spa_populate_msbox_list('rank_add', $rank_id, 'amember_id', $from, $to, 100);
    elseif ($action == 'delru')
       echo spa_populate_msbox_list('rank_del', $rank_id, 'dmember_id', $from, $to, 100);
    elseif ($action == 'addadmin')
       echo spa_populate_msbox_list('admin_add', '', 'member_id', $from, $to, 100);

    return;
}
die();

function spa_get_query_max($msbox, $uid, $filter) {
	$like = '';
	if ($filter != '') $like = " AND display_name LIKE '%".esc_sql(like_escape($filter))."%'";

	switch ($msbox) {
		case 'usergroup_add':
			$max = spdb_select('var', '
				SELECT COUNT('.SFMEMBERS.'.user_id)
				FROM '.SFMEMBERS.'
				WHERE user_id NOT IN (SELECT user_id FROM '.SFMEMBERSHIPS.' WHERE usergroup_id='.$uid.') AND admin=0 '.$like
			);
			break;

		case 'usergroup_del':
			$max = spdb_select('var', '
				SELECT COUNT('.SFMEMBERSHIPS.'.user_id)
				FROM '.SFMEMBERSHIPS.'
				JOIN '.SFMEMBERS.' ON '.SFMEMBERS.'.user_id = '.SFMEMBERSHIPS.'.user_id
				WHERE '.SFMEMBERSHIPS.'.usergroup_id='.$uid.$like
			);
			break;

		case 'rank_add':
            $specialRank = sp_get_sfmeta('special_rank', false, $uid);
            $data = spdb_table(SFMEMBERS, 'special_ranks != "" OR special_ranks != NULL');
            $memberlist = array();
            if ($data) {
                foreach ($data as $user) {
                    $userRanks =  unserialize($user->special_ranks);
                    if (is_array($userRanks) && in_array($specialRank[0]['meta_key'], $userRanks)) $memberlist[] = $user->user_id;
                }
            }    
			if ($memberlist) {
				$memberlist = "'".implode("','", $memberlist)."'";
				$where = 'user_id NOT IN ('.$memberlist.')'.$like;
			} else {
				$where = '';
			}
			$max = spdb_count(SFMEMBERS, $where);
			break;

		case 'rank_del':
            $specialRank = sp_get_sfmeta('special_rank', false, $uid);
            $data = spdb_table(SFMEMBERS, 'special_ranks!="" OR special_ranks!=NULL');
            $memberlist = array();
            if ($data) {
                foreach ($data as $user) {
                    $userRanks =  unserialize($user->special_ranks);
                    if (is_array($userRanks) && in_array($specialRank[0]['meta_key'], $userRanks)) $memberlist[] = $user->user_id;
                }
            }    
			$max = count($memberlist);
			break;

		case 'admin_add':
			$max = spdb_count(SFMEMBERS, 'admin=0'.$like);
			break;

	}
	if (!$max) $max = 0;
	return $max;
}

function spa_populate_msbox_list($msbox, $uid, $name, $from, $to, $num) {
	$out = '';

	switch ($msbox) {
		case 'usergroup_add':
			$records = spdb_select('set', '
				SELECT '.SFMEMBERS.'.user_id, display_name, admin
				FROM '.SFMEMBERS.'
				WHERE user_id NOT IN (SELECT user_id FROM '.SFMEMBERSHIPS.' WHERE usergroup_id='.$uid.') AND admin=0
				ORDER BY display_name
				LIMIT 0,'.$num
			);

			$max = spa_get_query_max($msbox, $uid, '');
			break;

		case 'usergroup_del':
			$records = 	spdb_select('set', '
				SELECT '.SFMEMBERSHIPS.'.user_id, display_name
				FROM '.SFMEMBERSHIPS.'
				JOIN '.SFMEMBERS.' ON '.SFMEMBERS.'.user_id = '.SFMEMBERSHIPS.'.user_id
				WHERE '.SFMEMBERSHIPS.'.usergroup_id='.$uid.'
				ORDER BY display_name
				LIMIT 0,'.$num
			);
			$max = spa_get_query_max($msbox, $uid, '');
			break;

		case 'rank_add':
            $specialRank = sp_get_sfmeta('special_rank', false, $uid);
            $data = spdb_table(SFMEMBERS, 'special_ranks != "" OR special_ranks != NULL');
            $memberlist = array();
            if ($data) {
                foreach ($data as $user) {
                    $userRanks =  unserialize($user->special_ranks);
                    if (is_array($userRanks) && in_array($specialRank[0]['meta_key'], $userRanks)) $memberlist[] = $user->user_id;
                }
            }    
			if ($memberlist) {
				$memberlist = "'".implode("','", $memberlist)."'";
				$where = 'user_id NOT IN ('.$memberlist.')';
			} else {
				$where = '';
			}
			$records = spdb_table(SFMEMBERS, $where, '', 'display_name', "0, $num");

			$max = spa_get_query_max($msbox, $uid, '');
			break;

		case 'rank_del':
            $specialRank = sp_get_sfmeta('special_rank', false, $uid);
            $data = spdb_table(SFMEMBERS, 'special_ranks!="" OR special_ranks!=NULL');
            $memberlist = array();
            if ($data) {
                foreach ($data as $user) {
                    $userRanks =  unserialize($user->special_ranks);
                    if (is_array($userRanks) && in_array($specialRank[0]['meta_key'], $userRanks)) $memberlist[] = $user->user_id;
                }
            }    
			$max = count($memberlist);
            $records = array();
            if ($memberlist) {
    			if ($filter != '') {
    				$newlist = array();
    				foreach ($memberlist as $k => $v) {
                        if (eregi($filter, $v)) $newlist[] = array ($k, $v);
    				}
    				$memberlist = $newlist;
    			}
    			$records = array_slice($memberlist, 0, $num);
            }
			break;

		case 'admin_add':
			$records = spdb_select('set', '
				SELECT '.SFMEMBERS.'.user_id, display_name
				FROM '.SFMEMBERS.'
				WHERE admin=0
				ORDER BY display_name
				LIMIT 0,'.$num
			);

			$max = spa_get_query_max($msbox, $uid, '');
			break;

		default:
			die(); # invalid msbox type
	}

	$out.= '<table>';
	$out.= '<tr>';
	$out.= '<td width="50%" style="border:none !important;vertical-align: top !important;">';
	$out.= '<div id="mslist-'.$name.$uid.'">';
	$out.= spa_render_msbox_list($msbox, $uid, $name, $from, $num, $records, 0, $max, '');
	$out.= '</div>';
	$out.= '</td>';

	$out.= '<td width="50%" style="border:none !important;vertical-align: top !important;">';
	$out.= '<div align="center"><strong>'.$to.'</strong></div>';
	$out.= '<select class="sfacontrol" multiple="multiple" size="10" id="'.$name.$uid.'" name="'.$name.'[]" >';
	$out.= '<option disabled="disabled" value="-1">'.spa_text('List is empty').'</option>';
	$out.= '</select>';
	$out.= '<div align="center" style="margin-top:42px;">';
	$out.= '<input type="button" id="add'.$uid.'" class="button button-highlighted" value="'.spa_text('Remove From Selected List').'" onclick="spjTransferSelectList(\''.$name.$uid.'\', \'temp-'.$name.$uid.'\', \''.esc_js(spa_text('List is Empty')).'\')" />';
	$out.= '</div>';
	$out.= '</td>';
	$out.= '</tr>';
	$out.= '</table>';

	return $out;
}

function spa_page_msbox_list($msbox, $uid, $name, $from, $num, $offset, $max, $filter) {
	$out = '';
	$like = '';
	if ($filter != '') $like = " AND display_name LIKE '%".esc_sql(like_escape($filter))."%'";

	switch ($msbox) {
		case 'usergroup_add':
			$sql = '
				SELECT user_id, display_name, admin
				FROM '.SFMEMBERS.'
				WHERE user_id NOT IN (SELECT user_id FROM '.SFMEMBERSHIPS.' WHERE usergroup_id='.$uid.') AND admin=0 '.$like.'
				ORDER BY display_name
				LIMIT '.$offset.', '.$num
			;
			$records = spdb_select('set', $sql);
			break;

		case 'usergroup_del':
			$sql = 'SELECT '.SFMEMBERSHIPS.'.user_id, display_name
				FROM '.SFMEMBERSHIPS.'
				JOIN '.SFMEMBERS.' ON '.SFMEMBERS.'.user_id = '.SFMEMBERSHIPS.'.user_id
				WHERE '.SFMEMBERSHIPS.'.usergroup_id='.$uid.$like.'
				ORDER BY display_name
				LIMIT '.$offset.', '.$num
			;
			$records = spdb_select('set', $sql);
			break;

		case 'rank_add':
            $specialRank = sp_get_sfmeta('special_rank', false, $uid);
            $data = spdb_table(SFMEMBERS, 'special_ranks!="" OR special_ranks!=NULL');
            $memberlist = array();
            if ($data) {
                foreach ($data as $user) {
                    $userRanks =  unserialize($user->special_ranks);
                    if (is_array($userRanks) && in_array($specialRank[0]['meta_key'], $userRanks)) $memberlist[] = $user->user_id;
                }
            }    
			if ($memberlist) {
				$memberlist = "'".implode("','", $memberlist)."'";
				$where = ' WHERE user_id NOT IN ('.$memberlist.')'.$like;
			} else {
				$where = ''.str_replace(' AND ', ' WHERE ', $like);
			}
			$sql = '
				SELECT user_id, display_name
				FROM '.SFMEMBERS.
				$where.'
				ORDER BY display_name
				LIMIT '.$offset.', '.$num
			;
			$records = spdb_select('set', $sql);
			break;

		case 'rank_del':
            $specialRank = sp_get_sfmeta('special_rank', false, $uid);
            $data = spdb_table(SFMEMBERS, 'special_ranks!="" OR special_ranks!=NULL');
            $memberlist = array();
            if ($data) {
                foreach ($data as $user) {
                    $userRanks =  unserialize($user->special_ranks);
                    if (is_array($userRanks) && in_array($specialRank[0]['meta_key'], $userRanks)) $memberlist[] = $user->user_id;
                }
            }    
			if ($filter != '') {
				$newlist = array();
				foreach ($memberlist as $k => $v) {
				      if (eregi($filter, $v)) $newlist[] = array ($k, $v);
				}
				$memberlist = $newlist;
			}
			$records = array_slice($memberlist, $offset, $num);
			break;

		case 'admin_add':
			$sql = '
				SELECT '.SFMEMBERS.'.user_id, display_name
				FROM '.SFMEMBERS.'
				WHERE admin=0'.$like.'
				ORDER BY display_name
				LIMIT '.$offset.', '.$num
			;
			$records = spdb_select('set', $sql);
			break;

		default:
			die(); # invalid msbox type
	}

	$out.= spa_render_msbox_list($msbox, $uid, $name, $from, $num, $records, $offset, $max, $filter);
	return $out;
}

function spa_render_msbox_list($msbox, $uid, $name, $from, $num, $records, $offset, $max, $filter) {
	$out = '';
	$empty = true;

	$out.= '<div align="center"><strong>'.$from.'</strong></div>';
	$out.= '<select class="sfacontrol" multiple="multiple" size="10" id="temp-'.$name.$uid.'" name="temp-'.$name.$uid.'[]">';

	if ($records) {
		foreach ($records as $record) {
			switch ($msbox) {
				case 'usergroup_add':
					if (!$record->admin) {
						$empty = false;
						$out.= '<option value="'.$record->user_id.'">'.sp_filter_name_display($record->display_name).'</option>'."\n";
					}
					break;

				case 'usergroup_del':
				case 'rank_add':
				case 'admin_add':
					$empty = false;
					$out.= '<option value="'.$record->user_id.'">'.sp_filter_name_display($record->display_name).'</option>'."\n";
					break;

				case 'rank_del':
					$empty = false;
					$out.= '<option value="'.$record.'">'.sp_filter_name_display(sp_get_member_item($record, 'display_name')).'</option>'."\n";
					break;

				default;
					break;
			}
		}
	}
	if ($empty) $out.= '<option disabled="disabled" value="-1">'.spa_text('List is empty').'</option>';
	$out.= '</select>';

	$out.= '<div align="center">';
	$out.= '<small style="line-height:1.6em;">'.spa_text('Paging Controls').'</small><br />';
    $out.= '<span id="filter-working"></span>';
	$last = floor($max / $num) * $num;
	if ($last >= $max) $last = $last - $num;
	$disabled = '';
	if (($offset + $num) >= $max) $disabled = ' disabled="disabled"';

    $site = SFHOMEURL.'index.php?sp_ahah=multiselect&amp;sfnonce='.wp_create_nonce('forum-ahah')."&amp;page_msbox=next&amp;msbox=$msbox&amp;uid=$uid&amp;name=$name&amp;from=".urlencode($from)."&amp;num=$num&amp;offset=$last&amp;max=$max&amp;filter=$filter";
	$out.= '<input type="button"'.$disabled.' id="lastpage'.$uid.'" class="button button-highlighted sfalignright" value="'.spa_text('Last').'" onclick="spjUpdateMultiSelectList(\''.$site.'\', \''.$name.$uid.'\');" />';

    $site = SFHOMEURL.'index.php?sp_ahah=multiselect&amp;sfnonce='.wp_create_nonce('forum-ahah')."&amp;page_msbox=next&amp;msbox=$msbox&amp;uid=$uid&amp;name=$name&amp;from=".urlencode($from)."&amp;num=$num&amp;offset=".($offset + $num)."&amp;max=$max&amp;filter=$filter";
	$out.= '<input type="button"'.$disabled.' id="nextpage'.$uid.'" class="button button-highlighted sfalignright" value="'.spa_text('Next').'" onclick="spjUpdateMultiSelectList(\''.$site.'\', \''.$name.$uid.'\');" />';

	$disabled = '';
	if ($offset == 0) $disabled = ' disabled="disabled"';

    $site = SFHOMEURL.'index.php?sp_ahah=multiselect&amp;sfnonce='.wp_create_nonce('forum-ahah')."&amp;page_msbox=next&amp;msbox=$msbox&amp;uid=$uid&amp;name=$name&amp;from=".urlencode($from)."&amp;num=$num&amp;offset=0&amp;max=$max&amp;filter=$filter";
	$out.= '<input type="button"'.$disabled.' id="firstpage'.$uid.'" class="button button-highlighted sfalignleft" value="'.spa_text('First').'" onclick="spjUpdateMultiSelectList(\''.$site.'\', \''.$name.$uid.'\');" />';

    $site = SFHOMEURL.'index.php?sp_ahah=multiselect&amp;sfnonce='.wp_create_nonce('forum-ahah')."&amp;page_msbox=next&amp;msbox=$msbox&amp;uid=$uid&amp;name=$name&amp;from=".urlencode($from)."&amp;num=$num&amp;offset=".($offset - $num)."&amp;max=$max&amp;filter=$filter";
	$out.= '<input type="button"'.$disabled.' id="prevpage'.$uid.'" class="button button-highlighted sfalignleft" value="'.spa_text('Prev').'" onclick="spjUpdateMultiSelectList(\''.$site.'\', \''.$name.$uid.'\');" />';

	$out.= '<div style="clear:both;padding: 5px 0pt;">';
	$out.= '<input type="button" id="add'.$uid.'" class="button button-highlighted" value="'.spa_text('Move to Selected List').'" onclick="spjTransferSelectList(\'temp-'.$name.$uid.'\', \''.$name.$uid.'\', \''.esc_js(spa_text('List is Empty')).'\')" />';
	$out.= '</div>';

	$out.= '<input type=text id="list-filter'.$name.$uid.'" name="list-filter'.$name.$uid.'" value="'.$filter.'" class="sfacontrol sfalignleft" style="width:50% !important;" />';
	$gif = SFCOMMONIMAGES."working.gif";
    $site = SFHOMEURL.'index.php?sp_ahah=multiselect&amp;sfnonce='.wp_create_nonce('forum-ahah')."&amp;page_msbox=filter&amp;msbox=$msbox&amp;uid=$uid&amp;name=$name&amp;from=".urlencode($from)."&amp;num=$num&amp;offset=0&amp;max=$max";
	$out.= '<input type="button" id="filter'.$uid.'" class="button button-highlighted" value="'.spa_text('Filter List').'" style="margin-top:1px" onclick="spjFilterMultiSelectList(\''.$site.'\', \''.$name.$uid.'\', \''.$gif.'\');" />';
    
	$out.= '</div>';
	return $out;
}
?>