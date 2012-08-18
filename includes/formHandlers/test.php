<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/wordpress/wp-content/classes/class.auth.php');
$auth = new auth;
$roles = $auth->init();

print_r($roles);