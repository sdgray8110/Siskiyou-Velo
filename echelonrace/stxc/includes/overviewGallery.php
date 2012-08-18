<?php
$filePath = $imagePath . 'photo/stxcGallery/';
$imgPath = '/docroot/img/photo/stxcGallery/';
$activeClass = 'active';
$alt = '2011 Jacksonville STXC';

echo '
<ul id="stxcGallery" class="imageGallery">
';

buildPhotoGallery($filePath, $imgPath, $activeClass, $alt, 'normal');

echo '
</ul>
';

?>