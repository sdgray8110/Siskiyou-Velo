<?php require_once("includes/auth.php"); ?>
<?php include("includes/header.php"); ?>
Homepage</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js" type="text/javascript"></script>
<script src="includes/js/validate.js" type="text/javascript"></script>
<script src="includes/js/corners.js" type="text/javascript"></script>
<script src="includes/js/commentsAjax.js" type="text/javascript"></script>

<?php include("includes/header_bottom.html"); ?>
<?php include("includes/login.php"); ?>
<?php include("includes/topnav.php"); ?>
<?php include("includes/homepage_features.html"); ?>

    
<!------- BEGIN MAIN BODY ------->
<?php include("includes/blog_comments_include.php"); ?>
        </div>
    </div>
    
<!-------- END MAIN BODY -------->
    
<?php include("includes/generic_feed.html"); ?>  
<?php include("includes/foot.html"); ?>

