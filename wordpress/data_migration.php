<?php
require_once('wp-content/classes/dbMigration/class.userMigration.php');
require_once('wp-content/classes/dbMigration/class.postMigration.php');


// User Migration
/*
$migration = new userMigration();
print_r($migration);
*/

// Post Migration
$migration = new postMigration();
$migration->migrate_posts();

