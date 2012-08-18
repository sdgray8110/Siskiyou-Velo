<?php

echo '

<div id="pageHead">
    <div class="headContent">
        <h3>Cycling in Southern Oregon</h3>
        <p>Whether a beginner looking for a fun day of riding, or a pro racing for the podium, Echelon Events has the race for you!  Everyone is welcome to compete in our races.  Our courses are fun, challenging and will appeal to all skill levels.</p>
        <p>Nestled between the Siskiyou and Cascade Mountains, the Rogue Valley is home to a large variety of outdoor recreation. Located along I-5, just north of the California border, Southern Oregon is a simple drive from Portland, Sacramento and Reno. This beautiful and temperate part of the state is made for cycling.</p>
        <p>We look forward to seeing you at the races!</p>
    </div>
';

include($includes . 'baseContent/fragments/homepageGallery.php');

echo '
</div>

<div id="mainContent">
    <div>
        <div class="leftContent announcements">
            <ul>
                <li>
                    <a class="announceLogo" href="http://www.bartonpdx.com/"><img src="docroot/img/photo/barton.jpg" alt="Mat Barton Recovery Fund" /></a>
                    <div>
                        <h3><a href="http://www.bartonpdx.com/">Mat Barton Recovery Fund</a></h3>
                        <p>Earlier this June, Mat Barton crashed during a single speed race at the Portland Short Track Series. Mat&rsquo;s injuries were severe and medical costs are high. All of us at Echelon Events extend our thoughts and prayers to Mat and his family. Please visit <a href="http://bartonpdx.com">bartonpdx.com</a> if you&rsquo;d like to offer your support or help contribute to his recovery fund.</p>
                    </div>
                </li>

                <li>
                    <a class="announceLogo" href="'.$stageCoachLink.'"><img src="docroot/img/logos/stagecoach_small.png" alt="Stage Coach Cross Country" /></a>
                    <div>
                        <h3><a href="'.$stageCoachLink.'">2012 XC & Super D OBRA Championships</a></h3>
                        <p>2012 brings both the Cross Country and Super D State Championships to the Rogue Valley. Epic climbs, sweet single-track trail & demanding descents will make this one of the most technical courses of the year. While the overall distance seems short, this course will require every bit of your resolve! This could be one of the most challenging race courses of the year!</p>
                        <p><a href="'.$stageCoachLink.'">Full Details</a></p>
                    </div>
                </li>
            </ul>
        </div>
';

include($includes . 'baseContent/fragments/facebookFeed.php');

echo '
    </div>
</div>

';

?>