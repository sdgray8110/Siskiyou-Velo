<?php $recentResults = resultsScraper::combined_results(); ?>

<header class="latestResults">
    <h3>Latest Results</h3>
</header>

<ul class="results">
<?php
    $i = 0;
    foreach($recentResults as $result) {
        if ($result->placing <= 10) {
            if ($i < 4) { ?>
                <li>
                    <p><?=$result->prettyDate;?> <strong><?=$result->prettyPlacing;?> Place</strong> - <?=$result->name;?></p>
                    <p><?=$result->category;?> <?=$result->event;?> | <?=$result->discipline;?></p>
                </li>
    <?php
            } else {
                break;
            }

            $i++;
        }
    }
?>
</ul>