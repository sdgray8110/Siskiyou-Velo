<ul id="homepageGallery" class="imageGallery">
<?php
$filePath = $imagePath . 'photo/homeGallery/';
$imgPath = '/docroot/img/photo/homeGallery/';
$activeClass = 'active';
$alt = 'Echelon Events';

buildPhotoGallery($filePath, $imgPath, $activeClass, $alt, 'shuffle');

?>
</ul>