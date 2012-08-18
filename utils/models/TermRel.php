<?php
class TermRel extends ActiveRecord\Model {
    static $connection = 'wp';
    static $table_name = 'wp_term_relationships';
}