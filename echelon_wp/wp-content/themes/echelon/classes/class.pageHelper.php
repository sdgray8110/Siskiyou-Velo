<?php
class pageHelper {

    public static function get_sponsors_list() {
        $args = array(
            'numberposts' => -1,
            'orderby' => 'post_title',
            'order' => 'ASC',
            'post_type' => 'sponsor',
            'post_status' => 'publish' );

        $sponsorData = get_posts($args);
        $sponsors = array();

        foreach ($sponsorData as $sponsor) {
            $data = new stdClass();
            $data->brand = $sponsor->post_title;
            $data->id = $sponsor->ID;

            $sponsors[] = $data;
        }

        return $sponsors;
    }

    public static function get_content($id) {
        $post = get_post($id);

        return $post->post_content;
    }

    public static function year() {
        return date('Y');
    }

    public static function first_item($i) {
        return $i == 0 ? ' class="active"' : '';
    }
}