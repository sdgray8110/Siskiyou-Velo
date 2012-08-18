<?php

$count = $_GET[count];
$countInc = $count + 1;

if ($count == 1) {
echo '
<fieldset class="merchandise">
    <legend>Merchandise:</legend>
';
}

echo '
    <div class="merchandise' . $count . '">

        <h4>Item ' . $count . '</h4>

        <label for="merchandiseName' . $count . '">Item Name:</label>
        <input id="merchandiseName' . $count . '" type="text" name="merchandiseName' . $count . '" />

        <label for="merchandisePrice' . $count . '">Item Cost:</label>
        <input id="merchandisePrice' . $count . '" type="text" name="merchandisePrice' . $count . '" />

        <label for="merchandiseLink' . $count . '">Item Link (Upload a photo):</label>
        <input id="merchandiseLink' . $count . '" type="text" name="merchandiseLink' . $count . '" />

        <label for="merchandiseDescription' . $count . '">Item Description:</label>
        <textarea id="merchandiseDescription' . $count . '" name="merchandiseDescription' . $count . '"></textarea>
    </div>
';

if ($count < 5) {
echo '
    <div class="merchandise' . $countInc . '">
        <a id="addMerchandise" href="">Add Another</a>
    </div>
';}

if ($count == 1) {
echo '
</fieldset>
';
}

?>