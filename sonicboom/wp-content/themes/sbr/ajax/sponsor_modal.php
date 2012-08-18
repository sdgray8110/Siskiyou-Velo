<?php
include('ajaxConfig.php');
include($ajaxConfig['path'] . 'wp-load.php');
$sponsorData = new sponsorHelper();
$sponsor = $sponsorData->sponsorData[$_GET['id']];
?>

<div class="sponsorModal">
    <h3>Sponsor Information</h3>
    <h4><?=$sponsor->name;?></h4>
    <p><a target=_blank href="<?=$sponsor->url;?>">Sponsor Website</a></p>
    <?=$sponsor->content;?>
    <img src="<?=$sponsor->images['thumb'];?>" alt="<?=$sponsor->name;?>" />
</div>