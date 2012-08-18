<?php $thisPage = 'home' ?>

<?php require_once("includes/auth.php"); ?>
<?php include("includes/functions.php"); ?>
<?php include("includes/header.php"); ?>
Homepage</title>

<?php include("includes/header_bottom.html"); ?>
<?php include("includes/login.php"); ?>
<?php include("includes/topnav.php"); ?>
<div id="leftContent">
<?php include("includes/homepage_features.php"); ?>


<!------- BEGIN MAIN BODY ------->
<?php include("includes/blog_comments_include.php"); ?>

</div>

<!-------- END MAIN BODY -------->

<?php include("includes/generic_feed.html"); ?>
<?php include("includes/foot.html"); ?>

