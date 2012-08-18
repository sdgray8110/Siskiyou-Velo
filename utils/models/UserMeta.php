<?php
class UserMeta extends ActiveRecord\Model {
    static $connection = 'wp';
    static $table_name = 'wp_usermeta';
    static $primary_key = 'umeta_id';
}