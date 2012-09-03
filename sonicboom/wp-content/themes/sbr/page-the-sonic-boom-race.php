<?php
$race = new raceHelper(get_the_ID());
get_header();
?>

<div id="content" class="race">
    <?php get_html_header(); ?>

    <div class="content">
        <section class="raceInfo">
            <header>
                <h2><?=$race->title;?></h2>
                <p class="location"><?=$race->location;?></p>
                <p class="prizes">
                    <span class="purse">$<?=$race->purse;?></span>
                    <span class="label">cash prizes</span>
                </p>
            </header>

            <article class="raceOverview">
                <img src="<?=$race->courseInfo->mapImage;?>" alt="<?=$race->title;?> Course Map" />


                <p class="year"><?=$race->date->year;?></p>

                <ul>
                    <li class="men">
                        <p>Men&rsquo;s Winner</p>
                        <p><strong><?=$race->results->men;?></strong></p>
                    </li>

                    <li class="women">
                        <p>Women&rsquo;s Winner</p>
                        <p><strong><?=$race->results->women;?></strong></p>
                    </li>
                </ul>

                <a target="_blank" class="button" href="<?=$race->results->link;?>"><?=$race->date->year;?> Results</a>

            </article>

            <article class="courseDescription">
                <aside>
                    <h4>The Course</h4>
                    <p><?=$race->courseInfo->fullDescription;?></p>
                </aside>


                <p class="shortDescription">
                    <span class="length"><?=$race->courseInfo->course_length;?></span>
                    <span class="descriptionText"><?=$race->courseInfo->shortDescription;?></span>
                </p>

                <p class="mapLink">
                    <a target="blank" href="<?=$race->courseInfo->mapLink;?>">Ride with GPS Course Map</a>
                </p>
            </article>
        </section>

        <section class="sponsors">
            <header>
                <h3><?=$race->date->year;?> Race Sponsors</h3>
            </header>

            <ul>
                <?php foreach ($race->sponsors as $sponsor) { ?>
                <li>
                    <a target="_blank" href="<?=$sponsor['website_url'];?>">
                        <img src="<?=$sponsor['logo'];?>" alt="<?=$sponsor['company_name'];?>" />
                    </a>
                </li>
                <?php } ?>
            </ul>
        </section>
    </div>
</div>

<?php get_footer(); ?>
