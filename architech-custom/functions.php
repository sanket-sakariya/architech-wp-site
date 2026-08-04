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

/**
 * Theme Options — Portfolio Gallery images (Appearance → Customize → Portfolio Gallery).
 * Lets the admin change the 5 Pavilion portfolio gallery images without editing code.
 */
function architech_custom_portfolio_defaults() {
    $base = 'https://tadao.qodeinteractive.com/wp-content/uploads/2023/04/';
    return array(
        'portfolio_image_1' => $base . 'port-slider-gallery-img-2.jpg',
        'portfolio_image_2' => $base . 'port-slider-gallery-img-3.jpg',
        'portfolio_image_3' => $base . 'port-slider-gallery-img-4.jpg',
        'portfolio_image_4' => $base . 'port-custom-4-gallery-img-1.jpg',
        'portfolio_image_5' => $base . 'port-custom-4-gallery-img-2.jpg',
    );
}

/** Helper used by templates to fetch a portfolio image (falls back to default). */
function architech_get_portfolio_image( $index ) {
    $defaults = architech_custom_portfolio_defaults();
    $key = 'portfolio_image_' . $index;
    $default = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
    return esc_url( get_theme_mod( $key, $default ) );
}

function architech_custom_customize_register( $wp_customize ) {
    $wp_customize->add_section( 'architech_portfolio_gallery', array(
        'title'       => __( 'Portfolio Gallery', 'architech-custom' ),
        'description' => __( 'Upload or change the images shown in the Pavilion portfolio gallery.', 'architech-custom' ),
        'priority'    => 30,
    ) );

    $defaults = architech_custom_portfolio_defaults();
    $i = 1;
    foreach ( $defaults as $key => $default_url ) {
        $wp_customize->add_setting( $key, array(
            'default'           => $default_url,
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        ) );
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $key, array(
            'label'    => sprintf( __( 'Portfolio Image %d', 'architech-custom' ), $i ),
            'section'  => 'architech_portfolio_gallery',
            'settings' => $key,
        ) ) );
        $i++;
    }
}
add_action( 'customize_register', 'architech_custom_customize_register' );
