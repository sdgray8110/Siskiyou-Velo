<?php

function blogpostQuery() {
    $rootContext = '/home/gray8110/public_html/';
    require_once($rootContext.'includes/db_connect.php');
    require_once($rootContext.'includes/functions.php');
    $result = mysql_query("SELECT * FROM sv_blogposts ORDER BY ID DESC LIMIT 4", $connection);

    return $result;
}

function homepageBlogPost($result) {
    if (!$result) {
        return 'Database connection failed';
    } else {
        while ($row = mysql_fetch_array($result)) {
            renderBlogPost($row);
        }
        
    }
}

function renderBlogPost($row) {
    $header = $row["header"];
    $body = $row["body"];
    $post_id = $row["ID"];

    echo
        '<div class="homepageNewsPost">
            <h1>' . $header . '</h1>'
            . $body .
            editPostMarkup($row) .
        '</div>';
}

function commentCount($post_id) {
    $rootContext = '/home/gray8110/public_html/';
    $commentResults = mysql_query("SELECT * FROM sv_blogposts_comments WHERE postID = $post_id ORDER BY ID ASC");
    $commentCount = mysql_num_rows($commentResults);
        if 	($commentCount == 1) {$commentCount = '1 Comment';}
        else {$commentCount = $commentCount.' Comments';}

    return $commentCount;
}

function editPostMarkup($row) {
    $author = $row["author"];
    $timestamp = getHowLongAgo($row["timestamp"]);
    $commentCount = commentCount($row["ID"]);
    trim($_SESSION['SESS_OFFICER']) != '' ? $officerStr = ' | <a href="blog_post_edit.php?id=' . $row["ID"] . '">Edit Post</a>' : $officerStr = '';

    return "<p class='blogInfo'>Posted by " . $author . " " . $timestamp . $officerStr. ' <strong>'.$commentCount.'</strong> | <span class="newComment" title="newComment_'.$row["ID"].'">View/Post New Comment</span></p>';
}

homepageBlogPost(blogpostQuery());

?>