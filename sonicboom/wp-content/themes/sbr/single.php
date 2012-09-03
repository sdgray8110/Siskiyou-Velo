<?php
$pageData = new blogHelper(get_the_id());

get_header(); ?>

<div id="content" class="blogpost">
    <?php get_html_header(); ?>

    <div class="single-blog-content">
        <div class="postContent">
            <article>
                <header class="<?=$pageData->metadata->classname;?>">
                    <h2><?=$pageData->title;?></h2>
                    <p><?=$pageData->date->date;?> | by <a href="<?=$pageData->author_archive_link;?>"><?=$pageData->author->firstname;?> <?=$pageData->author->lastname;?></a></p>
                </header>

                <div class="post">
                    <?=$pageData->post->post_content;?>
                </div>

                <?php include(get_stylesheet_directory() . '/includes/photo_gallery.php'); ?>
            </article>
        </div>

        <?php get_sidebar('blog'); ?>
    </div>
</div>

<?php get_footer(); ?>