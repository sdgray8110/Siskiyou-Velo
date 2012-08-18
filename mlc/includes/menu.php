<?php
echo '
<div class="pdmenu">
<div style="padding:175px 0 0 58px;">
    <ul>
        <li><a href="/">Home</a></li>
        <li><a href="#">Ride Details</a>
          <ul>
            <li><a href="'.$doubleLink.'">Double Metric Challenge</a></li>
            <li><a href="'.$centuryLink.'">Century Challenge</a></li>
            <li><a href="'.$metricLink.'">Metric Challenge</a></li>
          </ul>
        </li>
        </li>
        <li><a target="_blank" href="'.$photoLink.'">Photos</a></li>
        <li><a href="#">MLC Community</a>
          <ul>
            <li><a href="http://www.siskiyouvelo.org/">Siskiyou Velo</a></li>
            <li><a href="mailto:mlc@siskiyouvelo.org">Contact Us</a></li>
          </ul>
        </li>
        <li>'.$registrationLink.'</li>
        <!--<li><a href="" rel="superbox[ajax][includes/sponsors.php[436x250]">Sponsors</a></li>-->
      </ul>
</div>
</div>
';
?>
