<?php
$adminContext = '/home/gray8110/public_html/admin/';
$rootContext = '/home/gray8110/public_html/';
$eventID = $_GET['ID'];
$memberID = $_GET['memberID'];
$masterEventPrice = $_GET['masterEventPrice'];

include($adminContext . 'includes/functions.php');
include($adminContext . 'includes/events/functions/timeBuilder.php');
include($adminContext . 'includes/events/db_connect.php');

// Prepopulate Registration Form
include($rootContext . 'includes/events/mainDbConnect.php');

$eventID = $_GET['eventID'];
$memberID = $_GET['memberID'];
$eventName = $_GET['eventName'];

$memberResult = mysql_query("SELECT * FROM wp_users WHERE ID = '$memberID'", $mainConnection);
while($mainRow = mysql_fetch_array($memberResult)) {
    echo '
    <form id="registrationForm" name="registrationForm" action="includes/events/formHandlers/registration.php" method="POST">

    <h2>Registration Details</h2>
    <div class="globalFormContent">
        <input type="hidden" name="eventID" value="'.$eventID.'" />
        <input type="hidden" name="memberID" value="'.$memberID.'" />
        <input type="hidden" name="eventName" value="'.$eventName.'" />

        <label for="firstname">First Name:</label>
        <input type="text" id="firstname" name="firstname" value="'.$mainRow["firstname"].'" />

        <label for="lastname">Last Name:</label>
        <input type="text" id="lastname" name="lastname" value="'.$mainRow["lastname"].'" />

        <label for="email">Email Address:</label>
        <input type="text" id="email" name="email" value="'.$mainRow["email1"].'" />

        <label for="phone">Phone Number:</label>
        <input type="text" id="phone" name="phone" value="'.$mainRow["phone"].'" />

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" value="'.$mainRow["address"].'" />

        <label for="city">City:</label>
        <input type="text" id="city" name="city" value="'.$mainRow["city"].'" />

        <label for="state">State:</label>
        <input type="text" id="state" name="state" value="'.$mainRow["state"].'" />

        <label for="zip">ZIP Code:</label>
        <input type="text" id="zip" name="zip" value="'.$mainRow["zip"].'" />

        <div class="separate">
        <label for="attendees">How many club members are you registering? ($<var id="memberCost">'.$masterEventPrice.'</var> per person)</label>
        <select id="attendees" name="attendees">
        ';

        iteratedOptions(1, 9, 1);

        echo '
        </select>

        <div class="clearFix"></div>

        <label for="nonMembers">How many non-club members are you registering? ($<var id="nomMemberCost">20.00</var> per person)</label>
        <select id="nonMembers" name="nonMembers">
        ';

        iteratedOptions(0, 10, 0);

        echo '
        </select>
        </div>
    </div>

    <h2>Meal Selection</h2>
    <div class="globalFormContent">
        <h5>Please enter the total number of meals for each type. One meal per attendee:</h5>
        <label for="merch1">Prime Rib of Beef</label>
        <input type="text" class="narrow" id="merch1" name="merch1" value="0" maxlength="2" />

        <label for="merch2">Stuffed Chicken Breast</label>
        <input type="text" class="narrow" id="merch2" name="merch2" value="0" maxlength="2" />

        <label for="merch3">Vegetarian Lasagna</label>
        <input type="text" class="narrow" id="merch3" name="merch3" value="0" maxlength="2" />
    </div>

    <h2>Summary</h2>
    <dl class="globalPairedList">
        <dt>Total Attendees:</dt>
        <dd id="totalAttendees">1</dd>

        <dt>Total Cost:</dt>
        <dd id="totalCost">$10.00</dd>
    </dl>

    <h2>Additional Info</h2>
    <dl class="globalPairedList">
        <dt><label for="mlcVolunteer">MLC Volunteer?</label></dt>
        <dd><input type="checkbox" id="mlcVolunteer" name="mlcVolunteer" value="1" /></dd>
    </dl>

    <input type="hidden" name="finalTotal" id="finalTotal" value="'.$masterEventPrice.'" />
    <input id="continueRegistration" type="submit" value="Continue &raquo;" name="submit" class="submit">
</form>
    ';

}
?>