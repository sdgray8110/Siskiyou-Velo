<?php require_once("includes/auth.php"); ?>
<?php include("includes/header.php"); ?>
Login Required</title>
<?php include("includes/header_bottom.html"); ?>
<?php include("includes/login.php"); ?>
<?php include("includes/topnav.php"); ?>
<!------- BEGIN MAIN BODY ------->
<div id="leftContent">

<h1>Officers Only </h1>
<p>The page you have attempted to view is available only to officers of the Siskiyou Velo. If you believe you are getting this page in error, please <a href="mailto:webmaster@siskiyouvelo.org">contact the webmaster</a>.</p>

<?php include("login/login-form.php"); ?>

    </div>
    
<!-------- END MAIN BODY -------->
    
<?php include("includes/generic_feed.html"); ?>  
<?php include("includes/foot.html"); ?>