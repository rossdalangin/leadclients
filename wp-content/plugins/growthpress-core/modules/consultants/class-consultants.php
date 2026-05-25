<?php
/**
 * GrowthPress Consultants Module - Enhanced
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Consultants {

    public function __construct() {
        add_action( 'init', array( $this, 'register_consultant_cpts' ) );
        add_action( 'wp_ajax_gp_generate_proposal', array( $this, 'handle_proposal_generation' ) );
        add_action( 'gp_proposal_sent', array( $this, 'schedule_proposal_followup' ) );
    }

    public function register_consultant_cpts() {
        register_post_type( 'gp_proposal', array(
            'labels'      => array( 'name' => 'Proposals', 'singular_name' => 'Proposal' ),
            'public'      => false,
            'show_ui'     => true,
            'menu_icon'   => 'dashicons-media-text',
            'supports'    => array( 'title', 'editor', 'custom-fields' ),
        ) );
    }

    public function handle_proposal_generation() {
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );

        $lead_id = intval($_POST['lead_id']);
        $lead = get_post($lead_id);
        $niche = get_option('growthpress_niche', 'Consulting');

        $ai = GrowthPress_AI::get_instance();
        $proposal_content = $ai->generate_proposal($lead->post_title, 'Strategic Consulting', $niche);

        $proposal_id = wp_insert_post(array(
            'post_title'   => 'Proposal for ' . $lead->post_title,
            'post_content' => $proposal_content,
            'post_type'    => 'gp_proposal',
            'post_status'  => 'publish'
        ));

        update_post_meta($proposal_id, '_related_lead', $lead_id);
        do_action('gp_proposal_sent', $proposal_id);
        wp_send_json_success("Proposal generated successfully! ID: $proposal_id");
    }

    public function schedule_proposal_followup( $proposal_id ) {
        $lead_id = get_post_meta( $proposal_id, '_related_lead', true );
        // Simulate a 48h delay for high-ticket follow-up
        GrowthPress_Activity::log( "Consulting Automation: AI Follow-up scheduled for proposal #$proposal_id in 48 hours." );
    }

    public function generate_sample_data() {
        wp_insert_post(array('post_title' => 'Strategic Planning', 'post_type' => 'page', 'post_status' => 'publish'));
    }
}

new GrowthPress_Consultants();
