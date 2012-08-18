<?php
// AJAX CHECK
if (!isset($rootContext)) {
    $ajaxContext = '../../';
    include( $ajaxContext . 'includes/global/config.php');
}
?>

<div class="leftContent">
<h3>Directions:</h3>
<ul class="bullet">
        <li class="noBullet">
            <ol>
                <li class="noBullet">From I-5 take exit 30 to HWY 62/Crater Lake HWY. </li>
                <li class="noBullet">Head west following HWY 62/Crater Lake HWY which becomes HWY 238/Jacksonville HWY which becomes Rossanley Dr/HWY 238.</li>
                <li class="noBullet">Rossanley ends at Hanley Rd. Turn left. Follow Hanley in to Jacksonville and turn right at the stop sign on to E. California St/HWY 238 (Jacksonville's main street).</li>
                <li class="noBullet">Follow about 1 mile to Jacksonville Reservoir Rd.</li>
            </ol>
        </li>
    </ul>
</div>

<?php include($rootContext . 'stxc/includes/rightNav.php'); ?>