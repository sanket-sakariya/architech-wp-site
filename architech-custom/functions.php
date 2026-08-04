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
        'testimonial_1_quote' => '"Lorem ipsum dolor sit amet luctus sed do eiusmod temp nec ullam conse ctetur adipiscing elitse."',
        'testimonial_1_job'   => 'Investor,',
        'testimonial_1_name'  => 'Robert Green',
        'testimonial_2_quote' => '"Sed do eiusmod temp nec ullam conse ctetur adipiscing orem ipsum dolor sit amet luctus elitse."',
        'testimonial_2_job'   => 'Designer,',
        'testimonial_2_name'  => 'Helena Mour',
        'testimonial_3_quote' => '"Working with CNSS was seamless from concept to completion; the result exceeded every expectation."',
        'testimonial_3_job'   => 'Client,',
        'testimonial_3_name'  => 'Marcus Lee',
        'pavilion_title'        => 'Pavilion O',
        'pavilion_description'  => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
        'pavilion_client'       => 'Qode Interactive',
        'pavilion_architect'    => 'Tadao Architecture',
        'pavilion_location'     => 'France',
        'pavilion_category'     => 'Built',
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
        'title'       => __( 'Home Testimonials', 'architech-custom' ),
        'description' => __( 'Edit the testimonials shown in the homepage slider (auto-rotating).', 'architech-custom' ),
        'priority'    => 31,
    ) );
    $t_defaults = architech_custom_content_defaults();
    for ( $n = 1; $n <= 3; $n++ ) {
        $fields = array(
            "testimonial_{$n}_quote" => array( "Testimonial $n — Quote", 'textarea' ),
            "testimonial_{$n}_job"   => array( "Testimonial $n — Author Role", 'text' ),
            "testimonial_{$n}_name"  => array( "Testimonial $n — Author Name", 'text' ),
        );
        foreach ( $fields as $key => $meta ) {
            $wp_customize->add_setting( $key, array(
                'default'           => isset( $t_defaults[ $key ] ) ? $t_defaults[ $key ] : '',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'refresh',
            ) );
            $wp_customize->add_control( $key, array(
                'label'   => __( $meta[0], 'architech-custom' ),
                'section' => 'architech_home_testimonial',
                'type'    => $meta[1],
            ) );
        }
    }

    // --- Pavilion Project Details ---
    $wp_customize->add_section( 'architech_pavilion_details', array(
        'title'       => __( 'Portfolio Details', 'architech-custom' ),
        'description' => __( 'Edit the Pavilion title, description and project info (Client, Architect, etc.).', 'architech-custom' ),
        'priority'    => 32,
    ) );
    $d_fields = array(
        'pavilion_title'       => array( 'Project Title', 'text' ),
        'pavilion_description' => array( 'Description', 'textarea' ),
        'pavilion_client'      => array( 'Client', 'text' ),
        'pavilion_architect'   => array( 'Architect', 'text' ),
        'pavilion_location'    => array( 'Location', 'text' ),
        'pavilion_category'    => array( 'Category', 'text' ),
    );
    foreach ( $d_fields as $key => $meta ) {
        $wp_customize->add_setting( $key, array(
            'default'           => $defaults[ $key ],
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport'         => 'refresh',
        ) );
        $wp_customize->add_control( $key, array(
            'label'   => __( $meta[0], 'architech-custom' ),
            'section' => 'architech_pavilion_details',
            'type'    => $meta[1],
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

/* =====================================================================
 * CNSS Portfolio — Custom Post Type with per-project editable content
 * ===================================================================== */

// 1. Register the Portfolio custom post type
function architech_register_portfolio_cpt() {
    register_post_type( 'cnss_portfolio', array(
        'labels' => array(
            'name'          => __( 'Portfolio', 'architech-custom' ),
            'singular_name' => __( 'Project', 'architech-custom' ),
            'add_new_item'  => __( 'Add New Project', 'architech-custom' ),
            'edit_item'     => __( 'Edit Project', 'architech-custom' ),
            'all_items'     => __( 'All Projects', 'architech-custom' ),
            'menu_name'     => __( 'Portfolio', 'architech-custom' ),
        ),
        'public'       => true,
        'has_archive'  => true,
        'menu_icon'    => 'dashicons-portfolio',
        'supports'     => array( 'title', 'thumbnail' ),
        'rewrite'      => array( 'slug' => 'portfolio-project' ),
        'show_in_rest' => true,
    ) );
}
add_action( 'init', 'architech_register_portfolio_cpt' );

// Field map reused by meta box + save
function architech_portfolio_fields() {
    return array(
        'description'   => array( 'Description', 'textarea' ),
        'client'        => array( 'Client', 'text' ),
        'architect'     => array( 'Architect', 'text' ),
        'location'      => array( 'Location', 'text' ),
        'category'      => array( 'Category', 'text' ),
        'about_heading' => array( 'About Heading', 'text' ),
        'about_para1'   => array( 'About Paragraph 1', 'textarea' ),
        'about_para2'   => array( 'About Paragraph 2', 'textarea' ),
        'about_quote'   => array( 'Highlighted Quote', 'textarea' ),
        'about_para3'   => array( 'About Paragraph 3', 'textarea' ),
    );
}

// 2. Meta box
function architech_portfolio_add_metabox() {
    add_meta_box( 'cnss_portfolio_details', __( 'Project Details', 'architech-custom' ),
        'architech_portfolio_render_metabox', 'cnss_portfolio', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'architech_portfolio_add_metabox' );

function architech_portfolio_render_metabox( $post ) {
    wp_nonce_field( 'cnss_portfolio_save', 'cnss_portfolio_nonce' );
    echo '<style>.cnss-mb label{display:block;font-weight:600;margin:14px 0 4px;}.cnss-mb input[type=text],.cnss-mb textarea{width:100%;}.cnss-mb textarea{min-height:70px;}.cnss-gal-row{display:flex;align-items:center;gap:10px;margin:6px 0;}.cnss-gal-row input{flex:1;}.cnss-gal-prev{width:60px;height:44px;object-fit:cover;border:1px solid #ccc;}</style>';
    echo '<div class="cnss-mb">';

    // Gallery (5 image URL fields with media uploader)
    echo '<label>Gallery Images (up to 5)</label>';
    for ( $i = 1; $i <= 5; $i++ ) {
        $val = esc_attr( get_post_meta( $post->ID, '_cnss_img_' . $i, true ) );
        echo '<div class="cnss-gal-row">';
        echo '<img class="cnss-gal-prev" src="' . $val . '" onerror="this.style.visibility=\'hidden\'" />';
        echo '<input type="text" class="cnss-gal-url" name="cnss_img_' . $i . '" value="' . $val . '" placeholder="Image URL ' . $i . '" />';
        echo '<button type="button" class="button cnss-gal-upload">Select</button>';
        echo '</div>';
    }

    // Text / textarea fields
    foreach ( architech_portfolio_fields() as $key => $meta ) {
        $val = get_post_meta( $post->ID, '_cnss_' . $key, true );
        echo '<label>' . esc_html( $meta[0] ) . '</label>';
        if ( 'textarea' === $meta[1] ) {
            echo '<textarea name="cnss_' . $key . '">' . esc_textarea( $val ) . '</textarea>';
        } else {
            echo '<input type="text" name="cnss_' . $key . '" value="' . esc_attr( $val ) . '" />';
        }
    }
    echo '</div>';
}

// 3. Save handler
function architech_portfolio_save( $post_id ) {
    if ( ! isset( $_POST['cnss_portfolio_nonce'] ) || ! wp_verify_nonce( $_POST['cnss_portfolio_nonce'], 'cnss_portfolio_save' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    for ( $i = 1; $i <= 5; $i++ ) {
        if ( isset( $_POST['cnss_img_' . $i] ) ) {
            update_post_meta( $post_id, '_cnss_img_' . $i, esc_url_raw( $_POST['cnss_img_' . $i] ) );
        }
    }
    foreach ( architech_portfolio_fields() as $key => $meta ) {
        if ( isset( $_POST['cnss_' . $key] ) ) {
            update_post_meta( $post_id, '_cnss_' . $key, sanitize_textarea_field( wp_unslash( $_POST['cnss_' . $key] ) ) );
        }
    }
}
add_action( 'save_post_cnss_portfolio', 'architech_portfolio_save' );

// 4. Media uploader on the CPT edit screen
function architech_portfolio_admin_assets( $hook ) {
    global $post_type;
    if ( 'cnss_portfolio' !== $post_type ) return;
    wp_enqueue_media();
    $js = 'jQuery(function($){$(".cnss-gal-upload").on("click",function(e){e.preventDefault();var row=$(this).closest(".cnss-gal-row");var frame=wp.media({title:"Select Image",multiple:false});frame.on("select",function(){var url=frame.state().get("selection").first().toJSON().url;row.find(".cnss-gal-url").val(url);row.find(".cnss-gal-prev").attr("src",url).css("visibility","visible");});frame.open();});});';
    wp_add_inline_script( 'jquery-core', $js );
}
add_action( 'admin_enqueue_scripts', 'architech_portfolio_admin_assets' );

// 5. Front-end helpers (per-post, used by single-cnss_portfolio.php)
function architech_pf_meta( $key, $default = '' ) {
    $v = get_post_meta( get_the_ID(), '_cnss_' . $key, true );
    return ( '' !== $v && null !== $v ) ? $v : $default;
}
function architech_pf_image( $index ) {
    $v = get_post_meta( get_the_ID(), '_cnss_img_' . $index, true );
    return ( '' !== $v ) ? esc_url( $v ) : architech_get_portfolio_image( $index );
}
