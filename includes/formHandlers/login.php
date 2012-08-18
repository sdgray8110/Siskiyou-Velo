<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/wordpress/wp-content/classes/class.svdb.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/wordpress/wp-content/classes/class.auth.php');
$auth = new auth;
$auth->login($_POST['username'], $_POST['password']);