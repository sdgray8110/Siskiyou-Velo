<?php
/*
Simple:Press
User Class
$LastChangedDate: 2012-04-06 12:33:06 -0700 (Fri, 06 Apr 2012) $
$Rev: 8281 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

# --------------------------------------------------------------------------------------
#
#	User Class
#
# 	Version: 5.0
#	Pass in a user ID. 0 or null denotes a guest
#	Pass in the user login as an alternative
#
#--------------------------------------------------------------------------------------

class spUser {
	public $list = array();

	# ------------------------------------------
	#	spUser_build_list()
	#	Master list of data that is retrieved
	#	from users and usermeta tables along
	#	with the filter to apply
	# ------------------------------------------
	function spUser_build_list() {
		global $sfglobals;

		$this->list = array(
			'user_login' 		=> 'name',
			'user_email' 		=> 'email',
			'user_url'			=> 'url',
			'user_registered' 	=> '',
			'description'		=> 'text',
			'location'			=> 'title',
			'first_name'		=> 'name',
			'last_name'			=> 'name',
			'aim'				=> 'title',
			'yim'				=> 'title',
			'jabber'			=> 'title',
			'msn'				=> 'title',
			'icq'				=> 'title',
			'skype'				=> 'title',
			'facebook'			=> 'title',
			'myspace'			=> 'title',
			'twitter'			=> 'title',
			'linkedin'			=> 'title',
			'youtube'			=> 'title',
			'googleplus'		=> 'title',
			'display_name'		=> 'name',
			'signature'			=> 'signature',
			'guest_name'		=> 'name',
			'guest_email'		=> 'email',
			'sp_change_pw'		=> '',
			'photos'		    => '',
		);

        # allow plugins to add more usermeta class data
        $this->list = apply_filters('sph_user_class_meta', $this->list);
	}

	# ------------------------------------------
	#	spUser_filter()
	#	The display filter calls based upon
	#	the array of user entered data and
	#	filters to apply
	# ------------------------------------------
	function spUser_filter($item, $filter) {
		switch ($filter) {
			case 'title':
				$this->$item = sp_filter_title_display($this->$item);
				break;
			case 'email':
				$this->$item = sp_filter_email_display($this->$item);
				break;
			case 'url':
				$this->$item = sp_filter_url_display($this->$item);
				break;
			case 'text':
				$this->$item = sp_filter_text_display($this->$item);
				break;
			case 'name':
				$this->$item = sp_filter_name_display($this->$item);
				break;
			case 'signature':
				$this->$item = sp_filter_signature_display($this->$item);
				break;
		}
	}

	# ------------------------------------------
	#	spUser
	#	$ident		user id or user login
	#	$current	set to true for $spThisUser
	# ------------------------------------------
	function __construct($ident=0, $current=false) {
		global $SPSTATUS, $sfglobals;

		$id = 0;
		if (is_numeric($ident)) {
			$w = "ID=$ident";
		} elseif ($ident != false) {
			$w = "user_login='$ident'";
		}
		if ($ident) {
			# Users data
			$d = spdb_table(SFUSERS, $w, 'row');
			if ($d) {
				$this->ID = $d->ID;
				$id = $d->ID;
			}
		}

		$this->spUser_build_list();

		if ($id) {
			# Others
			$this->member = true;
			$this->guest = 0;
			$this->guest_name = '';
			$this->guest_email = '';
			$this->offmember = false;
			$this->usertype = 'User';

			# Users data
			foreach ($d as $key => $item) {
				if (array_key_exists($key, $this->list)) {
					$this->$key = $item;
				}
			}
			$this->user_registered = sp_member_registration_to_server_tz($this->user_registered);

			# usermeta data
			$d = spdb_table(SFUSERMETA, "user_id=$id");
			if ($d) {
				foreach( $d as $m) {
					$t = $m->meta_key;
					if (array_key_exists($t, $this->list)) {
						$this->$t = maybe_unserialize($m->meta_value);
					}
				}
			}

			# sfmembers data
			$d = spdb_table(SFMEMBERS, "user_id=$id", 'row');
			#check for ghost user
			if(empty($d)) {
				#create the member
				sp_create_member_data($id);
				$d = spdb_table(SFMEMBERS, "user_id=$id", 'row');
			}
			if ($d) {
				foreach($d as $key => $item) {
					if ($key == 'admin_options' && !empty($item)) {
						$opts = unserialize($item);
						foreach ($opts as $opt => $set) {
							$this->$opt = $set;
						}
					} elseif ($key=='user_options' && !empty($item)) {
						$opts = unserialize($item);
						foreach ($opts as $opt => $set) {
							$this->$opt = $set;
						}
					} elseif ($key == 'lastvisit') {
						$this->lastvisit = $item;
					} else {
						$this->$key = maybe_unserialize($item);
					}
				}
			}

			# Check for new post list size
			if(!isset($this->unreadposts) || empty($this->unreadposts)) {
				$controls = sp_get_option('sfcontrols');
				if(empty($controls['sfunreadposts']) ? $this->unreadposts=50 : $this->unreadposts=$controls['sfunreadposts']);
			}

			# usertype for moderators
			if ($this->moderator) $this->usertype = 'Moderator';

			# check for super admins and make admin a moderator as well
			if ($this->admin || (is_multisite() && is_super_admin($id))) {
				$this->admin = true;
				$this->moderator = true;
				$this->usertype = 'Admin';
				$ins = sp_get_option('spInspect');
				if (!empty($ins) && array_key_exists($id, $ins)) {
					$this->inspect = $ins[$id];
				} else {
					$this->inspect = '';
				}
			}
		} else {
			# some basics for guests
			$this->ID = 0;
			$this->guest = true;
			$this->member = 0;
			$this->admin = false;
			$this->moderator = false;
			$this->display_name = 'guest';
			$this->guest_name = '';
			$this->guest_email = '';
			$this->usertype = 'Guest';
			$this->offmember = sp_check_unlogged_user();
			$this->timezone = 0;
			$this->timezone_string = '';
			$this->posts = 0;
			$this->avatar = '';
			$this->user_email = '';

			# check the cookie for a guest
			if($current) {
				if (isset($_COOKIE['guestname_'.COOKIEHASH])) $this->guest_name = $_COOKIE['guestname_'.COOKIEHASH];
				if (isset($_COOKIE['guestemail_'.COOKIEHASH])) $this->guest_email = $_COOKIE['guestemail_'.COOKIEHASH];
				$this->display_name = $this->guest_name;
				if (empty($this->guest_name)) $this->guest_name = '';
				if (empty($this->guest_email)) $this->guest_email = '';
			}
			$this->auths = sp_get_option('sf_guest_auths');
	        $this->memberships = sp_get_option('sf_guest_memberships');
		}

		# Only perform this last section if forum is operational
		if ($SPSTATUS == 'ok') {
			# Ranking
			$this->rank = sp_get_user_forum_rank($this->usertype, $id, $this->posts);
			$this->special_rank = sp_get_user_special_ranks($id);

			# if no memberships rebuild them and save
			if (empty($this->memberships)) {
				$memberships = array();
				if (!empty($id)) {
					# get the usergroup memberships for the user and save in sfmembers table
					$memberships = sp_get_user_memberships($id);
					sp_update_member_item($id, 'memberships', $memberships);
				} else {
					# user is a guest or unassigned member so get the global permissions from the guest usergroup and save as option
					$value = sp_get_sfmeta('default usergroup', 'sfguests');
					$memberships[] = spdb_table(SFUSERGROUPS, 'usergroup_id='.$value[0]['meta_value'], 'row', '', '', ARRAY_A);
					sp_update_option('sf_guest_memberships', $memberships);
				}
				# put in the data
				$this->memberships = $memberships;
			}
			# if no auths rebuild them and save
			if (empty($this->auths)) {
				$this->auths = sp_rebuild_user_auths($id);
			}
		}

		$this->ip = sp_get_ip();
		$this->trackid = 0;

		# Things to do if user is current user
		if ($current) {
			# Set up editor type
			$sfglobals['editor']=0;
			# for a user...
			if ($this->member && !empty($this->editor)) $sfglobals['editor'] = $this->editor;

			# and if not defined or is for a guest...
			if ($sfglobals['editor'] == 0) {
				$defeditor = sp_get_option('speditor');
				if (!empty($defeditor)) $sfglobals['editor'] = $defeditor;
			}
			# final check to ensure selected editor type is indeed available
			if (($sfglobals['editor'] == 0) ||
				($sfglobals['editor'] == 1 && !defined('RICHTEXT')) ||
				($sfglobals['editor'] == 2 && !defined('HTML')) ||
				($sfglobals['editor'] == 3 && !defined('BBCODE'))) {

				$sfglobals['editor'] = PLAINTEXT;
				if (defined('BBCODE')) 		$sfglobals['editor'] = BBCODE;
				if (defined('HTML')) 		$sfglobals['editor'] = HTML;
				if (defined('RICHTEXT')) 	$sfglobals['editor'] = RICHTEXT;
			}

			# Grab any notices present
			if ($this->guest && !empty($this->guest_email)) {
				$this->user_notices = spdb_table(SFNOTICES, "guest_email='".$this->guest_email."'", '', $order='notice_id');
			} elseif ($this->member && !empty($this->user_email)) {
				$this->user_notices = spdb_table(SFNOTICES, "user_id=".$this->ID, '', $order='notice_id');
			}

			# allow plugins to add items to user class - ONLY for current user ($spThisUser)
            do_action_ref_array('sph_current_user_class', array(&$this));
		}

		# Finally filter the data for display
		foreach ($this->list as $item => $filter) {
			if (property_exists($this, $item)) $this->spUser_filter($item, $filter);
		}

		# allow plugins to add items to user class
        do_action_ref_array('sph_user_class', array(&$this));
	}
}

?>