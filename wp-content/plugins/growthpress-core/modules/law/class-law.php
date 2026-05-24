<?php
/**
 * GrowthPress Law Firm Module - Final
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Law {

    public function __construct() {
        add_action( 'init', array( $this, 'register_law_cpts' ) );
    }

    public function register_law_cpts() {
        register_post_type( 'gp_legal_case', array(
            'labels'      => array( 'name' => 'Cases', 'singular_name' => 'Case' ),
            'public'      => false,
            'show_ui'     => true,
            'menu_icon'   => 'dashicons-hammer',
            'supports'    => array( 'title', 'editor', 'custom-fields' ),
        ) );
    }

    public function generate_sample_data() {
        wp_insert_post(array('post_title' => 'Personal Injury Case #102', 'post_type' => 'gp_legal_case', 'post_status' => 'publish'));
    }
}
new GrowthPress_Law();
