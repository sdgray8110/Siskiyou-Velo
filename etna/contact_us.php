<?php
$pageTitle = 'Contact Us';
include('includes/header.php');
?>
<body>
<div id="wrapper">

<?php include("includes/nav.php"); ?>
    
  <div id="leftContent">
  
    <h1>Contact the team:</h1>
        <p class="postInfo"><span>Please allow 24-48 hours for a response.</span></p>
    
    <div class="post">
    <form style="clear:both" id="profile" name="profile" action="email_sent.php" method="post">
    
    <p>
    <select id="contact_type" name="contact_type">
        <option value="info@etna-desalvo.com" selected="selected">Choose your contact option:</option>
        <option value="marbleman@sisqtel.net">Team Manager</option>
        <option value="info@etna-desalvo.com">Webmaster</option>
        <option value="info@etna-desalvo.com">General Inquiries</option>
    </select>
    </p>
    
    <p>
    <input type="text" name="author" id="author" value="" size="22" tabindex="1" />
    
    <label for="author"><small>Name </small></label></p>
    
    <p><input type="text" name="honeypot" id="honeypot" size="22" tabindex="2" />
    <label for="email"><small>Email</small></label>
    </p>
    
    <p style="display:none;"><input type="text" name="email" id="email" size="22" tabindex="2" />
    <label for="email"><small>Email</small></label>
    </p>    
    
    <p><input type="text" name="subject" id="subject" size="22" tabindex="3" />
    <label for="url"><small>Subject</small></label></p>
    
    <p>
      <textarea name="comment" id="comment" cols="50" rows="10" tabindex="4"></textarea>
    </p>
    
    <p><input name="submit" type="submit" id="submit" tabindex="5" value="Submit" />
    
    </p>
    
    </form>
    </div>
  </div>
    
<?php include("includes/right_nav_include.php"); ?>

</div>

     <?php include("includes/footer.php"); ?>
     <?php include("includes/ga.php"); ?>   
</body>
</html>