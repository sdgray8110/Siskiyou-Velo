<?php
    $form = new emailHelper(105);
    $formData = $form->formData;
    $recipientDropdown = array(
        'className' => 'recipients',
        'title' => 'Department',
        'name' => 'department',
        'options' => $formData->recipients
    );
    $subjectDropdown = array(
        'className' => 'subjects',
        'title' => 'Subject',
        'name' => 'subject',
        'options' => $formData->subjects
    );
?>


<h4><?=$formData->header;?></h4>

<ul class="leaders">
<?php foreach ($formData->recipients as $recipient) { ?>
    <li><?= $recipient->title;?> - <?= $recipient->description ;?></li>
<?php } ?>
</ul>

<fieldset>
    <?=markupHelper::fauxSelect($recipientDropdown);?>

    <label for="name">First and last name</label>
    <input type="text" class="text inlineLabel" placeholder="First and last name" id="name" name="name" />

    <label for="email">Email Address</label>
    <input type="text" class="text inlineLabel" placeholder="Email Address" id="email" name="email" />

    <?=markupHelper::fauxSelect($subjectDropdown);?>

    <label for="email">Email Address</label>
    <textarea id="message" name="message" class="text inlineLabel" placeholder="Enter your message here. Thanks!"></textarea>

    <a class="submit" id="contactFormSubmit" href="#">Submit</a>
</fieldset>