<?php
class image_resize {
    public function __construct($path,$width = 278,$height = 278) {
        $this->path = $path;
        $this->width = $width;
        $this->height = $height;
    }
}