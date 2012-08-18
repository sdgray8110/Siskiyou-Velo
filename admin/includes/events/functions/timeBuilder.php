<?php
function timeBuilder($date, $time, $meridian) {
    $date = explode('/', $date);
    $time = explode(':', $time);
    $hours = $time[0];
        if ($meridian == 'pm' && $hours < 12) {$hours = $hours + 12;}
        else if ($meridian == 'am' && hours == 12) {$hours = '00';}
    $minutes = $time[1];

    $dateString = $date[2] . '-' . $date[1] . '-' . $date[0] . ' ' . $hours . ':' . $minutes . ':00';

    return $dateString;
}

function dateSplitter($date) {
    $dateSplit = explode(' ', $date);

    return $dateSplit;
}

function parseDate($date) {
    $date = dateSplitter($date);
    $date = $date[0];

    $dateSplit = explode('-', $date);
    $year = $dateSplit[0];
    $month = $dateSplit[1];
    $day = $dateSplit[2];

    $date = $day .'/'. $month .'/'. $year;

    return $date;
}

function parseTime($date) {
    $time = dateSplitter($date);
    $time = $time[1];
    $timeSplit = explode(':', $time);
    $hour = $timeSplit[0];
        if ($hour > 12) {
            $hour = $hour - 12;
            $meridian = 'pm';
        } elseif ($hour == 12) {
            $meridian = pm;
        } else {
            $meridian = 'am';
        }

        if ($hour < 10) {
            $hour = '0' . $hour;
        }

    $minute = $timeSplit[1];
    $time = $hour . $minute;
    return array($time,$meridian);
}
?>