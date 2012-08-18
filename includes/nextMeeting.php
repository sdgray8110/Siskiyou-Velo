<?php
function nextMeeting($frequency) {
    $meetMonths = meetMonths($frequency);
    $curDay = date('j');
    $curMonth = date('n');
    $curYear = date('Y');
    $endOfDay = strtotime($curYear . '-' . $curMonth . '-' . $curDay . ' 21:00:00');
    $curMeetingDate = strtotime("second Wednesday",strtotime($curMonth . '/1/' . $curYear));

    foreach ($meetMonths as $month) {
        if ($month == $curMonth) {
            if ($endOfDay < $curMeetingDate) {
                $monthToUse = $curMonth;
            } else {
                $curMonth <= 9 ? $monthToUse = ($curMonth + 2)  : $monthToUse = 1;
            }
            unset($month);
        } else if ($curMonth + 1 == $month) {
            $curMonth <= 10 ? $monthToUse = ($curMonth + 1)  : $monthToUse = 1;
            unset($month);
        }
    }

    return date("D, F j", strtotime("second Wednesday",strtotime($monthToUse . '/1/' . $curYear)));

}

function meetMonths($frequency) {
    if ($frequency == 'monthly') {
        return array(1,2,3,4,5,6,7,8,9,10,11,12);
    } elseif ($frequency == 'bimonthly') {
        return array(1,3,5,7,9,11);
    }
}
?>