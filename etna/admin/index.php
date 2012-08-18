<?php require_once("login/auth.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Etna Brewing Co./DeSalvo Custom Cycles Team Blog | Admin Suite</title>
<link href="admin.css" rel="stylesheet" type="text/css" />
<link rel="icon" type="image/x-icon" href="../favicon.png" />
<script src="../includes/js/lib/jquery.js"></script>  
<script type="text/javascript" src="../includes/js/jquery.validate.js"></script>
  <script>
  $(document).ready(function(){
    $("#profile").validate();
  });
  </script>
  
<?php include_once("../fckeditor/fckeditor.php");
$time = date('g:ia');
?>
</head>

<body>

<div id="wrapper">
  <div id="header">
  	<?php 
	if ($message != '') {
	    echo '<h3>'.$message.'</h3>';	
	}
	
	echo '<p>Hey ' . $_SESSION['SESS_FIRST_NAME']  . ', It&rsquo;s '.$time.', why aren&rsquo;t you riding?  | <a href="../admin/login/logout.php">Logout</a></a></p>'; ?>
  </div>
      
    <div id="mainContent">
    <?php 
	include("includes/nav.php");
	
	
	$getPage = $_GET['pageID'];

    if ($getPage) {
	    include('includes/rootPages/'.$getPage.'.php');
    } else {
		include('includes/rootPages/add_post.php');
	}
	?>
    </div>
    
</div>

</body>
</html>