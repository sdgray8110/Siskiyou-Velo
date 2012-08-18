<?php

echo '
<div class="leftContent">
';

if (!$_GET['complete']) {
    include($includes . 'baseContent/fragments/contactForm.php');
} else {
    include($includes . 'baseContent/fragments/formSent.php');
}

echo '
</div>
';

include($includes . 'baseContent/fragments/facebookFeed.php');

?>