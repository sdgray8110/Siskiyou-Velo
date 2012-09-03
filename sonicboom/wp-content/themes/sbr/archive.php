<?php
$hompageData = new homepageHelper(-1);
$postData = $hompageData->filter_posts($hompageData->blog_posts);
$blogPosts = $postData->posts;
get_header();
?>

<div id="content" class="archive">
    <?php get_html_header(); ?>

    <div class="content">
        <header class="archives">
            <h3>Archives</h3>
        </header>

        <div class="newsUpdates">
            <?php if ($postData->author) { ?>
                <h4>Posts by <?=$postData->author;?></h4>
            <?php } ?>

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

        <?php get_sidebar('archive'); ?>
    </div>
</div>

<?php get_footer(); ?>