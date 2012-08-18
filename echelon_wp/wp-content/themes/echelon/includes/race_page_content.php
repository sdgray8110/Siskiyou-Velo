<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

$raceData = $raceData ? $raceData : new raceHelper($_GET['id']);
$template = $_GET['template'];

?>

<div class="leftContent">
    <?php include($raceData->main_content_include_path($template)); ?>
</div>

<div class="rightContent">
<?php foreach ($raceData->rightNav as $module) { ?>
    <h3><?=$module->title;?></h3>
    <ul class="rightNav">
        <?php foreach ($module->items as $item) { ?>
            <li>
                <h5><?=$item->title;?>:</h5>
                <?=$item->content;?>
            </li>
        <?php } ?>
    </ul>
<?php } ?>

    <h3>Sponsors</h3>
    <ul class="rightNav imageGallery" id="sponsors">
        <?php
        $i = 0;
        foreach ($raceData->sponsors as $sponsor) { ?>
            <li<?=pageHelper::first_item($i);?>>
                <a href="<?=$sponsor->url;?>"><img src="<?=$sponsor->logo;?>" alt="<?=$sponsor->title;?>" /></a>
            </li>
        <?php
            $i++;
        } ?>
    </ul>
</div>