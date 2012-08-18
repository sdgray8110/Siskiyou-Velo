<?php
$filePath = $imagePath . 'photo/thawGallery/';
$imgPath = '/docroot/img/photo/thawGallery/';
$activeClass = 'active';
$alt = 'Spring Thaw';

echo '
<ul id="thawGallery" class="imageGallery">
';

buildPhotoGallery($filePath, $imgPath, $activeClass, $alt, 'shuffle');

echo '
</ul>
';

?>