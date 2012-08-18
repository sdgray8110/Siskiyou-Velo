<?php
include('../includes/dbFunctions.php');
include('../includes/orderFunctions.php');
$itemString = $_GET['products'];
$itemArray = explode(':',$itemString);
$itemNums = itemValues($itemArray, 0);
$itemQtys = itemValues($itemArray, 1);
$v = productData($itemNums, $itemQtys);
