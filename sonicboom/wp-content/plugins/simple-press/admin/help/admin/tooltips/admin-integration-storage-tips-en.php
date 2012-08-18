<?php
global $tooltips;

$tooltips=array();
$tooltips['plugins'] = "The <b>Plugins</b> folder contains all of the forum plugins you have added to your installation.  Plugins may reside in this directory or within a subdirectory one level down from this directory.<br /><br />
For the benefits of relocating folders, please click on the help button in the top right of this form.";
$tooltips['themes'] = "The <b>themes</b> folder contains all of the forum themes and theme templates you have added to your installation.<br /><br />
For the benefits of relocating folders, please click on the help button in the top right of this form.";
$tooltips['avatars'] = "The <b>Avatars</b> folder contains both the default three avatars and all avatars uploaded by your members. If you choose to not use the forum avatar options then you may ignore this folder.<br /><br />
If this folder failed to get created during the installation (due to permission settings) then you need to create it manually. Please follow these instructions:<br />
[1] Create the new folder within the WordPress '<b>wp-content</b>' folder. You may give this folder any name you choose or create a sub-folder path. The default name is '<b>forum-avatars</b>'.<br />
[2] Move or copy the three supplied default avatars into this new folder. They are supplied in the '<b>/styles/avatars/</b>' folder but can not be used from that location.<br />
[3] Make sure that your new folder has the correct permissions. If you are allowing your members to upload their avatars this will need to be '777'.<br />
[4] Finally - if you changed the name of the avatars folder - enter the path into thos storage locations form and update it.";
$tooltips['avatar-pool'] = "The <b>Avatar Pool</b> Folder is the location for storing a pool of images uploaded by the forum admin from which his users can select an avatar to use. Use of the Avatar Pool will depend, of course, of the general avatar settings made in the Profile > Avatars panel.";
$tooltips['smileys'] = "The <b>Smileys</b> folder contains both the default supplied smileys and all smileys you upload and add to the forum. If you choose to not use the forum smiley options then you may ignore this folder.<br /><br />
If this folder failed to get created during the installation (due to permission settings) then you need to create it manually. Please follow these instructions:<br />
[1] Create the new folder within the WordPress '<b>wp-content</b>' folder. You may give this folder any name you choose or create a sub-folder
path. The default name is '<b>forum-smileys</b>'.<br />
[2] Move or copy the supplied default smileys into this new folder. They are supplied in the '<b>/styles/smileys/</b>' folder but can not be used from that location.<br />
[3] Make sure that your new folder has the correct permissions. If you are likely to upload additional smileys this will need to be '777'.<br />
[4] Finally - if you changed the name of the smileys folder - enter the path into thos storage locations form and update it.";
$tooltips['ranks'] = "The <b>Forum Badges</b> folder contains any custom images that you want to use for forum ranks.  If you are not using forum ranks or do not want images (ie badges) with the forum ranks, then you do not need to worry about this storage location path.<br /><br />
This folder needs to be manually created with permissions of '777' and the path entered here.";
$tooltips['language-sp'] = "The <b>Simple:Press Language</b> folder should contain the .mo language file for the core Simple:Press plugin that matches your language.";
$tooltips['language-sp-plugins'] = "The <b>Simple:Press Plugin Language</b> folder should contain the .mo language files, if available, for any Simple:Press plugins you may have active.";
$tooltips['language-sp-themes'] = "The <b>Simple:Press Theme Language</b> folder should contain the .mo language files, if available, for the Simple:Press theme you are using.";
$tooltips['custom-icons'] = "The <b>Custom icons</b> folder is a general storage area for any custom icons used by the forum. These can be replacement icons for Groups and Forums
or the three custom locations set aside for custom icons available in the Conpoents > Custom Icons panel.";

$tooltips = apply_filters('sph_integration_tooltips', $tooltips);
?>