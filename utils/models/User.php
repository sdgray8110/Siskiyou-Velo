<?php
class User extends ActiveRecord\Model {
    static $connection = 'legacy';
    static $table_name = 'wp_users';
}