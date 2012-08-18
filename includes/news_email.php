<?php

$getSend = $_GET['send'];

if (!$getSend) {
include($_SERVER['DOCUMENT_ROOT'] . '/includes/db_connect.php');
$result = mysql_query("SELECT * FROM sv_newsbrief ORDER BY ID DESC LIMIT 1", $connection);
$row = mysql_fetch_array($result);

if ($row['sent'] != 1) {echo '<li><a href="includes/news_email.php?send=yes&height=200&width=300" class="thickbox">Send Newsletter Email</a></li>';}
}

else {

echo '
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js" type="text/javascript"></script>
<style type="text/css">
h2.sending {background:url(http://img3.musiciansfriend.com/dbase/usedGear/modalImages/loader.white.gif) 0 3px no-repeat; padding:0 0 0 25px;}
h2 {color:#333; font-family:verdana; font-size:18px; width:auto; margin:20px auto;}
</style>
<h2 class="sending">Sending...</h2>

<script type="text/javascript">
function sendEmail() {
	$.ajax({
	type: "GET",
	url: "email_templates/auto_email.php",
	data: "email=news",
  	success: function(){
		$("h2.sending").removeClass("sending").text("Email Sent Successfully!");
		},
	error: function() {
		alert("error");
		}
	});
						   
}

sendEmail();
</script>

';
}

?>
