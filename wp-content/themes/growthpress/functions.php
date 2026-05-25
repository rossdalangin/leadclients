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
}
add_action( 'after_setup_theme', 'growthpress_setup' );

/**
 * Register Customizer Settings
 */
function growthpress_customize_register( $wp_customize ) {
    // Branding Section
    $wp_customize->add_section( 'growthpress_branding', array(
        'title' => 'GrowthPress Global Branding',
        'description' => 'Configure your business identity and primary aesthetics. Example: Use a bold blue for trust or a sleek slate for enterprise feel.',
        'priority' => 30,
    ) );

    // Primary Color
    $wp_customize->add_setting( 'growthpress_primary_color', array(
        'default' => '#2563EB',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'growthpress_primary_color', array(
        'label' => 'Primary Brand Color',
        'description' => 'Used for buttons, links, and accents. Example: #2563EB (Growth Blue).',
        'section' => 'growthpress_branding',
    ) ) );

    // Homepage Content Section
    $wp_customize->add_section( 'growthpress_homepage', array(
        'title' => 'Homepage Hero Content',
        'description' => 'Manage your main headline and call to action. Note: These values update the Home page hero section.',
        'priority' => 31,
    ) );

    $wp_customize->add_setting( 'gp_hero_headline', array(
        'default' => 'Transform Your Business with AI',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'gp_hero_headline', array(
        'label' => 'Hero Headline',
        'description' => 'The main title on your homepage. Example: "The Future of Solar Energy is Here."',
        'section' => 'growthpress_homepage',
        'type' => 'text',
    ) );

    $wp_customize->add_setting( 'gp_hero_subheadline', array(
        'default' => 'Consolidate your CRM, Booking, and Marketing into one unified Operating System.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'gp_hero_subheadline', array(
        'label' => 'Hero Subheadline',
        'description' => 'A brief supporting text for your headline. Example: "Automate your client intake and double your bookings in 30 days."',
        'section' => 'growthpress_homepage',
        'type' => 'textarea',
    ) );

    // Service Page Content Section
    $wp_customize->add_section( 'growthpress_services', array(
        'title' => 'Services Page Management',
        'description' => 'Control the core messaging for your services. High-ticket businesses should focus on ROI and outcomes here.',
        'priority' => 32,
    ) );

    $wp_customize->add_setting( 'gp_services_intro', array(
        'default' => 'Tailored Solutions for Your Business Growth',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'gp_services_intro', array(
        'label' => 'Services Intro Headline',
        'description' => 'The main headline for the services overview page. Example: "Elite Dental Care for Modern Families."',
        'section' => 'growthpress_services',
        'type' => 'text',
    ) );

    $wp_customize->add_setting( 'gp_services_cta', array(
        'default' => 'Book a Discovery Call',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'gp_services_cta', array(
        'label' => 'Services CTA Text',
        'description' => 'The button text for service inquiries. Example: "Get a Free Roof Estimate."',
        'section' => 'growthpress_services',
        'type' => 'text',
    ) );

    // Contact Page Content Section
    $wp_customize->add_section( 'growthpress_contact', array(
        'title' => 'Contact Page Management',
        'description' => 'Manage contact information and business location details.',
        'priority' => 33,
    ) );

    $wp_customize->add_setting( 'gp_contact_address', array(
        'default' => '123 Business Growth Way, Suite 100, Silicon Valley, CA',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'gp_contact_address', array(
        'label' => 'Business Address',
        'description' => 'Your physical office location. Example: "789 Legal Plaza, Downtown, New York, NY"',
        'section' => 'growthpress_contact',
        'type' => 'textarea',
    ) );

    $wp_customize->add_setting( 'gp_contact_phone', array(
        'default' => '+1 (555) 000-GROW',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'gp_contact_phone', array(
        'label' => 'Contact Phone Number',
        'description' => 'Your main business line. Example: "Call: (800) 555-1212"',
        'section' => 'growthpress_contact',
        'type' => 'text',
    ) );

    $wp_customize->add_setting( 'gp_contact_email', array(
        'default' => 'hello@growthpress.ai',
        'sanitize_callback' => 'sanitize_email',
    ) );
    $wp_customize->add_control( 'gp_contact_email', array(
        'label' => 'Business Email',
        'description' => 'The email address for inquiries. Example: "support@solarexperts.com"',
        'section' => 'growthpress_contact',
        'type' => 'email',
    ) );
}
add_action( 'customize_register', 'growthpress_customize_register' );

function growthpress_scripts() {
	wp_enqueue_style( 'growthpress-inter-font', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap' );
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
        <h2>Wait! Before you go...</h2>
        <p>Get our AI Business Growth Roadmap for free.</p>
        <?php echo do_shortcode('[gp_lead_form]'); ?>
        <button onclick="jQuery('#gp-exit-popup').fadeOut()" style="margin-top:15px; border:none; background:none; cursor:pointer; color:#666;">No thanks, I'll pass.</button>
    </div>
    <?php
}
add_action( 'wp_footer', 'growthpress_footer_popup' );
