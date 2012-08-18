<?php
require_once('wordpress/wp-content/classes/class.classifieds.php');

$classified = new classifieds();
$result = $classified->result($_GET['id']);

while ($row = mysql_fetch_array($result)) {
?>
<div class="classified">
    <h1><?=$row["item"];?></h1>
    <dl class="classified_details">
        <dt class="price">Price:</dt>
        <dd class="price"><?=$classified->price($row["price"]);?></dd>

        <dt class="label">Contact Details:</dt>

        <dt>Email:</dt>
        <dd><?=$classified->emailLink($row);?></dd>

        <dt>Phone:</dt>
        <dd><?=$row['phone'];?></dd>
    </dl>

    <?=$row["post"]?>

    <div class="classifiedFull">
        <?=$classified->fullImageLink($row);?>
    </div>

    <p class="blogInfo"><?=$classified->postInfo($row);?></p>
</div>

<?php
}
?>