<?php

add_action( 'after_setup_theme', 'sv_setup' );

if ( ! function_exists( 'sv_setup' ) ) {
    function sv_setup() {
        $themeDir = get_stylesheet_directory();

    }


	register_nav_menus(
		array(
		  'main_menu' => 'Main Menu'
		)
	);
}

function get_html_header() {
    require_once(get_stylesheet_directory() . '/inc/html_header.php');
}