<?php

class homepageHelper {
    function __construct($postCount = 8) {
        $this->carousel_photos = $this->get_homepage_photos();
        $this->blog_posts = $this->get_homepage_posts($postCount);
    }

    private function get_homepage_photos() {
        $args = array(
            'numberposts'=> 5,
            'post_type' => 'photo_carousel'
        );

        $posts = get_posts($args);
        $images = array();

        foreach ($posts as $post) {
            $data = get_group('content', $post->ID);

            $image = new stdClass();
            $image->title = get_the_title($post->ID);
            $image->photo = $data[1]['content_photo'][1]['thumb'];
            $image->description = $data[1]['content_description'][1];

            $images[] = $image;
        }

        shuffle($images);

        return $images;
    }

    private function get_homepage_posts($postCount) {
        $args = array(
            'numberposts' => $postCount,
            'post_type' => 'post'
        );

        return $this->query_posts($args);
    }

    private function get_posts_by_author($id) {
        $args = array(
            'numberposts' => -1,
            'post_type' => 'post',
            'author' => $id
        );

        $posts = $this->query_posts($args);
        $posts->author = get_user_meta($id, 'first_name',true) . ' ' . get_user_meta($id, 'last_name',true);

        return $posts;
    }

    private function query_posts($args) {
        $data = get_posts($args);
        $blogdata = new stdClass();
        $blogdata->posts = array();
        $blogdata->gallery_image = $this->get_gallery_image($data);

        foreach ($data as $post) {
            $postData = new stdClass();
            $postData->id = $post->ID;
            $postData->title = $post->post_title;
            $postData->date = formatDate($post->post_date_gmt,'m/d');
            $postData->permalink = get_permalink($post->ID);
            $postData->category = $this->category_name($post->ID);

            $blogdata->posts[] = $postData;
        }

        return $blogdata;
    }

    private function category_name($id) {
        $categories = get_the_category($id);
        $slugs = array('race-report', 'training');
        $categoryName = 'Update';

        foreach ($categories as $category) {
            if (in_array($category->slug,$slugs)) {
                $categoryName = $category->slug;
            }
        }

        return $categoryName;
    }

    private function get_gallery_image($posts) {
        shuffle($posts);
        $photo = new stdClass();

        foreach ($posts as $post) {
            $postData = new blogHelper($post->ID);

            if (count($postData->photos)) {
                shuffle($postData->photos);
                $photo->original = $postData->photos[0]->original;
                $photo->thumb = '/wp-content/plugins/magic-fields-2/thirdparty/phpthumb/phpThumb.php?src=' . $this->relativePathImage($photo->original) . '&amp;w=278&amp;h=278';
                break;
            }
        }

        return $photo;
    }

    private function relativePathImage($photo) {
        $delim = '/wp-content/';
        $arr = explode($delim,$photo);

        return $delim . $arr[1];
    }

    public function filter_posts($posts) {
        $author = $_GET['author_id'];

        if ($author) {
            return $this->get_posts_by_author($author);
        }

        return $posts;
    }

    public static function oddEven($i) {
        return $i % 2 ? 'evenRow' : 'oddRow';
    }
}