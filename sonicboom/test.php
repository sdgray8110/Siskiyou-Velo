<?php
function browser() {
    $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
    // you can add different browsers with the same way ..
    if(preg_match('/(chromium)[ \/]([\w.]+)/', $ua))
        $browser = 'chromium';
    elseif(preg_match('/(chrome)[ \/]([\w.]+)/', $ua))
        $browser = 'chrome';
    elseif(preg_match('/(safari)[ \/]([\w.]+)/', $ua))
        $browser = 'safari';
    elseif(preg_match('/(opera)[ \/]([\w.]+)/', $ua))
        $browser = 'opera';
    elseif(preg_match('/(msie)[ \/]([\w.]+)/', $ua))
        $browser = 'msie';
    elseif(preg_match('/(firefox)[ \/]([\w.]+)/', $ua))
        $browser = 'firefox';

    preg_match('/('.$browser.')[ \/]([\w]+)/', $ua, $version);

    return array($browser,$version[2], 'name'=>$browser,'version'=>$version[2]);
}