<?php
/**
 * GrowthPress Consultants Module
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Consultants {

    public function __construct() {
        add_action( 'init', array( $this, 'register_consultant_cpts' ) );
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

    /**
     * Automated Proposal Generation
     */
    public function generate_proposal( $lead_id ) {
        // AI logic to generate a custom proposal based on lead data
    }
}

new GrowthPress_Consultants();
