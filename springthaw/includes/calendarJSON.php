<?php
include('calendarFunctions.php');

$curMonth = date('n|Y');
!$_GET['month'] ? $month = $curMonth : $month = $_GET['month'];
$dateInfo = dateInfo($month);
$eventInfo = calendarQuery($month);

echo '{
    "items" : [
        {
            "month" : "'.$dateInfo[0].'",
            "numMonth" : "'.$dateInfo[1].'",
            "year" : "'.$dateInfo[2].'",
            "duration" : "'.$dateInfo[3].'",
            "starts" : "'.$dateInfo[4].'",
            "events": [
';

$i = 1;


while ($eventRow = mysql_fetch_array($eventInfo)) {

    echo '
                {
                    "date" : "'.calDay($eventRow["date"]).'",
                    "name" : "'.$eventRow["name"].'",
                    "time" : "'.calTime($eventRow["date"]).'",
                    "location" : "'.$eventRow["location"].'",
                    "type" : "'.$eventRow["type"].'",
                    "link" : "'.$eventRow["link"].'",
                    "eventID" : "'.$eventRow["id"].'"
    ' .

    closingParens($i, $eventInfo);

    $i ++;
}

echo '
            ]
        }
    ]
}
';

?>