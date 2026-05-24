<?php
/**
 * GrowthPress Real Estate Module
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_RealEstate {

    public function __construct() {
        add_action( 'init', array( $this, 'register_property_cpt' ) );
    }

    public function register_property_cpt() {
        register_post_type( 'gp_property', array(
            'labels'      => array( 'name' => 'Properties', 'singular_name' => 'Property' ),
            'public'      => true,
            'show_ui'     => true,
            'menu_icon'   => 'dashicons-admin-home',
            'supports'    => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
        ) );
    }

    public function generate_sample_data() {
        $props = array(
            'Sunset Hills Estate' => 'Luxury 5-bedroom home with panoramic views.',
            'Modern Downtown Loft' => 'Sleek 2-bedroom loft in the heart of the city.'
        );
        foreach($props as $title => $content) {
            wp_insert_post(array('post_title' => $title, 'post_content' => $content, 'post_type' => 'gp_property', 'post_status' => 'publish'));
        }
    }
}
new GrowthPress_RealEstate();
