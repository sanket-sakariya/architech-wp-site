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

/** Content defaults for testimonial + pavilion about section. */
function architech_custom_content_defaults() {
    return array(
        'home_testimonial_quote'  => '"Lorem ipsum dolor sit amet luctus sed do eiusmod temp nec ullam conse ctetur adipiscing elitse."',
        'home_testimonial_job'    => 'Investor,',
        'home_testimonial_name'   => 'Robert Green',
        'pavilion_about_heading'  => 'About the Project',
        'pavilion_about_para1'    => 'Pavilion O is a study in light, structure and material honesty. Conceived as a quiet intervention within its landscape, the pavilion frames the surrounding environment through a disciplined grid of exposed columns and floor-to-ceiling glazing.',
        'pavilion_about_para2'    => 'The design balances mass and void: solid stone volumes anchor the plan while cantilevered planes float above, creating sheltered thresholds between interior and exterior. Every detail, from the joinery to the recessed lighting, is resolved to reinforce a sense of calm and permanence.',
        'pavilion_about_quote'    => 'Architecture should speak of its time and place, but yearn for timelessness.',
        'pavilion_about_para3'    => 'Completed for Qode Interactive in France, the project reflects CNSS Design Studio\'s commitment to considered, human-centred spaces that age gracefully alongside the people who inhabit them.',
    );
}

/** Helper: fetch a content theme mod with default fallback. */
function architech_get_content( $key ) {
    $defaults = architech_custom_content_defaults();
    $default = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
    return get_theme_mod( $key, $default );
}

function architech_custom_content_customize_register( $wp_customize ) {
    // --- Homepage Testimonial ---
    $wp_customize->add_section( 'architech_home_testimonial', array(
        'title'       => __( 'Home Testimonial', 'architech-custom' ),
        'description' => __( 'Edit the testimonial quote and author shown on the homepage.', 'architech-custom' ),
        'priority'    => 31,
    ) );
    $t_fields = array(
        'home_testimonial_quote' => array( 'Quote', 'textarea' ),
        'home_testimonial_job'   => array( 'Author Role (e.g. Designer,)', 'text' ),
        'home_testimonial_name'  => array( 'Author Name', 'text' ),
    );
    $defaults = architech_custom_content_defaults();
    foreach ( $t_fields as $key => $meta ) {
        $wp_customize->add_setting( $key, array(
            'default'           => $defaults[ $key ],
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );
        $wp_customize->add_control( $key, array(
            'label'    => __( $meta[0], 'architech-custom' ),
            'section'  => 'architech_home_testimonial',
            'type'     => $meta[1],
        ) );
    }

    // --- Pavilion About the Project ---
    $wp_customize->add_section( 'architech_pavilion_about', array(
        'title'       => __( 'Portfolio About Content', 'architech-custom' ),
        'description' => __( 'Edit the "About the Project" text below the Pavilion gallery.', 'architech-custom' ),
        'priority'    => 32,
    ) );
    $a_fields = array(
        'pavilion_about_heading' => array( 'Heading', 'text' ),
        'pavilion_about_para1'   => array( 'Paragraph 1', 'textarea' ),
        'pavilion_about_para2'   => array( 'Paragraph 2', 'textarea' ),
        'pavilion_about_quote'   => array( 'Highlighted Quote', 'textarea' ),
        'pavilion_about_para3'   => array( 'Paragraph 3', 'textarea' ),
    );
    foreach ( $a_fields as $key => $meta ) {
        $wp_customize->add_setting( $key, array(
            'default'           => $defaults[ $key ],
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport'         => 'refresh',
        ) );
        $wp_customize->add_control( $key, array(
            'label'    => __( $meta[0], 'architech-custom' ),
            'section'  => 'architech_pavilion_about',
            'type'     => $meta[1],
        ) );
    }
}
add_action( 'customize_register', 'architech_custom_content_customize_register' );
