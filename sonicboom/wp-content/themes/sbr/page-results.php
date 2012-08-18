<?php
get_header(); ?>

<div id="content" class="results">
    <?php get_html_header(); ?>

    <div class="content">
        <header class="results">
            <?=markupHelper::fauxSelect(resultsScraper::race_dropdown_data());?>
        </header>

        <div id="resultsContent" class="resultsContent">
            <?php while ( have_posts() ) : the_post(); ?>
            <?php the_content(); ?>
            <?php endwhile; // end of the loop. ?>
        </div>

        <?php get_sidebar('archive'); ?>
    </div>
</div>

<?php get_footer(); ?>
<var class="pageData hidden" id="ajaxHelpers">
    {
        "templateDir" : "<?=get_stylesheet_directory_uri();?>/ajax/",
        "handler": "results.php",
        "year": "<?=date('Y');?>"
    }
</var>
<script src="<?=get_stylesheet_directory_uri();?>/js/results.js"></script>