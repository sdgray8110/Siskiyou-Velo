<?php

include($_SERVER['DOCUMENT_ROOT'] . '/sonicboom/wp-content/themes/sbr/classes/class.usacResultsScraper.php');

$teamdata = new resultsScraper;

foreach ($teamdata->results as $rider) { ?>
<h2><?=$rider->riderName;?></h2>
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Place</th>
            <th>Event</th>
            <th>Discipline</th>
            <th>Category</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($rider->data as $race) { ?>
        <tr>
            <td><?=$race->date;?></td>
            <td><?=$race->placing;?></td>
            <td><?=$race->event;?></td>
            <td><?=$race->discipline;?></td>
            <td><?=$race->category;?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<?php } ?>