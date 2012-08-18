<?php
$adminContext = '/home/gray8110/public_html/admin/';
$rootContext = '/home/gray8110/public_html/';

include($rootContext . 'includes/functions.php');
include($adminContext . 'includes/events/db_connect.php');

$eventID=$_POST["eventID"];
$memberID=$_POST["memberID"];
$eventName=$_POST["eventName"];
$firstname=$_POST["firstname"];
$lastname=$_POST["lastname"];
$email=$_POST["email"];
$phone = $_POST["phone"];
$address=$_POST["address"];
$city=$_POST["city"];
$state=$_POST["state"];
$zip=$_POST["zip"];
$attendees=$_POST["attendees"];
$nonMember=$_POST["nonMembers"];
$merch1=$_POST["merch1"];
$merch2=$_POST["merch2"];
$merch3=$_POST["merch3"];
$merch4=$_POST["merch4"];
$merch5=$_POST["merch5"];
$mlcVolunter=$_POST["mlcVolunteer"];
$finalTotal=$_POST["finalTotal"];

$qry = "INSERT INTO registrants(
            eventID, eventName, firstName, lastName, memberID, email, phoneNumber, address, city, state, zip, memberAttendees, nonMemberAttendees, merch1, merch2, merch3, merch4, merch5, mlcVolunteer
        )

        VALUES (
            '$eventID', '$eventName', '$firstname', '$lastname', '$memberID', '$email', '$phone', '$address', '$city', '$state', '$zip', '$attendees', '$nonMember', '$merch1', '$merch2', '$merch3', '$merch4', '$merch5', '$mlcVolunter'
        )";

$result = @mysql_query($qry, $connection);

//Check whether the query was successful or not
if($result) {
    header("location: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=webmaster@siskiyouvelo.org&undefined_quantity=1&item_name=".$eventName."&amount=".$finalTotal."&custom=".$memberID."|event|".$eventID."|".$email."&return=http://www.siskiyouvelo.org/&rm=1&cancel_return=http://www.siskiyouvelo.org&currency_code=USD");
    //header("location: http://sandbox.paypal.com/cgi-bin/webscr?cmd=_xclick&business=gray8110@gmail.com&undefined_quantity=1&item_name=".$eventName."&amount=".$finalTotal."&custom=".$memberID."|event|".$eventID."|".$email."&return=http://www.siskiyouvelo.org/&rm=1&cancel_return=http://www.siskiyouvelo.org&currency_code=USD");
    exit();
}
else {
	//die("Query failed");
    echo $qry;
}

?>