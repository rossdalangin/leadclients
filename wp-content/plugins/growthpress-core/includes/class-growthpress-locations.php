<?php
/**
 * GrowthPress Multi-Location Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Locations {

    public function __construct() {
        add_action( 'init', array( $this, 'register_location_cpt' ) );
    }

    public function register_location_cpt() {
        register_post_type( 'gp_location', array(
            'labels'      => array( 'name' => 'Locations', 'singular_name' => 'Location' ),
            'public'      => true,
            'show_ui'     => true,
            'menu_icon'   => 'dashicons-location',
            'supports'    => array( 'title', 'editor', 'custom-fields' ),
        ) );
    }

    public function get_locations() {
        return get_posts( array( 'post_type' => 'gp_location', 'posts_per_page' => -1 ) );
    }
}

new GrowthPress_Locations();
