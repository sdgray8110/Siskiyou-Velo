<?php
class NewPost extends ActiveRecord\Model {
    static $connection = 'wp';
    static $table_name = 'wp_posts';
}