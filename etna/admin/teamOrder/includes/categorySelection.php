<?php
include('dbFunctions.php');
include('orderFunctions.php');
$categories = availableCategories('etna','deSalvoParts','id','ASC');

echo '
    <select class="categorySelection">'.$categories.'</select>
    <fieldset></fieldset>
';

?>