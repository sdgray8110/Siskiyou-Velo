<?php
require_once("includes/auth.php");
require_once('../includes/functions.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Siskiyou Velo | Officer Administration Tool</title>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
<script src="../etna/includes/js/thickbox.js" type="text/javascript"></script>
<script type="text/javascript" src="maskedInput.js"></script>
<script src="globalPlugins.js" type="text/javascript"></script>
<script src="admin.js" type="text/javascript"></script>    

<link href="admin.css" rel="stylesheet" />
<link rel="stylesheet" href="../etna/includes/thickbox.css" />

<!--[if lte ie 7]>
<style type="text/css">
	.clear {margin:0; padding:0;}
	.recipients div.check {margin-top:0;}
</style>
<![endif]-->

</head>
<body>

<?php
include('includes/html_header.php');
include('includes/nav.php');
?>

<div class="mainContent">
	<form name="emailForm" id="emailForm" action="includes/send_email.php" method="post">
    <div class="ckEditorForm">
    	<input type="hidden" name="from" value="<?php echo $from ?>" />
		<label for="subject">Email Subject:</label>
        <input type="text" name="subject" id="subject" />
		<label for="emailBody">Email Body:</label>
		<textarea class="ckeditor" rows="50" id="emailBody" name="emailBody"></textarea>
		<input class="submit" type="submit" value="Send Email"/>
	</div>        
	</form>
</div>

</body>
</html>