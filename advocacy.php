<?php require_once("includes/auth.php"); ?>
<?php include("includes/header.php"); ?>
Advocacy | <?php echo $_GET["pageID"]; ?></title>
<script src="includes/js/lib/jquery.js"></script>  
<script type="text/javascript" src="includes/js/jquery.validate.js"></script>
  <script>
  $(document).ready(function(){
    $("#profile").validate();
  });
  </script>
<?php include("includes/header_bottom.html"); ?>
<?php include("includes/login.php"); ?>
<?php include("includes/topnav.php"); ?>

<div id="leftContent">
    
<!------- BEGIN MAIN BODY ------->
 <?php 
	//include("includes/adv_nav.php");
	
	
	$getPage = $_GET['pageID'];
	if ($getPage == 'Contacts') {
		include("includes/contacts.php"); 
	}
	
	elseif ($getPage == 'Issues') {
		include("includes/issues.php"); 
	}	

	elseif ($getPage == 'Portfolio') {
		include("includes/portfolio.php"); 
	}
	
	elseif ($getPage == 'Construction') {
		include("includes/construction.php"); 
	}
	
	elseif ($getPage == 'Hazards') {
		include("includes/hazards.php"); 
	}

	elseif ($getPage == 'HazardsDev') {
		include("includes/hazardsDev.php");
	}
	
	elseif ($getPage == 'Email Sent') {
		include("includes/hazards_sent.php"); 
	}

    elseif ($getPage == 'Safety') {
        include("includes/safety_tips.php");
    }
	
	else {
		include("includes/advocacy.php");
	}
	?>
    </div>
    
<!-------- END MAIN BODY -------->
    
<?php include("includes/generic_feed.html"); ?>  
<?php include("includes/foot.html"); ?>

