<?php 
//Connect to database
include('includes/db_connect.php');
?>

<?php
$username = $_SESSION['SESS_FIRST_NAME']. " " . $_SESSION['SESS_LAST_NAME'];
$memberEmail = $_SESSION['SESS_EMAIL'];
$memberTitle = $_SESSION['SESS_TITLE'];
$result = mysql_query("SELECT * FROM sv_blogposts ORDER BY ID DESC LIMIT 4", $connection);
$eol = array("\r\n", "\r", "\n");

if (!result) {
   die("Database query failed: " . mysql_error());
}

while ($row = mysql_fetch_array($result)) {

$header = $row["header"];
$body = $row["body"];
$author = $row["author"];
$timestamp = $row["timestamp"];
$post_id = $row["ID"];
$commentResults = mysql_query("SELECT * FROM sv_blogposts_comments WHERE postID = $post_id ORDER BY ID ASC", $connection);
$commentCount = mysql_num_rows($commentResults);
	if 	($commentCount == 1) {$commentCount = '1 Comment';}
	else {$commentCount = $commentCount.' Comments';}

echo
    "<div class='homepageNewsPost'>
    <h1>" . $header . "</h1>"
		. $body . 
		"<p class='blogInfo'>Posted by " . $author . " " . getHowLongAgo($timestamp);
if (trim($_SESSION['SESS_OFFICER']) != '') {
	echo " | <a href='blog_post_edit.php?id=" . $post_id .  "'>Edit Post</a>";
}
	echo " | <strong>".$commentCount."</strong> | <span class='newComment' title='newComment_".$post_id."'>View/Post New Comment</span></p>";


echo '

<div id="newComment_'.$post_id.'" style="display:none;" class="newComment">
<div class="comments">
';

echo '<h2>This Post Has '.$commentCount.'</h2>';

while ($comments = mysql_fetch_array($commentResults)) {
	$poster = $comments["name"];
	$posterTitle = $comments["title"];
	$postDate = date("n/j/Y | g:ia",strtotime($comments["time"])); 
	$subject = $comments["subject"]; 
	$commentBody = str_replace($eol,'<br />',$comments["comment"]);
	$ipAddress = $comments["ipAddress"];

echo '
    <div class="commentContainer">
        <div class="commentInfo">
            <p class="poster">'.$poster.'</p>
            <p class="posterTitle">'.$posterTitle.'</p>
            <p class="postDate">'.$postDate.'</p>
        </div>
 
        <div class="comment">          
        <h4>'.$subject.'</h4>
        <p class="comment">'.$commentBody.'</p>';

	if (!isset($_SESSION['SESS_MEMBER_ID']) || (trim($_SESSION['SESS_MEMBER_ID']) == '')) {
    }
    else {
		
		if ($ipAddress != '') {
			echo  '
				<p class="ipAddress"><strong>IP Address: </strong>'.$ipAddress.' | <a href="">Delete Comment &amp; Block IP</a></p>';
		}
	}
	echo '
        </div>
    </div>
	<div style="clear:both;"></div>
';
}

echo '
</div>
<div class="formCont">
<form id="form_'.$post_id.'" class="newComment">
<h2>Post A New Comment &raquo;</h2>
<!--<p>Comments have been temporarily disabled - we apologize for the inconvenience.</p>-->

<div class="commentDetails">
<label for="name">Name:</label>';

if ($username != ' ') {echo '<input id="name" name="name" type="text" value="'.$username.'" tabindex="3" validation="required" />';}
else echo '<input id="name" name="name" type="text" tabindex="3" validation="required" />';

echo '
<span class="hidden">
<label for="email">Email: (Not Published)</label>
<input id="email" name="email" type="text" />
</span>
<input type="hidden" name="ipAddress" id="ipAddress" value="'.$Users_IP_address.'" />

<label for="scooby">Email: (Not Published)</label>
<input id="scooby" name="scooby" type="text" tabindex="4" value="'.$memberEmail.'" validation="required email" />

<label for="subject">Subject:</label>
<input id="subject" name="subject" type="text" tabindex="5" validation="required" />

<input id="postID" name="postID" type="hidden" value="'.$post_id.'" />';
if ($memberTitle != '') {echo '<input id="title" name="title" type="hidden" value="'.$memberTitle.'" />';}
else echo '<input id="title" name="title" type="hidden" value="Site Visitor" />';

echo '
<div id="submit_'.$post_id.'" name="submit" class="submit">Submit Comment &raquo;</div>
</div>

<div class="comment">
<label for="comment">Comment:</label>
<textarea id="comment" name="comment" class="comment" tabindex="6" validation="required" /></textarea>
</div>
<div style="clear:both"></div>

</form>
</div>
</div>
</div>
';

}

?>

<?php
//Close connection
mysql_close($connection);

?>