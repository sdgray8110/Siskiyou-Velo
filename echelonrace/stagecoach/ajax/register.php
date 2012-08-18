<?php
// AJAX CHECK
if (!isset($rootContext)) {
    $ajaxContext = '../../';
    include( $ajaxContext . 'includes/global/config.php');
}
?>  

<div class="leftContent">
    <h3>Stage Coach XC Entry Fee:</h3>
    <ul class="bullet">
        <li><strong>Entry Fee: </strong>$35</li>
        <li><strong>Juniors: </strong>$25</li>
        <li><strong>Late Fee: </strong> <em>If registering after July 22, 2012</em> - please add $5</li>
    </ul>
    
    <h3>Online Registration by Active.com:</h3>
    <p><a id="registerNow" target="_blank" href="https://echelonevents.webconnex.com/stagecoach"></a></p>

    <h3>Mail-in Registration:</h3>
    <p><a id="mailInRegistration" target="_blank" href="/docroot/img/forms/2012_Stagecoach_Registration_Waiver.pdf">Registration Form</a></p>

    <ul class="bullet negative15marginTop">
        <li class="noBullet"><strong>Mail form to:</strong></li>
        <li class="noBullet">Echelon Events LLC</li>
        <li class="noBullet">PO BOX 1180 </li>
        <li class="noBullet">Phoenix, Oregon 97535</li>
    </ul>

    <h3>Current OBRA License required:</h3>
    <p>Annual licenses are available for $25 (<em>$5 for juniors</em>). Single day license is included in your entry fee.</p>

    <ul class="noBullet">
        <li><a class="pdfForm" href="http://www.obra.org/pdfs/waiver.pdf">OBRA Waiver</a></li>
        <li><a class="pdfForm" href="http://www.obra.org/pdfs/membership_app.pdf">Annual License</a></li>
        <li><a class="pdfForm" href="http://www.obra.org/pdfs/single_event_license.pdf">Single day license</a></li>
    </ul>

</div>

<?php
include($rootContext . 'stagecoach/includes/rightNav.php');
?>