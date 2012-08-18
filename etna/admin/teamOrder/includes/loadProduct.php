<?php
include('../includes/dbFunctions.php');
include('../includes/orderFunctions.php');
$where = 'WHERE partCategory = "' . $_POST['cat'] .'"';
$products = availableParts('etna','deSalvoParts','id','ASC',false,$where);
echo '
    <select class="partSelection">'.$products.'</select>
';
?>