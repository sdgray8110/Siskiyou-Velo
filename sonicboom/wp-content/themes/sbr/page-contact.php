<?php
get_header(); ?>

<div id="content" class="contact">
    <?php get_html_header(); ?>

    <div class="content">
        <header class="contact">
            <h3>Archives</h3>
        </header>

        <form class="contact" id="contact_us" method="" action="">
            <?php while ( have_posts() ) : the_post(); ?>
            <?php the_content(); ?>
            <?php endwhile; // end of the loop. ?>
        </form>

        <?php get_sidebar('archive'); ?>
    </div>
</div>

<?php get_footer(); ?>
<var class="pageData hidden" id="ajaxHelpers">
    {
        "templateDir" : "<?=get_stylesheet_directory_uri();?>/ajax/",
        "handler": "contact_form.php"
    }
</var>
<script src="<?=get_stylesheet_directory_uri();?>/js/contact.js"></script>