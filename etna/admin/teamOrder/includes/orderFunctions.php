<?php
function allCategories($db,$table,$sort,$order) {
    $categoryRows = fullTableQuery($db,$table,$sort,$order,false,false);
    $array = array();
    
    while ($row = mysql_fetch_array($categoryRows)) {
        array_push($array,'<option value="'.$row['category'].'">'.$row['category'].'</option>');
    }

    return implode('',$array);
}

function availableCategories($db,$table,$sort,$order) {
    $categories = fullTableQuery($db,$table,$sort,$order,'DISTINCT partCategory',false);
    $initialCat = '<option>Select a category below...</option>';
    $array = array($initialCat);

    while ($row = mysql_fetch_array($categories)) {
        array_push($array,'<option value="'.$row['partCategory'].'">'.$row['partCategory'].'</option>');
    }

    return implode('',$array);
}

function partList($db,$table,$sort,$order) {
    $partRows = fullTableQuery($db,$table,$sort,$order,false,false);
    $array = array();

    while ($row = mysql_fetch_array($partRows)) {
        array_push($array,'<li>'.$row['productName'].' ' . ourPrice($row['price']) .'</li>');
    }

    return implode('',$array);
}

function availableParts($db,$table,$sort,$order,$select,$where) {
    $partRows = fullTableQuery($db,$table,$sort,$order,$select,$where);
    $initialCat = '<option>Select a part below...</option>';
    $array = array($initialCat);

    while ($row = mysql_fetch_array($partRows)) {
        $partDataArray = array($row['productName'],$row['partNumber'],ourPrice($row['price']));
        $value = implode('|',$partDataArray);
        array_push($array,'<option value="'.$value.'">'.$row['productName'].'</option>');
    }

    return implode('',$array);
}

function ourPrice($mikesPrice) {
    $markup = $mikesPrice * .15;
    return sprintf("%01.2f", $mikesPrice + $markup);
}

function productData($itemNums, $itemQtys) {
    $where = multiProductWhereClause($itemNums);
    $resultSet = orderQuery('etna','deSalvoParts','id','ASC',false,$where);
    $products = products($resultSet);

    for($i=0;$i<sizeof($itemNums);$i++) {
        $thisProduct = findProduct($itemNums[$i],$products);

        echo '<p><strong>Product:</strong> '.$thisProduct['productName'].' <strong>Qty: </strong>'.$itemQtys[$i].' <strong>Total Price: </strong>'.$itemQtys[$i] * $thisProduct['price'].'</p>';
    }
}

function products($resultSet) {
    $arr = array();
    while ($row = mysql_fetch_array($resultSet)) {
        array_push($arr,$row);
    }

    return $arr;
}

function findProduct($item,$products) {
    foreach ($products as $product) {
        if ($item == $product['partNumber']) {
            return $product;
        }
    }
}

function orderQuery($db,$table,$sort,$order,$select,$where) {
    return fullTableQuery($db,$table,$sort,$order,$select,$where);
}

function multiProductWhereClause($item) {
    $orClause =  implode('" OR partNumber = "', $item);
    $whereClause = 'WHERE partNumber = "' . $orClause .'"';

    return $whereClause;
}


function itemValues($arr, $pos) {
    $values = array();

    for ($i=0;$i<sizeof($arr);$i++) {
        $value = explode('|',$arr[$i]);
        $itemVal = $value[$pos];

        array_push($values,$itemVal);
    }

    return $values;
}

?>