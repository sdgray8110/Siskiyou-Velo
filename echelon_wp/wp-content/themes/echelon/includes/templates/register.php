<?php
$register = $raceData->tabs['register'];
?>

<h3>Entry Fees:</h3>
<ul class="bullet">
    <?php foreach ($register->content->fees as $fee) { ?>
    <li><strong><?=$fee->title;?>: </strong><?=$fee->description;?></li>
    <?php } ?>
</ul>

<h3>Online Registration</h3>
<p><a id="registerNow" target="_blank" href="<?=$register->content->link;?>"></a></p>

<h3>Mail-in Registration:</h3>
<p><a id="mailInRegistration" target="_blank" href="<?=$register->content->form;?>">Registration Form</a></p>

<ul class="bullet negative15marginTop">
    <li class="noBullet"><strong>Mail form to:</strong></li>
    <li class="noBullet">Echelon Events LLC</li>
    <li class="noBullet">PO BOX 1180 </li>
    <li class="noBullet">Phoenix, Oregon 97535</li>
</ul>

<h3>Current OBRA License required:</h3>
<p>Annual licenses are available for $25 (<em>$5 for juniors</em>). Single day license is included in your entry fee.</p>

<ul class="noBullet">
    <li><a class="pdfForm" href="http://www.obra.org/pdfs/waiver.pdf">OBRA Waiver</a></li>
    <li><a class="pdfForm" href="http://www.obra.org/pdfs/membership_app.pdf">Annual License</a></li>
    <li><a class="pdfForm" href="http://www.obra.org/pdfs/single_event_license.pdf">Single day license</a></li>
</ul>