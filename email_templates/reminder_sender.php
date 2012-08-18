<?php
require_once('/home/gray8110/public_html/wordpress/wp-content/classes/class.svdb.php');
$svdb = new svdb();
$svdb->send_reminder_emails();
$svdb->send_expiration_emails();
$svdb->send_renewal_emails();
$svdb->send_join_emails();