<?php

class sponsorHelper {
    public function __construct() {
        $this->sponsorData = $this->get_sponsors();
    }

    private function get_sponsors() {
        $args = array(
            'numberposts' => -1,
            'order' => 'ASC',
            'post_type' => 'sponsor'
        );

        $posts = get_posts($args);
        $sponsorData = array();

        foreach ($posts as $sponsor) {
            $data = new stdClass();
            $data->name = $sponsor->post_title;
            $data->content = $sponsor->post_content;
            $data->images = $this->get_images($sponsor);
            $data->url = $this->get_url($sponsor);
            $data->modal_url = $this->get_modal_url($sponsor);

            $sponsorData[$sponsor->ID] = $data;
        }

        return $sponsorData;
    }

    private function get_images($sponsor) {
        $groupData = get_group('data',$sponsor->ID);

        return $groupData[1]['data_logo'][1];
    }

    private function get_url($sponsor) {
        $groupData = get_group('data',$sponsor->ID);

        return $groupData[1]['data_url'][1];
    }

    private function get_modal_url($sponsor) {
        $fmt = '%s/ajax/sponsor_modal.php?id=%s';

        return sprintf($fmt,get_stylesheet_directory_uri(),$sponsor->ID);
    }

}