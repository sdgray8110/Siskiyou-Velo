<?php $pageScript = 'eventRegistration'; ?>
<?php require_once("includes/auth.php"); ?>
<?php include("includes/header.php"); ?>
Register for a Siskiyou Velo Event</title>

<?php include("includes/header_bottom.html"); ?>
<?php include("includes/login.php"); ?>
<?php include("includes/topnav.php"); ?>

    <div id="leftContent">
        <div id="eventRegistration">


<?php
$eventID = $_GET['ID'];


if (!isset($_SESSION['SESS_MEMBER_ID']) || (trim($_SESSION['SESS_MEMBER_ID']) == '')) {
    include('includes/ajaxLogin/loginForm.php');

} else {
    include('includes/events/eventDetail.php');
}



echo '
        </div>
    </div>
<var id="hiddenVar" class="hidden">?ID='.$eventID.'</var>
';

?>

<!-------- END MAIN BODY -------->

<?php include("includes/generic_feed.html"); ?>
<?php include("includes/foot.html"); ?>