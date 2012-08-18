<?php
// AJAX CHECK
if (!isset($rootContext)) {
    $ajaxContext = '../../';
    include( $ajaxContext . 'includes/global/config.php');
}
?>  

<div class="leftContent">
    <h3>Stuff You Need To Know</h3>
    <ul class="bullet">
        <li>You must wear a helmet at all times while participating in this event, even during your warm-up, our insurance requires this!</li>
        <li>Please help promote responsible mountain biking by not littering on the course. </li>
        <li>Spectators are welcome and encouraged to attend, please remember to not block the roadways or gates, in case Emergency Vehicles need access.</li>
        <li>This event is being held on Motorcycle Rider's Association and City of Jacksonville property. Special thank you to the MRA and the City of Jacksonville. </li>
        <li>If you register, but do not start (DNS) or do not finish (DNF) please tell someone at the Registration Tent or Finish Line so we don't assume you are lost and send out a search party for you.</li>
    </ul>
</div>

<?php
include($rootContext . 'stagecoach/includes/rightNav.php');
?>