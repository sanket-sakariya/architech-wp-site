<?php
/**
 * Architech Custom theme setup — minimal.
 * HTML is kept as-is; WordPress only provides title-tag, thumbnails, and a nav menu.
 */
function architech_custom_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption' ) );
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'architech-custom' ),
    ) );
}
add_action( 'after_setup_theme', 'architech_custom_setup' );
