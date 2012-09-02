<?php
$sponsorData = new sponsorHelper(); ?>

<div id="footer">
    <ul>
        <?php foreach ($sponsorData->sponsorData as $sponsor) { ?>
            <li>
                <a href="<?=$sponsor->modal_url;?>"><img src="<?=$sponsor->images['thumb'];?>" alt="<?=$sponsor->name;?>" /></a>
            </li>
        <?php } ?>
    </ul>
</div>


<?php wp_footer(); ?>

<script id="videoCarouselTemplate" type="text/x-jquery-tmpl">
    <header>
        <h3>Videos</h3>
    </header>

    <div class="carousel">
        <ol class="carouselControl">
            <li class="prev"><a></a></li>
            <li class="next"><a></a></li>
        </ol>

        <div class="carouselContainer">
            <ul id="videoCarousel">
                {{each(i) entry }}
                    <li class="carouselItem">
                        <div class="video">
                            <?php if (backgroundSizeSupported()) { ?>
                                <div class="thumbnail" style="background:url(${media$group.media$thumbnail[0].url})"></div>
                            <?php } else { ?>
                                <img class="thumbnail" src="${media$group.media$thumbnail[0].url}" />
                            <?php } ?>
                            <a href="http://www.youtube.com/embed/${global.embeddableVideoURL(id.$t)}?autoplay=1" class="play"></a>
                        </div>

                        <p><em>${global.youtubeDateFmt(published.$t)}</em> ${title.$t}</p>
                    </li>
                {{/each}}
            </ul>
        </div>
    </div>
</script>

<script id="blogMenuTemplate" type="text/x-jquery-tmpl">
    <h3 class="monthlyPosts"><a id="monthSelection">${blog.activeMonth}</a></h3>
    <ul class="postsMenu">
        {{each(i, month) global.pageData.postmenuData.months}}
        <li><a>${month}</a></li>
        {{/each}}
     </ul>

    <ul class="postlist">
        {{each(i) posts[blog.activeMonth]}}
        <li><a href="${permalink}">${date.shortDate}: ${truncatedTitle}</a></li>
        {{/each}}
    </ul>
</script>

</body>
</html>