<?php
$adminContext = '/home/gray8110/public_html/admin/';
$rootContext = '/home/gray8110/public_html/';

include($adminContext . 'includes/functions.php');
include($adminContext . 'includes/events/functions/timeBuilder.php');
include($adminContext . 'includes/events/db_connect.php');


$eventID = (!$eventID) ? $_GET['ID'] : $eventID;
$memberID = (!$_SESSION['SESS_MEMBER_ID']) ? $_GET['memberID'] : $_SESSION['SESS_MEMBER_ID'];
$result = mysql_query("SELECT * FROM events WHERE ID = '$eventID'", $connection);

while ($row = mysql_fetch_array($result)) {     
$eventName = $row['eventName'];
$date = date('l, F j, Y \a\t g:ia', strtotime($row['eventDate']));
$location = $row['location'];
$closingDate = date('l, F j, Y', strtotime($row['closingDate']));
$description = nl2br($row['description']);
$masterEventPrice = $row['masterEventPrice'];
$masterEventLink = $row['masterEventLink'];
$subeventName = $row['subeventName'];
$subeventPrice = $row['subeventPrice'];
$subeventDescription = $row['subeventDescription'];
$subeventLink = $row['subeventLink'];
$extraName = $row['extraName'];
$extraLink = $row['extraLink'];
$extraPrice = $row['extraPrice'];
$extraDesc = $row['extraDesc'];


echo '
<h1>Siskiyou Velo Event Registration</h1>
<h2>'.$eventName.'</h2>
<dl class="globalPairedList">
    <dt>Starts:</dt>
    <dd>'.$date.'</dd>

    <dt>Location:</dt>
';

if ($masterEventLink) {
    echo '
    <dd><a target="_blank" href="'.$masterEventLink.'">'.$location.'</a></dd>
    ';
} else {
    echo '
    <dd>'.$location.'</dd>
    ';
}

echo '
    <dt>Registration Closes:</dt>
    <dd>'.$closingDate.'</dd>
';

if ($masterEventPrice) {
    echo '
    <dt>Registration Fee:</dt>
    <dd>$'.$masterEventPrice.'</dd>
    ';
}

echo '
</dl>
';

if ((strtotime($row['closingDate']) + 86399) >= time()) {
    echo '
    <div class="registrationContent">
    <h4>Event Description:</h4>
    <p>'.$description.'</p>
    <p>After completing the registration process, you will be directed to Paypal for payment.</p>
    ';

    /*
    if ($masterEventLink) {
        echo '
            <dl>
                <dt>Event Link:</dt>
                <dd><a href="'.$masterEventLink.'">'.$masterEventLink.'</a></dd>
            </dl>
        ';
    }
    */

    echo '
    <form id="registerSubmit" action="" method="">
    <input class="submit" type="submit" id="registerNow" value="Register Now!">
    <input type="hidden" name="eventID" value="'.$eventID.'" />
    <input type="hidden" name="memberID" value="'.$memberID.'" />
    <input type="hidden" name="eventName" value="'.$eventName.'" />
    <input type="hidden" name="masterEventPrice" value="'.$masterEventPrice.'" />
    </form>
    ';

    if ($subeventName != '||||') {
        echo '
            <h2>Choose Your Event:</h2>
        ';

        buildFormFromArrays('Sub Event', 'eventDetail', $subeventName, $subeventPrice, $subeventLink, $subeventDescription, 'subeventName', 'subeventPrice', 'subeventLink', 'subeventDescription');

    }

} else {
    echo '
        <h4>Registration Closed:</h4>
        <p>We apologize for any inconvenience, but online registration for this event closed '.$closingDate.'.</p>';
    }

    echo '
    </div>
    ';
}
?>

