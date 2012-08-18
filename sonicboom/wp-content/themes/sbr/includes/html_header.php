<div id="header">
    <div class="containment">
        <?php if (is_home()) { ?>
        <h1><?=get_bloginfo('name');?></h1>
        <?php } else { ?>
        <h1><a href="<?=get_bloginfo('siteurl');?>"><?=get_bloginfo('name');?></a></h1>
        <?php } ?>
        <h2><?=get_bloginfo('description');?></h2>
    </div>

    <?php wp_nav_menu( array('menu' => 'Menu Menu')); ?>
</div>