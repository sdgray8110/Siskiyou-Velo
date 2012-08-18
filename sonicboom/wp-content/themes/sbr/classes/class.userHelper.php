<?php
class userHelper {
    public function __construct() {
        $this->user_ids = get_users(array('fields' => 'ID'));
        $this->cat = $_GET['cat'];
        $this->allUsers = $this->get_users();
        $this->pageHeader = $this->set_team_header();
    }

    private function get_users() {
        $users = array();

        foreach ($this->user_ids as $id) {
            if ($id != 1) {
                $data = $this->set_user_data($id);

                if ($this->filterData($data)) {
                    $users[] = $data;
                }
            }
        }

        objectSort($users,'lastName');

        return $users;
    }

    private function filterData($data) {
        $cat = $this->cat;

        if (!$cat) {
            return true;
        } elseif (is_numeric($cat)) {
            return $cat == $data->category;
        }

        return $data->$cat;
    }

    private function set_user_data($id) {
        $user = new stdClass();
        $user->id = $id;
        $user->firstName = get_user_meta($id,'first_name',true);
        $user->lastName = get_user_meta($id,'last_name',true);
        $user->name = $this->get_user_name($id,$user);
        $user->usac_license = get_user_meta( $id, 'usac_license', true );
        $user->short_bio = get_user_meta($id, 'short_bio', true );
        $user->category = get_user_meta($id, 'road_category', true);
        $user->elite = get_user_meta($id, 'elite_team', true );
        $user->masters = get_user_meta($id, 'masters', true );
        $user->photos = $this->get_user_photo($id);

        return $user;
    }

    private function get_user_name($id,$user) {
        $fmt = '%s %s';
        return sprintf($fmt,$user->firstName,$user->lastName);
    }

    private function get_user_photo($id) {
        $photos = new stdClass();
        $photos->approval = get_user_meta($id,'userphoto_approvalstatus', true);
        $photos->filename = get_user_meta($id,'userphoto_image_file', true);
        $photos->thumb_filename = get_user_meta($id,'userphoto_thumb_file', true);

        return $this->eval_photo($photos);
    }

    private function eval_photo($photos) {
        $defaultImage = get_stylesheet_directory_uri() . '/images/image_coming_soon.png';
        $userImage = '/wp-content/uploads/userphoto/' . $photos->filename;

        $photos->photo = $photos->approval == 2 ? $userImage : $defaultImage;

        return $photos;
    }

    private function set_team_header() {
        $cat = $this->cat ? $this->cat : 'default';
        $year = date('Y');
        $fmt = '%s %s';

        $headers = array(
            'default' => 'Sonic Boom Racing - Full Team',
            'elite' => 'Sonic Boom Racing Elite Team',
            'masters' => 'Sonic Boom Racing Masters 35+ Team',
            1 => 'Sonic Boom Racing Category 1 Riders',
            2 => 'Sonic Boom Racing Category 2 Riders',
            3 => 'Sonic Boom Racing Category 3 Riders',
            4 => 'Sonic Boom Racing Category 4 Riders'
        );

        return sprintf($fmt,$year,$headers[$cat]);
    }
}