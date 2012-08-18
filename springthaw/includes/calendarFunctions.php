<?php

function dateInfo($month) {
    $date = explode('|', $month);
    $reqMonth = strtotime($date[1] . '-' . $date[0] . '-' . 1);
    $calMonth = date('F', $reqMonth);
    $calMonthNum = date('n', $reqMonth);
    $calYear = date('Y', $reqMonth);
    $duration = date('t', $reqMonth);
    $starts = date('w', $reqMonth);

    return array($calMonth, $calMonthNum, $calYear, $duration, $starts);
}

function calendarQuery($month) {
    include('db_connect.php');
    $date = explode('|', $month);

    $qry = 'SELECT * FROM calendar WHERE MONTH(date) = '.$date[0].' and YEAR(date) = '.$date[1];

    $result = @mysql_query($qry, $connection);

    return $result;
}

function closingParens($i, $query) {
    if ($i < mysql_num_rows($query)) {
        $parenthesis = '
                },';
    } else {
        $parenthesis = '
                }';
    }

    return $parenthesis;
}

function calDay($fullDate) {
    $newDate = date('j', strtotime($fullDate));

    return $newDate;
}

function calTime($fullDate) {
    $time = date('ga', strtotime($fullDate));

    return $time;
}

?>