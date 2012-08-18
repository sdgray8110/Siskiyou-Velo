<select id='start_link' name='start_link' type='text' class='compare'>
<option value="" selected>Choose Start Location (if applies)</option>
<?php
include('includes/db_connect.php');

$query = mysql_query("SELECT * FROM start_locations ORDER BY name ASC", $connection);

while ($row = mysql_fetch_array($query)) {
	$name = $row['name'];
	$id = $row['id'];
	$url = $row['url'];
	
	echo '<option value="<a href=\''.$url.'\'>'.$name.'</a>">'.$name.'</option>
';
}

?>
</select>
