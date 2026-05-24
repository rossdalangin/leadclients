<?php
/**
 * GrowthPress Funnel Management Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Funnels {

    public function __construct() {
        add_action( 'init', array( $this, 'register_funnel_cpt' ) );
    }

    public function register_funnel_cpt() {
        register_post_type( 'gp_funnel', array(
            'labels'      => array( 'name' => 'Funnels', 'singular_name' => 'Funnel' ),
            'public'      => false,
            'show_ui'     => true,
            'menu_icon'   => 'dashicons-filter',
            'supports'    => array( 'title', 'custom-fields' ),
        ) );
    }

    public function get_funnel_steps( $funnel_id ) {
        return get_post_meta( $funnel_id, '_funnel_steps', true );
    }
}

new GrowthPress_Funnels();
