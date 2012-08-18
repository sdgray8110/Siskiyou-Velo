<?php
// AJAX CHECK
if (!isset($rootContext)) {
    $ajaxContext = '../../';
    include( $ajaxContext . 'includes/global/config.php');
}
?>  
<div class="leftContent">

    <?php include($rootContext . 'springthaw/includes/overviewGallery.php'); ?>

    <div class="imgRight width280"><img src="/docroot/img/logos/SpringThawLogo.png" alt="Spring Thaw Mountain Bike Festival " /></div>

    <p>The 21st Annual Spring Thaw Mountain Bike Festival will return to <a target="_blank" href="http://www.ashlandchamber.com/">Ashland, Oregon</a> this May 19-20, 2012.  The Spring Thaw will host a <a href="#" class="crossCountry">cross country race</a> on Saturday beginning at 9:15 am. On Sunday, the gravity riders get their shot with the <a href="" class="downhill">Spring Thaw Downhill</a> starting at 11:00 am. The entire weekends cash and prizes total US$10,000.  Echelon Events, LLC will organize this years event with the help of many generous sponsors and volunteers.</p>
</div>

<?php
include($rootContext . 'springthaw/includes/rightNav.php');
?>