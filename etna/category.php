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

$getID = $_GET['id'];
$pageTitle = 'Posts by Category';
include('includes/header.php');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Etna Brewing/DeSalvo Custom Cycles | Posts by Category | <?php echo $getID; ?></title>
<link href="2009.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<link rel="icon" type="image/x-icon" href="/favicon.png" />
</head>

<body>

<div id="wrapper">

	<?php include("includes/nav.php"); ?>
    
  <div id="leftContent">
<?php
if (!result) {
   die("Database query failed: " . mysql_error());
}

$result = mysql_query("SELECT * FROM posts WHERE category = '$getID' ORDER BY ID DESC LIMIT 10", $connection);
while ($row = mysql_fetch_array($result)) {

$username = $row["username"];
$userID = $row["userID"];
$title = $row["title"];
$post = $row["post"];
$timestamp = strtotime($row["timestamp"]);
$post_id = $row["ID"];
$date = date('n/j/Y', $timestamp);

$comments = mysql_query("SELECT * FROM comments WHERE postID = $post_id ", $connection);

$total = mysql_num_rows($comments);

echo '<h1><a href="full_entry.php?id=' . $post_id . '">' . $title . '</a></h1>
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

mysql_close($connection);

?>
  </div>
    
<?php include("includes/right_nav_include.php"); ?>

</div>

     <?php include("includes/footer.php"); ?>
     <?php include("includes/ga.php"); ?>   
</body>
</html>