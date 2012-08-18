<?php
class Posts extends ActiveRecord\Model {
    static $connection = 'legacy';
    static $table_name = 'sv_blogposts';
}