<?php
get_header(); ?>

<div id="content" class="team">
    <?php get_html_header(); ?>

    <div class="team-content">
        <div class="postContent">
            <article>
                <div class="post">
                    <?php while ( have_posts() ) : the_post(); ?>
                    <?php the_content(); ?>
                    <?php endwhile; // end of the loop. ?>
                </div>
            </article>
        </div>

        <?php get_sidebar('blog'); ?>
    </div>
</div>

<?php get_footer(); ?>