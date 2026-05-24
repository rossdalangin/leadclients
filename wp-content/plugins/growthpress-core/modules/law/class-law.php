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
        register_post_type( 'gp_legal_case', array(
            'labels'      => array( 'name' => 'Practice Areas', 'singular_name' => 'Practice Area' ),
            'public'      => true,
            'show_ui'     => true,
            'menu_icon'   => 'dashicons-hammer',
            'supports'    => array( 'title', 'editor', 'thumbnail' ),
        ) );
    }

    public function generate_sample_data() {
        $cases = array(
            'Personal Injury' => 'We help victims of accidents get the compensation they deserve.',
            'Family Law' => 'Compassionate legal support for divorce, custody, and family matters.',
            'Corporate Law' => 'Expert legal counsel for businesses of all sizes.'
        );
        foreach($cases as $title => $content) {
            wp_insert_post(array('post_title' => $title, 'post_content' => $content, 'post_type' => 'gp_legal_case', 'post_status' => 'publish'));
        }
    }
}
new GrowthPress_Law();
