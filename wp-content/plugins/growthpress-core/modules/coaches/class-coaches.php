<?php
/**
 * GrowthPress Coaches Module
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Coaches {

    public function __construct() {
        add_action( 'init', array( $this, 'register_coach_cpts' ) );
    }

    public function register_coach_cpts() {
        register_post_type( 'gp_course', array(
            'labels'      => array( 'name' => 'Courses', 'singular_name' => 'Course' ),
            'public'      => true,
            'show_ui'     => true,
            'menu_icon'   => 'dashicons-welcome-learn-more',
            'supports'    => array( 'title', 'editor', 'thumbnail' ),
        ) );
    }

    /**
     * Discovery Call Trigger
     */
    public function trigger_discovery_call( $lead_id ) {
        // Logic to schedule discovery call immediately
    }
}

new GrowthPress_Coaches();
