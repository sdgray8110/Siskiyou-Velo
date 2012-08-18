<?php
if (!result) {
   die("Database query failed: " . mysql_error());
}

while ($row = mysql_fetch_array($result)) {

$username = $row["username"];
$userID = $row["userID"];
$title = $row["title"];
$post = $row["post"];
$timestamp = strtotime($row["timestamp"]);
$post_id = $row["ID"];
$date = date('n/j/Y', $timestamp);
$search = array('<p><img ');
$replace = array('<p class="postImg"><img ');
$post = str_replace($search, $replace, $post);

$comments = mysql_query("SELECT * FROM comments WHERE postID = $post_id ", $connection);

$total = mysql_num_rows($comments);

echo '<h1>' . $title . '</h1>
		<p class="postInfo"><span>' . $date . '</span> &mdash; posted by <a href="">' . $username . '</a> | <span>';
	if ($total < 1) {
		echo 'No comments';
	}
	
	elseif ($total > 1) {
		echo '<a class="commentLink" href="full_entry.php?id=' . $post_id . '#comments">' . $total . ' comments</a>';
	}
	
	else {
		echo '<a class="commentLink" href="full_entry.php?id=' . $post_id . '#comments">1 comment</a>';
	}
		 
echo	 '</span></p><img class="comment" align="bottom" src="images/comments.png" width="28" height="25" title="Comments" alt="comments" />
		<div class="post">
		' . $post . '
		</div>';

}

echo '<a name="comments"></a>';

$result = mysql_query("SELECT * FROM comments WHERE postID = $getID ORDER BY timestamp ASC", $connection);

if (!result) {
   die("Database query failed: " . mysql_error());
}

function checkNum($num){
  return ($num%2) ? TRUE : FALSE;
}

$position = 1;
while ($commentRow = mysql_fetch_array($result)) {

$name = $commentRow["name"];
$website = $commentRow["website"];
$comment = $commentRow["comment"];
$time = strtotime($commentRow["timestamp"]);
$commentDate = date('F j, Y | g:i a', $time);

echo '<p class="position">' . $position . '</p>';

if(checkNum($position) === TRUE){
echo '<div class="comments">';
}

else {
echo '<div class="commentsEven">';
}

if ($website == '') {
		echo '<p class="name">' . $name . '</p>';
}
	
else {
		echo '<p class="name"><a href="' . $website . '">' . $name . '</a></p>';		
}

echo '<p><span>' . $commentDate . '</span></p>
		<p>' . $comment . '</p>	  
	  </div>';

$position++; // increment the position
}

echo '<ul class="socialWeb">
		<li class="cc"><a target="_blank" href="http://cyclecluster.com/pliggit.php?url=http://www.etna-desalvo.com/full_entry.php?id=' . $getID . '">CycleCluster</a></li>
		<li class="fb"><a target="_blank" href="http://www.facebook.com/share.php?u=http://www.etna-desalvo.com/full_entry.php?id=' . $getID . '">Facebook</a></li>
		<li class="digg"><a target="_blank" href="http://digg.com/submit?url=http://www.etna-desalvo.com/full_entry.php?id=' . $getID . '&title=' . $title . '&bodytext=' . $description . '&topic=other_sports">Digg</a></li>
		<li class="email"><a href="javascript:pop(\'includes/email_pop.php\',\'524\',\'416\');">email</a></li>
	  </ul>';


?>


<?php
//Close connection
mysql_close($connection);

?>
<div class="post">
<p style="clear:both;"><strong>Post a comment</strong></p>

<form style="clear:both;" id="profile" name="profile" action="includes/comments_post.php" method="post" onSubmit="return validate();">

<p>
<input type="text" class="required" minlength="2" name="author" id="author" size="22" tabindex="1" />

<label for="author"><small>Name </small></label></p>

<p class="honeypot"><input type="text" name="email" id="email" size="22" tabindex="2" />
<label for="email"><small>Email (will not be published) 
</small></label>
</p>

<p><input type="text" name="honeypot" id="honeypot" class="required email" size="22" tabindex="2" />
<label for="email"><small>Email (will not be published) 
</small></label>
</p>

<p><input type="text" class="url" name="url" id="url" size="22" tabindex="3" />
<label for="url"><small>Website</small></label></p>

<p><textarea name="comment" id="comment" cols="50" rows="10" tabindex="4"></textarea></p>

<input type="hidden" name="postid" id="postid" value="<?php echo $post_id; ?>" />

<p><input name="submit" type="submit" id="submit" tabindex="5" value="Submit" />

</p>

</form>

</div>