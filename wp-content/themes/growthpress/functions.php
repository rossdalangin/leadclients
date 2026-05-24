<?php
/**
 * GrowthPress Theme Functions - Customizer Enhanced
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

function growthpress_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
    add_theme_support( 'custom-logo' );
}
add_action( 'after_setup_theme', 'growthpress_setup' );

function growthpress_customize_register( $wp_customize ) {
    $wp_customize->add_section( 'growthpress_branding', array( 'title' => 'GrowthPress Branding', 'priority' => 30 ) );

    $wp_customize->add_setting( 'growthpress_primary_color', array( 'default' => '#2563EB', 'sanitize_callback' => 'sanitize_hex_color' ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'growthpress_primary_color', array( 'label' => 'Primary Brand Color', 'section' => 'growthpress_branding' ) ) );
}
add_action( 'customize_register', 'growthpress_customize_register' );

function growthpress_scripts() {
	wp_enqueue_style( 'growthpress-inter-font', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap' );
	wp_enqueue_style( 'growthpress-style', get_stylesheet_uri() );

    $primary = get_theme_mod( 'growthpress_primary_color', '#2563EB' );
    wp_add_inline_style( 'growthpress-style', ":root { --primary: $primary; }" );

    if ( defined( 'GROWTHPRESS_CORE_URL' ) ) {
	    wp_enqueue_script( 'growthpress-frontend-js', GROWTHPRESS_CORE_URL . 'assets/js/frontend.js', array('jquery'), '1.0.0', true );
	    wp_localize_script( 'growthpress-frontend-js', 'gp_ajax', array( 'ajaxurl' => admin_url('admin-ajax.php') ) );
    }
}
add_action( 'wp_enqueue_scripts', 'growthpress_scripts' );

function growthpress_footer_popup() {
    if ( is_admin() || ! defined( 'GROWTHPRESS_CORE_URL' ) ) return;
    ?>
    <div id="gp-exit-popup" class="glass-card" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); z-index:10000; width:400px; text-align:center;">
        <h2>Wait!</h2>
        <p>Get our Free AI Growth Guide.</p>
        <?php echo do_shortcode('[gp_lead_form]'); ?>
        <button onclick="jQuery('#gp-exit-popup').fadeOut()">Close</button>
    </div>
    <?php
}
add_action( 'wp_footer', 'growthpress_footer_popup' );
