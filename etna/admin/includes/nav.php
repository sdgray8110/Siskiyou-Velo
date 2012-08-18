        <ul class="nav">
<?php 
$pageget = $_SERVER['REQUEST_URI'];
$posts = "post";
$comments = "comments";
$links = "links";
$team = "team";
$schedule = "schedule";
$email = "email";
$upload = "upload";

			if ($pageget == "/admin/") {echo '<li class="active"><a href="/admin/">Add New Post</a></li>';}
			else {echo '<li><a href="/admin/">Add New Post</a></li>';}
            
			if (strpos($pageget, $posts) == true) {echo '<li class="active"><a href="/admin/?pageID=posts">Edit Post</a></li>';}
            else {echo '<li><a href="/admin/?pageID=posts">Edit Post</a></li>';}
			
/**			if (strpos($pageget, $comments) == true) {echo '<li class="active"><a href="/admin/?pageID=comments">Comments</a></li>';}
			else {echo '<li><a href="/admin/?pageID=comments">Comments</a></li>';}
**/			
			if (strpos($pageget, $team) == true) {echo '<li class="active"><a href="/admin/?pageID=team">Edit Profile</a></li>';}
			else {echo '<li><a href="/admin/?pageID=team">Edit Profile</a></li>';}
/**
			if (strpos($pageget, $links) == true) {echo '<li class="active"><a href="/admin/?pageID=links">Links</a></li>';}
			else {echo '<li><a href="/admin/?pageID=links">Links</a></li>';}
**/				
			if (strpos($pageget, $schedule) == true) {echo '<li class="active"><a href="/admin/?pageID=schedule">Race Schedule</a></li>';}
			else {echo '<li><a href="/admin/?pageID=schedule">Race Schedule</a></li>';}
			
			if (strpos($pageget, $email) == true) {echo '<li class="active"><a href="/admin/?pageID=email">Team Email</a></li>';}
			else {echo '<li><a href="/admin/?pageID=email">Team Email</a></li>';}

            if ($_SESSION['SESS_LAST_NAME'] == 'DeSalvo' || $_SESSION['SESS_LAST_NAME'] == 'Gray') {
                if (strpos($pageget, $upload) == true) {echo '<li class="active"><a href="/admin/?pageID=upload">File Upload</a></li>';}
                else {echo '<li><a href="/admin/?pageID=upload">File Upload</a></li>';}
            }

?>
			<li><a target="_blank" href="../../">Blog Homepage</a></li>			
        </ul>
		
