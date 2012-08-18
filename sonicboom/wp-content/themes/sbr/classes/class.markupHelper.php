<?php

class markupHelper {
    private static function init() {
        require_once(get_stylesheet_directory() . '/mustache.php/src/Mustache/Autoloader.php');
        Mustache_Autoloader::register();
    }

    public static function fauxSelect($data) {
        markupHelper::init();
        $m = new Mustache_Engine;
        $template = markupHelper::get_template('fauxSelect');

        return $m->render($template, $data);
    }

    public static function emailConfirmation() {
        markupHelper::init();
        $m = new Mustache_Engine;
        $template = markupHelper::get_template('emailConfirmation');

        echo $m->render($template,'');
    }

    public static function results($data) {
        markupHelper::init();
        $m = new Mustache_Engine;
        $template = markupHelper::get_template('results');

        echo $m->render($template,$data);
    }

    public static function strava($pageID) {
        markupHelper::init();
        $m = new Mustache_Engine;
        $data = array('rides' => json_decode(get_post_meta($pageID,'rides',true)));
        $template = markupHelper::get_template('strava');

        echo $m->render($template,$data);
    }

    private static function get_template($name) {
        $fmt = '%s/tmpl/%s.html';
        $fileName = sprintf($fmt,get_stylesheet_directory(),$name);

        return file_get_contents($fileName);
    }
}