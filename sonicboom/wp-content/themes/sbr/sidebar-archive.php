<?php
$hompageData = new homepageHelper(-1);
$galleryImage = $hompageData->blog_posts->gallery_image;
?>

<div class="sidebar">
    <header class="photoHeader"></header>
    <a id="sidebarGallery" href="<?=$galleryImage->original;?>"><img class="archiveGallery" src="<?=$galleryImage->thumb;?>" /></a>

    <?php include(get_stylesheet_directory() . '/includes/recent_results.php');?>

    <header class="socialMedia">
        <h3>News &amp; Updates</h3>
    </header>
    <iframe height='454' width='278' frameborder='0' allowtransparency='true' scrolling='no' src='http://app.strava.com/clubs/sonic-boom-racing-pb-lucky-pie/latest-rides/f830a48ae03eb669861cab7922b22084ff4bfc7c?show_rides=true'></iframe>
    <iframe height='160' width='278' frameborder='0' allowtransparency='true' scrolling='no' src='http://app.strava.com/clubs/sonic-boom-racing-pb-lucky-pie/latest-rides/f830a48ae03eb669861cab7922b22084ff4bfc7c?show_rides=false'></iframe>
</div>