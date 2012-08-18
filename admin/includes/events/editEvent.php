<?php
$adminContext = '/home/gray8110/public_html/admin/';
$rootContext = '/home/gray8110/public_html/';

include($adminContext . 'includes/functions.php');
include($adminContext . 'includes/events/functions/timeBuilder.php');
include($adminContext . 'includes/events/db_connect.php');

$eventID = $_GET['ID'];

$result = mysql_query("SELECT * FROM events WHERE ID = '$eventID'", $connection);

while ($row = mysql_fetch_array($result)) {
$eventName = $row['eventName'];
$date = parseDate($row['eventDate']);
$returnTime = parseTime($row['eventDate']);
    $meridian = $returnTime[1];
    $time = $returnTime[0];
$location = $row['location'];
$closingDate = date('m/d/Y', strtotime($row['closingDate']));
$description = $row['description'];
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
<h2>'.$eventName.'</h2>
<p><strong>Date: </strong>'.$date.'</strong></p>

<form id="editEventForm" method="" action="">
    <fieldset>
        <legend>Event Details:</legend>

        <input type="hidden" name="eventID" value="'.$eventID.'" />

        <label for="eventName">Event Name:</label>
        <input id="eventName" type="text" name="eventName" value="'.$eventName.'" />

        <label for="date">Event Date:</label>
        <input id="date" type="text" name="date" value="'.$date.'" />

        <label for="time">Event Time:</label>
        <input id="time" type="text" name="time" value="'.$time.'" />

        <select id="meridian" name="meridian">
';
        $meridianArray = array('am|AM','pm|PM');
        buildOptions($meridianArray, $meridian);
echo '
        </select>

        <label for="closingDate">Registration Closes:</label>
        <input id="closingDate" type="text" name="closingDate" value="'.$closingDate.'" />

        <label for="location">Event Location:</label>
        <input id="location" type="text" name="location" value="'.$location.'" />

        <label for="description">Event Description:</label>
        <textarea id="description" name="description">'.$description.'</textarea>
    </fieldset>
';

if ($masterEventPrice != '') {
echo '
    <fieldset class="singleEvent">
        <legend>More Details:</legend>
        <label for="eventPrice"> Event Price:</label>
        <input class="eventPrice" id="eventPrice" type="text" name="eventPrice" value="'.$masterEventPrice.'" />


        <label for="eventLink">Event Link (Map, Photos etc):</label>
        <input class="eventLink" id="eventLink" type="text" name="eventLink" value="'.$masterEventLink.'" />
    </fieldset>
';
} else {
    echo '
    <fieldset class="multipleEvents">
        <legend>Sub Events (Up To 5):</legend>
    ';
        buildFormFromArrays('Sub Event', 'edit', $subeventName, $subeventPrice, $subeventLink, $subeventDescription, 'subeventName', 'subeventPrice', 'subeventLink', 'subeventDescription');
    echo '
    </fieldset>
    ';
}

echo '
<fieldset>
    <legend>Event Merchandise (Up To 5):</legend>
';
    buildFormFromArrays('Item', 'edit', $extraName, $extraLink, $extraPrice, $extraDesc, 'merchandiseName', 'merchandiseLink', 'merchandisePrice', 'merchandiseDescription');
echo '
</fieldset>
';

echo '
    <fieldset>
        <input type="submit" id="submitEditEvent" value="Save Event" />
    </fieldset>
</form>
';

}
?>

<script type="text/javascript">
    $('#date, #closingDate').mask('99/99/9999');
    $('#time').mask('99:99');

    $('#submitEditEvent').live('click',function(e) {
        e.preventDefault();

            var formData = $('#editEventForm').serialize();

            $.ajax({
                type : 'POST',
                data : formData + '&edit=true',
                url : 'includes/events/formHandlers/createEvent.php',
                success : function(data) {
                    $('#editEventForm').parent().html(data);
                }
            });
    });
</script>