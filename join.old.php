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
<h1>Join the Siskiyou Velo</h1>

<p>Thank you for your interest in joining the Siskiyou Velo. The entire club would be happy to have you join us as members. Benefits include a discount at member bike shops, a monthly newsletter, regular rides with different paces to suit your ability and a chance to meet the many friendly and talented members of our club.</p>
<p>Membership is $15 per year for individuals, $20 for your entire family and $25 for businesses.</p>
<ul class="resources">
	<li>To Join the Siskiyou Velo:</li>
<ol>
<li>Print the <a target="_blank" href="images/PDF/JointheVelo.pdf">membership form</a></li>
<li>Complete and sign the form.</li>
<li>Mail the form with checks made payable to Siskiyou Velo at:</li></ol>
<div style="margin:10px 0 0 0;">
    <p>Siskiyou Velo</p>
    <p>PO BOX 974</p>
    <p>Ashland, Or 97520</p>
</div>
</ul>
<p style="font-size:10px; font-style:italic;">*The membership form requires <a href="http://www.adobe.com/products/acrobat/readstep2.html">Adobe Reader</a>.</p>
    </div>
    
<!-------- END MAIN BODY -------->
    
<?php include("includes/generic_feed.html"); ?>  
<?php include("includes/foot.html"); ?>

