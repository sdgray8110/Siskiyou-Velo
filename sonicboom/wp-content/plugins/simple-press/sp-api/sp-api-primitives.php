<?php
/*
Simple:Press
DESC: API Primitive Routines
$LastChangedDate: 2012-05-14 22:22:52 -0700 (Mon, 14 May 2012) $
$Rev: 8512 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

# ==================================================================
#
# 	CORE: This file is loaded at CORE
#	Primitive functions dealing with Options/Members/Meta tables
#	and assorted base routines
#
# ==================================================================

# ==================================================================
# Translation text dimain functions - front and back end
# ==================================================================

# ------------------------------------------------------------------
# spa_text()
# spa_etext()
#
# Version: 5.0
# Admin translation routines
# run using the 'spa' domain
# ------------------------------------------------------------------
function spa_text($text) {
	return esc_attr(__($text, 'spa'));
}

# Version: 5.0
function spa_etext($text) {
	echo esc_attr(__($text, 'spa'));
}

# Version: 5.0
function spa_text_noesc($text) {
	return __($text, 'spa');
}

# ------------------------------------------------------------------
# sp_text()
# sp_etext()
#
# Version: 5.0
# Forum translation routines
# run using the 'sp' domain
# ------------------------------------------------------------------
function sp_text($text) {
	return esc_attr(__($text, 'sp'));
}

# Version: 5.0
function sp_etext($text) {
	echo esc_attr(__($text, 'sp'));
}

# Version: 5.0
function sp_text_noesc($text) {
	return __($text, 'sp');
}

# ==================================================================
# option record handlers - uses table SFOPTIONS
# ==================================================================

# ------------------------------------------------------------------
# sp_get_option()
# Version: 5.0
# Retrieves SP option record - loads options into the $SPALLOPTIONS
# array if not already done
#
#	$option_name:		unique name of option record
#
# returns value or false if option_name doesn't exist
# ------------------------------------------------------------------
function sp_get_option($option_name) {
	global $SPALLOPTIONS;

	# check if the all options array is loaded
	if (empty($SPALLOPTIONS)) {
		$SPALLOPTIONS = array();
		$SPALLOPTIONS = sp_load_alloptions();
	}

	# set return value if exists
	if (!empty($SPALLOPTIONS) && $SPALLOPTIONS && array_key_exists($option_name, $SPALLOPTIONS)) {
		$value = sp_option_check($SPALLOPTIONS[$option_name]);
		$value = maybe_unserialize($value);
	} else {
		$value = false;
	}
	return $value;
}

# ------------------------------------------------------------------
# sp_add_option()
# Version: 5.0
# Adds new SP option record - If option_name alrewady exists then
# the current setting is updated instead
#
#	$option_name:		unique name of option record
#	$value:				the value for the new option_name
#
# returns nothing
# ------------------------------------------------------------------
function sp_add_option($option_name, $value = '') {
	global $wpdb, $SPALLOPTIONS;

	# make sure $SFALLOPTIONS has been populated before we try and use it
	if (empty($SPALLOPTIONS)) {
		$SPALLOPTIONS = array();
		$SPALLOPTIONS = sp_load_alloptions();
	}

	# Make sure the option doesn't already exist - call update if it does
	if (!empty($SPALLOPTIONS) && array_key_exists($option_name, $SPALLOPTIONS)) {
		sp_update_option($option_name, $value);
	} else {
		$value = maybe_serialize($value);
		$wpdb->insert(SFOPTIONS, array('option_name' => $option_name, 'option_value' => $value));
		$SPALLOPTIONS[$option_name] = $value;
	}
}

# ------------------------------------------------------------------
# sp_update_option()
# Version: 5.0
# Updates a SP option record - If option_name doesn't exists then
# a new option record is created
#
#	$option_name:		unique name of option record
#	$newvalue:			the value for the updated option_name
#
# returns true (success) false (failure or no data change)
# ------------------------------------------------------------------
function sp_update_option($option_name, $newvalue) {
	global $wpdb, $SPALLOPTIONS;

	$oldvalue = sp_get_option($option_name);
	if ($newvalue === $oldvalue) return false;

	if (!isset($SPALLOPTIONS[$option_name])) {
		sp_add_option($option_name, $newvalue);
		return true;
	}

	$newvalue = maybe_serialize($newvalue);
	$wpdb->update(SFOPTIONS, array('option_value' => $newvalue), array('option_name' => $option_name) );
	if ($wpdb->rows_affected == 1) {
		$SPALLOPTIONS[$option_name] = $newvalue;
		return true;
	}
	return false;
}

# ------------------------------------------------------------------
# sp_delete_option()
# Version: 5.0
# Deletes a SP option record if it exsists
#
#	$option_name:		unique name of option record
#
# returns true (success) false (failure or no option found)
# ------------------------------------------------------------------
function sp_delete_option($option_name) {
	global $SPALLOPTIONS;

	$option = spdb_select('row', "SELECT option_id FROM ".SFOPTIONS." WHERE option_name = '".$option_name."'");
	if (is_null($option) || !$option->option_id) return false;
	spdb_query( 'DELETE FROM '.SFOPTIONS." WHERE option_name = '$option_name'" );
	unset($SPALLOPTIONS[$option_name]);
	return true;
}

# ------------------------------------------------------------------
# sp_load_alloptions()
# Version: 5.0
# Loads all option data into the all $SPALLOPTIONS array
#
# returns array
# ------------------------------------------------------------------
function sp_load_alloptions() {
    # see if options table exists first
	$optionstable = spdb_select('var', "SHOW TABLES LIKE '".SF_PREFIX."sfoptions'");
	if($optionstable == false) return '';
	$alloptions_db = spdb_select('set', 'SELECT option_name, option_value FROM '.SF_PREFIX.'sfoptions');
	$alloptions = array();
	foreach ((array) $alloptions_db as $opt) {
		$alloptions[$opt->option_name] = $opt->option_value;
	}
	return $alloptions;
}

# ------------------------------------------------------------------
# sp_option_check()
# Version: 5.0
# Ensures that null vaslues are not returned bu option records
#
# returns value (success) false (failure)
# ------------------------------------------------------------------
function sp_option_check($value=false) {
	if (isset($value) && (!empty($value) || $value == 0)) {
		return $value;
	} else {
		return false;
	}
}

# ==================================================================
# member record handlers - uses table SFMEMBERS
# ==================================================================

# ------------------------------------------------------------------
# sp_get_member_row()
#
# Version: 5.0
# returns the members table content for specified user.
# NOTE: This us returned as an array - columns that require ot are
# NOT unserialized.
#	$userid:		User to lookup
# ------------------------------------------------------------------
function sp_get_member_row($userid) {
	global $SPSTATUS;

	if ($SPSTATUS != 'ok') return;

	$member = spdb_table(SFMEMBERS, "user_id=$userid", 'row', '', '', ARRAY_A);
	return $member;
}

# ------------------------------------------------------------------
# sp_get_member_item()
#
# Version: 5.0
# returns a specified column from members table for specified user.
# NOTE: This us returned as an var - columns that require it are
# returned unserialized.
#	$userid:		User to lookup
#	$item:			column name
# ------------------------------------------------------------------
function sp_get_member_item($userid, $item) {
	global $wpdb, $spThisUser;

	$thisitem = $wpdb->get_var("SELECT $item FROM ".SFMEMBERS." WHERE user_id = $userid");
	$thisitem = maybe_unserialize($thisitem);
	$thisitem = apply_filters('sph_memberdata_item', $thisitem, $userid, $item);

	return $thisitem;
}

# ------------------------------------------------------------------
# sp_update_member_item()
#
# Version: 5.0
# updates a specified column from members table for specified user.
# NOTE: Data requiring serialization must be passed as an array
# 'checktime' and 'lastvisit' are set to now() by the update code
#	$userid:		User to lookup
#	$itemname:		column name
#	$itemdata:		singe var or array
# ------------------------------------------------------------------
function sp_update_member_item($userid, $itemname, $itemdata) {
	global $wpdb, $spThisUser, $current_user;

	# hive off for cache updating if current user

	$itemdata = "'".maybe_serialize($itemdata)."'";

	# set 'lastvisit' or 'checktime' to 'now'
	if ($itemname == 'lastvisit' || $itemname == 'checktime') {
		sp_set_server_timezone();
		$itemdata = "'".sp_apply_timezone(time(), 'mysql', $userid)."'";
	}

	$sql = 'UPDATE '.SFMEMBERS." SET $itemname = $itemdata WHERE user_id=$userid";
	$sql = apply_filters('sph_memberdata_update_query', $sql, $itemname, $itemdata, $userid);
	$updateditem = $wpdb->query($sql);

    # allow plugins to add data
    do_action('sph_memberdata_update', $userid, $itemname, $itemdata);

    return $updateditem;
}

# = META TABLE HANDLERS ====================
# ------------------------------------------------------------------
# sp_add_sfmeta()
#
# Version: 5.0
# Adds a new record to the sfmeta table
#	$type:		The type of the meta record
#	$key:		The unique key name
#	$value:		value 
#	$autoload	Automatically load into $sfglobals
# ------------------------------------------------------------------
function sp_add_sfmeta($type, $key, $value, $autoload=0) {
	if (empty($type) || empty($key)) return false;

	# Check if already exists
	$sql = 	'SELECT meta_id FROM '.SFMETA.
			" WHERE meta_type='$type' AND meta_key='$key'";
	$check = spdb_select('var', $sql);

	# so - does it?
	if ($check) {
		# yes - so needs to be an update call
		sp_update_sfmeta($type, $key, $value, $check, $autoload);
	} else {
		$sql =  'INSERT INTO '.SFMETA.
				"(meta_type, meta_key, meta_value, autoload)
				VALUES
				('$type', '$key', '".maybe_serialize($value)."',$autoload)";
		spdb_query($sql);
	}
}

# ------------------------------------------------------------------
# sp_update_sfmeta()
#
# Version: 5.0
# Updates a record in the sfmeta table
#	$type:		The type of the meta record
#	$key:		The unique key name
#	$value:		value 
#	$id:		The meta records ID
# ------------------------------------------------------------------
function sp_update_sfmeta($type, $key, $value, $id, $autoload=0) {
	$sql =	'UPDATE '.SFMETA." SET
			 meta_type='$type',
			 meta_key='$key',
			 meta_value='".maybe_serialize($value)."',
			 autoload=$autoload
			 WHERE meta_id=$id";
	spdb_query($sql);
}

# ------------------------------------------------------------------
# sp_get_sfmeta()
#
# Version: 5.0
# Gets a record(s) from the sfmeta table
#	$type:		The type of the meta record
#	$key:		The unique key name - can be false to get all of type
#	$id:		If set then returns by id (one row regardless of $key)
# ------------------------------------------------------------------
function sp_get_sfmeta($type, $key=false, $id=0) {
	$WHERE = " meta_type='$type'";

	if ($id) {
		$WHERE.= " AND meta_id=$id";
	} else {
		if ($key) $WHERE.= " AND meta_key='$key'";
	}
	$sql =  'SELECT * FROM '.SFMETA." WHERE $WHERE ORDER BY meta_id";
	$records = spdb_select('set', $sql, ARRAY_A);
    if ($records) {
        foreach ($records as &$record) {
            $record['meta_value'] = maybe_unserialize($record['meta_value']);
        }
    }
	return $records;
}

# ------------------------------------------------------------------
# sp_delete_sfmeta()
#
# Version: 5.0
# Deletes a record in the sfmeta table
#	$id:		The meta records ID
# ------------------------------------------------------------------
function sp_delete_sfmeta($id) {
	$sql = 'DELETE FROM '.SFMETA." WHERE meta_id=$id";
	spdb_query($sql);
}

# = USER NOTICES HANDLING =====================
# Version: 5.0
function sp_add_notice($nData) {
	$spdb = new spdbComplex;
		$spdb->table	= SFNOTICES;
		$spdb->fields	= array('user_id', 'guest_email', 'post_id', 'link', 'link_text', 'message', 'expires');
		$spdb->data		= array($nData['user_id'], $nData['guest_email'], $nData['post_id'], $nData['link'], $nData['link_text'], $nData['message'], $nData['expires']);
	$spdb = apply_filters('sph_new_notice_data', $spdb);
	$spdb->insert();
}

# Version: 5.0
function sp_delete_notice($col, $data) {
	$sql = 'DELETE FROM '.SFNOTICES.' WHERE ';
	if(is_numeric($data)) {
		$sql.= "$col = $data";
	} else {
		$sql.= "$col = '$data'";
	}
	spdb_query($sql);
}

# = SPAM MATH HANDLING ========================
# Version: 5.0
function sp_math_spam_build() {
	$spammath[0] = rand(1, 12);
	$spammath[1] = rand(1, 12);

	# Calculate result
	$result = $spammath[0] + $spammath[1];

	# Add name of the weblog:
	$result .= get_bloginfo('name');
	# Add date:
	$result .= date('j').date('ny');
	# Get MD5 and reverse it
	$enc = strrev(md5($result));
	# Get only a few chars out of the string
	$enc = substr($enc, 26, 1).substr($enc, 10, 1).substr($enc, 23, 1).substr($enc, 3, 1).substr($enc, 19, 1);

	$spammath[2] = $enc;
	return $spammath;
}

# Version: 5.0
function sp_spamcheck() {
	$spamcheck = array();
	$spamcheck[0] = false;

	# Check dummy input field
	if (array_key_exists ('url', $_POST)) {
		if (!empty($_POST['url'])) {
			$spamcheck[0] = true;
			$spamcheck[1] = sp_text('Form not filled by human hands!');
			return $spamcheck;
		}
	}

	# Check math question
	$uKey = sp_get_option('spukey');
	$correct = sp_esc_str($_POST[$uKey.'2']);
	$test = sp_esc_str($_POST[$uKey.'1']);
	$test = preg_replace('/[^0-9]/','',$test);

	if ($test == '') {
		$spamcheck[0] = true;
		$spamcheck[1] = sp_text('No answer was given to the math question');
		return $spamcheck;
	}

	# Add name of the weblog:
	$test.= get_bloginfo('name');
	# Add date:
	$test.= date('j').date('ny');
	# Get MD5 and reverse it
	$enc = strrev(md5($test));
	# Get only a few chars out of the string
	$enc = substr($enc, 26, 1).substr($enc, 10, 1).substr($enc, 23, 1).substr($enc, 3, 1).substr($enc, 19, 1);

	if ($enc != $correct) {
		$spamcheck[0] = true;
		$spamcheck[1] = sp_text('The answer to the math question was incorrect');
		return $spamcheck;
	}
	return $spamcheck;
}

/* 	=====================================================================================

	sp_is_groupview()
	returns true if the current page being viewed is the spf group view (ie list of forums)
	parameters:
		none
 	===================================================================================*/

# Version: 5.0
function sp_is_groupview() {
	global $sfvars;
	return $sfvars['pageview'] == 'group';
}

/* 	=====================================================================================

	sp_is_forumview()
	returns true if the current page being viewed is the spf forum view (ie list of topics)
	parameters:
		none
 	===================================================================================*/

# Version: 5.0
function sp_is_forumview() {
	global $sfvars;
	return $sfvars['pageview'] == 'forum';
}

/* 	=====================================================================================
	sp_is_topicview()
	returns true if the current page being viewed is the spf topic view (ie list of posts)
 	===================================================================================*/
# Version: 5.0
function sp_is_topicview() {
	global $sfvars;
	return $sfvars['pageview'] == 'topic';
}

/* 	=====================================================================================
	sp_is_profileview()
	returns true if the current page being viewed is the spf profile view
 	===================================================================================*/
# Version: 5.0
function sp_is_profileview() {
	global $sfvars;
	return ($sfvars['pageview'] == 'profileedit' || $sfvars['pageview'] == 'profileshow');
}

/* 	=====================================================================================
	sp_is_listview()
	returns true if the current page being viewed is a spf members list page
 	===================================================================================*/
# Version: 5.0
function sp_is_listview() {
	global $sfvars;
	return $sfvars['pageview'] == 'list';
}

/* 	=====================================================================================
	sp_is_searchview()
	returns true if the current page being viewed is the spf is from search results
 	===================================================================================*/
# Version: 5.0
function sp_is_searchview() {
	global $sfvars;
	return $sfvars['searchpage'] == 1;
}

/* 	=====================================================================================
	sp_is_forumpage()
	returns true if the current page being viewed is an spf page
 	===================================================================================*/
# Version: 5.0
function sp_is_forumpage() {
	global $sfvars;
    return (!empty($sfvars['page']));
}

# ------------------------------------------------------------------
# sp_create_slug()
#
# Create a new slug
#	$itle:		Forum or Topic title
#	$type:		'forum', 'topic'
#	$checkdup	Check for duplicates (optional)
# ------------------------------------------------------------------
# Version: 5.0
function sp_create_slug($title, $type, $checkdup=true) {
	$slug = sanitize_title($title);
	if ($checkdup) $slug = sp_check_slug_unique($slug, $type);
	$slug = apply_filters('sph_create_slug', $slug, $type);
	return $slug;
}

# ------------------------------------------------------------------
# sp_check_slug_unique()
#
# Version: 5.0
# Check new slug is unique and not used. Add numeric suffix if
# exists. If slug receved is empty then return empty.
#	$itle:		Forum or Topic title new slug
#	$type:		'forum' or 'topic'
# ------------------------------------------------------------------
function sp_check_slug_unique($title, $type) {
	if (empty($title)) return '';
	$exists = true;
	$suffix = 1;
	$testtitle = $title;
	while ($exists) {
		$check = '';
		if ($type == 'forum') {
			$check = spdb_table(SFFORUMS, "forum_slug='$testtitle'", 'forum_slug');
		} elseif($type == 'topic') {
			$check = spdb_table(SFTOPICS, "topic_slug='$testtitle'", 'topic_slug');
		}
		if ($check) {
			$testtitle = $title.'-'.$suffix;
			$suffix++;
		} else {
			$exists = false;
		}
	}
	return $testtitle;
}

# = CENTRAL EMAIL ROUTINE =====================
# Version: 5.0
function sp_send_email($mailto, $mailsubject, $mailtext, $replyto='') {
    # only affect SP emails
    $sfmail = array();
    $sfmail = sp_get_option('sfmail');
    if ($sfmail['sfmailuse']) {
    	add_filter('wp_mail_from', 		'sp_mail_filter_from', 100);
    	add_filter('wp_mail_from_name', 'sp_mail_filter_name', 100);
    }

	$email_sent = array();
	if ($replyto <> '') {
		$header = "MIME-Version: 1.0\n".
		"From: wordpress@".preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']))."\n" .
		"Reply-To: {$replyto}\n" .
		"Content-Type: text/plain; charset=\"".get_settings('blog_charset')."\"\n";
		$email = wp_mail($mailto, $mailsubject, $mailtext, $header);
	} else {
		$email = wp_mail($mailto, $mailsubject, $mailtext);
	}

	if ($email == false) {
		$email_sent[0] = false;
		$email_sent[1] = sp_text('Email notification failed');
	} else {
		$email_sent[0] = true;
		$email_sent[1] = sp_text('Email notification sent');
	}
	return $email_sent;
}

# ------------------------------------------------------------------
# sp_mail_filter_from()
#
# Version: 5.0
# Filter Call
# Sets up the 'from' email options
#	$from:		Passed in to filter
# ------------------------------------------------------------------
function sp_mail_filter_from($from) {
	$sfmail = sp_get_option('sfmail');
	$mailfrom = $sfmail['sfmailfrom'];
	$maildomain = $sfmail['sfmaildomain'];
	if ((!empty($mailfrom)) && (!empty($maildomain))) $from = $mailfrom.'@'.$maildomain;
	return $from;
}

# ------------------------------------------------------------------
# sp_mail_filter_name()
#
# Version: 5.0
# Filter Call
# Sets up the 'from' email options
#	$from:		Passed in to filter
# ------------------------------------------------------------------
function sp_mail_filter_name($from) {
	$sfmail = sp_get_option('sfmail');
	$mailsender = $sfmail['sfmailsender'];
	if (!empty($mailsender)) $from = $mailsender;
	return $from;
}

# ------------------------------------------------------------------
# sp_check_url()
#
# Version: 5.0
# Check url has http (else browser will assume relative link
#	$url:		URL to be checked
# ------------------------------------------------------------------
function sp_check_url($url) {
	if ($url == 'http://' || $url == 'https://') $url = '';
	return $url;
}

# ------------------------------------------------------------------
# sp_get_ip()
#
# Version: 5.0
# Return the IP address of the current user
# Checks HTTP_X_FORWARDED_FOR in case of proxy or load balancer
# ------------------------------------------------------------------
function sp_get_ip() {
	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { # used by proxies and load balancers
	    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    $ip_array = explode(",", $ip);
	    $ip = $ip_array[count($ip_array) - 1]; # get last IP in list
	} else {
	    $ip = $_SERVER['REMOTE_ADDR']; # general case - just return remote address
	}
	return $ip;
}

# ------------------------------------------------------------------
# sp_array_insert()
#
# Version: 5.0
# Inserts a value into specified location within an array
# ------------------------------------------------------------------
function sp_array_insert(&$array, $value, $offset) {
    if (is_array($array)) {
        $array  = array_values($array);
        $offset = intval($offset);
        if ($offset < 0 || $offset >= count($array)) {
            array_push($array, $value);
        } elseif ($offset == 0) {
            array_unshift($array, $value);
        } else {
            $temp  = array_slice($array, 0, $offset);
            array_push($temp, $value);
            $array = array_slice($array, $offset);
            $array = array_merge($temp, $array);
        }
    } else {
        $array = array($value);
    }
    return count($array);
}

# Version: 5.0
function sp_strpos_arr($haystack, $needle) {
    if (!is_array($needle)) $needle = array($needle);
    foreach ($needle as $what) {
        if (($pos = strpos($haystack, $what)) !== false) return $pos;
    }
    return false;
}

# = SUCCESS/FAILURE NOTIFICATIONS==============
# ------------------------------------------------------------------
# sp_notify()
#
# Version: 5.0
# Creates a notification message
#	$type: 		0 = Success : 1 = Failure
#	$text:		Message text
# ------------------------------------------------------------------
function sp_notify($type, $text) {
	global $spThisUser;
	# test for extreme condition
	if (empty($spThisUser->trackid)) return;
	$data = serialize(array($type, $text));
	spdb_query('UPDATE '.SFTRACK."
			   SET notification='$data'
			   WHERE id=$spThisUser->trackid");
}

# ------------------------------------------------------------------
# sp_render_queued_notification()
#
# Version: 5.0
# Retrieves and renders a notification message
# NOTE: ALWAYS creates the spNotificationCenter div
# ------------------------------------------------------------------
function sp_render_queued_notification() {
	global $SPSTATUS, $spThisUser, $ISFORUMADMIN;
	if(isset($spThisUser) && $SPSTATUS == 'ok'  && $ISFORUMADMIN==false) {
		if (!empty($spThisUser->notification)) {
			$notification = $spThisUser->notification;
		} else {
			$notification = spdb_table(SFTRACK, 'id='.$spThisUser->trackid, 'notification');
		}
		if($notification) {
			# Remove it from sftrack
			spdb_query('UPDATE '.SFTRACK." SET notification='' WHERE id=$spThisUser->trackid");
			# And pass it through to the js for display
			$notification = unserialize($notification);
			if ($notification[0] == 0) {
				# Success = 0
				$icon = '<img class="spIcon" src="'.SPTHEMEICONSURL.'sp_Success.png" alt="" />';
				$class= "spMessageSuccess";
			} else {
				# Failure = 1
				$icon = '<img class="spIcon" src="'.SPTHEMEICONSURL.'sp_Failure.png" alt="" />';
				$class= "spMessageFailure";
			}
			apply_filters('sph_queued_notification', $notification[1]);
			$out.= '<div id="spNotification" class="'.$class.'">'.$icon.esc_js($notification[1]).'</div>';
			# inline script to display main message at top of screen
			do_action( 'sph_message', $out);
		}
	}
}

# inline functions to dislay messages
add_action('sph_message', 'sp_display_top_notification', 1, 1);
function sp_display_top_notification($msg) {
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	spjDisplayNotification('<?php echo($msg); ?>');
});
</script>
<?php
}

?>