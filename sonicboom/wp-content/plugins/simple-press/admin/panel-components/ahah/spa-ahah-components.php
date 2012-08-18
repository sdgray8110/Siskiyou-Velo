<?php
/*
Simple:Press
Component Specials
$LastChangedDate: 2012-05-26 09:05:10 -0700 (Sat, 26 May 2012) $
$Rev: 8571 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

spa_admin_ahah_support();

# Check Whether User Can Manage Components
if (!sp_current_user_can('SPF Manage Components')) {
	if (!is_user_logged_in()) {
		spa_etext('Access denied - are you logged in?');
	} else {
		spa_etext('Access denied - you do not have permission');
	}
 	die();
}

global $SPPATHS;

$action = $_GET['action'];

if ($action == 'del_rank') {
	$key = sp_esc_int($_GET['key']);

	# remove the forum rank
	$sql = 'DELETE FROM '.SFMETA." WHERE meta_type='forum_rank' AND meta_id='$key'";
	spdb_query($sql);
}

if ($action == 'del_specialrank') {
	$key = sp_esc_int($_GET['key']);

    # remove members rank first
    $specialRank = sp_get_sfmeta('special_rank', false, $key);
    $data = spdb_table(SFMEMBERS, 'special_ranks != "" OR special_ranks != NULL');
    if ($data) {
        foreach ($data as $user) {
            if ($user->special_ranks) {
                $newUserRanks = array();
                foreach ($user->special_ranks as $rank) {
                    if ($rank != $specialRank[0]['meta_key']) $newUserRanks[] = $rank;
                }
                sp_update_member_item($user->user_id, 'special_ranks', $newUserRanks);
            }
        }
    }    

	# remove the forum rank
	$sql = 'DELETE FROM '.SFMETA." WHERE meta_type='special_rank' AND meta_id='$key'";
	spdb_query($sql);    
}

if ($action == 'show') {
    $key = sp_esc_int($_GET['key']);   
    $specialRank = sp_get_sfmeta('special_rank', false, $key);
    $data = spdb_table(SFMEMBERS, 'special_ranks!="" OR special_ranks!=NULL');
    $users = array();
    if ($data) {
        foreach ($data as $user) {
            $userRanks =  unserialize($user->special_ranks);
            if (is_array($userRanks) && in_array($specialRank[0]['meta_key'], $userRanks)) $users[] = $user->user_id;
        }
    }    
    
    echo '<fieldset class="sfsubfieldset">';
    echo '<legend>'.spa_text('Special Rank Members').'</legend>';
    if ($users) {
    	echo '<ul class="memberlist">';
    	for ($x=0; $x<count($users); $x++) {
    		echo '<li>'.sp_filter_name_display(sp_get_member_item($users[$x], 'display_name')).'</li>';
    	}
    	echo '</ul>';
    } else {
    	spa_etext('No users with this special rank');
    }
    
    echo '</fieldset>';
}

if ($action == 'delsmiley') {
	$file = sp_esc_str($_GET['file']);
	$path = SF_STORE_DIR.'/'.$SPPATHS['smileys'].'/'.$file;
	@unlink($path);

	# load smiles from sfmeta
	$meta = sp_get_sfmeta('smileys', 'smileys');

	# now cycle through to remove this entry and resave
	if (!empty($meta[0]['meta_value'])) {
		$newsmileys = array();
		foreach ($meta[0]['meta_value'] as $name => $info) {
			if($info[0] != $file) {
				$newsmileys[$name][0] = sp_filter_title_save($info[0]);
				$newsmileys[$name][1] = sp_filter_name_save($info[1]);
				$newsmileys[$name][2] = sp_filter_name_save($info[2]);
				$newsmileys[$name][3] = $info[3];
				$newsmileys[$name][4] = $info[4];
			}
		}
		sp_update_sfmeta('smileys', 'smileys', $newsmileys, $meta[0]['meta_id'], true);
	}

	echo '1';
}

if ($action == 'delbadge') {
	$file = sp_esc_str($_GET['file']);
	$path = SF_STORE_DIR.'/'.$SPPATHS['ranks'].'/'.$file;
	@unlink($path);
	echo '1';
}

die();

?>