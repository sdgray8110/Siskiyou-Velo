<?php if ($pageData->photos) { ?>
    <div class="photoGallery">
        <ul>
        <?php foreach ($pageData->photos as $photo) { ?>
            <li<?=$photo->class;?>>
                <a rel="group_<?=$pageData->id;?>" title="<?=$photo->caption;?>" href="<?=$photo->original;?>"><img src="<?=$photo->thumb;?>" alt="<?=$photo->caption;?>" /><span class="gloss"></span></a>
            </li>
        <?php } ?>
        </ul>
    </div>
<?php } ?>
