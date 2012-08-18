<?php require_once("includes/auth.php"); ?>
<?php include("includes/header.php"); ?>
Links</title>

	<link rel="stylesheet" href="demo.css" /> 
	<script type="text/javascript" src="includes/js/lib/jquery.js"></script>
	<script type="text/javascript" src="includes/js/lib/jquery.dimensions.js"></script>
	<script type="text/javascript" src="includes/js/jquery.accordion.js"></script>

	<script type="text/javascript">
	jQuery().ready(function(){
		// simple accordion
		jQuery('#list1a').accordion({
			header: 'a.open',
			autoheight: false
		});
	});
	</script>

<?php include("includes/header_bottom.html"); ?>
<?php include("includes/login.php"); ?>
<?php include("includes/topnav.php"); ?>

    
<!------- BEGIN MAIN BODY ------->
<div id="leftContent">
<h1>Links</h1>
<p>The Web has many resources of excellent use for cyclists.The menus below organize some of our favorite sites into their appropriate categories. 	If you would like a link added, please <a href="mailto:webmaster@siskiyouvelo.org">contact us </a>with the site information and we'll consider adding it.</p>
<?php include("includes/links.php"); ?>

    </div>
    
<!-------- END MAIN BODY -------->
    
<?php include("includes/generic_feed.html"); ?>  
<?php include("includes/foot.html"); ?>

