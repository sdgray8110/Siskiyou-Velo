<?php
// AJAX CHECK
if (!isset($rootContext)) {
    $ajaxContext = '../../';
    include( $ajaxContext . 'includes/global/config.php');
}
?>
<div class="leftContent">

    <div class="imgRight width280">
        <img alt="Stage Coach Cross Country" src="/docroot/img/logos/ShootoutSuperEnduro.png">
    </div>

    <p>Enjoy the technical terrain and superb scenery of Jacksonville's Forest Park. These Super D races include some of the most diverse terrain in the state of Oregon.</p>

    <p>Saturday's race begins at the top of the mountain. This course is similar to the 2010 Super D, but racers will be able to squeeze in another 700 plus feet of elevation drop. The race will start at the Table Rock Overlook at 3854ft. You will be blown away as you fly down the trail 6.34 miles and 1900ft! A couple of short but steep 200ft climbs will put you on some of the best trails Southern Oregon has to offer.</p>

    <p>On Sunday, we move east on the mountain and offer a new epic course. Racers will start at 3245ft, and drop 3.72 miles and 1000ft! Saturday and Sunday's times will be combined and the award ceremony will be held on Sunday afternoon. Built as a true Super D, bike and tire selection will come in to play for these courses.</p>

    <p>Awards after each day with Final trophies handed out after Sunday's event followed by a generous raffle and good times by all this is sure to be a "MUST DO" for every racers calendar. The perfect warm up for Sea Otter or just a good reason to get out and ride, we hope to see you there!</p>
</div>

<?php

include($rootContext . '/' . stripslashes($bikeFestLink) . '/includes/rightNav.php');
?>