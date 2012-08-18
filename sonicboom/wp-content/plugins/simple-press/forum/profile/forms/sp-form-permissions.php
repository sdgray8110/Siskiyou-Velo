<?php
/*
Simple:Press
Profile Permissions Form
$LastChangedDate: 2012-04-06 08:55:31 -0700 (Fri, 06 Apr 2012) $
$Rev: 8278 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

	# double check we have a user
	if (empty($userid)) return;

	$out = '';
	$out.= '<p>';
	$out.= sp_text('Permissions are what enable you to do things on forums. For the forums you have access to, your permissions are shown below.');
	$out.= '</p>';
	$out.= '<hr>';

	# get the users profile data
	# Start the 'groupView' section
	# ----------------------------------------------------------------------
	$out.= '<div class="spProfileUserPermissions spListSection">';

	$out = apply_filters('sph_ProfileFormTop', $out, $userid, $thisSlug);
	$out = apply_filters('sph_ProfileUserPermissionsFormTop', $out, $userid);

	# Start the Group Loop
	if (sp_has_groups()) : while (sp_loop_groups()) : sp_the_group();
		# Start the 'groupHeader' section
		$out.= '<div class="spGroupViewSection">';
		$icon = (!empty($spThisGroup->group_icon)) ? SFCUSTOMURL.$spThisGroup->group_icon : SPTHEMEICONSURL.'sp_GroupIcon.png';
		$out.= "<img class='spHeaderIcon spLeft' src='$icon' alt='' />";
		$out.= "<div class='spHeaderName'>".$spThisGroup->group_name."</div>";
		$out.= "<div class='spHeaderDescription'>".$spThisGroup->group_desc."</div>";

		$out.= sp_InsertBreak('echo=0');

		# Start the Forum Loop
		global $thisAlt;
		$thisAlt = 'spOdd';
		if (sp_has_forums()) : while (sp_loop_forums()) : sp_the_forum();
			$out.= sp_ProfilePermissionsForum($spThisForum, $userid);

			# do subforums
			if ($spThisForumSubs) {
				foreach ($spThisForumSubs as $sub) {
					$out.= sp_ProfilePermissionsForum($sub, $userid);
				}
			}
		endwhile; else:
			sp_NoForumMessage('tagClass=spMessage', sp_text('No Forums Found in this Group'));
		endif;
		$out.= '</div>';
	endwhile; else:
		sp_NoGroupMessage('tagClass=spMessage', sp_text('Access denied'), sp_text('No Groups Defined'));
	endif;

	$out = apply_filters('sph_ProfileUserPermissionsFormBottom', $out, $userid);
	$out = apply_filters('sph_ProfileFormBottom', $out, $userid, $thisSlug);

	$out.= '</div>';

	$out = apply_filters('sph_ProfilePermissionsForm', $out);
	echo $out;

	# routine for outputting forum or subforum row
	function sp_ProfilePermissionsForum($spThisForum, $userid) {
		global $thisAlt;

		# Start the 'forum' section
		$out = "<div class='spGroupForumSection $thisAlt'>";

		# Column 1 of the forum row
		$out.= '<div class="spColumnSection spLeft" style="width:8%; height:70px">';
		$icon = (!empty($spThisForum->forum_icon)) ? SFCUSTOMURL.$spThisForum->forum_icon : SPTHEMEICONSURL.'sp_ForumIcon.png';
		$out.= "<img class='spRowIcon spLeft' src='$icon' alt='' />";
		$out.= '</div>';

		# Column 2 of the forum row
		$out.= '<div class="spColumnSection spLeft" style="width:70%; height:70px">';
		$out.= "<div class='spRowName'>".$spThisForum->forum_name."</div>";
        $desc = (!empty($spThisForum->forum_desc)) ? $spThisForum->forum_desc : '';
		$out.= "<div class='spRowName'>".$desc."</div>";
		$out.= '</div>';

		# Column 3 of the forum row
		$site = SFHOMEURL."index.php?sp_ahah=permissions&amp;sfnonce=".wp_create_nonce('forum-ahah')."&amp;forum=".$spThisForum->forum_id.'&amp;userid='.$userid;
		$img = SFCOMMONIMAGES.'/working.gif';
		$out.= '<div class="spColumnSection spRight" style="width:20%; height:70px">';
		$out.= '<a rel="nofollow" href="javascript:void(null)" onclick="spjLoadTool(\''.$site.'\', \'perm'.$spThisForum->forum_id.'\', \''.$img.'\');">';
		$out.= '<input type="submit" class="spSubmit spRight" value="'.sp_text('View Permissions').'" />';
		$out.= '</a>';
		$out.= '</div>';

		$out.= sp_InsertBreak('echo=0');

		# hidden area for the permissions for this forum
		$out.= '<div id="perm'.$spThisForum->forum_id.'" class="spHiddenSection"></div>';

		$out.= '</div>';

		$thisAlt = ($thisAlt == 'spOdd') ? 'spEven' : 'spOdd';

		return $out;
	}
?>