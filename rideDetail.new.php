<?php require_once("includes/auth.php"); ?>
<?php include("includes/functions.php"); ?>
<?php include("includes/header.php"); ?>
Ride Detail</title>

<?php include("includes/header_bottom.html"); ?>
<?php include("includes/login.php"); ?>
<?php include("includes/topnav.php"); ?>

<?php
require_once('wordpress/wp-content/classes/class.ride.php');
$rideID = $_GET['id'];
$ride = new ride;
$ride->init($rideID);
$accordion = $ride->get_accordion();
?>

<style>
    #leftContent.fullWidth {width:960px;}
    .hidden {display:none}
    #elevationProfile {width:960px; height:300px; margin:15px 0; float:left; clear:both;}
    .rideInfo {float:left; width:470px}
    .rideInfo h2 {background-position:430px 2px}
    .rideInfo dl.globalPairedList {margin-bottom: 0;}
    .rideInfo dl.globalPairedList dd {width:auto;}
    #leftContent .rideInfo p {font-size:12px; padding:7px 10px 16px 10px;}
    .rideMap {float:right;}
</style>

<var class="hidden" id="rideID"><?=$rideID;?></var>   

<div id="leftContent" class="fullWidth">
    <h1><?=$ride->get_ride_name();?></h1>

    <div class="rideDetails">
        <div class="rideInfo">
            <h2 class="active">Ride Stats</h2>
            <dl class="globalPairedList active">
                <dt>Distance:</dt>
                <dd><?=$ride->get_distance(true);?></dd>

                <dt>Elevation Gain:</dt>
                <dd><?=$ride->get_elevation_gain(true);?></dd>

                <dt>Max Grade:</dt>
                <dd><?=$ride->get_max_grade();?>%</dd>
            </dl>

            <?php for($i=0;$i<count($accordion['body']);$i++) { ?>

                <h2><?=$accordion['headlines'][$i];?></h2>
                <p><?=$accordion['body'][$i];?></p>

            <?php } ?>

        </div>

        <div class="rideMap">
            <img src="images/sandbox/fakeridemap.jpg" alt="" />
        </div>
    </div>


<div id="elevationProfile"></div>

</div>
<script src="includes/js/lib/highcharts.js" type="text/javascript"></script>
<script src="includes/js/modules/accordion.js" type="text/javascript"></script>
<script src="includes/js/rideDetail.js" type="text/javascript"></script>


<?php include("includes/foot.html"); ?>

