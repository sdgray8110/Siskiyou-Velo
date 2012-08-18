<?php

add_action( 'after_setup_theme', 'echelon_setup' );

if ( ! function_exists( 'echelon_setup' ) ) {
    function echelon_setup() {
        if ( !class_exists('pageHelper')) {
            require_once('classes/class.pageHelper.php');
        }

        if ( !class_exists('homepageHelper')) {
            require_once('classes/class.homepageHelper.php');
        }

        if ( !class_exists('raceHelper')) {
            require_once('classes/class.raceHelper.php');
        }

        // Hide Admin Bar
        add_filter( 'show_admin_bar', '__return_false' );

        // Add Scripts To The Footer
        add_action('wp_enqueue_scripts', 'footer_scripts');

    }
}

function get_html_header() {
    require_once(get_stylesheet_directory() . '/html_header.php');
}

if ( function_exists( 'register_nav_menus' ) ) {
	register_nav_menus(
		array(
		  'main_menu' => 'Main Menu'
		)
	);
}


function footer_scripts() {

    $deps = array();
    $src = false;
    $ver = '1.0';
    $in_footer = true;
    $template_dir = get_stylesheet_directory_uri();

    // Register Scripts
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', $template_dir . '/js/lib/jquery1.4.4.min.js');
    wp_register_script( 'ajaxTransition', $template_dir . '/js/modules/ajaxTransition.js');
    wp_register_script( 'facebookFeed', $template_dir . '/js/modules/facebookFeed.js');
    wp_register_script( 'hashWatcher', $template_dir . '/js/modules/hashWatcher.js');
    wp_register_script( 'fadeGallery', $template_dir . '/js/modules/fadeGallery.js');
    wp_register_script( 'tabs', $template_dir . '/js/modules/tabs.js');
    wp_register_script( 'global', $template_dir . '/js/global.js');
    wp_register_script( 'home', $template_dir . '/js/home.js');
    wp_register_script( 'race', $template_dir . '/js/race.js');

    // Enqueue Scripts $handle, $src, $deps, $ver, $in_footer
    wp_enqueue_script( 'jquery',$src,$deps,$ver,$in_footer);
    wp_enqueue_script( 'facebookFeed',$src,$deps,$ver,$in_footer);
    wp_enqueue_script( 'fadeGallery',$src,$deps,$ver,$in_footer);
    wp_enqueue_script( 'global',$src,$deps,$ver,$in_footer);

    if (is_home()) {
        wp_enqueue_script( 'home',$src,$deps,$ver,$in_footer);
    } else {
        wp_enqueue_script( 'ajaxTransition',$src,$deps,$ver,$in_footer);
        wp_enqueue_script( 'hashWatcher',$src,$deps,$ver,$in_footer);
        wp_enqueue_script( 'tabs',$src,$deps,$ver,$in_footer);
        wp_enqueue_script( 'race',$src,$deps,$ver,$in_footer);
    }
}

function objectSort(&$data, $key) {
    for ($i = count($data) - 1; $i >= 0; $i--) {
        $swapped = false;
        for ($j = 0; $j < $i; $j++) {
            if ($data[$j]->$key > $data[$j + 1]->$key) {
                $tmp = $data[$j];
                $data[$j] = $data[$j + 1];
                $data[$j + 1] = $tmp;
                $swapped = true;
            }
        }
        if (!$swapped) return;
    }
}

function number_suffix($number){

    // Validate and translate our input
    if ( is_numeric($number)){

        // Get the last two digits (only once)
        $n = $number % 100;

    }
    else {
        // If the last two characters are numbers
        if ( preg_match( '/[0-9]?[0-9]$/', $number, $matches )){

            // Return the last one or two digits
            $n = array_pop($matches);
        }
        else {

            // Return the string, we can add a suffix to it
            return $number;
        }
    }

    // Skip the switch for as many numbers as possible.
    if ( $n > 3 && $n < 21 )
        return $number . 'th';

    // Determine the suffix for numbers ending in 1, 2 or 3, otherwise add a 'th'
    switch ( $n % 10 ){
        case '1': return $number . 'st';
        case '2': return $number . 'nd';
        case '3': return $number . 'rd';
        default:  return $number . 'th';
    }
}


function formatDate($date,$fmt) {
        $utc_date = DateTime::createFromFormat('Y-m-d G:i:s', $date, new DateTimeZone('UTC'));
        $local_date = $utc_date;
        $local_date->setTimeZone(new DateTimeZone('America/Denver'));

        return $local_date->format($fmt);
    }
?>