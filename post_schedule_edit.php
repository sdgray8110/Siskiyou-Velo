<?php require_once("login/auth_officer.php"); ?>
<?php include("includes/header.php"); ?>Post Ride Schedule</title>

<script src="includes/js/lib/jquery.js"></script>  
<script type="text/javascript" src="includes/js/jquery.validate.js"></script>
  <script>
  $(document).ready(function(){
    $("#schedpage").validate();
  });
  </script>

<?php include("includes/header_bottom.html"); ?>
<?php include("includes/login.php"); ?>
<?php include("includes/topnav.php"); ?>
<!------- BEGIN MAIN BODY ------->
<div id="leftContent">

<h1>Edit Ride Details</h1>
<?php include("includes/schedule_form_edit.php"); ?>
</div>
<!-------- END MAIN BODY -------->
    
<?php include("includes/generic_feed.html"); ?>  
<?php include("includes/foot.html"); ?>