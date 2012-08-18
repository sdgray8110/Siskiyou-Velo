<?php
$raceData = new raceHelper(get_the_ID());
get_header();
?>
<div id="content" class="racepage">
    <?php get_html_header(); ?>
    <div id="pageHead">
        <div class="headContent">
            <h3><?=pageHelper::year();?> <?=$raceData->title;?></h3>
        </div>
    </div>

    <div class="raceNav">
        <ul>
            <?php
                $i=0;
                foreach($raceData->tabs as $tab) { ?>
                <li> <a<?=pageHelper::first_item($i);?> href="<?=$raceData->ajaxURL;?>?template=<?=$tab->slug;?>&id=<?=$raceData->id;?>" id="<?=$tab->slug;?>"><?=$tab->title;?></a></li>
            <?php
                    $i++;
                } ?>
        </ul>
    </div>

    <div id="mainContent">
        <div>
            <?php include(get_template_directory() . '/includes/race_page_content.php');?>
            </div>
    </div>
    <?php get_footer(); ?>

</div>