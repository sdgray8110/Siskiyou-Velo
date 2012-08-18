<?php
$blogData = blogHelper::get_sidebar_posts();
?>

<div class="sidebar">
    <div id="monthlyPosts">
        <h3 class="monthlyPosts"><a id="monthSelection"><?=$blogData->months[0];?></a></h3>
        <ul class="postsMenu">
            <?php foreach($blogData->months as $month) {?>
            <li><a><?=$month;?></a></li>
            <?php } ?>
         </ul>

        <ul class="postlist">
            <?php foreach ($blogData->posts[$blogData->months[0]] as $post) { ?>
            <li><a href="<?=$post->permalink;?>"><?=$post->date->shortDate;?>: <?=$post->truncatedTitle?></a></li>
            <?php } ?>
        </ul>
    </div>

    <?php include(get_stylesheet_directory() . '/includes/recent_results.php');?>


    <header class="socialMedia">
        <h3>News &amp; Updates</h3>
    </header>
    <iframe height='454' width='278' frameborder='0' allowtransparency='true' scrolling='no' src='http://app.strava.com/clubs/sonic-boom-racing-pb-lucky-pie/latest-rides/f830a48ae03eb669861cab7922b22084ff4bfc7c?show_rides=true'></iframe>
    <iframe height='160' width='278' frameborder='0' allowtransparency='true' scrolling='no' src='http://app.strava.com/clubs/sonic-boom-racing-pb-lucky-pie/latest-rides/f830a48ae03eb669861cab7922b22084ff4bfc7c?show_rides=false'></iframe>

    <var id="postmenuData" class="hidden pageData"><?=json_encode($blogData);?></var>
</div>