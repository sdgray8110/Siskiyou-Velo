<?php
include($includes . 'global/head.php');

echo '
<body class="'.$pageData[1].'">

<div id="content">
';

include($includes . 'global/header.php');
include($includes . 'baseContent/'.$pageData[1].'.php');
include($includes . 'global/footer.php');

echo '
</div>
';

include($includes . 'global/jsIncludes.php');

echo '
</body>
</html>
';
?>