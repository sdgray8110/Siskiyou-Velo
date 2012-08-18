<?php require_once("login/auth_officer.php"); ?>
<?php include("includes/header.php"); ?>Upload Newsletter</title>
<?php include("includes/header_bottom.html"); ?>
<?php include("includes/login.php"); ?>
<?php include("includes/topnav.old.php"); ?>
<!------- BEGIN MAIN BODY ------->
<div id="leftContent">

<h1>Upload Newsletter</h1>

<form id="profile" enctype="multipart/form-data" action="includes/news_upload_exec.php" method="POST">
<dl><dt><label for='upload'>Choose a File: </label></dt>
<dd><input name="uploaded"  type="file" /><br />
<input type="submit" value="Upload" class="submit" /></dd></dl>
</form> 

</div>
<!-------- END MAIN BODY -------->
    
<?php include("includes/generic_feed.html"); ?>  
<?php include("includes/foot.html"); ?>