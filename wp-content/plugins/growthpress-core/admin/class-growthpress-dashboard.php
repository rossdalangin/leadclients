<?php
/**
 * GrowthPress Admin Dashboard Class - Funnel Enhanced
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Dashboard {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_dashboard_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_dashboard_assets' ) );
        add_action( 'wp_ajax_gp_setup_niche', array( $this, 'handle_niche_setup' ) );
        add_action( 'wp_ajax_gp_update_lead_stage', array( $this, 'handle_lead_stage_update' ) );
    }

    public function add_dashboard_menu() {
        add_menu_page( 'GrowthPress', 'GrowthPress', 'manage_options', 'growthpress-dashboard', array( $this, 'render_dashboard' ), 'dashicons-chart-line', 2 );
    }

    public function enqueue_dashboard_assets( $hook ) {
        if ( 'toplevel_page_growthpress-dashboard' !== $hook ) return;
        wp_enqueue_style( 'growthpress-admin-css', GROWTHPRESS_CORE_URL . 'assets/css/admin-dashboard.css', array(), GROWTHPRESS_CORE_VERSION );
        wp_enqueue_script( 'chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array(), '3.9.1', true );
        wp_enqueue_script( 'jquery-ui-draggable' );
        wp_enqueue_script( 'jquery-ui-droppable' );
        wp_enqueue_script( 'growthpress-admin-js', GROWTHPRESS_CORE_URL . 'assets/js/admin-dashboard.js', array( 'jquery', 'chart-js', 'jquery-ui-draggable', 'jquery-ui-droppable' ), GROWTHPRESS_CORE_VERSION, true );
        wp_localize_script( 'growthpress-admin-js', 'gp_admin', array( 'nonce' => wp_create_nonce( 'gp_admin_nonce' ) ));
    }

    public function handle_lead_stage_update() {
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );
        wp_set_object_terms( intval($_POST['lead_id']), sanitize_text_field($_POST['stage']), 'gp_lead_stage' );
        wp_send_json_success();
    }

    public function handle_niche_setup() {
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );
        $niche = sanitize_text_field($_POST['niche']);

        $this->generate_niche_pages($niche);
        $this->generate_niche_funnel($niche);

        $class_name = 'GrowthPress_' . str_replace(' ', '', ucwords(str_replace('-', ' ', $niche)));
        if ( class_exists($class_name) ) {
            $instance = new $class_name();
            if ( method_exists($instance, 'generate_sample_data') ) $instance->generate_sample_data();
        }

        update_option( 'growthpress_niche', $niche );
        wp_send_json_success( "System configured for $niche with pages and funnels." );
    }

    private function generate_niche_pages($niche) {
        $pages = array(
            'Home'     => 'Welcome to our ' . $niche . ' OS. [gp_urgency_banner] [gp_lead_form]',
            'Services' => 'Our ' . $niche . ' services. [gp_booking_form]',
            'Contact'  => 'Talk to us. [gp_ai_faq]',
        );
        foreach($pages as $title => $content) {
            if ( ! get_page_by_title($title) ) wp_insert_post(array('post_title' => $title, 'post_content' => $content, 'post_type' => 'page', 'post_status' => 'publish'));
        }
    }

    private function generate_niche_funnel($niche) {
        $funnel_pages = array(
            'Free AI Strategy Guide' => 'Get your ' . $niche . ' growth guide. [gp_lead_form]',
            'Thank You' => 'Check your email for the guide. Next step: [gp_booking_form]'
        );
        foreach($funnel_pages as $title => $content) {
            wp_insert_post(array('post_title' => $title, 'post_content' => $content, 'post_type' => 'page', 'post_status' => 'publish'));
        }
    }

    public function render_dashboard() {
        include_once GROWTHPRESS_CORE_PATH . 'admin/views/dashboard.php';
    }
}
new GrowthPress_Dashboard();
