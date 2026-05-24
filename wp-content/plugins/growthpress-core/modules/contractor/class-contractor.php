<?php
/**
 * GrowthPress Contractor Module
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Contractor {

    public function __construct() {
        add_action( 'init', array( $this, 'register_contractor_cpts' ) );
    }

    public function register_contractor_cpts() {
        register_post_type( 'gp_project', array(
            'labels'      => array( 'name' => 'Portfolio', 'singular_name' => 'Project' ),
            'public'      => true,
            'show_ui'     => true,
            'menu_icon'   => 'dashicons-admin-tools',
            'supports'    => array( 'title', 'editor', 'thumbnail' ),
        ) );
    }

    public function generate_sample_data() {
        $projects = array(
            'Modern Kitchen Remodel' => 'A complete overhaul of a 1950s kitchen into a modern chef\'s paradise.',
            'Luxury Bathroom Suite' => 'Transforming a standard bathroom into a spa-like retreat.'
        );
        foreach($projects as $title => $content) {
            wp_insert_post(array('post_title' => $title, 'post_content' => $content, 'post_type' => 'gp_project', 'post_status' => 'publish'));
        }
    }

    public function calculate_estimate( $sq_ft, $material_type ) {
        $rates = array( 'basic' => 50, 'premium' => 120 );
        return $sq_ft * ( $rates[$material_type] ?? 80 );
    }
}
new GrowthPress_Contractor();
