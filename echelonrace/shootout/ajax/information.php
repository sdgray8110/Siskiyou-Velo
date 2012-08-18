<?php
// AJAX CHECK
if (!isset($rootContext)) {
    $ajaxContext = '../../';
    include( $ajaxContext . 'includes/global/config.php');
}
?>
<div class="leftContent">
    <h3>Stuff you need to know</h3>
    <ul class="bullet">
        <li>You must wear a helmet at all times while participating in this event, even during your warm-up, our insurance requires this! Eye protection and gloves are also strongly recommended. </li>
        <li>SHUTTLE WILL NOT BE AVAILABLE FOR PRACTICE RUNS</li>
        <li>If you choose to practice prior to the race day, please use caution on Jacksonville Reservoir Road.  The upper section of the gravel road is not recommended for low clearance vehicles. Many of the trails are owned by the MRA and used by Off Road Vehicles, please be aware.</li>
        <li>Please help promote responsible mountain biking by not littering on the course. </li>
        <li>Your race number/race plate will correspond to your category and start time.</li>
        <li class="noBullet">CAT 1/PRO = 100 series, CAT 2/Hardtail = 200 series, CAT 3 = 300 series.</li>
        <li class="noBullet">The last two digits will be your start time. (For example: Plate # 216, means CAT2/Hardtail racer with a start time of 12:16pm).</li>
        <li>PLEASE KEEP YOUR RACE NUMBER FOR BOTH DAYS!</li>
        <li>The shuttle to the Start Line is time consuming.  Please allow riders with 100 race plates to board the shuttle first, then 200's, then 300's. This will ensure that everyone makes it to the Start Line on time.  </li>
        <li>Spectators are welcome, please remember to not block the roadways for the Shuttle and Emergency vehicles.</li>
        <li>This event is being held on Motorcycle Rider's Association and City of Jacksonville property.  Special thank you to the MRA and the City of Jacksonville</li>
        <li>If you register, but do not start (DNS) or do not finish (DNF) please tell someone at the Registration Tent, so we don't assume you are lost and send out a search party for you.</li>
    </ul>
</div>

<?php
include($rootContext . '/' . stripslashes($bikeFestLink) . '/includes/rightNav.php');
?>