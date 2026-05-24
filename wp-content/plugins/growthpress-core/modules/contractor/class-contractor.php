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

    /**
     * Project Estimation Calculator Logic
     */
    public function calculate_estimate( $sq_ft, $material_type ) {
        $rates = array( 'basic' => 50, 'premium' => 120 );
        $base_price = $sq_ft * ( $rates[$material_type] ?? 80 );
        return $base_price;
    }
}

new GrowthPress_Contractor();
