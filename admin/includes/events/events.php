<?php
$adminContext = '/home/gray8110/public_html/admin/';
$rootContext = '/home/gray8110/public_html/';

include($adminContext . 'includes/events/db_connect.php');
include($rootContext . 'includes/functions.php');

echo '

<table width="100%" border="0" cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th>&nbsp;</th>
            <th width="130">Edit</th>
            <th>Event Name</th>
            <th>Event Date</th>
            <th>Registration Link</th>
            <th>Location</th>
        </tr>
    </thead>
    <tbody>
';

$result = mysql_query("SELECT * FROM events ORDER BY eventDate ASC", $connection);
$num = 1;

while ($row = mysql_fetch_array($result)) {
$eventID = $row['ID'];
$eventName = $row['eventName'];
$date = $row['eventDate'];
$location = $row['location'];
$link = 'http://www.siskiyouvelo.org/eventRegistration.php?ID=' . $eventID;

if(checkNum($num) === TRUE){echo '
        <tr>
';}

else {echo '
        <tr class ="odd">
';}

echo '
            <td class="num">'.$num.'</td>
            <td><a class="thickbox" href="includes/events/editEvent.php?ID='.$eventID.'&height=500&width=720" title="'.$eventName.'">View/Edit Event Details &raquo;</a></td>
            <td>'.$eventName.'</td>
            <td>'.$date.'</td>
            <td><a href="'.$link.'">'.$link.'</a></td>
            <td>'.$location.'</td>
        </tr>
';

$num ++;    
}

echo '
    </tbody>
</table>';
?>
 
