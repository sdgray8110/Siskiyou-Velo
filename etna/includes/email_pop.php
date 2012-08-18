<?php 
$cookie = $_COOKIE["URL"];
$url = 'Hello, I thought you might interested in reading this entry at the Etna Brewing/DeSalvo Custom Cycles team blog.<br />' . $cookie;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Etna Brewing/DeSalvo Custom Cycles | Share The Blog</title>
<link rel="icon" type="image/x-icon" href="/favicon.png" />
<link href="../2009.css" rel="stylesheet" type="text/css" />
<script src="js/lib/jquery.js"></script>  
<script type="text/javascript" src="js/jquery.validate.js"></script>
  <script>
  $(document).ready(function(){
    $("#profile").validate();
  });
  </script>
</head>
<body class="emailBody">
<div id="emailContent">
<h1>Share The Blog</h1>
        <p class="postInfo"><strong>Send this page to a friend.</strong></p>
<div class="post">
<form id="profile" name="profile" method="post" action="email_pop_sent.php" onSubmit="return validate();">

<p><input type="text" id="to" name="to" class="required email" size="22" tabindex="1" />
<label for "to"><small>Your friend&rsquo;s email address</small></label></p>

<p><input type="text" id="author" name="author" class="required" size="22" minlength="2" tabindex="1" />
<label for "to"><small>Your name</small></label></p>

<p><input type="text" id="from" name="from" class="required email" size="22" tabindex="1" />
<label for "to"><small>Your email address</small></label></p>

<p>
  <textarea name="comment" id="comment" class="required" cols="50" rows="10" tabindex="4"><?php echo $url ?></textarea>
</p>
<p><input name="submit" type="submit" id="submit" tabindex="5" value="Submit" /></p>

</form>
</div>
</div>
</body></html>