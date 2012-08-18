<?php require_once("includes/auth.php"); ?>
<?php include("includes/header.php"); ?>
Ride Maps &amp; Descriptions</title>
<?php include("includes/header_bottom.html"); ?>
<?php include("includes/login.php"); ?>
<?php include("includes/topnav.php"); ?>

    
<!------- BEGIN MAIN BODY ------->
<div id="leftContent">
<h1>Ride Maps and Descriptions</h1>
<p style="padding-bottom:10px;">We have some of the most popular local rides organized into the various categories listed below. Clicking on each category will display a brief overview of each ride and links to a detailed ride page. As appropriate, scroll left or right to see additional rides in each category. Each ride page includes a detailed description, a ride map and elevation chart. Some rides also include photos taken along that course.</p>

<?php include("rides.php"); ?>

    </div>
    
<!-------- END MAIN BODY -------->
    
<?php include("includes/generic_feed.html"); ?>  
<?php include("includes/foot.html"); ?>

