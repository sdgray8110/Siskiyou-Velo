<style>
form {width:420px;}
label {width:200px; display:block; float:left; margin:3px 0;}
input {display:block; float:left; width:200; margin:3px 0;}
</style>

<?php include('includes/db_connect.php');?>

<?php
	//Function to sanitize values received from the form. Prevents SQL injection
	function clean($str) {
		$str = @trim($str);
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		return mysql_real_escape_string($str);
	}

$date = date("d/m/Y");
$postcheck = $_POST["date"];

if ($postcheck == '') {
echo '
<form id="tracking" method="post" action="tracking.php">

<label for="date">Date:</label>
<input type="text" name="date" id="date" value="'.$date.'" />

<label for="date">Enter Intake:</label>
<input type="text" name="intake" id="intake" />

<label for="date">Enter Expenditure:</label>
<input type="text" name="expend" id="expend" />

<div style="border-top:1px dotted #ccc; width:100%; clear:both;"></div>

<label for="date">Waking Weight</label>
<input type="text" name="waking_wt" id="waking_wt" />

<label for="date">Pre-Ride Weight</label>
<input type="text" name="preride_wt" id="preride_wt" />

<label for="date">Pre-Ride Water Percentage</label>
<input type="text" name="preride_water" id="preride_water" />

<label for="date">Post-Ride Weight</label>
<input type="text" name="postride_wt" id="postride_wt" />

<label for="date">Post-Ride Water Percentage</label>
<input type="text" name="postride_water" id="postride_water" />

<label for="date">Bedtime Weight</label>
<input type="text" name="bed_wt" id="bed_wt" />

<label for="date">Body Fat</label>
<input type="text" name="bodyfat" id="bodyfat" />

<input type="submit" value="Submit" />
</form>
';
}

else {

$postdate = clean($_POST['date']);
$intake = clean($_POST['intake']);
$expend = clean($_POST['expend']);
$waking_wt = clean($_POST['waking_wt']);
$preride_wt = clean($_POST['preride_wt']);
$preride_water = clean($_POST['preride_water']);
$postride_wt = clean($_POST['postride_wt']);
$postride_water = clean($_POST['postride_water']);
$bed_wt = clean($_POST['bed_wt']);
$bodyfat = clean($_POST['bodyfat']);

if ($intake == '' || $expend == '') {

	//Create INSERT query
	$qry = "INSERT INTO gray8110_svblogs . tracking(
			date,
			intake,
			expend,
			waking_wt,
			preride_wt,
			preride_water,
			postride_wt,
			postride_water,
			bed_wt,
			bodyfat
			)
			VALUES ('$postdate, $intake, $expend, $waking_wt, $preride_wt, $preride_water, $postride_wt, $postride_water, $bed_wt, $bodyfat')";
			
	$result = @mysql_query($qry);
	
	//Check whether the query was successful or not
	if($result) {
		header("location: tracking.php");
		exit();
	}else {
		die("Query failed");
	}			

}

}



?>
