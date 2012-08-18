<?php
get_header(); ?>

<div id="content" class="forum background_<?=rand(1,2);?>">
    <?php get_html_header(); ?>

    <div class="forum-content">
        <div class="postContent">
            <article>
                <div class="post">
                    <header>
                        <h2><?=get_the_title(get_the_ID());?></h2>
                    </header>

                    <div class="theForum">
                        <?php while ( have_posts() ) : the_post(); ?>
                        <?php the_content(); ?>
                        <?php endwhile; // end of the loop. ?>
                    </div>
                </div>
            </article>
        </div>
    </div>
</div>

<?php get_footer(); ?>