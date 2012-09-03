<?php
class blogHelper {
    public function __construct($id) {
        $this->id = $id;
        $this->post = get_post($id);
        $this->author = $this->get_author();
        $this->title = $this->post->post_title;
        $this->date = $this->post_date();
        $this->permalink = get_permalink($id);
        $this->photos = $this->get_photo_gallery();
        $this->author_archive_link = $this->get_author_link();
        $this->metadata = $this->get_meta();
    }

    private function get_author() {
        $id =$this->post->post_author;

        $userdata = new stdClass;
        $userdata->id =$id;
        $userdata->firstname =  get_user_meta($id, 'first_name',true);
        $userdata->lastname =  get_user_meta($id, 'last_name',true);
        $userdata->nickname =  get_user_meta($id, 'nickname',true);


        return $userdata;
    }

    private function post_date() {
        $postInfo = new stdClass();
        $postInfo->date = formatDate($this->post->post_date_gmt,'m/d/Y');
        $postInfo->shortDate = formatDate($this->post->post_date_gmt,'m/d');
        $postInfo->month = formatDate($this->post->post_date_gmt,'F');
        $postInfo->year = formatDate($this->post->post_date_gmt,'Y');

        return $postInfo;
    }

    private function get_photo_gallery() {
        $groupData = get_group('photo_gallery',$this->id);
        $photos = array();
        $i = 1;

        foreach ($groupData as $photo) {
            $data = new stdClass();
            $data->thumb = $photo['photo_gallery_photo'][1]['thumb'];
            $data->original = $photo['photo_gallery_photo'][1]['original'];
            $data->caption = $photo['photo_gallery_caption'][1];
            $data->class = $this->middle_item($i);

            $photos[] = $data;
            $i++;
        }

        return $photos;
    }

    private function middle_item($i) {
        return ($i % 3) - 2 == 0 ? ' class="middle"' : '';
    }

    private function get_author_link() {
        return '/blog?author_id=' . $this->author->id;
    }

    public static function get_blog_posts() {
        $args = array(
            'numberposts' => -1,
            'post_type' => 'post'
        );

        return get_posts($args);
    }

    public static function get_sidebar_posts() {
        $posts = blogHelper::get_blog_posts();
        $postData = new stdClass();
        $postData->posts = array();
        $postData->months = array();

        foreach ($posts as $item) {
            $post = new blogHelper($item->ID);
            $thisPost = new stdClass();
            $thisPost->title = $post->title;
            $thisPost->truncatedTitle = truncateMarkup($post->title,50);
            $thisPost->permalink = $post->permalink;
            $thisPost->date = $post->date;

            $monthStr = $post->date->month . ' ' . $post->date->year;

            if (!$postData->posts[$monthStr]) {
                $postData->posts[$monthStr] = array();
            }

            $postData->posts[$monthStr][] = $thisPost;

            if (!in_array($monthStr,$postData->months) && count($postData->months <= 10)) {
                $postData->months[] = $monthStr;
            }

        }

        return $postData;
    }

    private function get_meta() {
        $data = new stdClass();
        $data->win = ac_get_field('race_win', $this->id);
        $data->classname = $data->win[0] ? 'race_win' : '';

        return $data;
    }
}
