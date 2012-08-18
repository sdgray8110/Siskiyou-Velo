<?php
// AJAX CHECK
if (!isset($rootContext)) {
    $ajaxContext = '../../';
    include( $ajaxContext . 'includes/global/config.php');
}
?>
<div class="leftContent">
    <h3>Visitor Information</h3>
    <ul class="address">
        <li>Jacksonville Chamber of Commerce</li>
        <li><strong>Website:</strong> <a target="_blank" href="http://www.jacksonvilleoregon.org/index.html">www.jacksonvilleoregon.org/</a></li>
    </ul>


    <h3>The Magnolia Inn</h3>
    <ul class="address">
        <li>245 North 5th Street</li>
        <li>Jacksonville, OR 97530</li>
        <li><strong>Phone:</strong> 541-899-0255</li>
        <li><strong>Toll Free:</strong> 1-866-899-0255</li>
        <li><strong>Website:</strong> <a target="_blank" href="http://www.magnolia-inn.com">www.magnolia-inn.com</a></li>
    </ul>

    <h3>Stage Lodge Motel</h3>
    <ul class="address">
        <li>830 N. 5th Street</li>
        <li>Jacksonville, OR 97530</li>
        <li><strong>Phone:</strong> 541-899-3953</li>
        <li><strong>Toll Free:</strong> 1-800-253-8254</li>
        <li><strong>Website:</strong> <a target="_blank" href="http://www.stagelodge.com">www.stagelodge.com</a></li>
    </ul>

    <h3>Best Western Horizon Inn</h3>
    <ul class="address">
        <li>1154 East Barnett Road</li>
        <li>Medford, Oregon 97504</li>
        <li><strong>Phone:</strong> 541-779-5085</li>
        <li><strong>Toll Free:</strong> 1-800-452-2255</li>
        <li><strong>Website:</strong> <a target="_blank" href="http://www.rogueweb.com/horizoninn/">www.rogueweb.com/horizoninn/</a></li>
    </ul>

    <h3>Holiday Inn Express</h3>
    <ul class="address">
        <li>1501 S. Pacific Highway</li>
        <li>Medford, Oregon 97501</li>
        <li><strong>Phone:</strong> 541-732-1400</li>
        <li><strong>Toll Free:</strong> 1-888-HOLIDAY (1-888-465-4329)</li>
        <li><strong>Website:</strong> <a target="_blank" href="http://www.rogueweb.com/holidaymedford/">www.rogueweb.com/holidaymedford/</a></li>
    </ul>

    <h3>Camping at Cantrall-Buckley County Park</h3>
    <ul class="address">
        <li><strong>Website:</strong> <a target="_blank" href="http://www.co.jackson.or.us/Page.asp?NavID=3271">www.co.jackson.or.us/Page.asp?NavID=3271</a></li>
    </ul>

    <h3>Primitive Camping at Lily Prarie</h3>
    <p>The Motorcycle Racing Association allows overnight camping at Lily Prairie. There is NO water or bathroom facilities at the prairie.  Please use caution on the road, as low clearance vehicles are NOT recommended.  Follow Jacksonville Reservoir Road (stay to the right at the fork in the road).  Lily Prairie has a large, three tiered parking area. There are a few campsites with fire pits available. Please view <a href="/docroot/img/maps/stagecoach.pdf">this map</a> for an area overview and directions for getting to Lily Prairie.  Please be courteous and clean up after yourself.  Thank you!</p>
</div>

<?php
include($rootContext . 'stagecoach/includes/rightNav.php');
?>