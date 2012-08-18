<?php
$pageTitle = 'Century Challenge';
include('includes/functions.php');
include('includes/header.php');
include('includes/flashPhoto.php');
?>

<div id="bodyCont">

<?php include("includes/menu.php");

echo '
<div id="doubleCont">
    <div class="content">
        <p>Designed to bring a Double Metric Century to the MLC lineup, the ride adds a beautiful &quot;out-and-back&quot; to the Century route to add miles and an extra challenge. The &quot;Double Trouble&quot; is about 135 miles and participants will climb about 10,000 feet for the day.</p>
        <p>Starting at 1,950 feet in Ashland, head south past Emigrant Lake and climb historic Highway 66 (2,000 feet in 7 miles) to the Green Springs Summit at 4,551 feet. </p>
        <p>After a stop at the Green Springs Summit for refreshments, head southeast on Highway 66 to enjoy a great, rolling, nontechnical descent. Then a gentle climb to the top of Parker Mountain Pass and another wonderful descent and climb to Hayden Mountain Summit Pass for a refueling rest stop. The trip back is a repeat of great descents and climbs along a rural road with virtually no traffic. </p>
        <p>Take time for a brief stop at Tub Springs along the historic Applegate Trail for a taste of the delicious, cool, spring water. Then arrive back at the Greensprings rest stop and join the Century route.  Ride north on Hyatt Lake Road with an optional rest stop at Lily Glen.</p>
        <p>Using back roads to get to Highway 37, and then to Highway 140, climb over a 5,120-foot pass and arrive Lake of the Woods for a well -deserved lunch. </p>
        <p>Stop at Lily Glen to refill your water bottle and pick up a few snacks before making the last climb to Buck Prairie and the long, breathtaking descent down Dead Indian Memorial Highway.  (3,300 feet in 13 miles). Wheel back in to Grace Point Church in Ashland before 4 p.m. and help yourself to a great dessert. You&rsquo;ve earned it with over 10,000 cumulative feet of climbing!</p>
    </div>
</div>

<div id="rideDetails">
    <div class="rightContent">
        <ul style="border-bottom:solid 1px #000000;">
            <li><strong>Start/Finish:</strong> '.$gracePointLink.'</li>
            <li><strong>Start Time</strong>: 6am</li>
            <li><strong>Distance</strong>: 135 Miles</li>
            <li><strong>Climbing</strong>: 10,000&rsquo; cumulative</li>
            <li><strong>Rest Stops:</strong> 4</li>
        </ul>

        <ul>
            <li><a href="images/ridedetails/DoubleMetric.pdf">Ride Map</a></li>
            <li><a href="images/ridedetails/Double_Metric_Profile.pdf">Elevation Profile</a></li>
        </ul>

        <ul>
            <li>'.$activeLink.'</li>
            <!--<li><a target="_blank" href="'.$printLink.'">Mail-In Registration</a></li>-->
            <li><strong>Entry Fee</strong>: $45</li>
            <li><strong>Day of Registration Fee</strong>: $55</li>
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