<?php require_once("includes/auth.php");
include ('includes/db_connect.php');

$rideID = $_GET["id"];

//DB Query
$result = mysql_query("SELECT * FROM sv_rides WHERE $rideID = ID ORDER BY ridename", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}
include("includes/header.html");
//Use returned data
while ($row = mysql_fetch_array($result)) {
$description = $row["description"];
$images = $row["images"];
$kml = $row["kml"];
$coords = $row["gmapcoords"];
$zoom = $row["gmapzoom"];
$ridename = $row["ridename"];
$about = $row["about"];
$directions = $row["directions"];
$traffic = $row["traffic"];
$optionalh = $row["optional_head"];
$optionalb  = $row["optional_body"];
$photos = $row["photos"];
$rideID = $row["ID"];

echo $ridename . '</title>';
?>

       <link rel="stylesheet" href="includes/ui.tabs.css" type="text/css" media="print, projection, screen">
	   <link rel="stylesheet" href="demo.css" />       

       <script src="includes/js/lib/jquery.js" type="text/javascript"></script>
       <script src="includes/js/ui.core.js" type="text/javascript"></script>
       <script src="includes/js/ui.tabs.js" type="text/javascript"></script>
       <script type="text/javascript">
            $(function() {
                $('#container-1 > ul').tabs();
            });
       </script>
       
<?php include("includes/gallery_header.php");?>       
       
        <script type="text/javascript" src="includes/js/lib/jquery.dimensions.js"></script>
		<script type="text/javascript" src="includes/js/jquery.accordion.js"></script>
		<script type="text/javascript">

		jQuery().ready(function(){
			// simple accordion
			jQuery('#list1a').accordion({
				header: 'a.open',
				autoheight: false
			});
		});		
		</script>      
    </head>
    <body>
<?php

echo '
<!--- Google Maps  -->
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAY00ndnCr6n4Wf8jGYB324RTVBBedOk0pny7S5RgxiTAHlwiF5RRvbhaR05vZhYDGVl_913ddydTpoQ"
type="text/javascript"></script>

<script type="text/javascript">

var map;
var geoXml = new GGeoXml("http://www.siskiyouvelo.org/images/rides/' . $kml .'");

function onLoad() {
  if (GBrowserIsCompatible()) {
    map = new GMap2(document.getElementById("mapdata"));
	map.checkResize();
	map.addControl(new GSmallMapControl());
	map.addControl(new GMapTypeControl());
	map.addMapType(G_PHYSICAL_MAP);
    map.setCenter(new GLatLng(' . $coords . '), ' . $zoom . '); 
    map.enableDoubleClickZoom(); 
    map.addOverlay(geoXml);
  }
} 
</script>
<!-- End Google Maps -->

';

include("includes/gmaps_header_bottom.html");

include("includes/login.php");
include("includes/topnav.php");

echo '<div id="leftContent">
		<h1>' . $ridename . '</h1>';

if ($photos == 1) {
	include("includes/photos.php");	
}

else {
	include("includes/no_photos.php");
}

}
?>

<?php
//Close connection
mysql_close($connection);

?>

    </div>
    
<!-------- END MAIN BODY -------->
    
<?php include("includes/generic_feed.html"); ?>  
<?php include("includes/foot.html"); ?>
