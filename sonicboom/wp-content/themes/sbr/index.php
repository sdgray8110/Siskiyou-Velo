<?php
$hompageData = new homepageHelper();
$blogPosts = $hompageData->blog_posts->posts;
get_header();
?>

<div id="content">
    <?php get_html_header(); ?>

    <div id="photoCarousel" class="content carousel">
        <div class="carouselContainer">
            <ul>
            <?php for ($i = 0;$i<count($hompageData->carousel_photos);$i++) { ?>
                <li class="photoCarouselItem" id="carouselItem_<?=$i;?>">
                    <img src="<?=$hompageData->carousel_photos[$i]->photo;?>" alt="<?=$hompageData->carousel_photos[$i]->title;?>" />
                    <p class="description"><?=$hompageData->carousel_photos[$i]->description;?></p>
                </li>
            <?php } ?>
            </ul>
        </div>

        <ol class="carouselControl"></ol>
    </div>

    <div id="youtubeFeed" class="content"></div>

    <div class="content">
        <div class="newsUpdates">
            <header>
                <h3>News &amp; Updates</h3>
            </header>

            <dl>
            <?php for ($i=0;$i<count($blogPosts);$i++) { ?>
            <?php $post = $blogPosts[$i]; ?>
                <dt class="<?=$post->blogentry->metadata->classname;?>"><?=$post->date;?></dt>
                <dd class="<?=$hompageData->oddEven($i);?>">
                    <p class="title"><?=$post->title;?></p>
                    <?php if ($post->category == 'race-report') { ?>
                        <a class="icon race_report" href="<?=$post->permalink;?>">Read Full Race Report</a>
                    <?php } else if ($post->category == 'training') { ?>
                        <a class="icon training" href="<?=$post->permalink;?>">Read Tech &amp; Training Tip</a>
                    <?php } ?>
                </dd>
            <?php } ?>
            </dl>
        </div>

        <?php get_sidebar(); ?>
    </div>
</div>

<?php get_footer(); ?>