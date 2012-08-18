<?php
require_once('wordpress/wp-content/classes/class.classifieds.php');
require_once('wordpress/wp-content/classes/class.string.php');

$classifieds = new classifieds();
$result = $classifieds->result();

if (mysql_num_rows($result)) {
    while ($row = mysql_fetch_array($result)) {
?>
        <li>
            <div class="classifiedThumb">
                <?=$classifieds->imageLink($row);?>
            </div>

            <div class="cs_classified_copy">
                <h3><?=$classifieds->headerLink($row);?></h3>
                <p><?=$classifieds->shortCopy($row["post"]);?></p>
            </div>

            <div class="cs_classified_details">
                <h3>Price: <strong><?=$classifieds->price($row["price"]);?></strong></h3>
                <dl>
                    <dt>Email:</dt>
                    <dd><?=$classifieds->emailLink($row);?></dd>

                    <dt>Phone:</dt>
                    <dd><?=$row["phone"]?></dd>
                </dl>
            </div>
        </li>
<?php
    }
} else {
?>

        <li class="noResults"><p>No classified items are currently available.</p></li>

<?php
}
?>