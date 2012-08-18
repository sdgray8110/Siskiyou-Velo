<?php
echo '
<h3>Have Questions? Need To Get in Touch?</h3>

<form id="'.$pageData[1].'" method="post" action="'.$formHandlers.$pageData[1].'.php">

<fieldset class="blockTextarea">
    <p>Use the form below to contact us. Whether you have questions about our races or just want to talk bikes, we&rsquo;ll get back to you as quickly as possible.</p>

    <label for="contactName">Name:</label>
    <input type="text" id="contactName" name="contactName" value="" />

    <label for="contactEmail">Email Address:</label>
    <input type="text" id="contactEmail" name="contactEmail" value="" />

    <label for="contactSubject">Subject:</label>
    <input type="text" id="contactSubject" name="contactSubject" value="" />

    <label for="contactQuestion">Question:</label>
    <textarea id="contactQuestion" name="contactQuestion"></textarea>
</fieldset>

<input type="submit" id="volunteerSubmit" name="volunteerSubmit" value="Submit" />

</form>
';

?>