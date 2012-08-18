<form id="schedpage" name="schedpage" method="post"  action="includes/recurring_exec.php">
<?php 

//Connect to database
$connection = mysql_connect("localhost","gray8110","8aU_V{^{,RJC");
if (!$connection) {
	die ("Database connection failed: " . mysql_error());
}

$db_select = mysql_select_db("gray8110_svblogs",$connection);

if (!db_select) {
	die("Database selection failed: " . mysql_error());
} 
?>

<h2>Time &amp; Date</h2>
<table class='schedule' width='654' border='0'>
    <tr>
    	<td><input type='hidden' value='$ID' id='rideID' name='rideID' /><label for'date'>Day(s) Of Week</label></td>
    	<td><label for'time'>Start Time</label></td>
	</tr>
    <tr>        
        <td><input id='date' name='date' type='text' /></td>
        <td><input id='time' name='time' minlength='4' maxlength='10' type='text' /></td>
    </tr>    
</table>

<h2>Ride Location</h2>
<table class='schedule' width='654' border='0'>
    <tr>
    	<td width='33%'><label for'start_link'>Start Location List</label></td>
    	<td width='33%'><label for'start'>Start Location<em style='font-size:9px; font-weight:normal; color:#FFF;'> (If not in list)</em></label></td>
    	<td width='33%'><label for'city'>Start City<em style='font-size:9px; font-weight:normal; color:#FFF;'> (If not in list)</em></label></td>
	</tr>
    <tr>      
        <td valign='top'>
        <select id='start_link' name='start_link' type='text'>
            <option value='' selected>Choose Start Location (if applies)</option>
            <option value='<a href=http://maps.google.com/maps/ms?ie=UTF8&msa=0&msid=111834297442330671200.00000111ca498f3c8f81a&ll=41.848977,-122.575836&spn=0.100891,0.142307&z=13>Collier Rest Stop on I-5</a>'>Collier Rest Stop on I-5</option>
            <option value='<a href=http://maps.google.com/maps/ms?msa=0&msid=102488823537648119423.00000112d45bc827b54f4&ie=UTF8&ll=42.265178,-122.818372&spn=0.012529,0.017788&z=16>Colver Park in Phoenix</a>'>Colver Park in Phoenix</option>
            <option value='<a href=http://maps.google.com/maps?f=q&hl=en&geocode=&q=Dog+Park+in+Ashland,+Or&sll=42.32987,-122.867746&sspn=0.025032,0.035577&ie=UTF8&ll=42.212786,-122.712092&spn=0.025079,0.035577&z=15&msa=0&msid=102488823537648119423.00000112d45bc827b54f4>Dog Park in Ashland</a>'>Dog Park in Ashland</option>
            <option value='<a href=http://maps.google.com/maps?f=q&hl=en&geocode=&q=Hawthorne+Park&sll=42.265178,-122.818372&sspn=0.012529,0.017788&ie=UTF8&ll=42.32987,-122.867746&spn=0.025032,0.035577&z=15&msa=0&msid=102488823537648119423.00000112d45bc827b54f4>Hawthorne Park in Medford</a>'>Hawthorne Park in Medford</option>
            <option value='<a href=http://maps.google.com/maps?f=q&hl=en&geocode=&q=917+E+Main+St,+Ashland,+OR%E2%80%8E&sll=42.21412,-122.699003&sspn=0.050156,0.071154&ie=UTF8&z=16&iwloc=A>The Roasting Company in Ashland</a>'>The Roasting Company in Ashland</option>
            <option value='<a href=http://maps.google.com/maps?f=q&hl=en&geocode=&q=2687+W+Main+S,+97501&sll=37.0625,-95.677068&sspn=54.269804,72.861328&ie=UTF8&ll=42.321113,-122.907314&spn=0.025036,0.035577&z=15>W.Main Bi-Mart in Medford</a>'>W.Main Bi-Mart in Medford</option>
        </select>
    	</td>
        <td><input id='start' name='start' type='text' /></td>
        <td><input id='city' name='city' type='text' /></td>
	</tr>
</table>

<h2>Ride Details</h2>
<table class='schedule' width='654' border='0'>
    <tr width='50%'>
    	<td><label for'ride_name'>Ride Name</label></td>
    	<td>&nbsp;</td>
	</tr>
    <tr width='50%'>
        <td><input id='ride_name' name='ride_name' type='text' /></td>
        <td valign='top'>&nbsp;</td>
	</tr>
    <tr>
    	<td><label for'pace'>Pace</label></td>
    	<td><label for'distance'>Distance</label></td>
	</tr>
    <tr>        
        <td valign='top'><input id='pace' name='pace' type='text' /></td>
        <td><input id='distance' name='distance' type='text' /></td>
</tr>    
</table>

<h2>Ride Leader</h2>
<table class='schedule' width='654' border='0'>
    <tr>
    	<td><label for'ride_leader'>Ride Leader</label></td>
    	<td><label for'phone'>Phone Number</label></td>
    	<td><label for'email'>Email Address</label></td>
    </tr>
	<tr>
        <td><input id='ride_leader' name='ride_leader' type='text' /></td>
        <td><input id='phone' name='phone' type='text' /></td>
        <td><input id='email' name='email' type='text' /></td>
	</tr>
</table>

<table width="654" border="0">
	<tr valign="middle">
        <td width="500" rowspan="2"><input type="submit" value="Update" class="submit" /></td>
		<td><label for="nonclub">Non Club Ride</label>
            <input style="width:15px; margin:3px 0 0 7px;" id="nonclub" name="nonclub" type="checkbox" value="1" <?php if ($nonclub == 1) {echo "checked";} ?> /></td>
	</tr>
</table>

</form>

<p><a href="schedule.php">View Schedule</a></p>


<h1>Field Definitions</h1>
<ul class="mainlist">
    <li class="head"><strong>Day(s) Of Week</strong>:</li>
    <li>Day of the Week which the ride occurs on (Every Wednesday)</li>

    <li class="head"><strong>Start Time</strong>:</li>
    <li>The planned departure time for the ride.</li>
    
    <li class="head"><strong>Start Location List</strong>:</li>
    <li>Choose start location from list - this will autopopulate the city and link.</li>
    
    <li class="head"><strong>Start Location</strong>:</li>
    <li>Name of start location if not listed in pulldown list.</li>
    
    <li class="head"><strong>Start City</strong>:</li>
    <li>City where start is located (if location isn&rsquo;t in pulldown list</li>
    
    <li class="head"><strong>Ride Name</strong>:</li>
    <li>Name of Ride</li>
    
    <li class="head"><strong>Ride Link</strong>:</li>
    <li>If ride shows in list, select it to link to Ride Description &amp; Map page</li>
    
    <li class="head"><strong>Pace</strong>:</li>
    <li>Choose ride pace from list</li>
    
    <li class="head"><strong>Distance</strong>:</li>
    <li>Distance in miles or TBD (40)</li>
    
    <li class="head"><strong>Ride Leader</strong>:</li>
    <li>Enter rider&rsquo;s name or enter &ldquo;No Ride Leader&rdquo;</li>
    
    <li class="head"><strong>Phone Number</strong>:</li>
    <li>Ride Leader&rsquo;s 7-or-10 digit phone number</li>
    
    <li class="head"><strong>Email Address</strong>:</li>
    <li>Ride Leader&rsquo;s email address</li>
</ul>