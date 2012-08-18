<?php
require_once('wordpress/wp-content/classes/class.svdb.php');
require_once('wordpress/wp-content/classes/class.mainNav.php');
$nav = new mainNav();
?>
    <div id="mainNav">
        <div class="pdmenu">
            <ul>
                <?php $nav->renderNav(); ?>
            </ul>
        </div>
    </div>
