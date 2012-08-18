<?php
class userData {
    private static function setUserData() {
        if (!$current_user) {
            global $current_user;
            get_currentuserinfo();
        }

        return $current_user;
    }

    public static function loggedIn() {
        if (!$logged_in) {
            global $logged_in;
            $logged_in = is_user_logged_in();
        }

        return $logged_in;
    }

    public static function isOfficer() {
        return false;
    }

    public function fullname() {
        $user = $this->setUserData();

        return $user->user_firstname . ' ' . $user->user_lastname;
    }

    public function loggedInMessage() {
        $user = $this->setUserData();
        
        $logoutLink = sprintf('<a href="%s">Logout</a>', wp_logout_url(home_url()));
        $profileLink = sprintf('<a href="%s">Member Profile</a>', get_profile_link());
        $message = sprintf('You are logged in as: %s | %s | %s', $this->fullname(), $profileLink, $logoutLink);

        return $message;
    }
}

?>