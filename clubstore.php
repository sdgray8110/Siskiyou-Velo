<?php require_once("includes/auth.php"); ?>
<?php include("includes/header.php"); ?>
Club Store</title>
<?php include("includes/header_bottom.html"); ?>
<?php include("includes/login.php"); ?>
<?php include("includes/topnav.php"); ?>

    
<!------- BEGIN MAIN BODY ------->
<div id="leftContent">
<h1>Club Store &amp; Classifieds</h1>
<p style="margin-bottom:10px;">Siskiyou Velo Members <a href="member_classifieds.php">can post bicycle related items</a> for sale on our classifieds section. Only members can post, but the classifieds are visible to anyone visiting the site. This is a great way to sell your used gear, keep it in the area and avoid shipping charges. Items will be retained in the club store for 6 months before they expire. Sellers are responsible to return and remove items when the item is no longer available.</p>
<ul class="classifieds">
<?php include("includes/classifieds.php"); ?>
</ul>
    </div>
    
<!-------- END MAIN BODY -------->
    
<?php include("includes/generic_feed.html"); ?>  
<?php include("includes/foot.html"); ?>

