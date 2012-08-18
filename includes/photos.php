<?php 
echo '	<div id="container-1">

			<p style="float:right; font-size:10px;"><a href="ridemaps.php">View All Rides &raquo;</a></p>
			<ul>
                <li><a href="#fragment-1"><span>Photos</span></a></li>
                <li><a href="#fragment-2"><span>Ride Map</span></a></li>
                <li><a href="#fragment-3"><span>Elevation Profile</span></a></li>
			</ul>
					
            <div id="fragment-1">';
                
				include('includes/gallery.php');
				
echo          '</div>
	
            <div id="fragment-2">
		<div id="mapdata"></div>
		<p style="text-align:right; font-size:10px; margin:5px 0 5px 0;"><a target="_blank" href="http://maps.google.com/maps?f=q&hl=en&geocode=&q=http:%2F%2Fwww.siskiyouvelo.org%2Fimages%2Frides%2F' . $kml . '&ie=UTF8&ll=' . $coords . '&spn=0.112075,0.264359&z=' . ($zoom +1) . '">View Full Screen Map</a></p>

            </div>
            <div id="fragment-3">
               <img src="images/rides/profiles/'.$rideID.'.png" width="654" height="436" border="0" alt="'.$ridename.' Elevation Profile" />   
            </div>

		</div>
		<div class="basic" style="float:left;"  id="list1a">
			<a class="open">Ride Description</a>
		<p class="description">' . $description . '</p>
			<a class="open">About This Ride</a>
		<p class="description">' . $about . '</p>
			<a class="open">Directions</a>
		<p class="description">' . $directions . '</p>
			<a class="open">Traffic</a>
		<p class="description">' . $traffic . '</p>';

if ($optionalh != "") {
	echo '<a class="open">' . $optionalh . '</a>
		<p class="description">' . $optionalb . '</p>';
	
}

echo '</div>';
?>