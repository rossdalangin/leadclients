<?php
/**
 * GrowthPress Funnel Management Class - A/B Testing Enhanced
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

    public function track_variation_hit( $funnel_id, $variation = 'A' ) {
        $hits = get_post_meta( $funnel_id, "_hits_$variation", true ) ?: 0;
        update_post_meta( $funnel_id, "_hits_$variation", ++$hits );
    }

    public function get_performance( $funnel_id ) {
        return array(
            'A' => get_post_meta( $funnel_id, '_hits_A', true ) ?: 0,
            'B' => get_post_meta( $funnel_id, '_hits_B', true ) ?: 0,
        );
    }
}

new GrowthPress_Funnels();
