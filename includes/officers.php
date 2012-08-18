<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/wordpress/wp-content/classes/class.officers.php');
$officers = new officers();
$officers->init();
$roles = $officers->get_position();
$data = $officers->get_officers();
?>
<h1>Siskiyou Velo Officers &amp; Contacts</h1>
    
<ul>
<?php foreach ($roles as $role) { ?>
    <li>
        <h2 class="memberDetails"><?=$role;?></h2>
        <?php foreach ($data[$role] as $officer) { ?>
        <dl class="globalPairedList">
            <dt>Officer:</dt>
            <dd><?=$officer['firstname'];?> <?=$officer['lastname'];?></dd>
            <dt>Email:</dt>
            <dd><a href="mailto:<?=$officer['email1'];?>"><?=$officer['email1'];?></a></dd>
        </dl>
        <?php } ?>
    </li>
<?php } ?>
</ul>