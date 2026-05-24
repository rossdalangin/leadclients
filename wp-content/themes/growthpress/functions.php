<?php
/**
 * GrowthPress Theme Functions - Final
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

function growthpress_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
}
add_action( 'after_setup_theme', 'growthpress_setup' );

function growthpress_scripts() {
	wp_enqueue_style( 'growthpress-inter-font', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap' );
	wp_enqueue_style( 'growthpress-style', get_stylesheet_uri() );
	wp_enqueue_script( 'growthpress-frontend-js', GROWTHPRESS_CORE_URL . 'assets/js/frontend.js', array('jquery'), '1.0.0', true );
	wp_localize_script( 'growthpress-frontend-js', 'gp_ajax', array( 'ajaxurl' => admin_url('admin-ajax.php') ) );
}
add_action( 'wp_enqueue_scripts', 'growthpress_scripts' );

function growthpress_footer_popup() {
    if ( is_admin() ) return;
    ?>
    <div id="gp-exit-popup" class="glass-card" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); z-index:10000; width:400px; text-align:center; box-shadow: 0 0 100px rgba(0,0,0,0.5);">
        <h2>Wait! Before you go...</h2>
        <p>Get our AI Business Growth Roadmap for free.</p>
        <?php echo do_shortcode('[gp_lead_form]'); ?>
        <button onclick="jQuery('#gp-exit-popup').fadeOut()" style="margin-top:10px; background:none; border:none; color:#666; cursor:pointer;">No thanks, I'll pass.</button>
    </div>
    <?php
}
add_action( 'wp_footer', 'growthpress_footer_popup' );
