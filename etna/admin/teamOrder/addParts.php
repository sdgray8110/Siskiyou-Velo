<?php
include('includes/dbFunctions.php');
include('includes/orderFunctions.php');
$categories = allCategories('etna','deSalvoCategories','id','ASC');
$partlist = partList('etna','deSalvoParts','id','ASC');

echo '
<form id="addParts" name="addParts" action="formHandlers/addParts.php" method="post" >
    <label for="productName">Product Name:</label>
    <input type="text" name="productName" id="productName" />

    <label for="partNumber">SBA Part #:</label>
    <input type="text" name="partNumber" id="partNumber" />

    <label for="price">Cost:</label>
    <input type="text" name="price" id="price" />

    <label for="partCategory">Category</label>
    <select name="partCategory" id="partCategory">'.$categories.'</select>

    <input type="submit" value="Add Item" />
</form>

<h3>Current Parts:</h3>
<ul>'.$partlist.'</ul>
';
?>