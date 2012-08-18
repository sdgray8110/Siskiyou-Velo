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

if (isset($_GET['pageno'])) {
   $pageno = $_GET['pageno'];
} else {
   $pageno = 1;
} // if

$query = "SELECT * FROM posts WHERE draft != '1'";
$initResult = mysql_query($query, $connection) or trigger_error("SQL", E_USER_ERROR);
$query_data = mysql_fetch_row($initResult);
$numrows = mysql_num_rows($initResult);

$rows_per_page = 6;
$lastpage = ceil($numrows/$rows_per_page);

$limit = 'LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;
$pageno = (int)$pageno;

if ($pageno > $lastpage) {
   $pageno = $lastpage;
} // if
if ($pageno < 1) {
   $pageno = 1;
} // if

$limit = 'LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;

$result = mysql_query("SELECT * FROM posts WHERE draft != '1' ORDER BY ID DESC $limit", $connection);

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
		echo '<a class="commentLink" href="full_entry.php?id=' . $post_id . '#comments">No comments</a>';
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
echo '<p style="text-align:right;"><span class="paginate">';

echo "Page $pageno of $lastpage</span>";

if ($pageno == 1) {
   echo "<span class='null'><small>FIRST</small> | &laquo; PREV |</span>";
} else {
   echo " <a href='{$_SERVER['PHP_SELF']}?pageno=1'><small>FIRST</small></a> |";
   $prevpage = $pageno-1;
   echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$prevpage'>&laquo; PREV</a> ";
} // if



if ($pageno == $lastpage) {
   echo "<span class='null'>| NEXT &raquo; | <small>LAST</small> </span>";
} else {
   $nextpage = $pageno+1;
   echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$nextpage'>NEXT &raquo;</a> |";
   echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$lastpage'><small>LAST</small></a> ";
} 
echo '</p>';


?>

<?php
//Close connection
mysql_close($connection);

?>


