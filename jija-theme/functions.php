<?php
add_action( 'after_setup_theme', 'jija_setup' );
function jija_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'search-form', 'comment-list', 'comment-form', 'gallery', 'caption' ) );
    register_nav_menus( array( 'primary' => 'Primary Menu' ) );
}

// ponytail: theme serves pre-built HTML directly from scraped pages. No wp_enqueue needed since CSS is inline in the HTML files.
