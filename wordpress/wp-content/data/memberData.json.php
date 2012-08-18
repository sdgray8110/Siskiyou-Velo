<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/wordpress/wp-content/classes/class.svdb.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/wordpress/wp-content/classes/class.memberData.php');

$memberData = new memberData();
echo $memberData->generateJSON();

?>