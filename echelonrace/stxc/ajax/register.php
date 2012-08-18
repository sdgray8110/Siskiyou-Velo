<?php
// AJAX CHECK
if (!isset($rootContext)) {
    $ajaxContext = '../../';
    include( $ajaxContext . 'includes/global/config.php');
}
?>
<div class="leftContent">
    <h3>STXC Registration</h3>
    <p>Registration opens at 5:00pm at the Start Line, and closes 5 minutes prior to your start time. Bring completed paper work to the race for quick registration.  All registration forms will be available at the race.</p>


    <h3>STXC Fees:</h3>
    <p>$10 per race, $35 for all 4 paid at 1st race. Juniors 18 & Under are FREE (with OBRA membership)</p>
    <p><em>* Juniors MUST have a parent/guardian present to sign race waiver</em></p>

    <h3>Want to race two categories?</h3>
    <p>Extra $5 per race, or $20 for all 4 races.</p>

    <h3>Current OBRA License required:</h3>
    <p>Annual licenses are available for $25 (<em>$5 for juniors</em>). Single day licenses are $5.</p>

    <ul class="noBullet">
        <li><a class="pdfForm" href="http://www.obra.org/pdfs/waiver.pdf">OBRA Waiver</a></li>
        <li><a class="pdfForm" href="http://www.obra.org/pdfs/membership_app.pdf">Annual License</a></li>
        <li><a class="pdfForm" href="http://www.obra.org/pdfs/single_event_license.pdf">Single day license</a></li>
    </ul>
</div>

<?php include($rootContext . 'stxc/includes/rightNav.php'); ?>