<?php require_once("login/auth.php");

//Connect to database
$connection = mysql_connect("localhost","gray8110","8aU_V{^{,RJC");

//Debug
if (!$connection) {
	die ("Database connection failed: " . mysql_error());
}

//Select database
$db_select = mysql_select_db("gray8110_svblogs",$connection);

//Debug
if (!db_select) {
	die("Database selection failed: " . mysql_error());
}

$rideID = $_GET["id"];

//DB Query
$result = mysql_query("SELECT * FROM sv_rides WHERE $rideID = ID ORDER BY ridename", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}
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

echo $ridename . '</title>

<!--- Google Maps  -->
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAY00ndnCr6n4Wf8jGYB324RTVBBedOk0pny7S5RgxiTAHlwiF5RRvbhaR05vZhYDGVl_913ddydTpoQ"
type="text/javascript"></script>

<script type="text/javascript">

var map;
var geoXml = new GGeoXml("http://www.siskiyouvelo.org/2009_Redesign/images/rides/' . $kml .'");

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
		<h1>' . $ridename . '</h1>
        <div id="container-1">
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
		<p style="text-align:right; font-size:10px; margin:5px 0 5px 0;"><a href="http://maps.google.com/maps?f=q&hl=en&geocode=&q=http:%2F%2Fwww.siskiyouvelo.org%2F2009_Redesign%2Fimages%2Frides%2F' . $kml . '&ie=UTF8&ll=' . $coords . '&spn=0.112075,0.264359&z=' . ($zoom +1) . '">View Full Screen Map</a></p>

            </div>
            <div id="fragment-3">
                
				Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.
                
            </div>

		</div>
		<div class="basic" style="float:left;"  id="list1a">
			<a class="open">Ride Description</a>
		<p>' . $description . '</p>
			<a class="open">About This Ride</a>
		<p>' . $about . '</p>
			<a class="open">Directions</a>
		<p>' . $directions . '</p>
			<a class="open">Traffic</a>
		<p>' . $traffic . '</p>';

if ($optionalh != "") {
	echo '<a class="open">' . $optionalh . '</a>
		<p>' . $optionalb . '</p>';
	
}

echo '
		</div>
';

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
