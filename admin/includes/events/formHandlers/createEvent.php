<?php
$adminContext = '/home/gray8110/public_html/admin/';
$rootContext = '/home/gray8110/public_html/';

include($rootContext . 'includes/functions.php');
include($adminContext . 'includes/events/functions/timeBuilder.php');
include($adminContext . 'includes/events/db_connect.php');

$edit = $_POST['edit'];
$eventID = $_POST['eventID'];
$eventName = $_POST['eventName'];
$date = timeBuilder($_POST['date'], $_POST['time'], $_POST['meridian']);    
$closingDate = date('Y/m/d 00:00:00', strtotime($_POST['closingDate']));
$location = $_POST['location'];
$description = clean($_POST['description']);
$nestedRadio = $_POST['nestedRadio'];
$merchandise = $_POST['merchandise'];
$eventPrice = $_POST['eventPrice'];
$eventLink = $_POST['eventLink'];
$subeventNames = $_POST['subeventName1']."|".$_POST['subeventName2']."|".$_POST['subeventName3']."|".$_POST['subeventName4']."|".$_POST['subeventName5'];
$subeventDescriptions = $_POST['subeventDescription1']."|".$_POST['subeventDescription2']."|".$_POST['subeventDescription3']."|".$_POST['subeventDescription4']."|".$_POST['subeventDescription5'];
$subeventPrices = $_POST['subeventPrice1']."|". $_POST['subeventPrice2']."|". $_POST['subeventPrice3']."|". $_POST['subeventPrice4']."|". $_POST['subeventPrice5'];
$subeventLinks = $_POST['subeventLink1']."|".$_POST['subeventLink2']."|".$_POST['subeventLink3']."|".$_POST['subeventLink4']."|".$_POST['subeventLink5'];
$merchandiseNames = $_POST['merchandiseName1']."|".$_POST['merchandiseName2']."|".$_POST['merchandiseName3']."|".$_POST['merchandiseName4']."|".$_POST['merchandiseName5'];
$merchandiseDescriptions = $_POST['merchandiseDescription1']."|".$_POST['merchandiseDescription2']."|".$_POST['merchandiseDescription3']."|".$_POST['merchandiseDescription4']."|".$_POST['merchandiseDescription5'];
$merchandisePrices = $_POST['merchandisePrice1']."|".$_POST['merchandisePrice2']."|".$_POST['merchandisePrice3']."|".$_POST['merchandisePrice4']."|".$_POST['merchandisePrice5'];
$merchandiseLinks = $_POST['merchandiseLink1']."|".$_POST['merchandiseLink2']."|".$_POST['merchandiseLink3']."|".$_POST['merchandiseLink4']."|".$_POST['merchandiseLink5'];

if ($edit != 'true') {
    //Create INSERT query
    $qry = "INSERT INTO events(
                eventName, eventDate, closingDate, location, description, masterEventPrice, masterEventLink, subeventName, subeventPrice, subeventDescription, subeventLink, extraName, extraLink, extraPrice, extraDesc
            )

            VALUES (
                '$eventName', '$date', '$closingDate', '$location', '$description', '$eventPrice', '$eventLink', '$subeventNames', '$subeventPrices', '$subeventDescriptions', '$subeventLinks', '$merchandiseNames', '$merchandiseLinks', '$merchandisePrices', '$merchandiseDescriptions'
            )";
} else {
    //Create UPDATE query
    $qry = "UPDATE events SET
        eventName = '$eventName',
        eventDate = '$date',
        closingDate = '$closingDate',
        location = '$location',
        description = '$description',
        masterEventPrice = '$eventPrice',
        masterEventLink = '$eventLink',
        subeventName = '$subeventNames',
        subeventPrice = '$subeventPrices',
        subeventDescription = '$subeventDescriptions',
        subeventLink = '$subeventLinks',
        extraName = '$merchandiseNames',
        extraLink = '$merchandiseLinks',
        extraPrice = '$merchandisePrices',
        extraDesc = '$merchandiseDescriptions'
        WHERE ID = '$eventID'";

}

$result = @mysql_query($qry, $connection);

//Check whether the query was successful or not
if($result) {
    echo '<p>Success!!</p>'.$_GET['closingDate'];
}
else {
	//die("Query failed");
    echo $qry;
}

?>