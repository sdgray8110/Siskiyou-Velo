<?php
class NewUser extends ActiveRecord\Model {
    static $connection = 'wp';
    static $table_name = 'wp_users';
}