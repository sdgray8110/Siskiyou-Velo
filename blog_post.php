<?php require_once("login/auth_officer.php"); ?>
<?php include_once("fckeditor/fckeditor.php");?>
<?php include("includes/header.php"); ?>Homepage Blog Post</title>
<?php include("includes/header_bottom.html"); ?>
<?php include("includes/login.php"); ?>
<?php include("includes/topnav.old.php"); ?>
<!------- BEGIN MAIN BODY ------->
<div id="leftContent">

<h1>Create New Homepage Blog Post</h1>

<form id="profile" action="blog_exec.php" method="post">

<dl>
    <dt><label for="header">Headline:</label></dt>
    <dd><input type="text" name="header" id="header" /></dd>
</dl>

<?php
$FCKeditor = new FCKeditor('FCKeditor1') ;
$FCKeditor->BasePath = '/fckeditor/' ;
$FCKeditor->Height = '550px';
$FCKeditor->Create() ;
?>
    <br /><br />
    <input type="submit" value="Submit" class="submit">
  </form>


</div>
<!-------- END MAIN BODY -------->

<?php include("includes/generic_feed.html"); ?>  
<?php include("includes/foot.html"); ?>