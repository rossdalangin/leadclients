<?php
/**
 * GrowthPress Law Firm Module
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Law {

    public function __construct() {
        add_action( 'init', array( $this, 'register_law_cpts' ) );
    }

    public function register_law_cpts() {
        // Legal Cases / Practice Areas
        register_post_type( 'gp_legal_case', array(
            'labels'      => array( 'name' => 'Practice Areas', 'singular_name' => 'Practice Area' ),
            'public'      => true,
            'show_ui'     => true,
            'menu_icon'   => 'dashicons-hammer',
            'supports'    => array( 'title', 'editor', 'thumbnail' ),
            'has_archive' => true,
        ) );
    }

    /**
     * Legal Intake Form Processing
     */
    public function process_legal_intake( $data ) {
        // AI urgency scoring for legal matters
        $ai = GrowthPress_AI::get_instance();
        $analysis = $ai->analyze_sentiment( $data['case_description'] );

        // Save as lead with legal metadata
        // ...
    }
}

new GrowthPress_Law();
