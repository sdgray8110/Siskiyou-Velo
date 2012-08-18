<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Etna Brewing Co./DeSalvo Custom Cycles Team Blog | Admin Login</title>
<link href="../admin.css" rel="stylesheet" type="text/css" />
<script src="../../includes/js/lib/jquery.js"></script>  
<script type="text/javascript" src="../../includes/js/jquery.validate.js"></script>
  <script>
  $(document).ready(function(){
    $("#profile").validate();
    $("#profile1").validate();	
  });
  </script>
</head>
<body class="login_body">
<div class="login">
	<div class="login_top"></div>    
		<div class="login_body">
		<h2>Team Member Login:</h2>
        <form id="profile" name="profile" action="login-exec.php" method="post" onSubmit="return validate();">
        <label for "username">Email Address</label>
        <input type="text" id="username" class="required email" tabindex="4" name="username">
        <label for "Password">Password</label>
        <input class="required" type="password" id="password" tabindex="5" name="password">
        
        <input class="submit" type="submit" tabindex="6" alt="Submit" name="submit" value="Login &raquo;"/>
        
        </form>
<!--        
        <hr noshade="noshade" color="#CCCCCC" size="1" />
        
        
        <h2>Forget Your Password?</h2>
        <p>If you've forgotten your password, enter your email address in the form below and we'll email you with instructions to reset it.</p>
        
        <form id="profile1" name="pw_reset" action="pw_retrieval.php" method="post">
        
        <label for "email">Email Address</label>
        <input class="required email" type="text" id="email" tabindex="7" name="email" />
        
        <input class="submit" type="submit" tabindex="8" alt="Submit" name="submit" value="Reset Password &raquo;"/>
        
        </form>
-->        
	</div>
	<div class="login_bottom"></div>    
</div>
</body></html>