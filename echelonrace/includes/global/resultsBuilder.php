<?php
function fieldNames($db,$table,$sort,$order,$select,$where) {
    $fields = fullTableQuery($db,$table,$sort,$order,$select,$where);
    $fieldNames = array();

    while ($row = mysql_fetch_array($fields)) {
        array_push($fieldNames,$row['field']);
    }

    return $fieldNames;
}

function buildResults($db,$table,$sort,$order,$select,$where,$fields,$tableFieldNames) {
    $fieldNames = fieldNames($db,$table,$sort,$order,$select,$where);
    $fieldSize = sizeof($fieldNames);

    if (!$fields) {
        $fields = '"Place","Number","Last Name","First Name","Team","Hometown","Time"';
        $tableFieldNames = array('place', 'number', 'last', 'first', 'team', 'town', 'time');
    }


    echo '[';

    for ($i=0;$i<$fieldSize;$i++) {
        $whereClause = 'WHERE field = "' . $fieldNames[$i] . '" && place != 0';
        $fieldQuery = fullTableQuery($db,$table,$sort,$order,false,$whereClause);
        renderResults($fieldQuery, $fieldSize, $fieldNames[$i], $i, $fields, $tableFieldNames);
    }

    echo ']';
}

function renderResults($fieldQuery, $fieldSize, $field, $i, $fields, $tableFieldNames) {
    $fieldSize = $fieldSize -1;
    $rowCt = mysql_num_rows($fieldQuery);
    $ct = 1;
    
    $i < $fieldSize ? $sep = ',' : $sep = '';

    echo '{"field" : "'.$field.'","results" : [['.$fields.'],';

    while ($row = mysql_fetch_array($fieldQuery)) {
        $ct < $rowCt ? $sepa = ',' : $sepa = '';

        echo '["'.$row[$tableFieldNames[0]].'","'.$row[$tableFieldNames[1]].'","'.$row[$tableFieldNames[2]].'","'.$row[$tableFieldNames[3]].'","'.$row[$tableFieldNames[4]].'","'.$row[$tableFieldNames[5]].'","'.$row[$tableFieldNames[6]].'"]' . $sepa;

        $ct++;
    }

    echo ']}' . $sep;
}
?>