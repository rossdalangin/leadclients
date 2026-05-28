<?php
/**
 * GrowthPress Theme Functions - Advanced Customizer
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

function growthpress_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
    add_theme_support( 'custom-logo' );
    register_nav_menus( array( 'primary' => 'Primary Menu' ) );
}
add_action( 'after_setup_theme', 'growthpress_setup' );

/**
 * Register Customizer Settings
 */
function growthpress_customize_register( $wp_customize ) {
    // Branding Section
    $wp_customize->add_section( 'growthpress_branding', array(
        'title' => 'Elite Branding & Identity',
        'description' => 'Configure your premium business aesthetics.',
        'priority' => 30,
    ) );

    // Primary Color
    $wp_customize->add_setting( 'growthpress_primary_color', array(
        'default' => '#2563EB',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'growthpress_primary_color', array(
        'label' => 'Primary Brand Color',
        'section' => 'growthpress_branding',
    ) ) );

    // Homepage Content Section
    $wp_customize->add_section( 'growthpress_homepage', array(
        'title' => 'Homepage Hero Engine',
        'priority' => 31,
    ) );

    $wp_customize->add_setting( 'gp_hero_headline', array(
        'default' => 'Transform Your Business with AI Intelligence',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'gp_hero_headline', array(
        'label' => 'Hero Headline',
        'section' => 'growthpress_homepage',
        'type' => 'text',
    ) );

    $wp_customize->add_setting( 'gp_hero_subheadline', array(
        'default' => 'The unified operating system for high-ticket service firms. Scale faster, automate smarter.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'gp_hero_subheadline', array(
        'label' => 'Hero Subheadline',
        'section' => 'growthpress_homepage',
        'type' => 'textarea',
    ) );

    // Ecosystem Maintenance Section
    $wp_customize->add_section( 'growthpress_maintenance', array(
        'title' => 'OS Maintenance & Sync',
        'priority' => 100,
    ) );

    $wp_customize->add_setting( 'gp_regenerate_trigger', array( 'default' => '' ) );
    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'gp_regenerate_trigger', array(
        'label' => 'Sync Ecosystem',
        'section' => 'growthpress_maintenance',
        'type' => 'button',
        'input_attrs' => array(
            'value' => 'Apply Updates & Sync',
            'class' => 'button button-primary',
            'onclick' => 'if(confirm("Regenerate core pages now?")){ jQuery.post(ajaxurl, {action:"gp_regenerate_pages", gp_nonce:"'.wp_create_nonce("gp_admin_nonce").'"}); }',
        ),
    ) ) );
}
add_action( 'customize_register', 'growthpress_customize_register' );

function growthpress_scripts() {
	wp_enqueue_style( 'growthpress-inter-font', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap' );
	wp_enqueue_style( 'growthpress-style', get_stylesheet_uri() );
    wp_enqueue_style( 'growthpress-accents', get_template_directory_uri() . '/niche-accents.css' );
    wp_enqueue_style( 'growthpress-mobile-cta', get_template_directory_uri() . '/mobile-cta.css' );

    $primary = get_theme_mod( 'growthpress_primary_color', '#2563EB' );
    wp_add_inline_style( 'growthpress-style', ":root { --primary: $primary; }" );

    if ( defined( 'GROWTHPRESS_CORE_URL' ) ) {
	    wp_enqueue_script( 'growthpress-frontend-js', GROWTHPRESS_CORE_URL . 'assets/js/frontend.js', array('jquery'), '1.0.0', true );
	    wp_localize_script( 'growthpress-frontend-js', 'gp_ajax', array( 'ajaxurl' => admin_url('admin-ajax.php') ) );
    }
}
add_action( 'wp_enqueue_scripts', 'growthpress_scripts' );

function growthpress_body_classes( $classes ) {
    $niche = get_option( 'growthpress_niche', 'business' );
    $classes[] = 'gp-niche-' . $niche;
    return $classes;
}
add_filter( 'body_class', 'growthpress_body_classes' );

function growthpress_footer_popup() {
    if ( is_admin() || ! defined( 'GROWTHPRESS_CORE_URL' ) ) return;
    ?>
    <div id="gp-exit-popup" class="glass-card" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); z-index:10000; width:460px; text-align:center; padding: 50px;">
        <h2 class="text-gradient">Wait! Get Your Free AI Roadmap</h2>
        <p>Enter your details below to receive a custom 12-month business growth strategy powered by GPT-4.</p>
        <?php echo do_shortcode('[gp_lead_form]'); ?>
        <button onclick="jQuery('#gp-exit-popup').fadeOut()" style="margin-top:20px; border:none; background:none; cursor:pointer; color:#64748B; font-weight:700;">No thanks, I'll pass.</button>
    </div>
    <?php
}
add_action( 'wp_footer', 'growthpress_footer_popup' );
