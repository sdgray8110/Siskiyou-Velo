<?php 
$getEmail = $_GET['email'];
include('includes/db_connect.php');

	//Create update query
	$qry = "UPDATE wp_users SET
			emailOptOut = '1'					
			WHERE email1 = '$getEmail'";
        $qry2 = "DELETE FROM mlc_emails WHERE email = '$getEmail'";


if ($getEmail != '') {
	$result = @mysql_query($qry);
        $result2 = @mysql_query($qry2);
	
	//Check whether the query was successful or not
	if($result || $result2) {
		echo '<p><strong>'.$getEmail.'</strong> has been unsubscribed from future Siskiyou Velo email campaigns. If you wish to modify your email subscriptions, you can do so at any time via your member profile on the <a href="http://www.siskiyouvelo.org">Siskiyou Velo website</a>.</p>';
	}else {
		die("Query failed");
	}
}

else {echo '<p>There appears to have been a problem. Please <a href="mailto:webmaster@siskiyouvelo.org">contact the webmaster</a> to notify us.</p>';}
?>