<?php
/**
 * GrowthPress CRM Core Class - Final Advanced
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
    }

    public function trigger_lead_automations( $lead_id ) {
        $ai = GrowthPress_AI::get_instance();
        $lead = get_post($lead_id);

        $analysis_raw = $ai->analyze_sentiment($lead->post_content);
        $analysis = json_decode($analysis_raw, true) ?: array('urgency' => 5);

        // Advanced Sentiment-Based Routing
        if ( $analysis['urgency'] >= 9 ) {
            $staff = get_users( array( 'role' => 'administrator', 'number' => 1 ) );
            if ( ! empty($staff) ) {
                update_post_meta( $lead_id, '_assigned_staff', $staff[0]->ID );
                GrowthPress_Activity::log( "URGENT LEAD #$lead_id routed to " . $staff[0]->display_name );
            }
        }
    }

    public function handle_lead_submission() {}
}
GrowthPress_CRM::get_instance();
