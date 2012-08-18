<?php
$activity = $_GET['id'];

include($activity.'/intro.php');


// Ranking
echo '<h3>Ranking</h3>';
include($activity.'/ranking.php');

// Terrain
echo '<h3>Terrain</h3>
      <p><i>(scale is a 0 - 5 system based on accessibility, variety of terrain, and quality of terrain.)</i></p>';
include($activity.'/terrain.php');

?>