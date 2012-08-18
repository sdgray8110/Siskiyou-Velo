<?php
include('/home/gray8110/public_html/includes/db_connect.php');

$unsubscribe = $_GET['email'];
$qry = 'DELETE FROM mlc_emails WHERE email = "'.$unsubscribe.'"  LIMIT 1';
$result = mysql_query($qry,$connection);

if ($result) {
    echo $unsubscribe . ' has been removed from the Mountain Lakes Challenge mailing list';
} else {
    echo 'There was an error. Please <a href="mailto:webmaster@siskiyouvelo.org">contact the webmaster</a>';
}