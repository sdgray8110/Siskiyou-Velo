<?php

$count = $_GET[count];
$countInc = $count + 1;

if ($count == 1) {
echo '
<fieldset class="multipleEvents">
    <legend>Sub-Events:</legend>
';
}

echo '
    <div class="subevent' . $count . '">

        <h4>Sub-Event ' . $count . '</h4>

        <label for="subeventName' . $count . '">Sub-Event Name:</label>
        <input id="subeventName' . $count . '" type="text" name="subeventName' . $count . '" />

        <label for="subeventPrice' . $count . '">Event Price:</label>
        <input id="subeventPrice' . $count . '" type="text" name="subeventPrice' . $count . '" />

        <label for="subeventLink' . $count . '">Event Link (Map, Photos etc):</label>
        <input id="subeventLink' . $count . '" type="text" name="subeventLink' . $count . '" />

        <label for="subeventDescription' . $count . '">Event Description:</label>
        <textarea id="subeventDescription' . $count . '" name="subeventDescription' . $count . '"></textarea>        
    </div>
';

if ($count < 5) {
echo '
    <div class="subevent' . $countInc . '">
        <a id="addSubEvent" href="">Add Another</a>
    </div>
';}

if ($count == 1) {
echo '
</fieldset>
';
}

?>