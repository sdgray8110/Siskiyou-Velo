<?php require_once("includes/auth.php"); ?>
<?php include("includes/header.php"); ?>
Login Failed</title>
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
<!------- BEGIN MAIN BODY ------->
<div id="leftContent">
    
<!------- BEGIN MAIN BODY ------->
<?php 
if ($_GET['pw'] != 'reset') {
echo '
<h1>Access Denied </h1>
<p><strong>Incorrect Login</strong><br />Check your username and password</p>';
}

else {
echo '<h1>Password Reset</h1>';	
}
include("login/login-form.php"); ?>
    </div>
    
<!-------- END MAIN BODY -------->
    
<?php include("includes/generic_feed.html"); ?>  
<?php include("includes/foot.html"); ?>