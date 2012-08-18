<?php
$pageTitle = 'Metric Challenge';
include('includes/functions.php');
include('includes/header.php');
include('includes/flashPhoto.php');
?>

<div id="bodyCont">

<?php include("includes/menu.php"); 

echo '
<div id="metricCont">
    <div class="content">
        <p>This metric century starts at Grace Point Church in beautiful Ashland and follows the century riders up the challenging climb to the Green Springs summit. Continue with the century riders along the Keno Access Road to Dead Indian Memorial Highway. From here, you&rsquo;ll turn left and head back towards Ashland while the Century riders turn right and stay in the mountains. There will be water and snacks at Lily Glen County Park for those needing to refill/refuel for the remaining 21 miles which include a 13 mile, 3,300&rsquo; descent back to the finish. This is a challenging ride with cumulative climbing of 5,000 feet. So at the end of the tour, cruise into Grace Point Church Park and indulge yourself in a post-ride dessert.  You’ve earned it.</p>
    </div>
</div>

<div id="rideDetails">
    <div class="rightContent">
        <ul style="border-bottom:solid 1px #000000;">
            <li><strong>Start/Finish:</strong> '.$gracePointLink.'</li>
            <li><strong>Start Time</strong>: 7am</li>
            <li><strong>Distance</strong>: 58 Miles</li>
            <li><strong>Climbing</strong>: 5,000&rsquo; cumulative</li>
            <li><strong>Rest Stops:</strong> 2</li>
        </ul>

        <ul>
            <li><a href="'.$metricMap.'">Ride Map</a></li>
            <li><a href="images/ridedetails/metric_profile.pdf">Elevation Profile</a></li>
            <!--<li><a href="images/ridedetails/metric_route.pdf">Route Sheet</a></li>-->
        </ul>

        <ul>
            <li>'.$activeLink.'</li>
            <!--<li><a target="_blank" href="'.$printLink.'">Mail-In Registration</a></li>-->
            <li><strong>Entry Fee</strong>: $35</li>
            <li><strong>Day of Registration Fee</strong>: $45</li>
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