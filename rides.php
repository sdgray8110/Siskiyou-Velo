	<link rel="stylesheet" href="demo.css" />
	<link rel="stylesheet" type="text/css" href="includes/js/lib/jquery.jcarousel.css" />
	<link rel="stylesheet" type="text/css" href="includes/js/lib/skin.css" /> 
	<script type="text/javascript" src="includes/js/lib/jquery.js"></script>
	<script type="text/javascript" src="includes/js/lib/jquery.dimensions.js"></script>
	<script type="text/javascript" src="includes/js/jquery.accordion.js"></script>
	<script type="text/javascript" src="includes/js/lib/jquery.jcarousel.pack.js"></script>


<script type="text/javascript">

jQuery(document).ready(function() {
    jQuery('#mycarousel').jcarousel({
		scroll: 1,
		start: 1
		
    });
    jQuery('#mycarousel1').jcarousel({
		scroll: 1,
        start: 1
    });
    jQuery('#mycarousel2').jcarousel({
		scroll: 1,
		start: 1
		
    });
    jQuery('#mycarousel3').jcarousel({
		scroll: 1,
        start: 1
    });	
    jQuery('#mycarousel4').jcarousel({
		scroll: 1,
        start: 1
    });		
});

</script>

	<script type="text/javascript">
	jQuery().ready(function(){
		// simple accordion
		jQuery('#list1a').accordion({
			header: 'a.open'
		});
	});
	</script>

<?php

    function shortenText($text) {
        $chars = 380;
        $text = $text." ";
        $text = substr($text,0,$chars);
        $text = substr($text,0,strrpos($text,' '));
		if (substr($text, -4) == '</p>') {
				$text = substr_replace($text, '', -4) . '</p>';
		}
		
		elseif (substr($text, -4) == '</li>') {
				$text = substr_replace($text, '', -4) . '</li>';
		}		
		
		else {
		$text = $text . '';
		}

        return $text;
    }

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
?>

<?php
echo '<div class="basic" style="float:left;"  id="list1a">
			<a class="open">Common Club Rides</a>
  <ul id="mycarousel" class="jcarousel-skin-tango">';

//DB Query
$result = mysql_query("SELECT * FROM sv_rides ORDER BY ridename", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}

//Use returned data
while ($row = mysql_fetch_array($result)) {
$description = $row["description"];
$shortDesc = shortenText($description);
$mapgif = $row["mapgif"];

//COMMON RIDES
if ($row['common'] == '1') {
	echo '
		<li>
			  <p style="font-size:14px; margin:0;"><a href="ride_detail.php?id=' . $row["ID"] . '">' . $row["ridename"] . '</a></p>
			  <div class="mapgif"><a href="ride_detail.php?id=' . $row["ID"] . '"><img src="images/rides/' . $row["mapgif"] . '" width="188" height="188" border="0" alt="Woodrat" /></a></div>
			  <p class="rideHead"><strong>Distance:</strong> ' . $row["distance"] . ' miles</p>
			  <p class="rideHead"><strong>Elevation Gain:</strong> ' . $row['elevation'] . '&#39;</p>
			  <p class="rideHead"><strong>Description:</strong><br /><br />' . $shortDesc . ' ...</p>    
		</li>';
}
}

echo '</ul>
			<a class="open">Hilly Rides</a>
  <ul id="mycarousel1" class="jcarousel-skin-tango">';

//DB Query
$result = mysql_query("SELECT * FROM sv_rides ORDER BY ridename", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}

//Use returned data
while ($row1 = mysql_fetch_array($result)) {
$description = $row1["description"];
$shortDesc = shortenText($description);
$mapgif = $row1["mapgif"];

//HILLY RIDES
if ($row1['hilly'] == '1') {
	echo '
		<li>
			  <p style="font-size:14px; margin:0;"><a href="ride_detail.php?id=' . $row1["ID"] . '">' . $row1["ridename"] . '</a></p>
			  <div class="mapgif"><a href="ride_detail.php?id=' . $row1["ID"] . '"><img src="images/rides/' . $row1["mapgif"] . '" width="188" height="188" border="0" alt="Woodrat" /></a></div>
			  <p class="rideHead"><strong>Distance:</strong> ' . $row1["distance"] . ' miles</p>
			  <p class="rideHead"><strong>Elevation Gain:</strong> ' . $row1['elevation'] . '&#39;</p>
			  <p class="rideHead"><strong>Description:</strong><br /><br />' . $shortDesc . ' ...</p>    
		</li>';
}
}

//LOCAL CENTURIES
echo '</ul>
			<a class="open">Local Centuries &amp; Event Rides</a>
  		<ul class="links" style="background:none;">';
  
//DB Query
$result = mysql_query("SELECT * FROM links WHERE category = 'Local Centuries' ORDER BY name ASC", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}

//Use returned data
while ($row = mysql_fetch_array($result)) {
	echo '
		<li><a target="_blank" href="'.$row["url"].'" />'.$row["name"].'</a></li>				
		 ';
}


echo '</ul>
			<a class="open">Dirt Rides</a>
  <ul id="mycarousel3" class="jcarousel-skin-tango">';

//DB Query
$result = mysql_query("SELECT * FROM sv_rides ORDER BY ridename", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}

//Use returned data
while ($row3 = mysql_fetch_array($result)) {
$description = $row3["description"];
$shortDesc = shortenText($description);
$mapgif = $row3["mapgif"];

//Local Centuries
if ($row3['dirt'] == '1') {
	echo '
		<li>
			  <p style="font-size:14px; margin:0;"><a href="ride_detail.php?id=' . $row3["ID"] . '">' . $row3["ridename"] . '</a></p>
			  <div class="mapgif"><a href="ride_detail.php?id=' . $row3["ID"] . '"><img src="images/rides/' . $row3["mapgif"] . '" width="188" height="188" border="0" alt="Woodrat" /></a></div>
			  <p class="rideHead"><strong>Distance:</strong> ' . $row3["distance"] . ' miles</p>
			  <p class="rideHead"><strong>Elevation Gain:</strong> ' . $row3['elevation'] . '&#39;</p>
			  <p class="rideHead"><strong>Description:</strong><br /><br />' . $shortDesc . ' ...</p>    
		</li>';
}
}

echo '</ul>
			<a class="open">Northern California Rides</a>
  <ul id="mycarousel4" class="jcarousel-skin-tango">';

//DB Query
$result = mysql_query("SELECT * FROM sv_rides ORDER BY ridename", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}

//Use returned data
while ($row4 = mysql_fetch_array($result)) {
$description = $row4["description"];
$shortDesc = shortenText($description);
$mapgif = $row4["mapgif"];

//Local Centuries
if ($row4['cali'] == '1') {
	echo '
		<li>
			  <p style="font-size:14px; margin:0;"><a href="ride_detail.php?id=' . $row4["ID"] . '">' . $row4["ridename"] . '</a></p>
			  <div class="mapgif"><a href="ride_detail.php?id=' . $row4["ID"] . '"><img src="images/rides/' . $row4["mapgif"] . '" width="188" height="188" border="0" alt="Woodrat" /></a></div>
			  <p class="rideHead"><strong>Distance:</strong> ' . $row4["distance"] . ' miles</p>
			  <p class="rideHead"><strong>Elevation Gain:</strong> ' . $row4['elevation'] . '&#39;</p>
			  <p class="rideHead"><strong>Description:</strong><br /><br />' . $shortDesc . ' ...</p>    
		</li>';
}
}

echo '</ul>';


echo '    
			</div>';


?>

  
<?php
//Close connection
mysql_close($connection);

?>