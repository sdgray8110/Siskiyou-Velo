<?php
$pageTitle = 'Century Challenge';
include('includes/functions.php');
include('includes/header.php');
include('includes/flashPhoto.php');
?>

<div id="bodyCont">

<?php include("includes/menu.php");

echo '
<div id="centuryCont">
    <div class="content">
        <p>Starting at 1,950 feet in Ashland, cyclists head south past Emigrant Lake and grind their way up historic Highway 66 (2,000 feet in 7 miles) to the Green Springs Summit at 4,551 feet. After a stop at the Green Springs Summit for refreshments, the route heads north on Hyatt Lake Road. After an optional rest stop at Lily Glen, head out Dead Indian Memorial Highway to Forest Route 37, then north to Highway 140 and climb over a 5,120-foot pass on the way to the Lake of the Woods for lunch. Return via Dead Indian Memorial Highway, stopping at Lily Glen to refill your water bottle and pick up a few snacks before making the last climb to Buck Prairie and the long, breathtaking descent to the valley floor (3,300 ft. in 13 miles). Wheel back in to Grace Point Church in Ashland before 4 p.m. and help yourself to a great dessert. You&rsquo;ve earned it with 7,100 cumulative feet of climbing!</p>
    </div>
</div>

<div id="rideDetails">
    <div class="rightContent">
        <ul style="border-bottom:solid 1px #000000;">
            <li><strong>Start/Finish:</strong> '.$gracePointLink.'</li>
            <li><strong>Start Time</strong>: 7am</li>
            <li><strong>Distance</strong>: 100 Miles</li>
            <li><strong>Climbing</strong>: 7,100&rsquo; cumulative</li>
            <li><strong>Rest Stops:</strong> 3</li>
        </ul>

        <ul>
            <li><a href="'.$centuryMap.'">Ride Map</a></li>
            <li><a href="images/ridedetails/CenturyProfile.pdf">Elevation Profile</a></li>
            <!--<li><a href="images/ridedetails/metric_route.pdf">Route Sheet</a></li>-->
        </ul>

        <ul>
            <li>'.$activeLink.'</li>
            <!--<li><a target="_blank" href="'.$printLink.'">Mail-In Registration</a></li>-->
            <li><strong>Entry Fee</strong>: $40</li>
            <li><strong>Day of Registration Fee</strong>: $50</li>
        </ul>
    </div>
</div>
';

include('includes/footer.php');
?>

<script type="text/javascript">
    $.superbox();
</script>
</body>
</html>