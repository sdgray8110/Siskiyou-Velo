<?php
echo '
<div id="pageHead">
    <div class="headContent">
        <h3>'.$pageData[0].'</h3>
    </div>
</div>
';

if ($pageData[2] == 'racePage') {
    include($rootContext . $pageData[1] . '/includes/nav.php');
}
?>
 
