<?php 

//Connect to database
$connection = mysql_connect("localhost","gray8110","8aU_V{^{,RJC");
if (!$connection) {
	die ("Database connection failed: " . mysql_error());
}

$db_select = mysql_select_db("gray8110_etna",$connection);

if (!db_select) {
	die("Database selection failed: " . mysql_error());
} 
?>

<?php
$getID = $_GET["id"];
$result = mysql_query("SELECT * FROM posts WHERE draft != '1'AND userID = $getID ORDER BY ID DESC LIMIT 10", $connection);

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

echo '<h1><a href="full_entry.php?id=' . $post_id . '">' . $title . '</a></h1>
		<p class="postInfo"><span>' . $date . '</span> &mdash; posted by <a href="team.php#' . $userID . '">' . $username . '</a> | <span>';
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
?>

<?php
//Close connection
mysql_close($connection);

?>


