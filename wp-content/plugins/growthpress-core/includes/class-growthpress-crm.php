<?php
/**
 * GrowthPress CRM Core Class - Segmentation Enhanced
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_CRM {

    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'init', array( $this, 'register_cpts' ) );
        add_action( 'gp_lead_captured', array( $this, 'trigger_lead_automations' ) );
    }

    public function register_cpts() {
        register_post_type( 'gp_lead', array( 'labels' => array( 'name' => 'Leads' ), 'public' => false, 'show_ui' => true, 'supports' => array( 'title', 'editor', 'custom-fields' ) ) );
        register_taxonomy( 'gp_lead_tag', 'gp_lead', array( 'hierarchical' => false, 'show_ui' => true ) );
    }

    public function trigger_lead_automations( $lead_id ) {
        $ai = GrowthPress_AI::get_instance();
        $lead = get_post( $lead_id );

        // 1. AI Analysis & Segmentation
        $analysis_raw = $ai->call_ai("Segment this lead as 'Residential', 'Commercial', or 'Enterprise' based on message: \"{$lead->post_content}\". Return only the tag.", "Lead Segmenter");
        $segment = trim($analysis_raw);

        // 2. Assign Tags
        if ( in_array($segment, array('Residential', 'Commercial', 'Enterprise')) ) {
            wp_set_post_terms( $lead_id, $segment, 'gp_lead_tag', true );
        }

        // 3. Automated Logic
        error_log( "CRM: Lead $lead_id segmented as $segment" );
    }

    public function handle_lead_submission() { /* Logic... */ }
    public function render_lead_form() { /* UI... */ }
}
GrowthPress_CRM::get_instance();
