<script type="text/javascript">
$(document).ready(function() {
	$('div.comment').corner('7px');
	$('div.commentInfo').corner('tl 7px').corner('bl 7px');	
});
</script>

<?php 

include("../includes/db_connect.php");

$post_id = $_GET["comment"];
$results = mysql_query("SELECT * FROM sv_blogposts_comments WHERE postID = $post_id ORDER BY ID ASC", $connection);
$commentCount = mysql_num_rows($results);

echo '

<div class="comments">
';

if ($commentCount != 0) {echo '<h2>'.$commentCount.' Comments</h2>
';}
else {echo '<h2>No Comments</h2>
';}


while ($comments = mysql_fetch_array($results)) {
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

<form id="form_'.$post_id.'" class="newComment" method="post">
<h2>Comment Posted!</h2>
<p>Your comment has been posted. Thanks for visiting the Siskiyou Velo website. This is a public forum and we encourage visitors to comment on items posted here, however comments are subject to moderation by the Siskiyou Velo and inappropriate content will be removed. Repeated offenders may not be allowed to post again.</p>

<div style="clear:both"></div>
</form>
';

?>