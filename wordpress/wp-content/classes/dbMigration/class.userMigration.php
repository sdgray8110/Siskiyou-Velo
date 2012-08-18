<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/utils/php-activerecord/ActiveRecord.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/utils/php-activerecord/config.inc.php';


class userMigration {
    public function __construct() {
        $this->users = $this->legacyUsers();
    }

    private function legacyUsers() {
        $users = User::all();

        foreach($users as $user) {
            if ($user->email1) {
                $id = $this->set_new_user($user);
                $this->set_new_usermeta($user,$id);
            }
        }

        return NewUser::all();
    }

    private function set_new_user($user) {
        $dateJoined = $user->datejoined != '0000-00-00 00:00:00' ? $user->datejoined : '2009-01-01 00:00:00';

        $data = array(
            'user_login' => $user->email1,
            'user_pass' => $user->user_pass,
            'user_email' => $user->email1,
            'user_url' => $user->website,
            'user_registered' => $dateJoined
        );

        $newUser = NewUser::create($data);

        return $newUser->id;
    }

    private function set_new_usermeta($user,$id) {
        $data = array(
            'legacy_id' => $user->id,
            'first_name' => $user->firstname,
            'last_name' => $user->lastname,
            'description' => $user->other,
            'nickname' => $user->firstname . ' ' . $user->lastname,
            'email2' => $user->email2,
            'address' => $user->address,
            'addressl2' => $user->addressl2,
            'city' => $user->city,
            'state' => $user->state,
            'zip' => $user->zip,
            'phone' => $user->phone,
            'officer' => $user->officer,
            'officer2' => $user->officer2,
            'officer3' => $user->officer3,
            'nl' => $user->nl,
            'type' => $user->type,
            'familymembers' => $user->familymembers,
            'comments' => $user->comments,
            'age' => $user->age,
            'bicycles' => $user->bicycles,
            'riding_style' => $user->riding_style,
            'riding_speed' => $user->riding_speed,
            'reason_for_joining' => $user->reason_for_joining,
            'referrer' => $user->referrer,
            'volunteering' => $user->volunteering,
            'displayphone' => $user->displayphone,
            'displayemail1' => $user->displayemail1,
            'displayemail2' => $user->displayemail2,
            'displaycontact' => $user->displaycontact,
            'displayaddress' => $user->displayaddress,
            'displaycity' => $user->displaycity,
            'displaystate' => $user->displaystate,
            'displayzip' => $user->displayzip,
            'committees' => $user->committees,
            'newsletter' => $user->newsletter,
            'rideleader' => $user->rideleader,
            'emailoptout' => $user->emailoptout,
            'pendingpayment' => $user->pendingpayment,
            'dateexpire' => $user->dateexpire,
            'daterenewed' => $user->daterenewed
        );

        foreach ($data as $key => $value) {
            $item = array(
                'user_id' => $id,
                'meta_key' => $key,
                'meta_value' => $value
            );

            UserMeta::create($item);
        }
    }
}