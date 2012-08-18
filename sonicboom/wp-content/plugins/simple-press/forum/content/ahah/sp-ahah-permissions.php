<?php
/*
Simple:Press
Ahah call for acknowledgements
$LastChangedDate: 2012-03-30 10:19:35 -0700 (Fri, 30 Mar 2012) $
$Rev: 8248 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

sp_forum_api_support();

$forumid = sp_esc_int($_GET['forum']);
if (empty($forumid)) die();

$userid = sp_esc_int($_GET['userid']);
if (empty($forumid)) die();

global $sfglobals;
$curcol = 1;
foreach ($sfglobals['auths_map'] as $auth_name => $auth_id) {
	echo '<div class="spColumnSection spLeft" style="width:45%; valign:middle; font-size:80%; padding-left:10px;">';
	if (sp_get_auth($auth_name, $forumid, $userid)) {
		echo '<img src="'.SPTHEMEICONSURL.'sp_PermissionYes.png" />&nbsp;&nbsp;'.spa_text($sfglobals['auths'][$auth_id]->auth_desc);
	} else {
		echo '<img src="'.SPTHEMEICONSURL.'sp_PermissionNo.png" />&nbsp;&nbsp;'.spa_text($sfglobals['auths'][$auth_id]->auth_desc);
	}
	echo '</div>';

	$curcol++;
	if ($curcol > 2) {
		echo '<div class="spClear"></div>';
		$curcol = 1;
	} else {
		echo '<div class="spColumnSection spLeft" style="width:5%;"></div>';
	}
}

echo '<div class="spClear"></div>';
echo '<p class="spCenter"><a href="javascript:void(null)" style="padding-left:10px" onclick="spjClearIt(\'perm'.$forumid.'\');">';
echo '<input type="button" class="spSubmit" value="'.sp_text('Close').'" />';
echo '</a></p>';
?>
	<script type="text/javascript">
	jQuery(document).ready(function() {
		baseHeight = Math.max(jQuery("#spProfileData").outerHeight(true) + 10, jQuery("#spProfileMenu").outerHeight(true));
       	jQuery("#spProfileContent").height(baseHeight + jQuery("#spProfileHeader").outerHeight(true));
	})
	</script>
<?php

die();
?>