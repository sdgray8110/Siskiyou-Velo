<?php require_once("includes/auth.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php include("includes/header.php"); ?>Post Newsletter Briefs</title>
<?php include("includes/header_bottom.html"); ?>
<?php include("includes/login.php"); ?>
<?php include("includes/topnav.php"); ?>
<!------- BEGIN MAIN BODY ------->
<div id="leftContent">

<h1>Post Newsletter Briefs</h1>

<form id='profile' class="newsletterBriefs" name='brief' action='includes/brief_exec.php' method='post'>

<?php 

$filename = $_COOKIE['fileName'];

echo "
<input type='hidden' name='filename' value='" . $filename . "' id='filename' />

<dl style='margin-top:7px'><dt><label for='issue'>Issue <em style='font-weight:normal;'>(Month and Year)</em>:</label></dt>
<dd>".next12Months()."</dd></dl>

<dl><dt><label for='item1'>Item 1:</label></dt>
<dd><input type='text' name='item1' id='item1' maxlength='28' /></dd></dl>

<dl><dt><label for='item2'>Item 2:</label></dt>
<dd><input type='text' name='item2' id='item2'' maxlength='28' /></dd></dl>

<dl><dt><label for='item3'>Item 3:</label></dt>
<dd><input type='text' name='item3' id='item3'' maxlength='28' /></dd></dl>


<dl><dt><label>&nbsp;</label></dt>
<dd><input class='submit' type='submit' alt='Submit' name='submit' value='Submit Briefs &raquo;'/></dd></dl>
</form>";

?>
    </div>
    
<!-------- END MAIN BODY -------->
    
<?php include("includes/generic_feed.html"); ?>  
<?php include("includes/foot.html"); ?>