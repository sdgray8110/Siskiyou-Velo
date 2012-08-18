<?php
$homepage = new homepageHelper(10);
get_header();
?>
<div id="content">
    <?php get_html_header(); ?>
    <div id="pageHead">
        <div class="headContent">
            <h3><?=$homepage->title;?></h3>
            <?=$homepage->content;?>
        </div>
        <ul id="homepageGallery" class="imageGallery">
            <?php
            $i = 0;
            foreach ($homepage->photos as $photo) { ?>
                <li<?=pageHelper::first_item($i);?>><img src="<?=$photo['thumb'];?>" alt="Echelon Events" /></li>
            <?php
                $i++;
            } ?>
        </ul>
    </div>

    <div id="mainContent">
        <div>
            <div class="leftContent announcements">
                <ul>
                    <?php foreach ($homepage->announcements as $announcement) { ?>
                    <li>
                        <a class="announceLogo" href="<?=$announcement->link;?>"><img src="<?=$announcement->icon;?>" alt="<?=$announcement->headline;?>" /></a>
                        <div>
                            <h3><a href="<?=$announcement->link;?>"><?=$announcement->headline;?></a></h3>
                            <?=$announcement->description;?>
                        </div>
                    </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="facebookContainer">
                <var class="hidden" id="fbToken">206671276041024|6cfj_8hJ-hND0nrXVbO8gCfwvyQ</var>
                <h3>Echelon Events&rsquo; Wall<span></span></h3>
                <ul id="fbFeed">

                </ul>
            </div>
        </div>
    </div>

    <?php get_footer(); ?>
</div>