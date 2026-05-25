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
        register_post_type( 'gp_lead', array(
            'labels' => array( 'name' => 'Leads' ),
            'public' => false,
            'show_ui' => true,
            'supports' => array( 'title', 'editor', 'custom-fields' ),
            'menu_icon' => 'dashicons-id-alt'
        ) );

        register_taxonomy( 'gp_lead_stage', 'gp_lead', array(
            'labels' => array( 'name' => 'Lead Stages' ),
            'hierarchical' => true,
            'show_ui' => true
        ) );

        add_shortcode( 'gp_lead_form', array( $this, 'render_lead_form' ) );
        add_shortcode( 'gp_quiz_lead_form', array( $this, 'render_quiz_form' ) );
        add_action( 'wp_ajax_gp_submit_lead', array( $this, 'handle_lead_submission' ) );
        add_action( 'wp_ajax_nopriv_gp_submit_lead', array( $this, 'handle_lead_submission' ) );
    }

    public function render_lead_form() {
        return '<form class="gp-form glass-card" data-action="gp_submit_lead">
            <input type="text" name="lead_name" placeholder="Full Name" required>
            <input type="email" name="lead_email" placeholder="Email Address" required>
            <textarea name="lead_msg" placeholder="Tell us about your needs..."></textarea>
            <button type="submit" class="button button-primary">Scale My Business</button>
        </form>';
    }

    public function render_quiz_form() {
        return '<div class="gp-quiz-container glass-card">
            <h3>Quick Qualification Quiz</h3>
            <div id="gp-quiz-step-1">
                <p>What is your current monthly revenue?</p>
                <button onclick="nextStep(1)">$0 - $10k</button>
                <button onclick="nextStep(2)">$10k - $50k</button>
                <button onclick="nextStep(3)">$50k+</button>
            </div>
            <div id="gp-quiz-form" style="display:none;">
                ' . $this->render_lead_form() . '
            </div>
        </div>';
    }

    public function handle_lead_submission() {
        $name = sanitize_text_field($_POST['lead_name']);
        $email = sanitize_email($_POST['lead_email']);
        $msg = sanitize_textarea_field($_POST['lead_msg']);

        $lead_id = wp_insert_post(array(
            'post_title' => $name,
            'post_content' => $msg,
            'post_type' => 'gp_lead',
            'post_status' => 'publish'
        ));

        update_post_meta($lead_id, '_lead_email', $email);
        wp_set_object_terms($lead_id, 'new', 'gp_lead_stage');

        do_action('gp_lead_captured', $lead_id);
        wp_send_json_success("Lead captured! We will contact you soon.");
    }

    public function trigger_lead_automations( $lead_id ) {
        $ai = GrowthPress_AI::get_instance();
        $lead = get_post($lead_id);

        $analysis_raw = $ai->analyze_sentiment($lead->post_content);
        $analysis = json_decode($analysis_raw, true) ?: array('urgency' => 5);

        // Cache AI scoring to prevent dashboard slowdowns
        $prob = $ai->predict_deal_probability($lead_id);
        update_post_meta($lead_id, '_gp_ai_probability', $prob);
        update_post_meta($lead_id, '_gp_ai_sentiment_json', $analysis_raw);

        // Advanced Sentiment-Based Routing
        if ( isset($analysis['urgency']) && $analysis['urgency'] >= 9 ) {
            $staff = get_users( array( 'role' => 'administrator', 'number' => 1 ) );
            if ( ! empty($staff) ) {
                update_post_meta( $lead_id, '_assigned_staff', $staff[0]->ID );
                GrowthPress_Activity::log( "URGENT LEAD #$lead_id routed to " . $staff[0]->display_name );
            }
        }
    }
}
GrowthPress_CRM::get_instance();
