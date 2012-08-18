<?php require_once("login/auth_officer.php"); ?>
<?php include("includes/header.php"); ?>Post Ride Schedule</title>

  <script>
  $(document).ready(function(){
    $("#schedpage").validate();
  });
  </script>

<?php include("includes/header_bottom.html"); ?>
<?php include("includes/login.php"); ?>
<?php include("includes/topnav.old.php"); ?>
<!------- BEGIN MAIN BODY ------->
<div id="leftContent">
<?php
$scheduleType = $_POST['type'];

if ($scheduleType == 'nonRecurring') {
    echo '
        <h1>Post Ride Schedule - Non-Recurring Ride</h1>
    ';
    include("includes/schedule_form.php");
} else if ($scheduleType == 'recurring') {
    echo '
        <h1>Post Ride Schedule - Recurring Ride</h1>
    ';
    include("includes/recurring_form.php");
} else {
    echo '
    <h1>Post Ride Schedule - Select Ride Type</h1>
    <p>Is this a non-recurring ride (typically a weekend ride) or a recurring ride that will meet on a regular schedule?</p>

    <form class="rideType" id="schedpage" name="schedpage" method="post" action="post_schedule.php">
        <label for="rideType_nonRecurring">Non-Recurring Ride:</label>
        <input class="checkbox" type="radio" name="type" value="nonRecurring" id="rideType_nonRecurring">

        <label for="rideType_recurring">Recurring Ride:</label>
        <input class="checkbox" type="radio" name="type" value="recurring" id="rideType_recurring">

        <input class="submit" type="submit" id="continueRide" value="Create Ride" name="continueRide">
    </form>
    ';
}

?>
</div>



<!-------- END MAIN BODY -------->
    
<?php include("includes/generic_feed.html"); ?>  
<?php include("includes/foot.html"); ?>