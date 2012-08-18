<?php
$userData = new userHelper();
$users = $userData->allUsers;
?>

<header>
    <h2><?= $userData->pageHeader ;?></h2>
</header>

<ul class="riders">
<?php
$i = 1;
foreach ($users as $user) { ?>
    <li class="col_<?=$i;?>">
        <img src="<?=$user->photos->photo;?>" alt="<?=$user->name;?>" />
        <div class="riderInfo">
            <span class="background"><var class="arrow"></var></span>
            <h4><?=$user->name;?></h4>
            <p><?=$user->short_bio;?></p>
        </div>
    </li>
<?php
$i = $i <3 ? $i + 1 : 1;
} ?>
</ul>