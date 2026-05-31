<?php
/**
 * GrowthPress Unified Proposal System
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Proposals {

    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'init', array( $this, 'register_proposal_cpt' ) );
        add_action( 'wp_ajax_gp_generate_ai_proposal', array( $this, 'handle_ai_proposal_generation' ) );
        add_action( 'wp_ajax_gp_accept_proposal', array( $this, 'handle_proposal_acceptance' ) );
        add_action( 'wp_ajax_nopriv_gp_accept_proposal', array( $this, 'handle_proposal_acceptance' ) );
    }

    public function register_proposal_cpt() {
        register_post_type( 'gp_proposal', array(
            'labels'      => array( 'name' => 'Proposals', 'singular_name' => 'Proposal' ),
            'public'      => false,
            'show_ui'     => true,
            'menu_icon'   => 'dashicons-media-text',
            'supports'    => array( 'title', 'editor', 'custom-fields' ),
        ) );
    }

    public function handle_ai_proposal_generation() {
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error();
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );

        $lead_id = intval($_POST['lead_id']);
        $lead = get_post($lead_id);
        if ( ! $lead ) wp_send_json_error('Lead not found.');

        $niche = get_option('growthpress_niche', 'business');
        $ai = GrowthPress_AI::get_instance();

        $proposal_content = $ai->generate_proposal($lead->post_title, "Advanced $niche Solutions", $niche);

        $proposal_id = wp_insert_post(array(
            'post_title'   => 'Proposal: ' . $lead->post_title,
            'post_content' => $proposal_content,
            'post_type'    => 'gp_proposal',
            'post_status'  => 'publish'
        ));

        update_post_meta($proposal_id, '_related_lead', $lead_id);
        update_post_meta($proposal_id, '_proposal_status', 'Sent');

        // Estimate value based on niche/probability
        $prob = get_post_meta($lead_id, '_gp_ai_probability', true) ?: 50;
        $base_values = array('law' => 5000, 'solar' => 25000, 'dental' => 3000, 'contractor' => 15000, 'accounting' => 2000);
        $est_val = $base_values[$niche] ?? 5000;
        update_post_meta($proposal_id, '_proposal_value', $est_val);

        GrowthPress_Activity::log( "AI Proposal #$proposal_id generated for " . $lead->post_title );
        wp_send_json_success( "Proposal generated! ID: $proposal_id. Value: $$est_val" );
    }

    public function handle_proposal_acceptance() {
        $proposal_id = intval($_POST['proposal_id']);
        update_post_meta($proposal_id, '_proposal_status', 'Accepted');

        $value = get_post_meta($proposal_id, '_proposal_value', true);
        $payments = new GrowthPress_Payments();
        $invoice_id = $payments->create_invoice($value, $proposal_id, 'proposal');

        GrowthPress_Activity::log( "Proposal #$proposal_id accepted. Invoice #$invoice_id generated." );
        wp_send_json_success(array('invoice_id' => $invoice_id));
    }

    public function get_pipeline_value() {
        $proposals = get_posts(array(
            'post_type' => 'gp_proposal',
            'posts_per_page' => -1,
            'meta_query' => array( array( 'key' => '_proposal_status', 'value' => 'Sent' ) )
        ));
        $total = 0;
        foreach($proposals as $p) $total += (float)get_post_meta($p->ID, '_proposal_value', true);
        return $total;
    }
}
GrowthPress_Proposals::get_instance();
